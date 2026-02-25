<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function schedules()
    {
        return $this->hasMany(EmployeeSchedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function fingerprintLogs()
    {
        return $this->hasMany(FingerprintLog::class, 'barcode', 'machine_barcode');
    }
    
}
