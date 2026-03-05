<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use App\Services\BranchService;

class BranchController extends Controller
{
    public function __construct(private BranchService $branchService){}
    public function index()
    {
        $branches = Branch::latest()->paginate(5)->withQueryString();
        return view('branches.index', compact('branches'));
    }

    public function store(BranchRequest $request)
    {
        try {
            $this->branchService->createBranch($request);
            return redirect()->route('branches.index')->with('success', 'Branch added successfully');
        } catch (\Throwable $e) {
            logger()->error('Add branch failed', [
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('branches.index')->withErrors('Failed to add branch');
        }
    }

    public function update(BranchRequest $request, Branch $branch)
    {
        try {
            $this->branchService->updateBranch($branch, $request);
            return redirect()->route('branches.index')->with('success', 'Branch updated successfully');
        } catch (\Throwable $e) {
            logger()->error('Update branch failed', [
                'branch_id' => $branch->id,
                'error'       => $e->getMessage(),
            ]);
            return redirect()->route('branches.index')->withErrors('Failed to update branch');
        }
    }

    public function destroy(Branch $branch)
    {
        try {
            $this->branchService->deleteBranch($branch);
            return redirect()->route('branches.index')->with('success', 'Branch deleted successfully');
        } catch (\Throwable $e) {
            logger()->error('Delete branch failed', [
                'branch_id' => $branch->id,
                'error'       => $e->getMessage(),
            ]);
            return redirect()->route('branches.index')->withErrors('Failed to delete branch');
        }
    }
}
