<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use Illuminate\Http\Request;

class AdminWorkshopApprovalController extends Controller
{
    public function index()
    {
        $workshops = Workshop::where('status',Workshop::STATUS_PENDING)
            ->latest()
            ->paginate(10);

        return view('admin.workshops.index', compact('workshops'));
    }

    public function approve(Workshop $workshop)
    {
        $workshop->update([
            'status' => Workshop::STATUS_APPROVED,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Workshop approved successfully');
    }

    public function reject(Workshop $workshop)
    {
        $workshop->update([
            'status' => Workshop::STATUS_REJECTED,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Workshop rejected successfully');
    }

}
