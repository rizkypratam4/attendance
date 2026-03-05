<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Location;
use App\Services\EmployeeImportService;
use App\Services\EmployeeService;
use Illuminate\Http\Request;


class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['branch','department','location'])->latest()->paginate(10)->withQueryString();
        $branches = Branch::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('employees.index', compact('employees', 'branches', 'departments', 'locations'));
    }

    public function import(Request $request, EmployeeImportService $importService)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120',
        ]);

        try {
            $result = $importService->import($request->file('file'));
            $msg = "Imported {$result['success']} rows.";
            if (!empty($result['errors'])) {
                return redirect()->route('employees.index')->with('success', $msg)->with('import_errors', $result['errors']);
            }

            return redirect()->route('employees.index')->with('success', $msg);
        } catch (\Throwable $e) {
            logger()->error('Employee import failed', ['error' => $e->getMessage()]);
            return redirect()->route('employees.index')->withErrors('Import failed: ' . $e->getMessage());
        }
    }

    public function store(EmployeeRequest $request, EmployeeService $service)
    {
        try {
            $service->createEmployee($request);
            return redirect()->route('employees.index')->with('success', 'Employee created successfully');
        } catch (\Throwable $e) {
            logger()->error('Create employee failed', [
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('employees.index')->withErrors('Failed to create employee');
        }
    }

    public function update(Employee $employee, EmployeeRequest $request, EmployeeService $service)
    {
        try {
            $service->updateEmployee($employee, $request);
            return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
        } catch (\Throwable $e) {
            logger()->error('Update employee failed', [
                'employee_id' => $employee->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('employees.index')->withErrors('Failed to update employee');
        }
    }

    public function destroy(Employee $employee, EmployeeService $service)
    {
        try {
            $service->deleteEmployee($employee);
            return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
        } catch (\Throwable $e) {
            logger()->error('Delete employee failed', [
                'employee_id' => $employee->id,
                'error'       => $e->getMessage(),
            ]);
            return redirect()->route('employees.index')->withErrors('Failed to delete employee');
        }
    }
}
