<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftCodeRequest;
use App\Models\Shift;
use App\Models\ShiftCode;
use App\Services\ShiftCodeService;
use Illuminate\Http\Request;

class ShiftCodeController extends Controller
{
    protected $shiftCodeService;

    public function __construct(ShiftCodeService $shiftCodeService)
    {
        $this->shiftCodeService = $shiftCodeService;
    }

    public function index()
    {
        $shiftCodes = ShiftCode::with('shift')->paginate(10);
        $shifts = Shift::all();
        return view('shift_codes.index', compact('shiftCodes', 'shifts'));
    }

    public function create()
    {
        $shifts = Shift::all();
        return view('shift_codes.create', compact('shifts'));
    }

    public function store(ShiftCodeRequest $request)
    {
        try {
            $this->shiftCodeService->create($request->validated());
            return redirect()->route('shift_codes.index')->with('success', 'Shift Code created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create Shift Code: ' . $e->getMessage());
        }
    }

    public function update(ShiftCodeRequest $request, ShiftCode $shiftCode)
    {
        try {
            $this->shiftCodeService->update($shiftCode, $request->validated());
            return redirect()->route('shift_codes.index')->with('success', 'Shift Code updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update Shift Code: ' . $e->getMessage());
        }
    }

    public function destroy(ShiftCode $shiftCode)
    {
        try {
            $this->shiftCodeService->delete($shiftCode);
            return redirect()->route('shift_codes.index')->with('success', 'Shift Code deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete Shift Code: ' . $e->getMessage());
        }
    }
}
