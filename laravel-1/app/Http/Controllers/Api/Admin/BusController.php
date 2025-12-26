<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\Bustype;
use App\Models\Route as RouteModel; // alias to avoid conflict

class BusController extends Controller
{
    /**
     * Display all buses (with pagination).
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $buses = Bus::orderBy('name', 'asc')
            ->paginate($perPage);

        return response()->json([
            'status' => 200,
            'data'   => $buses,
        ]);
    }



    public function coachesByType($type)
    {
        $coaches = Bus::where('bus_type', $type)
            ->pluck('coach_no');

        return response()->json([
            'status' => 200,
            'data' => $coaches
        ]);
    }

    /**
     * Store a newly created bus.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'coach_no'      => 'required|integer',
            'license'       => 'required|string|max:255',
            'company'       => 'required|string|max:255',
            'bus_type'      => 'required|string|max:255',
            'route'         => 'required|string|max:255',
            'seat_layout'   => 'required|string|max:255',
            'seat_capacity' => 'required|integer',
        ]);

        $bus = Bus::create($validated);

        return response()->json([
            'status'  => 201,
            'message' => 'Bus added successfully',
            'data'    => $bus,
        ], 201);
    }

    /**
     * Display a single bus.
     */
    public function show($id)
    {
        $bus = Bus::find($id);

        if (!$bus) {
            return response()->json([
                'status'  => 404,
                'message' => 'Bus not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data'   => $bus,
        ]);
    }

    /**
     * Update a bus.
     */
    public function update(Request $request, $id)
    {
        $bus = Bus::find($id);

        if (!$bus) {
            return response()->json([
                'status'  => 404,
                'message' => 'Bus not found',
            ], 404);
        }

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'coach_no'      => 'required|integer',
            'license'       => 'required|string|max:255',
            'company'       => 'required|string|max:255',
            'bus_type'      => 'required|string|max:255',
            'route'         => 'required|string|max:255',
            'seat_layout'   => 'required|string|max:255',
            'seat_capacity' => 'required|integer',
        ]);

        $bus->update($validated);

        return response()->json([
            'status'  => 200,
            'message' => 'Bus updated successfully',
            'data'    => $bus,
        ]);
    }

    /**
     * Delete a bus.
     */
    public function destroy($id)
    {
        $bus = Bus::find($id);

        if (!$bus) {
            return response()->json([
                'status'  => 404,
                'message' => 'Bus not found',
            ], 404);
        }

        $bus->delete();

        return response()->json([
            'status'  => 200,
            'message' => 'Bus deleted successfully',
        ]);
    }

    /**
     * Fetch bus types and routes (helper endpoint).
     */
    public function meta()
    {
        return response()->json([
            'status' => 200,
            'data'   => [
                'types'  => Bustype::orderBy('name')->get(['id', 'name']),
                'routes' => RouteModel::orderBy('route_code')->get(['id', 'route_code']),
            ],
        ]);
    }
}
