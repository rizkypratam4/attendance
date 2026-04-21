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

    public function import(EmployeeShiftAssignmentImportRequest $request)
    {
        $result = $this->service->import($request->file('file'));

        if ($result['success'] === 0 && empty($result['errors'])) {
            return redirect()->back()->with('import_errors', ['Tidak ada data yang berhasil diimport.']);
        }

        if (!empty($result['errors'])) {
            return redirect()->back()
                ->with('import_errors', $result['errors'])
                ->with('import_success', $result['success']);
        }

        return redirect()->back()->with('import_success', $result['success']);
    }

    public function store(EmployeeShiftAssignmentRequest $request)
    {
        $this->service->createAssignment($request->validated());
        return redirect()->back();
    }

    public function edit(EmployeeShiftAssignment $employee_shift_assignment)
    {
        return response()->json(
            $employee_shift_assignment->load(['employee.department', 'shiftCode.shift'])
        );
    }

    public function update(EmployeeShiftAssignmentRequest $request, EmployeeShiftAssignment $employee_shift_assignment)
    {
        $data = $request->validated();
        $data['new_working_shift_id'] = $request->input('new_working_shift_id') ?: null;
        $this->service->updateAssignment($employee_shift_assignment, $data);
        return redirect()->back()->with('success', 'Assignment berhasil diupdate');
    }

    public function destroy(EmployeeShiftAssignment $employee_shift_assignment)
    {
        $this->service->deleteAssignment($employee_shift_assignment);
        return redirect()->back()->with('success', 'Assignment berhasil dihapus');
    }

    public function bulkAssign()
    {
        $validated = request()->validate([
            'assign_type'    => 'required|in:employee,department,operator',
            'shift_codes'    => 'required|array|min:1',
            'shift_codes.*'  => 'exists:shift_codes,id',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'employee_ids'   => 'array',
            'employee_ids.*' => 'exists:employees,id',
            'department_ids'   => 'array',
            'department_ids.*' => 'exists:departments,id',
            'operator_ids'   => 'array',
            'operator_ids.*' => 'exists:employees,id',
        ]);

        $result = $this->service->bulkAssign($validated);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }
}
