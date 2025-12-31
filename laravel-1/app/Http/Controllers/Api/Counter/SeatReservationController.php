<?php

namespace App\Http\Controllers\Api\Counter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeatReservation;
use App\Models\Schedule;
use App\Models\Booked_seat;

class SeatReservationController extends Controller
{
    public function __construct() {}

    public function showSchedule($id)
    {
        $schedule = Schedule::with('bus')->findOrFail($id);

        $bookedSeats = Booked_seat::where('schedule_id', $id)
            ->pluck('booked_seats')
            ->toArray();

        $bookedSeats = collect($bookedSeats)
            ->flatMap(fn($s) => explode(',', $s))
            ->map(fn($s) => trim($s))
            ->toArray();

        return response()->json([
            'schedule' => $schedule,
            'bookedSeats' => $bookedSeats,
            'seatLayout' => $schedule->bus->seat_layout,
            'seatCapacity' => $schedule->bus->seat_capacity,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'schedule_id' => 'required|exists:schedules,id',
                'bus_type' => 'required|string',
                'coach_no' => 'required|string',
                'route' => 'required|string',
                'seat_price' => 'required|numeric',
                'departure' => 'required|string',
                'boarding' => 'required|string',
                'dropping' => 'required|string',
                'selected_seats' => 'required|string',
                'total' => 'required|numeric',
                'name' => 'required|string|max:100',
                'mobile' => 'required|string|max:20',
            ]);

            // ✅ REAL logged-in user
            $validated['user_id'] = auth()->id();

            if (!$validated['user_id']) {
                return response()->json([
                    'message' => 'User not authenticated'
                ], 401);
            }

            $validated['status'] = 'pending';

            $reservation = SeatReservation::create($validated);

            return response()->json([
                'message' => 'Reservation created successfully',
                'reservation_id' => $reservation->id,
                'redirect_url' => "/payment/{$reservation->id}",
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Seat reservation failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function paymentPage($id)
    {
        return response()->json([
            'reservation' => SeatReservation::findOrFail($id),
        ]);
    }
}
