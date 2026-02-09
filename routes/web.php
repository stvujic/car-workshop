<?php

use App\Http\Controllers\Admin\AdminWorkshopApprovalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Owner\OwnerBookingController;
use App\Http\Controllers\Owner\OwnerClosedDayController;
use App\Http\Controllers\Owner\OwnerWorkingHourController;
use App\Http\Controllers\Owner\OwnerWorkshopController;
use App\Http\Controllers\Owner\OwnerWorkshopServiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkshopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/workshops/{workshop:slug}', [WorkshopController::class, 'show'])->name('workshops.show');
Route::get('/workshops/{workshop}/available-times', [WorkshopController::class, 'availableTimes'])->name('workshops.available-times');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/workshops/{workshop:slug}/bookings', [WorkshopController::class, 'storeBooking'])
        ->name('workshops.bookings.store');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
});

Route::middleware(['auth', 'owner'])->prefix('owner')->group(function () {
   Route::get('/myshops', [OwnerWorkshopController::class, 'index'])->name('owner.myshops.index');
   Route::get('/myshops/create', [OwnerWorkshopController::class, 'create'])->name('owner.myshops.create');
   Route::post('/myshops', [OwnerWorkshopController::class, 'store'])->name('owner.myshops.store');
   Route::get('/myshops/{workshop}/edit', [OwnerWorkshopController::class, 'edit'])->name('owner.myshops.edit');
   Route::put('/myshops/{workshop}', [OwnerWorkshopController::class, 'update'])->name('owner.myshops.update');
   Route::delete('/myshops/{workshop}', [OwnerWorkshopController::class, 'destroy'])->name('owner.myshops.destroy');

   Route::get('/bookings', [OwnerBookingController::class, 'index'])->name('owner.bookings.index');
   Route::patch('/bookings/{booking}/approve', [OwnerBookingController::class, 'approve'])->name('owner.bookings.approve');
   Route::patch('/bookings/{booking}/cancel', [OwnerBookingController::class, 'cancel'])->name('owner.bookings.cancel');

   Route::get('/myshops/{workshop}/services', [OwnerWorkshopServiceController::class, 'index'])->name('owner.services.index');
   Route::get('/myshops/{workshop}/services/create', [OwnerWorkshopServiceController::class, 'create'])->name('owner.services.create');
   Route::post('/myshops/{workshop}/services', [OwnerWorkshopServiceController::class, 'store'])->name('owner.services.store');
   Route::get('/myshops/{workshop}/services/{service}/edit', [OwnerWorkshopServiceController::class, 'edit'])->name('owner.services.edit');
   Route::put('/myshops/{workshop}/services/{service}', [OwnerWorkshopServiceController::class, 'update'])->name('owner.services.update');
   Route::delete('/myshops/{workshop}/services/{service}', [OwnerWorkshopServiceController::class, 'destroy'])->name('owner.services.destroy');

   Route::get('/myshops/{workshop}/working-hours', [OwnerWorkingHourController::class, 'index'])->name('owner.working_hours.index');
   Route::post('/myshops/{workshop}/working-hours', [OwnerWorkingHourController::class, 'store'])->name('owner.working_hours.store');

    Route::get('/myshops/{workshop}/closed-days', [OwnerClosedDayController::class, 'index'])->name('owner.closed_days.index');
    Route::post('/myshops/{workshop}/closed-days', [OwnerClosedDayController::class, 'store'])->name('owner.closed_days.store');
    Route::delete('/myshops/{workshop}/closed-days/{closedDay}', [OwnerClosedDayController::class, 'destroy'])->name('owner.closed_days.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
   Route::get('/workshops/pending', [AdminWorkshopApprovalController::class, 'index'])->name('admin.workshops.pending');
   Route::patch('/workshops/{workshop}/approve', [AdminWorkshopApprovalController::class, 'approve'])->name('admin.workshops.approve');
   Route::patch('/workshops/{workshop}/reject', [AdminWorkshopApprovalController::class, 'reject'])->name('admin.workshops.reject');
});

require __DIR__.'/auth.php';
