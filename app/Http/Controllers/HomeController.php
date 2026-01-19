<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workshop;

class HomeController extends Controller
{
    public function index()
    {
        $workshops = Workshop::where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('home', compact('workshops'));
    }
}
