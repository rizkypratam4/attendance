<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftGroup extends Model
{
    protected $fillable = ['name','sort_order'];

    public function shiftDefinitions()
    {
        return $this->hasMany(ShiftDefinition::class);
    }
}
