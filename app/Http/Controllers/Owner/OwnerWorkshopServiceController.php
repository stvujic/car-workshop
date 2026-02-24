<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\WorkshopServiceRequest;
use App\Models\Service;
use App\Models\Workshop;

class OwnerWorkshopServiceController extends Controller
{
    public function index(Workshop $workshop)
    {
        $this->authorize('manage', $workshop);

        $services = $workshop->services()->latest()->paginate(20);

        return view('owner.services.index', compact('workshop', 'services'));
    }

    public function create(Workshop $workshop)
    {
        $this->authorize('manage', $workshop);

        return view('owner.services.create', compact('workshop'));
    }

    public function store(WorkshopServiceRequest $request, Workshop $workshop)
    {
        $this->authorize('manage', $workshop);

        $data = $request->validated();

        $workshop->services()->create($data);

        return redirect()->route('owner.services.index', $workshop)
            ->with('success', 'Service added successfully.');
    }

    public function edit(Workshop $workshop, Service $service)
    {
        $this->authorize('manage', $workshop);

        if($service->workshop_id !== $workshop->id)
        {
            abort(404);
        }

        return view('owner.services.edit', compact('workshop', 'service'));
    }

    public function update(WorkshopServiceRequest $request,Workshop $workshop, Service $service)
    {
        $this->authorize('manage', $workshop);

        if($service->workshop_id !== $workshop->id)
        {
            abort(404);
        }

        $data = $request->validated();

        $service->update($data);

        return redirect()->route('owner.services.index', $workshop)
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Workshop $workshop, Service $service)
    {
        $this->authorize('manage', $workshop);


        if($service->workshop_id !== $workshop->id)
        {
            abort(404);
        }

        $service->delete();

        return redirect()->route('owner.services.index', $workshop)
            ->with('success', 'Service deleted successfully.');
    }
}
