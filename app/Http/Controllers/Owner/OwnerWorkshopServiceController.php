<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Workshop;
use Illuminate\Http\Request;

class OwnerWorkshopServiceController extends Controller
{
    public function index(Workshop $workshop)
    {
        if($workshop->owner_id !== auth()->id())
        {
            abort(403);
        }

        $services = $workshop->services()->latest()->paginate(20);

        return view('owner.services.index', compact('workshop', 'services'));
    }

    public function create(Workshop $workshop)
    {
        if($workshop->owner_id !== auth()->id())
        {
            abort(403);
        }

        return view('owner.services.create', compact('workshop'));
    }

    public function store(Request $request, Workshop $workshop)
    {
        if($workshop->owner_id !== auth()->id())
        {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'price' => 'required|numeric|min:0|max:999999.99',
        ]);

        $workshop->services()->create($data);

        return redirect()->route('owner.services.index', $workshop)
            ->with('success', 'Service added successfully.');
    }

    public function edit(Workshop $workshop, Service $service)
    {
        if($workshop->owner_id !== auth()->id())
        {
            abort(403);
        }

        return view('owner.services.edit', compact('workshop', 'service'));
    }

    public function update(Request $request,Workshop $workshop, Service $service)
    {
        if($workshop->owner_id !== auth()->id())
        {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'price' => 'required|numeric|min:0|max:999999.99',
        ]);

        $service->update($data);

        return redirect()->route('owner.services.index', $workshop)
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Workshop $workshop, Service $service)
    {
        if($workshop->owner_id !== auth()->id())
        {
            abort(403);
        }

        $service->delete();

        return redirect()->route('owner.services.index', $workshop)
            ->with('success', 'Service deleted successfully.');
    }
}
