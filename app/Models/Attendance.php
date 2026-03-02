<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    const STATUS_PRESENT    = 'present';
    const STATUS_ABSENT     = 'absent';
    const STATUS_LATE       = 'late';
    const STATUS_DAY_OFF    = 'day_off';
    const STATUS_PERMIT     = 'permit';
    const STATUS_SICK       = 'sick';
    const STATUS_HOLIDAY    = 'holiday';
    const STATUS_INCOMPLETE = 'incomplete';
    const STATUS_NO_CLOCKIN = 'no_clockin';

    protected $fillable = [
        'employee_id',
        'shift_schedule_id',
        'attendance_date',
        'clock_in',
        'clock_in_location',
        'clock_in_photo',
        'clock_in_device',
        'idt_time',
        'idt_location',
        'idt_photo',
        'clock_out',
        'clock_out_location',
        'clock_out_photo',
        'clock_out_device',
        'work_duration_minutes',
        'late_minutes',
        'early_leave_minutes',
        'overtime_minutes',
        'status',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'clock_in'        => 'datetime',
        'idt_time'        => 'datetime',
        'clock_out'       => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shiftSchedule(): BelongsTo
    {
        return $this->belongsTo(ShiftSchedule::class);
    }

    public function scopeOnDate($query, string $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('attendance_date', now()->month)
                     ->whereYear('attendance_date', now()->year);
    }

    public function hasIdt(): bool
    {
        return $this->shiftSchedule?->shiftCode?->has_idt ?? false;
    }
}
