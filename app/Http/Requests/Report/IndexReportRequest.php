<?php

namespace App\Http\Requests\Report;

use App\Http\Requests\Traits\NormalizesCommaSeparated;
use Illuminate\Foundation\Http\FormRequest;
    
// Tuple sample:
// http://localhost:9000/api/reports?dates[0]=2025-02-01&dates[1]=2025-02-02

// Single date sample:
// http://localhost:9000/api/reports?dates=2025-02-01
class IndexReportRequest extends FormRequest
{
    use NormalizesCommaSeparated;

    public function rules()
    {
        $rules = [
            'user_id' => 'nullable|integer|exists:users,id', // Assuming 'users' table and 'id' column
        ];

        if (is_array($this->input('dates'))) {
            return array_merge($rules, [
                'dates' => 'array',
                'dates.*' => 'date',
                'dates.1' => 'after_or_equal:dates.0',
            ]);
        }

        return array_merge($rules, [
            'dates' => 'nullable|date'
        ]);
    }
}