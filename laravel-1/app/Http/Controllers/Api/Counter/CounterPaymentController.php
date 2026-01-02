<?php

namespace App\Http\Controllers\Api\Counter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeatReservation;
use App\Models\Booked_seat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CounterPaymentController extends Controller
{
    public function payNow($id)
    {
        DB::beginTransaction();

        try {

            // ✅ Sanctum auth
            if (!Auth::guard('sanctum')->check()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $user = Auth::guard('sanctum')->user();

            // ✅ Safe reservation fetch
            $reservation = SeatReservation::where('id', $id)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->first();

            if (!$reservation) {
                return response()->json([
                    'message' => 'Reservation not found or already paid'
                ], 404);
            }

            $requestedSeats = array_filter(
                array_map('trim', explode(',', $reservation->selected_seats))
            );

            if (empty($requestedSeats)) {
                return response()->json(['message' => 'No seats selected'], 400);
            }

            // ✅ Seat conflict check
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

            // ✅ Counter ID
            $counterId = $user->role === 'counter' ? $user->id : null;

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
                'message'   => 'Payment successful',
                'ticket_id' => $reservation->id,
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            \Log::error('PAYMENT ERROR', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Payment failed'
            ], 500);
        }
    }

    public function ticket($id)
    {
        return response()->json(['ticket' => SeatReservation::with('schedule')->where('id', $id)->where('status', 'paid')->firstOrFail()]);
    }
}
