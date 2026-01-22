<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Workshop;

class WorkshopController extends Controller
{
    public function show(Workshop $workshop)
    {
        if($workshop->status !== 'approved'){abort(404);}

        return view('workshops.show', compact('workshop'));
    }

    public function storeBooking(Request $request, Workshop $workshop)
    {
        if($workshop->status !== 'approved'){abort(404);}

        $data = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'note' => 'nullable|string|max:1000',
        ]);

        try{
            Booking::create([
                'workshop_id' => $workshop->id,
                'user_id' => auth()->id(),
                'date' => $data['date'],
                'time' => $data['time'],
                'note' => $data['note'] ?? null,
                'status' => 'pending',
            ]);
        }

        catch (\Illuminate\Database\QueryException $e){
            return back()
                ->withInput()
                ->with('error', 'This time slot is already taken. Choose another time.');
        }

        return back()->with('success', 'Workshop booked successfully.');
    }
}
