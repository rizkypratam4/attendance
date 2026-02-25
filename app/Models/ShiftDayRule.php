<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftDayRule extends Model
{
    protected $fillable = ['shift_definition_id','day_of_week','notes'];

    public function shift()
    {
        return $this->belongsTo(ShiftDefinition::class, 'shift_definition_id');
    }
}
