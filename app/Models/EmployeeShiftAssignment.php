<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeShiftAssignment extends Model
{
    protected $fillable = [
        'employee_id',
        'shift_code_id',
        'effective_date',
        'end_date',
        'created_by',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'end_date'       => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shiftCode(): BelongsTo
    {
        return $this->belongsTo(ShiftCode::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }
}
