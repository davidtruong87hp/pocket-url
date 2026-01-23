<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

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
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->input('startDate') && $this->input('endDate')) {
                    $start = Carbon::parse($this->input('startDate'));
                    $end = Carbon::parse($this->input('endDate'));

                    if ($start->diffInDays($end) > 90) {
                        $validator->errors()->add('endDate', 'The end date must be within 90 days of the start date.');
                    }
                }
            },
        ];
    }
}
