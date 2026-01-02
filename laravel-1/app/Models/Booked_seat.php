<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booked_seat extends Model
{
    protected $fillable = [
        'reservation_id',
        'user_id',
        'counter_id',
        'schedule_id',
        'coach_no',
        'booked_seats',
        'total',
    ];
}
