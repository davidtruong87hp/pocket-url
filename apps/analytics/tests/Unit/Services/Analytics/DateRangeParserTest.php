<?php

namespace Tests\Unit\Services\Analytics;

use App\Services\Analytics\DateRangeParser;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DateRangeParserTest extends TestCase
{
    private DateRangeParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new DateRangeParser;
    }

    public function test_it_parses_today_preset()
    {
        Carbon::setTestNow('2026-01-22 15:30:00');

        $result = $this->parser->parse(['dateRange' => 'today']);

        $this->assertEquals('2026-01-22 00:00:00', $result['start']->toDateTimeString());
        $this->assertEquals('2026-01-22 23:59:59', $result['end']->toDateTimeString());
    }

    public function test_it_parses_last_7_days_preset()
    {
        Carbon::setTestNow('2026-01-22');

        $result = $this->parser->parse(['dateRange' => 'last_7_days']);

        $this->assertEquals('2026-01-16', $result['start']->toDateString());
        $this->assertEquals('2026-01-22', $result['end']->toDateString());
    }

    public function test_it_parses_custom_date_range()
    {
        $result = $this->parser->parse([
            'startDate' => '2026-01-01',
            'endDate' => '2026-01-31',
        ]);

        $this->assertEquals('2026-01-01', $result['start']->toDateString());
        $this->assertEquals('2026-01-31', $result['end']->toDateString());
    }

    public function test_it_parses_with_timezone()
    {
        Carbon::setTestNow('2026-01-22 15:30:00');

        $result = $this->parser->parse([
            'startDate' => '2026-01-22',
            'endDate' => '2026-01-22',
            'timezone' => 'America/New_York',
        ]);

        $this->assertEquals('America/New_York', $result['timezone']);
        $this->assertEquals('America/New_York', $result['start']->timezone->getName());
    }
}
