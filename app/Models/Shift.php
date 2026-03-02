<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $fillable = ['name'];

    public function shiftCodes(): HasMany
    {
        return $this->hasMany(ShiftCode::class);
    }
}
