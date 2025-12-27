<?php

namespace App\Http\Controllers\Api\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Bus;

class ScheduleController extends Controller
{
    /**
     * Display all routes (with pagination).
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $schedules = Schedule::orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => 200,
            'data' => $schedules,
        ]);
    }

    /**
     * Store a newly created route.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'set_date' => 'required|date',
            'set_time' => 'required',
            'route_code' => 'required|string|max:100',
            'start_location' => 'required|string|max:100',
            'end_location' => 'required|string|max:100',
            'distance' => 'nullable|string|max:50',
            'duration' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'bus_type' => 'required|string|max:100',
            'coach_no' => 'required|string|max:50',
        ]);

        $schedule = Schedule::create($validated);

        return response()->json([
            'status'  => 201,
            'message' => 'Route added successfully',
            'data'    => $schedule,
        ], 201);
    }

    public function ReservationData($id)
{
    $schedule = Schedule::with('bus')->findOrFail($id);

    return response()->json([
        'status' => 200,
        'data' => [
            'schedule' => $schedule,
            'seatLayout' => $schedule->bus->seat_layout ?? '2:2',
            'seatCapacity' => $schedule->bus->seat_capacity ?? 40,
        ]
    ]);
}


    /**
     * Display a single route.
     */
    public function show($id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'status'  => 404,
                'message' => 'Schedule not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data'   => $schedule,
        ]);
    }

    /**
     * Update a route.
     */
    public function update(Request $request, $id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'status'  => 404,
                'message' => 'Schedule not found',
            ], 404);
        }

        $validated = $request->validate([
            'set_date' => 'required|date',
            'set_time' => 'required',
            'route_code' => 'required|string|max:100',
            'start_location' => 'required|string|max:100',
            'end_location' => 'required|string|max:100',
            'distance' => 'nullable|string|max:50',
            'duration' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'bus_type' => 'required|string|max:100',
            'coach_no' => 'required|string|max:50',
        ]);

        $schedule->update($validated);

        return response()->json([
            'status'  => 200,
            'message' => 'Route updated successfully',
            'data'    => $schedule,
        ]);
    }

    /**
     * Delete a route.
     */
    public function destroy($id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'status'  => 404,
                'message' => 'Schedule not found',
            ], 404);
        }

        $schedule->delete();

        return response()->json([
            'status'  => 200,
            'message' => 'Schedule deleted successfully',
        ]);
    }
}
