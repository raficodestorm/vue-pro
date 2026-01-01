<?php

namespace App\Http\Controllers\Api\Counter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeatReservation;
use App\Models\Booked_seat;
use Illuminate\Support\Facades\DB;

class CounterPaymentController extends Controller
{
    public function payNow($id)
{
    DB::beginTransaction();

    try {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $reservation = SeatReservation::where('id', $id)
            ->where('status', 'pending')
            ->lockForUpdate()
            ->firstOrFail();

        $requestedSeats = array_filter(array_map('trim', explode(',', $reservation->selected_seats)));

        if (empty($requestedSeats)) {
            return response()->json(['message' => 'No seats selected'], 400);
        }

        // ✅ Use FIND_IN_SET instead of LIKE for comma strings
        $alreadyBooked = Booked_seat::where('schedule_id', $reservation->schedule_id)
            ->where(function ($q) use ($requestedSeats) {
                foreach ($requestedSeats as $seat) {
                    $q->orWhereRaw("FIND_IN_SET(?, booked_seats)", [$seat]);
                }
            })
            ->exists();

        if ($alreadyBooked) {
            return response()->json([
                'message' => 'One or more seats already booked'
            ], 409);
        }

        $counterId = auth()->user()->role === 'counter' ? auth()->user()->id : null;

        Booked_seat::create([
            'reservation_id' => $reservation->id,
            'user_id'        => $reservation->user_id,
            'counter_id'     => $counterId,
            'schedule_id'    => $reservation->schedule_id,
            'coach_no'       => $reservation->coach_no,
            'booked_seats'   => $reservation->selected_seats,
            'total'          => $reservation->total,
        ]);

        $reservation->update(['status' => 'paid']);

        DB::commit();

        return response()->json([
            'message' => 'Payment successful',
            'ticket_id' => $reservation->id,
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Payment failed',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function ticket($id)
{
    return response()->json([
        'ticket' => SeatReservation::with('schedule')
            ->where('id', $id)
            ->where('status', 'paid')
            ->firstOrFail()
    ]);
}

}
