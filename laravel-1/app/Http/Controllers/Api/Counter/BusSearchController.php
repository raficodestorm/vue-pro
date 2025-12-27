<?php

namespace App\Http\Controllers\Api\Counter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;

class BusSearchController extends Controller
{
  /**
   * Search bus for journey
   * From, To, Date
   */
  public function search(Request $request)
  {
    $request->validate([
      'from' => 'required|string|max:100',
      'to'   => 'required|string|max:100',
      'date' => 'required|date',
    ]);

    $buses = Schedule::where('start_location', $request->from)
      ->where('end_location', trim($request->to))
      ->whereDate('set_date', trim($request->date))
      ->where('status', 'pending')   // IMPORTANT
      ->orderBy('set_time', 'asc')
      ->get();

    return response()->json([
      'status' => 200,
      'count'  => $buses->count(),
      'data'   => $buses,
    ]);
  }
}
