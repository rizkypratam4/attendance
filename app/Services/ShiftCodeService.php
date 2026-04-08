<?php

namespace App\Services;

use App\Models\ShiftCode;
use Illuminate\Database\Eloquent\Collection;

class ShiftCodeService
{
    public function getAll(): Collection
    {
        return ShiftCode::with('shift')->get();
    }

    public function create(array $data): ShiftCode
    {
        return ShiftCode::create($data);
    }

    public function update(ShiftCode $shiftCode, array $data): ShiftCode
    {
        $shiftCode->update($data);
        return $shiftCode->fresh();
    }

    public function delete(ShiftCode $shiftCode): bool
    {
        return $shiftCode->delete();
    }

    public function findById(int $id): ShiftCode
    {
        return ShiftCode::with('shift')->findOrFail($id);
    }
}
