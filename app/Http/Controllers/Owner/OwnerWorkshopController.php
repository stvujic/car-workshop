<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

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
        if ($workshop->owner_id != auth()->id()) {
            abort(403);
        }

        return view('owner.myshops.edit', compact('workshop'));
    }

    public function update(Request $request, Workshop $workshop)
    {
        if ($workshop->owner_id != auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        // reset statusa na pending jer admin mora da odobri promene
        $data['status'] = Workshop::STATUS_PENDING;

        $workshop->update($data);

        return redirect()
            ->route('owner.myshops.index')
            ->with('success', 'Workshop successfully updated and waiting for approval.');
    }

    public function destroy(Workshop $workshop)
    {
        if ($workshop->owner_id != auth()->id()) {
            abort(403);
        }

        $workshop->delete();

        return redirect()
            ->route('owner.myshops.index')
            ->with('success', 'Workshop successfully deleted');
    }
}
