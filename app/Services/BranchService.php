<?php

namespace App\Services;

use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use RealRashid\SweetAlert\Facades\Alert;

class BranchService
{
    public function createBranch(BranchRequest $request): Branch
    {
        $branch = Branch::create([
            'name'        => $request->name,
            'is_active' => $request->is_active,
        ]);

        Alert::success('Created!', $branch->name . ' has been added.');

        return $branch;
    }

    public function updateBranch(Branch $branch, BranchRequest $request): Branch
    {
        $branch->update([
            'name'        => $request->name,
            'is_active' => $request->is_active,
        ]);

        Alert::success('Updated!', $branch->name . ' has been updated.');

        return $branch;
    }

    public function deleteBranch(Branch $branch): void
    {
        $branch->delete();
        Alert::success('Deleted!', 'Branch has been deleted.');
    }
}
