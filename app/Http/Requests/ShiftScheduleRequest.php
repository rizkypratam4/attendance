<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShiftScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_day_off' => $this->has('is_day_off') ? 1 : 0,
            'is_overnight' => $this->has('is_overnight') ? 1 : 0,
        ]);
    }

    public function rules(): array
    {
        $scheduleId = $this->route('shift_schedule') ? $this->route('shift_schedule')->id : null;

        return [
            'shift_code_id' => 'required|exists:shift_codes,id',
            'day_type' => [
                'required',
                Rule::in(['senin_kamis', 'jumat', 'sabtu']),
                Rule::unique('shift_schedules')->where(function ($query) {
                    return $query->where('shift_code_id', $this->input('shift_code_id'));
                })->ignore($scheduleId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'shift_code_id.required' => 'Please select a Shift Code.',
            'shift_code_id.exists' => 'Selected Shift Code does not exist.',
            'day_type.required' => 'Day type is required.',
            'day_type.in' => 'Invalid day type.',
            'day_type.unique' => 'Schedule for this day and shift code already exists.',
        ];
    }
}