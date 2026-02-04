<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use Illuminate\Http\Request;

class OwnerWorkingHourController extends Controller
{

    public function index(Workshop $workshop)
    {
        if ($workshop->owner_id !== auth()->id()) {
            abort(403);
        }

        //sadrži SAMO ono što postoji u bazi, ako ne postoji zapis, ne postoji u $existing
        $existing = $workshop->workingHours()
            ->orderBy('day_of_week')
            ->get()
            ->keyBy('day_of_week');

        //ovo nam sluzi da cuva dane u formi kljuc=>vrednost
        $days = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];

        // kolekcija / niz, jedinstvena struktura podataka koja sadrzi radno vreme za svih 7 dana,
        // spremna za direktan prikaz i obradu u Blade-u
        $workingHours = [];

        // Prolazimo kroz svih 7 dana u nedelji i za svaki dan proveravamo da li postoji zapis u bazi sa $existing
        // Ako postoji onda cemo imati $row podatak, popunjavamo podatke iz baze koji su vec sacuvani,
        // a ako ne postoji, postavljamo default vrednosti false/null
        foreach ($days as $dayNumber => $dayName) {

            $row = $existing->get($dayNumber);

            $workingHours[$dayNumber] = [
                'label'      => $dayName,
                'is_active'  => $row ? (bool) $row->is_active : false,
                'start_time' => $row ? substr((string) $row->start_time, 0, 5) : null, // HH:MM
                'end_time'   => $row ? substr((string) $row->end_time, 0, 5) : null,   // HH:MM
            ];
        }

        return view('owner.working_hours.index', compact('workshop', 'workingHours', 'days'));
    }

    public function store(Request $request, Workshop $workshop)
    {
        if ($workshop->owner_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'days' => ['required', 'array'],
            'days.*.is_active' => ['nullable', 'boolean'],
            'days.*.start_time' => ['nullable', 'date_format:H:i'],
            'days.*.end_time' => ['nullable', 'date_format:H:i'],
        ]);

        for ($day = 1; $day <= 7; $day++) {

            // Uzmi validirane podatke za konkretan dan iz 'days'; ako ne postoje, koristi prazan niz
            $dayData = $data['days'][$day] ?? [];

            //Iz podataka za jedan dan ($dayData) izdvoj mi vrednosti koje me zanimaju i smesti ih u jasne,
            // stabilne varijable kako bih dalje mogao da donosim odluke (if).
            $isActive  = (bool) ($dayData['is_active'] ?? false);
            $startTime = $dayData['start_time'] ?? null;
            $endTime   = $dayData['end_time'] ?? null;

            // Ako nije aktivan dan -> obrisemo zapis (ili ga mozemo setovati inactive, ali ovo je cisto)
            if (!$isActive) {
                $workshop->workingHours()->where('day_of_week', $day)->delete();
                continue;
            }

            // Ako je aktivan, moraju biti popunjena vremena
            if (!$startTime || !$endTime) {
                return back()
                    ->withInput()
                    ->with('error', "Please set start and end time for day {$day}.");
            }

            // validacija logike: end mora biti posle start
            if ($endTime <= $startTime) {
                return back()
                    ->withInput()
                    ->with('error', "End time must be after start time for day {$day}.");
            }

            $workshop->workingHours()->updateOrCreate(
                ['day_of_week' => $day],
                [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_active' => true,
                ]
            );
        }

        return redirect()
            ->route('owner.working_hours.index', $workshop)
            ->with('success', 'Working hours saved successfully.');
    }

}
