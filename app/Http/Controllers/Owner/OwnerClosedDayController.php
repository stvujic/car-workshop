<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreClosedDayRequest;
use App\Models\Workshop;
use App\Models\WorkshopClosedDay;

class OwnerClosedDayController extends Controller
{
    public function index(Workshop $workshop)
    {
        if($workshop->owner_id !== auth()->id())
            {
                abort(403);
            }

        $closedDays = $workshop->closedDays()
            ->orderBy('start_date')
            ->get();

        return view('owner.closed_days.index', compact('workshop', 'closedDays'));
    }

    public function store(StoreClosedDayRequest $request, Workshop $workshop)
    {
        if($workshop->owner_id !== auth()->id())
        {
            abort(403);
        }

        $data = $request->validated();

        //ovo smo stavili ako imamo situaciju da je neradan jedan dan samo pa da ne dupliramo podatak npr 7.jan-7.jan
        $data['end_date'] =$data['end_date'] ?? $data['start_date'];

        $workshop->closedDays()->create($data);

        return back()->with('success', 'Closed day added');

    }

    public function destroy(Workshop $workshop, WorkshopClosedDay $closedDay)
    {
        if($workshop->owner_id !== auth()->id())
        {
            abort(403);
        }

        if ($closedDay->workshop_id !== $workshop->id) {
            abort(404);
        }

        $closedDay->delete();

        return back()->with('success', 'Closed day deleted');
    }
}

