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
        $this->assertEquals([], $this->parser->parse(''));
    }

    public function test_parses_with_valid_browser_user_agent()
    {
        $this->assertEquals([
            'isBot' => false,
            'isMobile' => false,
        ], $this->parser->parse('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36'));
    }

    public function test_parses_with_valid_bot_user_agent()
    {
        $this->assertEquals([
            'isBot' => true,
            'isMobile' => false,
        ], $this->parser->parse('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'));
    }

    public function test_parses_with_valid_mobile_user_agent()
    {
        $this->assertEquals([
            'isBot' => false,
            'isMobile' => true,
        ], $this->parser->parse('Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1'));
    }
}
