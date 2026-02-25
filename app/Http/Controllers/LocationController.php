<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(private LocationService $locationService){}

    public function index(Request $request)
    {
        $locations = Location::latest()->paginate(5)->withQueryString();
        $totalLocations  = $locations->total();               
        $activeLocations = $locations->where('is_active', 1)->count();
        $typesCount      = $locations->pluck('location_type')->countBy();
        $typeSummary     = $typesCount->map(fn($count, $type) => "$count $type")->join(', ');

        return view('locations.index', compact(
            'locations',
            'totalLocations',
            'activeLocations',
            'typeSummary'
        ));
    }

    public function store(LocationRequest $request)
    {
        try {
            $this->locationService->createLocation($request);
            return redirect()->route('locations.index')->with('success', 'Location added successfully');
        } catch (\Throwable $e) {
            logger()->error('Add location failed', [
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('locations.index')->withErrors('Failed to add location');
        }
    }

    public function update(LocationRequest $request, Location $location)
    {
        try {
            $this->locationService->updateLocation($location, $request);
            return redirect()->route('locations.index')->with('success', 'Location updated successfully');
        } catch (\Throwable $e) {
            logger()->error('Update location failed', [
                'location_id' => $location->id,
                'error'       => $e->getMessage(),
            ]);
            return redirect()->route('locations.index')->withErrors('Failed to update location');
        }
    }

    public function destroy(Location $location)
    {
        $this->locationService->deleteLocation($location);
        return redirect()->route('locations.index');
    }
}
