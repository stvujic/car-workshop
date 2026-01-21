<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Workshop;

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
        $data['slug'] = \Str::slug($data['name']) . '-' . \Str::random(6);
        $data['status'] = 'pending';

        Workshop::create($data);

        return redirect()
            ->route('owner.myshops.index')
            ->with('success', 'Workshop successfully created and waiting for approval.');
    }

}
