<?php

namespace App\Services;

use App\Http\Requests\LocationRequest;
use App\Models\Location;
use RealRashid\SweetAlert\Facades\Alert;

class LocationService
{
    public function createLocation(LocationRequest $request): Location
    {
        $location = Location::create([
            'name'        => $request->name,
            'description' => $request->description,
            'address'     => $request->address,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        Alert::success('Created!', $location->name . ' has been added.');

        return $location;
    }

    public function updateLocation(Location $location, LocationRequest $request): Location
    {
        $location->update([
            'name'        => $request->name,
            'description' => $request->description,
            'address'     => $request->address,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        Alert::success('Updated!', $location->name . ' has been updated.');

        return $location;
    }

    public function deleteLocation(Location $location): void
    {
        $location->delete();
        Alert::success('Deleted!', 'Location has been deleted.');
    }
}
