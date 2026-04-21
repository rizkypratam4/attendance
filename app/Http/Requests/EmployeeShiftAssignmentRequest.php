<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeShiftAssignmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'          => ['required', 'exists:employees,id'],
            'shift_code_id'        => ['nullable', 'exists:shift_codes,id'],
            'new_working_shift_id' => ['nullable', 'exists:shift_codes,id'],
            'date'                 => ['required', 'date'],
        ];
    }
}
