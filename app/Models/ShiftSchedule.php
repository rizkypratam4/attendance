<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\ShiftCode;

class ShiftSchedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shift_code_id',
        'day_type',
        'schedule_code',
        'start_time',
        'end_time',
        'is_day_off',
        'is_overnight',
    ];

    /**
     * The shift code this schedule belongs to.
     */
    public function shiftCode()
    {
        return $this->belongsTo(ShiftCode::class);
    }
}
