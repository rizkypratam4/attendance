<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FingerprintLog extends Model
{
    protected $fillable = [
        'barcode','attendance_date','attendance_time','attendance_type','synced_at'
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'synced_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'barcode', 'machine_barcode');
    }
}
