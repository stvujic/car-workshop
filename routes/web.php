<?php

use App\Http\Controllers\Admin\AdminWorkshopApprovalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Owner\OwnerWorkshopController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkshopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/workshops/{workshop:slug}', [WorkshopController::class, 'show'])->name('workshops.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/workshops/{workshop:slug}/bookings', [WorkshopController::class, 'storeBooking'])
        ->name('workshops.bookings.store');
});

Route::middleware(['auth', 'owner'])->prefix('owner')->group(function () {
   Route::get('/myshops', [OwnerWorkshopController::class, 'index'])->name('owner.myshops.index');
   Route::get('/myshops/create', [OwnerWorkshopController::class, 'create'])->name('owner.myshops.create');
   Route::post('/myshops', [OwnerWorkshopController::class, 'store'])->name('owner.myshops.store');
   Route::get('/myshops/{workshop}/edit', [OwnerWorkshopController::class, 'edit'])->name('owner.myshops.edit');
   Route::put('/myshops/{workshop}', [OwnerWorkshopController::class, 'update'])->name('owner.myshops.update');
   Route::delete('/myshops/{workshop}', [OwnerWorkshopController::class, 'destroy'])->name('owner.myshops.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
   Route::get('/workshops/pending', [AdminWorkshopApprovalController::class, 'index'])->name('admin.workshops.pending');
   Route::patch('/workshops/{workshop}/approve', [AdminWorkshopApprovalController::class, 'approve'])->name('admin.workshops.approve');
   Route::patch('/workshops/{workshop}/reject', [AdminWorkshopApprovalController::class, 'reject'])->name('admin.workshops.reject');
});

require __DIR__.'/auth.php';
