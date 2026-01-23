<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\GetAnalyticsRequest;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GetAnalyticsRequestTest extends TestCase
{
    private GetAnalyticsRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new GetAnalyticsRequest;
    }

    #[DataProvider('invalidDataProvider')]
    public function test_it_fails_validation(array $data)
    {
        $rules = $this->request->rules();
        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
    }

    #[DataProvider('validDataProvider')]
    public function test_it_passes_validation(array $data)
    {
        $rules = $this->request->rules();
        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes());
    }

    public static function invalidDataProvider()
    {
        return [
            'invalid start date' => [['startDate' => 'invalid-date']],
            'invalid end date' => [['endDate' => 'invalid-date']],
            'start date after end date' => [['startDate' => '2023-01-01', 'endDate' => '2022-01-01']],
            'invalid date range' => [['dateRange' => 'invalid-range']],
        ];
    }

    public static function validDataProvider()
    {
        return [
            'valid start date' => [['startDate' => '2023-01-01']],
            'valid end date' => [['endDate' => '2023-01-01']],
            'valid custom date range' => [['startDate' => '2023-01-01', 'endDate' => '2023-01-31']],
            'valid date range preset' => [['dateRange' => 'today']],
        ];
    }
}
