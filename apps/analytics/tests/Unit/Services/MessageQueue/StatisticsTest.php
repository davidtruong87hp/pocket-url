<?php

namespace Tests\Unit\Services\MessageQueue;

use App\Services\MessageQueue\Statistics;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    private Statistics $stats;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stats = new Statistics;
    }

    public function test_records_success()
    {
        $this->stats->recordSuccess(100.5);

        $this->assertEquals(1, $this->stats->getTotalProcessed());
        $this->assertEquals(0, $this->stats->getTotalFailed());
        $this->assertEquals(100.5, $this->stats->getAverageProcessingTime());
    }

    public function test_records_failure()
    {
        $this->stats->recordFailure();

        $this->assertEquals(0, $this->stats->getTotalProcessed());
        $this->assertEquals(1, $this->stats->getTotalFailed());
    }

    public function test_calculate_success_rate()
    {
        $this->stats->recordSuccess(100);
        $this->stats->recordSuccess(100);
        $this->stats->recordSuccess(100);
        $this->stats->recordFailure();

        $this->assertEquals(75.0, $this->stats->getSuccessRate());
    }

    public function test_calculate_average_processing_time()
    {
        $this->stats->recordSuccess(100);
        $this->stats->recordSuccess(200);
        $this->stats->recordSuccess(300);

        $this->assertEquals(200.0, $this->stats->getAverageProcessingTime());
    }

    public function test_limits_processing_time_samples()
    {
        for ($i = 0; $i < 1500; $i++) {
            $this->stats->recordSuccess(100);
        }

        $this->assertEquals(1500, $this->stats->getTotalProcessed());
        $this->assertEquals(100.0, $this->stats->getAverageProcessingTime());
    }
}
