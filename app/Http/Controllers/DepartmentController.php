<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct(private DepartmentService $departmentService){}
    
    public function index()
    {
        $query = Department::withCount('employees');
        
        // Search
        if (request('search')) {
            $search = '%' . request('search') . '%';
            $query->where('name', 'like', $search)
                  ->orWhere('subtitle', 'like', $search);
        }
        
        $departments = $query->latest()->paginate(10)->withQueryString();
        $totalEmployees = \App\Models\Employee::whereNotNull('department_id')->count();
        return view('departments.index', compact('departments', 'totalEmployees'));
    }

    public function store(DepartmentRequest $request)
    {
        try {
            $this->departmentService->createDepartment($request);
            return redirect()->route('departments.index');
        } catch (\Throwable $e) {
            logger()->error('Add department failed', [
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('departments.index')->withErrors('Failed to add department');
        }
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        try {
            $this->departmentService->updateDepartment($department, $request);
            return redirect()->route('departments.index');
        } catch (\Throwable $e) {
            logger()->error('Update department failed', [
                'department_id' => $department->id,
                'error'       => $e->getMessage(),
            ]);
            return redirect()->route('departments.index')->withErrors('Failed to update department');
        }
    }

    public function destroy(Department $department)
    {
        $this->departmentService->deleteDepartment($department);
        return redirect()->route('departments.index');
    }
}
