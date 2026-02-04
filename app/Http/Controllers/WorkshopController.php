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

        $days= [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];

        $existing = $workshop->workingHours()
            ->orderBy('day_of_week')
            ->get()
            ->keyBy('day_of_week');

        $workingHoursForView = [];

        foreach($days as $dayNumber=> $dayName) {
            $row = $existing->get($dayNumber);

            $workingHoursForView[] = [
                'label' => $dayName,
                'is_active' => $row ? (bool) $row->is_active : false,
                'start_time' => $row ? substr($row->start_time, 0, 5) : null,
                'end_time' => $row ? substr($row->end_time, 0, 5) : null,
            ];
        }

        return view('workshops.show', compact('workshop', 'workingHoursForView'));
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
