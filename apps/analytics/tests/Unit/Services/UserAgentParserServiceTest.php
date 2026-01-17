<?php

namespace Tests\Unit\Services;

use App\Services\UserAgentParserService as ServicesUserAgentParserService;
use Tests\TestCase;

class UserAgentParserServiceTest extends TestCase
{
    private ServicesUserAgentParserService $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new ServicesUserAgentParserService;
    }

    public function test_parses_with_empty_user_agent()
    {
        $this->assertEquals([
            'is_bot' => false,
            'is_mobile' => false,
            'device_type' => null,
            'device_brand' => null,
            'device_model' => null,
            'os_name' => null,
            'os_version' => null,
            'browser_name' => null,
            'browser_version' => null,
        ], $this->parser->parse(''));
    }

    public function test_parses_device_information_from_browser()
    {
        $this->assertEquals([
            'isBot' => false,
            'isMobile' => true,
            'device_type' => 'phone',
            'device_brand' => 'iPhone',
            'device_model' => null,
            'os_name' => 'iOS',
            'os_version' => '13_2_3',
            'browser_name' => 'Safari',
            'browser_version' => '13.0.3',
        ], $this->parser->parse('Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1'));
    }

    public function test_parses_device_information_from_bot()
    {
        $this->assertEquals([
            'isBot' => true,
            'isMobile' => false,
            'device_type' => 'robot',
            'device_brand' => 'Bot',
            'device_model' => null,
            'os_name' => false,
            'os_version' => false,
            'browser_name' => 'Mozilla',
            'browser_version' => false,
        ], $this->parser->parse('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'));
    }

    public function test_parses_device_information_from_android_device()
    {
        $this->assertEquals([
            'isBot' => false,
            'isMobile' => true,
            'device_type' => 'tablet',
            'device_brand' => 'Pixel',
            'device_model' => null,
            'os_name' => 'AndroidOS',
            'os_version' => '7.0',
            'browser_name' => 'Chrome',
            'browser_version' => '52.0.2743.98',
        ], $this->parser->parse('Mozilla/5.0 (Linux; Android 7.0; Pixel C Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/52.0.2743.98 Safari/537.36'));
    }
}
