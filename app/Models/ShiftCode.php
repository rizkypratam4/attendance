<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShiftCode extends Model
{
    protected $fillable = [
        'shift_id',
        'code',
        'on_time',
        'off_time',
        'is_day_off',
        'has_idt',
    ];

    protected $casts = [
        'is_day_off' => 'boolean',
        'has_idt'    => 'boolean',
    ];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(EmployeeShiftAssignment::class);
    }
}