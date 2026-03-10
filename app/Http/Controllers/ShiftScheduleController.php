<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftScheduleRequest;
use App\Models\ShiftSchedule;
use App\Models\ShiftCode;
use App\Services\ShiftScheduleService;
use Illuminate\Http\Request;

class ShiftScheduleController extends Controller
{
    protected $service;

    public function __construct(ShiftScheduleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $schedules = $this->service->paginate(10);
        $shiftCodes = ShiftCode::with('shift')->get();
        return view('shift_schedules.index', compact('schedules', 'shiftCodes'));
    }

    public function store(ShiftScheduleRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return redirect()->route('shift_schedules.index')->with('success', 'Shift schedule created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create schedule: ' . $e->getMessage());
        }
    }

    public function update(ShiftScheduleRequest $request, ShiftSchedule $shiftSchedule)
    {
        try {
            $this->service->update($shiftSchedule, $request->validated());
            return redirect()->route('shift_schedules.index')->with('success', 'Shift schedule updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update schedule: ' . $e->getMessage());
        }
    }

    public function destroy(ShiftSchedule $shiftSchedule)
    {
        try {
            $this->service->delete($shiftSchedule);
            return redirect()->route('shift_schedules.index')->with('success', 'Shift schedule deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete schedule: ' . $e->getMessage());
        }
    }
}   
