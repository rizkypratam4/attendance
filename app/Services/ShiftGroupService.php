<?php

namespace App\Services;

use App\Models\Shift;
use RealRashid\SweetAlert\Facades\Alert;

class ShiftGroupService
{
    public function getAllShiftGroups()
    {
        return Shift::latest()->paginate(5)->withQueryString();
    }
    public function createShiftGroup(array $data): Shift
    {
        $shiftGroup = Shift::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        Alert::success('Created!', 'Shift group "' . $shiftGroup->name . '" has been created.');

        return $shiftGroup;
    }

    public function updateShiftGroup($id, array $data): Shift
    {
        $shiftGroup = Shift::findOrFail($id);
        $shiftGroup->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        Alert::success('Updated!', 'Shift group "' . $shiftGroup->name . '" has been updated.');

        return $shiftGroup;
    }

    public function deleteShiftGroup($id): void
    {
        $shiftGroup = Shift::find($id);
        if ($shiftGroup) {
            $shiftGroup->delete();
            Alert::success('Deleted!', 'Shift group has been deleted.');
        }
    }
}
