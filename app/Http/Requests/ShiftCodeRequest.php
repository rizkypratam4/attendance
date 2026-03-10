<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShiftCodeRequest extends FormRequest
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
        $shiftCodeId = $this->route('shift_code') ? $this->route('shift_code')->id : null;

        return [
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('shift_codes', 'code')->ignore($shiftCodeId),
            ],
            'shift_id' => 'required|exists:shifts,id',
            'has_idt' => 'required|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Shift Code is required.',
            'code.unique' => 'This Shift Code already exists.',
            'code.max' => 'Shift Code must not exceed 10 characters.',
            'shift_id.required' => 'Please select a Shift.',
            'shift_id.exists' => 'Selected Shift does not exist.',
            'has_idt.required' => 'IDT Status is required.',
            'has_idt.boolean' => 'IDT Status must be Yes or No.',
        ];
    }
}
