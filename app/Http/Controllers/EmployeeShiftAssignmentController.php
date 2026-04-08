<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeShiftAssignmentImportRequest;
use App\Http\Requests\EmployeeShiftAssignmentRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeShiftAssignment;
use App\Models\ShiftCode;
use App\Services\EmployeeShiftAssignmentService;

class EmployeeShiftAssignmentController extends Controller
{
    protected EmployeeShiftAssignmentService $service;

    public function __construct(EmployeeShiftAssignmentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $assignments = $this->service->getAll();
        $employees   = Employee::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $shiftCodes  = ShiftCode::with('shift')->orderBy('code')->get();

        return view('employee_shift_assignments.index', compact(
            'assignments',
            'employees',
            'departments',
            'shiftCodes'
        ));
    }

    /**
     * Handle import request from spreadsheet/csv
     */
    public function import(EmployeeShiftAssignmentImportRequest $request)
    {
        $result = $this->service->import($request->file('file'));

        if (!empty($result['errors'])) {
            return redirect()->back()->with('import_errors', $result['errors']);
        }

        return redirect()->back()->with('import_success', $result['success']);
    }

    /**
     * Store a newly created assignment.
     */
    public function store(EmployeeShiftAssignmentRequest $request)
    {
        $this->service->createAssignment($request->validated());
        return redirect()->back();
    }

    /**
     * Return JSON details for editing (used by JS if needed).
     */
    public function edit(EmployeeShiftAssignment $employee_shift_assignment)
    {
        return response()->json(
            $employee_shift_assignment->load(['employee.department','shiftCode.shift'])
        );
    }

    /**
     * Update an existing assignment.
     */
    public function update(EmployeeShiftAssignmentRequest $request, EmployeeShiftAssignment $employee_shift_assignment)
    {
        $this->service->updateAssignment($employee_shift_assignment, $request->validated());
        return redirect()->back();
    }

    /**
     * Delete an assignment.
     */
    public function destroy(EmployeeShiftAssignment $employee_shift_assignment)
    {
        $this->service->deleteAssignment($employee_shift_assignment);
        return redirect()->back();
    }
}