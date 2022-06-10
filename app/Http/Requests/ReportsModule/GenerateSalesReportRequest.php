<?php

namespace App\Http\Requests\ReportsModule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GenerateSalesReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->designation == 'manager';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date_from' => ['required', 'date', 'before_or_equal:date_to', 'date_format:Y-m-d'],
            'date_to' => ['required', 'date', 'after_or_equal:date_from', 'before_or_equal:today', 'date_format:Y-m-d'],
        ];
    }
}
