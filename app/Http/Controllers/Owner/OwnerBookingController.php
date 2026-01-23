<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class OwnerBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::whereHas('workshop', function ($query) {
            $query->where('owner_id', auth()->id());
        })
            ->with('workshop', 'user')
            ->latest()
            ->paginate(10);

        return view('owner.bookings.index', compact('bookings'));
    }
}
