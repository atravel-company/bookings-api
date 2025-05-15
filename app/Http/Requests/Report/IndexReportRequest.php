<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

// Tuple sample:
// http://localhost:9000/api/reports?dates[0]=2025-02-01&dates[1]=2025-02-02

// Single date sample:
// http://localhost:9000/api/reports?dates=2025-02-01
class IndexReportRequest extends FormRequest
{
    public function rules()
    {
        // If the "dates" input is an array, then validate it as a period.
        if ($this->has('dates') && is_array($this->input('dates'))) {
            return [
                'dates' => 'array',
                'dates.0' => 'required|date',
                'dates.1' => 'required|date|after_or_equal:dates.0',
            ];
        }

        // Otherwise, allow a single date (nullable).
        return [
            'dates' => 'nullable|date',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('dates')) {
            $dates = $this->input('dates');
            if (is_string($dates)) {
                $dates = explode(',', $dates);
                count($dates) == 1 ? $dates = $dates[0] : $dates;
            }
            $this->merge(['dates' => $dates]);
        }
    }

    protected function passedValidation()
    {
        // If no date provided, default to today as a single date.
        if (!$this->has('dates') || empty($this->input('dates'))) {
            $this->merge(['dates' => Carbon::today()->toDateTimeString()]);
        }
    }
}