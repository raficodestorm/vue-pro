<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of all locations.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $locations = Location::orderBy('district', 'asc')->paginate($perPage);

        return response()->json([
            'status' => 200,
            'data' => $locations,
        ]);
    }

    /**
     * Store a new location.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'district' => 'required|string|max:100|unique:locations,district',
            'division' => 'nullable|string|max:100',
        ]);

        $location = Location::create($validated);

        return response()->json([
            'status' => 201,
            'message' => 'Location added successfully!',
            'data' => $location,
        ], 201);
    }

    /**
     * Show a single location.
     */
    public function show($id)
    {
        $location = Location::find($id);
        return response()->json([
            'status' => 200,
            'data' => $location,
        ]);
    }

    /**
     * Update an existing location.
     */
    public function update(Request $request, $id)
    {
        $location = Location::find($id);
        $validated = $request->validate([
            'district' => 'required|string|max:100|unique:locations,district,' . $location->id,
            'division' => 'nullable|string|max:100',
        ]);

        $location->update($validated);

        return response()->json([
            'status' => 200,
            'message' => 'Location updated successfully!',
            'data' => $location,
        ]);
    }

    /**
     * Delete a location.
     */
    public function destroy($id)
    {
        $location = Location::find($id);
        if (!$location) {
            return response()->json([
                'status'  => 404,
                'message' => 'Location not found',
            ], 404);
        }
        $location->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Location deleted successfully!',
        ]);
    }
}
