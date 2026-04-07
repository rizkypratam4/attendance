<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    const STATUS_PRESENT = 'present';
    const STATUS_LATE    = 'late';
    const STATUS_ABSENT  = 'absent';
    const STATUS_DAY_OFF = 'day_off';
    const STATUS_HOLIDAY = 'holiday';
    const STATUS_PERMIT  = 'permit';
    const STATUS_SICK    = 'sick';

    protected $fillable = [
        'employee_id',
        'shift_code_id',     // ← shift saat absen
        'attendance_date',
        'clock_in',
        'clock_out',
        'late_minutes',
        'work_duration_minutes',
        'status',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'clock_in'        => 'datetime',
        'clock_out'       => 'datetime',
    ];

    // ==========================================
    // RELASI
    // ==========================================

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shiftCode(): BelongsTo
    {
        return $this->belongsTo(ShiftCode::class);
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeOnDate($query, string $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('attendance_date', today());
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('attendance_date', now()->month)
                     ->whereYear('attendance_date', now()->year);
    }

    public function scopePresent($query)
    {
        return $query->whereIn('status', [self::STATUS_PRESENT, self::STATUS_LATE]);
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', self::STATUS_ABSENT);
    }

    public function scopeLate($query)
    {
        return $query->where('status', self::STATUS_LATE);
    }

    // ==========================================
    // HELPERS
    // ==========================================

    public function isPresent(): bool
    {
        return in_array($this->status, [self::STATUS_PRESENT, self::STATUS_LATE]);
    }

    public function isLate(): bool
    {
        return $this->status === self::STATUS_LATE;
    }

    public function isAbsent(): bool
    {
        return $this->status === self::STATUS_ABSENT;
    }

    public function isDayOff(): bool
    {
        return in_array($this->status, [self::STATUS_DAY_OFF, self::STATUS_HOLIDAY]);
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            self::STATUS_PRESENT => 'Hadir',
            self::STATUS_LATE    => 'Terlambat',
            self::STATUS_ABSENT  => 'Tidak Hadir',
            self::STATUS_DAY_OFF => 'Day Off',
            self::STATUS_HOLIDAY => 'Libur',
            self::STATUS_PERMIT  => 'Izin',
            self::STATUS_SICK    => 'Sakit',
            default              => '-',
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            self::STATUS_PRESENT => '#22c55e',
            self::STATUS_LATE    => '#f59e0b',
            self::STATUS_ABSENT  => '#ef4444',
            self::STATUS_DAY_OFF => '#64748b',
            self::STATUS_HOLIDAY => '#64748b',
            self::STATUS_PERMIT  => '#60a5fa',
            self::STATUS_SICK    => '#a78bfa',
            default              => '#64748b',
        };
    }
}