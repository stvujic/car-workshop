<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\WorkshopRequest;
use App\Models\Workshop;
use Illuminate\Support\Str;

class OwnerWorkshopController extends Controller
{
    public function index()
    {
        $workshops = Workshop::where('owner_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('owner.myshops.index', compact('workshops'));
    }

    public function create()
    {
        return view('owner.myshops.create');
    }

    public function store(WorkshopRequest $request)
    {
        $data = $request->validated();

        $data['owner_id'] = auth()->id();
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
        $data['status'] = Workshop::STATUS_PENDING;

        Workshop::create($data);

        return redirect()
            ->route('owner.myshops.index')
            ->with('success', 'Workshop successfully created and waiting for approval.');
    }

    public function edit(Workshop $workshop)
    {
        $this->authorize('manage', $workshop);

        return view('owner.myshops.edit', compact('workshop'));
    }

    public function update(WorkshopRequest $request, Workshop $workshop)
    {
        $this->authorize('manage', $workshop);

        $data = $request->validated();

        // reset statusa na pending jer admin mora da odobri promene
        $data['status'] = Workshop::STATUS_PENDING;

        $workshop->update($data);

        return redirect()
            ->route('owner.myshops.index')
            ->with('success', 'Workshop successfully updated and waiting for approval.');
    }

    public function destroy(Workshop $workshop)
    {
        $this->authorize('manage', $workshop);

        $workshop->delete();

        return redirect()
            ->route('owner.myshops.index')
            ->with('success', 'Workshop successfully deleted');
    }
}
