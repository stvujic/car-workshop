<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Workshop;
use Carbon\Carbon;

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

        $closedDays = $workshop->closedDays()
            ->orderBy('start_date')
            ->get();

        return view('workshops.show', compact('workshop', 'workingHoursForView', 'closedDays'));
    }

    public function storeBooking(Request $request, Workshop $workshop)
    {
        if($workshop->status !== 'approved'){abort(404);}

        $data = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'note' => 'nullable|string|max:1000',
        ]);

        $date=Carbon::parse($data['date']);
        $dateString=$date->toDateString();
        $time=$data['time'];

        $isClosed = $workshop->closedDays()
            ->whereDate('start_date', '<=', $dateString) //Daj mi samo one closed days zapise gde je start_date pre ili na taj datum tj $dateString, jer ako zatvaranje pocinje posle tog datuma, ne moze da ga blokira
            ->where(function ($q) use ($dateString) {
                $q->whereNull('end_date') //Ako end_date ne postoji onda je zatvoren samo jedan dan
                    ->orWhereDate('end_date', '>=', $dateString); //ako end_date postoji, proveri da li je end_date posle ili na trazeni datum
            })
            ->exists(); //Da li postoji bar jedan red koji ispunjava ove uslove

        if($isClosed) {
            return back()
                ->withInput()
                ->with('error', 'Workshop is closed on selected date.');
        }

        // 1) Provera working hours za taj dan u nedelji
        $dayOfWeek = $date->dayOfWeekIso;

        $wh=$workshop->workingHours()
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if(!$wh || !$wh->is_active)
        {
            return back()
                ->withInput()
                ->with('error', 'Workshop is not available on selected date.');
        }

        $start = substr($wh->start_time, 0, 5);
        $end   = substr($wh->end_time, 0, 5);

        // 2) Provera da je time unutar radnog vremena
        if($time<$start||$time>=$end)
        {
            return back()
                ->withInput()
                ->with('error', 'Please choose a time between '.$start.' and '.$end.'.');
        }

        // 3) Provera zauzetosti
        $alreadyTaken =$workshop->bookings()
            ->where('date', $dateString)
            ->where('time', $time)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if($alreadyTaken)
            return back()
                ->withInput()
                ->with('error', 'This time slot is already taken. Choose another time.');


        Booking::create([
                'workshop_id' => $workshop->id,
                'user_id' => auth()->id(),
                'date' => $dateString,
                'time' => $time,
                'note' => $data['note'] ?? null,
                'status' => 'pending',
            ]);

        return back()->with('success', 'Workshop booked successfully.');
    }

    public function availableTimes(Request $request, Workshop $workshop)
    {
        if($workshop->status !== 'approved')
        {
            abort(404);
        }

        $date= $request->validate([
           'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $date=Carbon::parse($date['date']);
        $dayOfWeek = $date->dayOfWeek;
        $dateString= $date->toDateString();

        $isClosed = $workshop->closedDays()
            ->whereDate('start_date', '<=', $dateString)
            ->whereDate('end_date', '>=', $dateString)
            ->exists();

        if($isClosed){
            return response()->json([
                'available_times'=>[],
                'message' => 'Workshop is closed on selected date.',
            ]);
        }

        $wh = $workshop->workingHours()
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$wh || !$wh->is_active) {
            return response()->json([
                'available_times' => [],
                'message' => 'Workshop is not working on selected day.',
            ]);
        }

        $start = substr($wh->start_time, 0, 5); // HH:MM
        $end   = substr($wh->end_time, 0, 5);

        // 3) Existing bookings for that date (pending + approved block slots)
        $taken = $workshop->bookings()
            ->where('date', $dateString)
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('time')
            ->map(fn ($t) => substr($t, 0, 5))
            ->values()
            ->all();

        // 4) Generate 30-min slots within working hours
        $slots = [];
        $cursor = Carbon::createFromFormat('Y-m-d H:i', $dateString . ' ' . $start);
        $endDt  = Carbon::createFromFormat('Y-m-d H:i', $dateString . ' ' . $end);

        while ($cursor->lte($endDt)) {
            $slots[] = $cursor->format('H:i');
            $cursor->addMinutes(30);
        }

        // 5) Remove taken slots
        $available = array_values(array_diff($slots, $taken));

        return response()->json([
            'available_times' => $available,
            'message' => count($available) ? null : 'No free times for selected date.',
            'working_hours' => ['start' => $start, 'end' => $end],
        ]);
    }

}
