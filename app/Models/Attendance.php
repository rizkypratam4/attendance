<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id','work_date','shift_definition_id',
        'check_in','check_out',
        'late_minutes','early_leave_minutes',
        'idt_used','idt_reason','remark'
    ];

    protected $casts = [
        'work_date' => 'date',
        'idt_used' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(ShiftDefinition::class, 'shift_definition_id');
    }

    public function schedule()
    {
        return $this->hasOne(EmployeeSchedule::class, 'employee_id', 'employee_id')
            ->whereColumn('employee_schedules.work_date', 'attendances.work_date');
    }
}
