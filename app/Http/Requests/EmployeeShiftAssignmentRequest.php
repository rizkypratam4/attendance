<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeShiftAssignmentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // add auth logic if needed
    }

    public function rules()
    {
        return [
            'employee_id'    => ['required','exists:employees,id'],
            'shift_code_id'  => ['required','exists:shift_codes,id'],
            'effective_date' => ['required','date'],
            'end_date'       => ['nullable','date','after_or_equal:effective_date'],
        ];
    }
}
