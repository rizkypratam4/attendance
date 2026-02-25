<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftDefinition extends Model
{
    protected $fillable = [
        'shift_code','shift_group_id','start_time','end_time',
        'break_minutes','is_off','idt_allowed','idt_cutoff_time','notes'
    ];

    protected $casts = [
        'is_off' => 'boolean',
        'idt_allowed' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(ShiftGroup::class, 'shift_group_id');
    }

    public function dayRules()
    {
        return $this->hasMany(ShiftDayRule::class);
    }

    public function schedules()
    {
        return $this->hasMany(EmployeeSchedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
