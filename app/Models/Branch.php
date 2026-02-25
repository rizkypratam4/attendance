<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name','location_id','is_active'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
