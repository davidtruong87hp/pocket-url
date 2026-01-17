<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\Traits\InteractsWithRabbitMQ;

class ConsumerIntegrationTest extends TestCase
{
    use InteractsWithRabbitMQ, WithFaker;

    private const TEST_EXCHANGE = 'test.analytics.exchange';

    private const TEST_QUEUE = 'test.analytics_clicks';

    private const TEST_DLQ = 'test.analytics_clicks.failed';

    private static $consumerProcess = null;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::startTestConsumer();

        sleep(3);
    }

    public static function tearDownAfterClass(): void
    {
        self::stopTestConsumer();

        parent::tearDownAfterClass();
    }

    private static function startTestConsumer(): void
    {
        echo "\n🔍 Starting test consumer...\n";

        // Start consumer in background
        $command = 'php artisan rabbitmq:consume:test > /dev/null 2>&1 & echo $!';
        $pid = shell_exec($command);

        if ($pid) {
            self::$consumerProcess = $pid;
            echo '✅ Test consumer started (PID: '.self::$consumerProcess.")\n";
        }
    }

    private static function stopTestConsumer(): void
    {
        if (self::$consumerProcess) {
            echo "\n🛑 Stopping test consumer (PID: ".self::$consumerProcess.")...\n";
            shell_exec('kill '.self::$consumerProcess);
            echo "✅ Test consumer stopped\n";
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanupAllTestData();

        $this->createQueue(
            self::TEST_QUEUE,
            self::TEST_EXCHANGE,
            'click.tracked'
        );

        // Verify consumer is running
        $maxAttempts = 10;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            if ($this->isTestConsumerRunning()) {
                break;
            }

            $attempt++;
            sleep(1);
        }

        if (! $this->isTestConsumerRunning()) {
            $this->markTestSkipped("⚠️  Test consumer not running.\n");
        }

        $this->purgeQueue(self::TEST_QUEUE);
        $this->purgeQueue(self::TEST_DLQ);
    }

    private function isTestConsumerRunning(): bool
    {
        $stats = $this->getQueueStats(self::TEST_QUEUE);

        return $stats['consumers'] > 0;
    }

    protected function tearDown(): void
    {
        $this->purgeQueue(self::TEST_QUEUE);
        $this->purgeQueue(self::TEST_DLQ);

        $this->cleanupAllTestData();

        parent::tearDown();
    }

    public function test_consumes_valid_messages(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->publishMessage(
                exchange: self::TEST_EXCHANGE,
                routingKey: 'click.tracked',
                data: [
                    'shortcode' => $this->faker->lexify('??????'),
                    'ip' => $this->faker->ipv4(),
                    'userAgent' => $this->faker->userAgent(),
                    'timestamp' => now(),
                    'referer' => $this->faker->url(),
                ]
            );
        }

        // Wait for consumer to process
        $consumed = $this->waitForQueueCount(self::TEST_QUEUE, 0, 5);

        $this->assertTrue($consumed);
        $this->assertQueueEmpty(self::TEST_QUEUE);

        $saved = $this->waitForDatabaseCount('link_clicks', 3);
        $this->assertTrue($saved);
    }

    public function test_message_missing_required_fields_goes_to_dlq(): void
    {
        $this->publishMessage(
            exchange: self::TEST_EXCHANGE,
            routingKey: 'click.tracked',
            data: [
                'foo' => 'bar',
            ]
        );

        $this->waitForDLQMessage(self::TEST_DLQ, 3);

        $this->assertQueueEmpty(self::TEST_QUEUE);
        $this->assertQueueHasMessages(self::TEST_DLQ, 1);

        $dlqMessages = $this->peekMessages(self::TEST_DLQ, 1);

        $this->assertEquals('bar', $dlqMessages[0]['body']['foo']);
    }

    public function test_consumer_continues_after_pause(): void
    {
        $this->publishMessage(
            exchange: self::TEST_EXCHANGE,
            routingKey: 'click.tracked',
            data: [
                'shortcode' => $this->faker->lexify('??????'),
                'ip' => $this->faker->ipv4(),
                'userAgent' => $this->faker->userAgent(),
                'timestamp' => now(),
            ]
        );

        $this->waitForQueueCount(self::TEST_QUEUE, 0, 5);
        $this->assertQueueEmpty(self::TEST_QUEUE);

        // Pause (simulate idle time)
        sleep(5);

        $this->publishMessage(
            exchange: self::TEST_EXCHANGE,
            routingKey: 'click.tracked',
            data: [
                'shortcode' => $this->faker->lexify('??????'),
                'ip' => $this->faker->ipv4(),
                'userAgent' => $this->faker->userAgent(),
                'timestamp' => now(),
                'referer' => $this->faker->url(),
            ]
        );

        $this->waitForQueueCount(self::TEST_QUEUE, 0, 5);
        $this->assertQueueEmpty(self::TEST_QUEUE);

        $saved = $this->waitForDatabaseCount('link_clicks', 2);
        $this->assertTrue($saved);
    }

    public function test_consumer_maintains_accurate_stats(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->publishMessage(
                exchange: self::TEST_EXCHANGE,
                routingKey: 'click.tracked',
                data: [
                    'shortcode' => $this->faker->lexify('??????'),
                    'ip' => $this->faker->ipv4(),
                    'userAgent' => $this->faker->userAgent(),
                    'timestamp' => now(),
                ]
            );
        }

        $this->waitForQueueCount(self::TEST_QUEUE, 0, 10);

        $stats = $this->getQueueStats(self::TEST_QUEUE);

        $this->assertEquals(0, $stats['messages']);
        $this->assertGreaterThan(0, $stats['consumers']);
    }

    /**
     * Clean up ALL test data (in case previous test crashed)
     */
    private function cleanupAllTestData(): void
    {
        DB::table('link_clicks')->delete();
    }
}
