<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()
            ->bookings()
            ->with('workshop')
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }
}
