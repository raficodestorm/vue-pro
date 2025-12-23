<?php

namespace App\Http\Controllers\Api\Controller;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Booked_seat;
use App\Models\SeatReservation;
use App\Models\Route;
use App\Models\Bus;
use App\Models\Driver;
use App\Models\Supervisor;
use App\Models\Bustype;
use Illuminate\Http\Request;
use PDF;

class ScheduleController extends Controller
{
    /* =========================
     |  LISTING
     ========================= */

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $schedules = Schedule::latest()
            ->paginate($perPage);

        return response()->json([
            'status' => 200,
            'data' => $schedules
        ]);
    }

    public function byStatus($status, Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $schedules = Schedule::where('status', $status)
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'status' => 200,
            'data' => $schedules
        ]);
    }

    /* =========================
     |  CREATE
     ========================= */

    public function create()
    {
        return response()->json([
            'status' => 200,
            'routes' => Route::orderBy('route_code')->get(),
            'bustypes' => Bustype::orderBy('type')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateSchedule($request);

        $schedule = Schedule::create($validated);

        return response()->json([
            'status' => 201,
            'message' => 'Schedule created successfully',
            'data' => $schedule
        ]);
    }

    /* =========================
     |  SHOW
     ========================= */

    public function show($id)
    {
        $schedule = Schedule::with(['driver', 'supervisor'])
            ->findOrFail($id);

        return response()->json([
            'status' => 200,
            'data' => $schedule
        ]);
    }

    /* =========================
     |  UPDATE
     ========================= */

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $this->validateSchedule($request, true);

        $schedule->update($validated);

        return response()->json([
            'status' => 200,
            'message' => 'Schedule updated successfully',
            'data' => $schedule
        ]);
    }

    /* =========================
     |  DELETE
     ========================= */

    public function destroy($id)
    {
        Schedule::findOrFail($id)->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Schedule deleted successfully'
        ]);
    }

    /* =========================
     |  TRIP STATUS
     ========================= */

    public function start($id)
    {
        return $this->updateStatus($id, 'running');
    }

    public function finish($id)
    {
        return $this->updateStatus($id, 'finished');
    }

    public function pending($id)
    {
        return $this->updateStatus($id, 'pending');
    }

    private function updateStatus($id, $status)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update(['status' => $status]);

        return response()->json([
            'status' => 200,
            'message' => "Trip {$status} successfully",
            'data' => $schedule
        ]);
    }

    /* =========================
     |  DRIVER & SUPERVISOR
     ========================= */

    public function assignDriver(Request $request, $id)
    {
        $request->validate([
            'driver_id' => 'nullable|exists:drivers,id'
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update(['driver_id' => $request->driver_id]);

        return response()->json([
            'status' => 200,
            'message' => 'Driver assigned successfully'
        ]);
    }

    public function assignSupervisor(Request $request, $id)
    {
        $request->validate([
            'supervisor_id' => 'nullable|exists:supervisors,id'
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update(['supervisor_id' => $request->supervisor_id]);

        return response()->json([
            'status' => 200,
            'message' => 'Supervisor assigned successfully'
        ]);
    }

    /* =========================
     |  AJAX HELPERS
     ========================= */

    public function getRouteInfo($routeCode)
    {
        $route = Route::where('route_code', $routeCode)->first();

        return response()->json([
            'status' => 200,
            'data' => $route
        ]);
    }

    public function getCoachesByBusType($busType)
    {
        $buses = Bus::where('bus_type', 'LIKE', "%{$busType}%")
            ->pluck('coach_no');

        return response()->json([
            'status' => 200,
            'data' => $buses
        ]);
    }

    /* =========================
     |  TRIP REPORT (PDF)
     ========================= */

    public function tripReport($id)
    {
        $schedule = Schedule::findOrFail($id);

        $bookedSeats = Booked_seat::where('schedule_id', $id)->get();

        $totalPassengers = $bookedSeats->reduce(function ($carry, $item) {
            return $carry + count(array_filter(explode(',', $item->booked_seats)));
        }, 0);

        $seatReservations = SeatReservation::where('schedule_id', $id)->get();

        $pdf = PDF::loadView('pages.controller.trip.trip-report', compact(
            'schedule',
            'bookedSeats',
            'seatReservations',
            'totalPassengers'
        ));

        return $pdf->stream("Trip_Report_{$id}.pdf");
    }

    /* =========================
     |  VALIDATION
     ========================= */

    private function validateSchedule(Request $request, $isUpdate = false)
    {
        return $request->validate([
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
            'status' => $isUpdate ? 'required|in:pending,running,finished' : 'nullable',
        ]);
    }
}
