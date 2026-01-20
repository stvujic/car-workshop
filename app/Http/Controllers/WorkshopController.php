<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workshop;

class WorkshopController extends Controller
{
    public function show(Workshop $workshop)
    {
        if($workshop->status !== 'approved'){abort(404);}

        return view('workshops.show', compact('workshop'));
    }
}
