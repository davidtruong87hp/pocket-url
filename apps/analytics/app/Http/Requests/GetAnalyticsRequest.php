<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAnalyticsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'dateRange' => 'nullable|string|in:today,yesterday,last_7_days,last_30_days,this_month,last_month',
            'groupBy' => 'nullable|string|in:day,week,month',
        ];
    }
}
