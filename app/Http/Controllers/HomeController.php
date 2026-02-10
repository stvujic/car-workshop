<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workshop;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Workshop::query()->where('status', 'approved');

        // lista gradova za dropdown (samo iz approved workshopova)
        $cities = Workshop::where('status', 'approved')
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        // filter
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $workshops = $query->latest()->paginate(10);

        // da paginacija zadrzi ?city=...
        $workshops->appends($request->only('city'));

        return view('home', compact('workshops', 'cities'));
    }
}
