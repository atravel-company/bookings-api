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
        $dateRules = [];
        if (is_array($this->input('dates'))) {
            $dateRules = [
                'dates' => 'required|array|size:2',
                'dates.*' => 'required|date_format:Y-m-d',
                'dates.1' => 'after_or_equal:dates.0',
            ];
        } else {
            $dateRules = [
                'dates' => 'nullable|date_format:Y-m-d'
            ];
        }

        $userIdRules = [];
        if (is_array($this->input('users'))) {
            $userIdRules = [
                'users' => 'nullable|array',
                'users.*' => 'integer|exists:users,id',
            ];
        } else {
            $userIdRules = [
                'users' => 'nullable|integer|exists:users,id',
            ];
        }

        return array_merge($dateRules, $userIdRules);
    }
}