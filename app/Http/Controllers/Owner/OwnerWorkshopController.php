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
}
