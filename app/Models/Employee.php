<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    public $fillable = [
        'name',
        'nik',
        'machine_barcode',
        'department_id',
        'employee_status',
        'contract_count',
        'branch_id',
        'position',
        'location_id',
        'title',
        'is_active',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function shiftAssignments(): HasMany
    {
        return $this->hasMany(EmployeeShiftAssignment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
   
    public function getActiveShiftCode(?string $date = null): ?ShiftCode
    {
        $date = $date ?? now()->toDateString();

        $assignment = $this->shiftAssignments()
            ->where('effective_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $date);
            })
            ->latest('effective_date')
            ->first();

        return $assignment?->shiftCode;
    }

    public function getActiveShiftSchedule(?string $date = null): ?ShiftSchedule
    {
        $date      = $date ?? now()->toDateString();
        $dayType   = self::determineDayType($date);
        $shiftCode = $this->getActiveShiftCode($date);

        return $shiftCode?->schedules()->where('day_type', $dayType)->first();
    }

    public static function determineDayType(string $date): string
    {
        return match (Carbon::parse($date)->dayOfWeek) {
            Carbon::FRIDAY   => 'jumat',
            Carbon::SATURDAY => 'sabtu',
            default          => 'senin_kamis',
        };
    }
}
