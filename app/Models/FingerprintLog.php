<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FingerprintLog extends Model
{
    const UPDATED_AT = null;

    const TYPE_CLOCK_IN  = 0;
    const TYPE_CLOCK_OUT = 1;

    protected $fillable = [
        'barcode',
        'attendance_date',
        'attendance_time',
        'attendance_type',
        'is_processed',
        'processed_at',
        'raw_created_date',
    ];

    protected $casts = [
        'attendance_date'  => 'date',
        'is_processed'     => 'boolean',
        'processed_at'     => 'datetime',
        'raw_created_date' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'barcode', 'machine_barcode');
    }

    public function scopeUnprocessed($query)
    {
        return $query->where('is_processed', false);
    }
}
