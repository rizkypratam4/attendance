<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftGroupRequest;
use App\Services\ShiftGroupService;


class ShiftGroupController extends Controller
{
    public function __construct(private ShiftGroupService $service) {}

    public function index() {
        $paginated = $this->service->getAllShiftGroups();
        
        return view('shift_groups.index', [
            'shiftGroups' => $paginated->items(),
            'currentPage' => $paginated->currentPage(),
            'totalPages' => $paginated->lastPage(),
            'from' => $paginated->firstItem(),
            'to' => $paginated->lastItem(),
            'total' => $paginated->total(),
        ]);
    }

    public function store(ShiftGroupRequest $request) {
        try {
            $this->service->createShiftGroup($request->validated());
            return redirect()->route('shift_groups.index')->with('success', 'Shift group created successfully.');
        } catch (\Exception $e) {
            logger()->error('Failed to create shift group', ['error' => $e->getMessage()]);
            return redirect()->route('shift_groups.index')->withErrors('An error occurred while creating the shift group.');
        }
    }

    public function update(ShiftGroupRequest $request, $id) {
        try {
            $this->service->updateShiftGroup($id, $request->validated());
            return redirect()->route('shift_groups.index')->with('success', 'Shift group updated successfully.');
        } catch (\Exception $e) {
            logger()->error('Failed to update shift group', ['error' => $e->getMessage()]);
            return redirect()->route('shift_groups.index')->withErrors('An error occurred while updating the shift group.');
        }
    }

    public function destroy($id) {
        try {
            $this->service->deleteShiftGroup($id);
            return redirect()->route('shift_groups.index')->with('success', 'Shift group deleted successfully.');
        } catch (\Exception $e) {
            logger()->error('Failed to delete shift group', ['error' => $e->getMessage()]);
            return redirect()->route('shift_groups.index')->withErrors('An error occurred while deleting the shift group.');
        }
    }
}
