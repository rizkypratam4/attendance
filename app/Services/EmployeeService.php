<?php

namespace App\Services;

use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeService
{
    public function createEmployee(EmployeeRequest $request): Employee
    {
        $employee = Employee::create([
            'name' => $request->name,
            'nik' => $request->nik,
            'machine_barcode' => $request->machine_barcode,
            'branch_id' => $request->branch_id,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'location_id' => $request->location_id,
            'title' => $request->title,
            'employee_status' => $request->employee_status,
            'is_active' => $request->is_active,
        ]);

        Alert::success('Created!', $employee->name . ' has been created.');

        return $employee;
    }

    public function updateEmployee(Employee $employee, EmployeeRequest $request): Employee
    {
        $employee->update([
            'name' => $request->name,
            'nik' => $request->nik,
            'machine_barcode' => $request->machine_barcode,
            'branch_id' => $request->branch_id,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'location_id' => $request->location_id,
            'title' => $request->title,
            'employee_status' => $request->employee_status,
            'is_active' => $request->is_active,
        ]);

        Alert::success('Updated!', $employee->name . ' has been updated.');

        return $employee;
    }

    public function deleteEmployee(Employee $employee): void
    {
        $employee->delete();
        Alert::success('Deleted!', $employee->name . ' has been deleted.');
    }
}
