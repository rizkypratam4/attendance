<?php

namespace App\Services;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentService
{
    public function createDepartment(DepartmentRequest $request): Department
    {
        $department = Department::create([
            'name'        => $request->name,
            'head_employee_id' => $request->head_employee_id,
            'subtitle'    => $request->subtitle,
        ]);

        Alert::success('Created!', $department->name . ' has been added.');

        return $department;
    }

    public function updateDepartment(Department $department, DepartmentRequest $request): Department
    {
        $department->update([
            'name'        => $request->name,
            'head_employee_id' => $request->head_employee_id,
            'subtitle'    => $request->subtitle,
        ]);

        Alert::success('Updated!', $department->name . ' has been updated.');

        return $department;
    }

    public function deleteDepartment(Department $department): void
    {
        $department->delete();
        Alert::success('Deleted!', 'Location has been deleted.');
    }
}
