<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if (!$user) {
        return redirect()->route('login');
    }
    
    if ($user->role === 'penyewa') {
        return redirect()->route('penyewa.dashboard');
    } elseif ($user->role === 'pemilik') {
        return redirect()->route('pemilik.dashboard');
    } elseif ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes untuk Penyewa
Route::middleware(['auth', 'role:penyewa'])->prefix('penyewa')->name('penyewa.')->group(function () {
    Route::get('/dashboard', [PenyewaController::class, 'dashboard'])->name('dashboard');
    
    // Motor routes
    Route::get('/motors', [PenyewaController::class, 'motors'])->name('motors');
    Route::get('/motors/{id}', [PenyewaController::class, 'motorDetail'])->name('motor.detail');
    
    // Booking routes
    Route::get('/booking/{motorId}', [PenyewaController::class, 'bookingForm'])->name('booking.form');
    Route::post('/booking', [PenyewaController::class, 'processBooking'])->name('booking.store');
    Route::get('/bookings', [PenyewaController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{id}', [PenyewaController::class, 'bookingDetail'])->name('booking.detail');
    Route::patch('/bookings/{id}/cancel', [PenyewaController::class, 'cancelBooking'])->name('booking.cancel');
    
    // Payment routes
    Route::get('/payment/{bookingId}', [PenyewaController::class, 'paymentForm'])->name('payment.form');
    Route::post('/payment', [PenyewaController::class, 'processPayment'])->name('payment.store');
    Route::get('/payment-history', [PenyewaController::class, 'paymentHistory'])->name('payment.history');
});

// Routes untuk Pemilik Motor
Route::middleware(['auth', 'role:pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard', [PemilikController::class, 'dashboard'])->name('dashboard');
    
    // Motor routes
    Route::get('/motors', [PemilikController::class, 'motors'])->name('motors');
    Route::get('/motors/create', [PemilikController::class, 'createMotor'])->name('motor.create');
    Route::post('/motors', [PemilikController::class, 'storeMotor'])->name('motor.store');
    Route::get('/motors/{id}', [PemilikController::class, 'motorDetail'])->name('motor.detail');
    Route::get('/motors/{id}/edit', [PemilikController::class, 'editMotor'])->name('motor.edit');
    Route::patch('/motors/{id}', [PemilikController::class, 'updateMotor'])->name('motor.update');
    Route::delete('/motors/{id}', [PemilikController::class, 'deleteMotor'])->name('motor.delete');
    
    // Booking routes
    Route::get('/bookings', [PemilikController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{id}', [PemilikController::class, 'bookingDetail'])->name('booking.detail');
    
    // Revenue routes
    Route::get('/revenue-report', [PemilikController::class, 'revenueReport'])->name('revenue.report');
    Route::get('/revenue-download', [PemilikController::class, 'downloadRevenueReport'])->name('revenue.download');
});

// Routes untuk Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [AdminController::class, 'userDetail'])->name('user.detail');
    
    // Motor verification
    Route::get('/motors', [AdminController::class, 'motors'])->name('motors');
    Route::get('/motors/{id}', [AdminController::class, 'motorDetail'])->name('motor.detail');
    Route::patch('/motors/{id}/verify', [AdminController::class, 'verifyMotor'])->name('motor.verify');
    Route::patch('/motors/{id}/rate', [AdminController::class, 'updateRentalRate'])->name('motor.rate');
    
    // Booking management
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{id}', [AdminController::class, 'bookingDetail'])->name('booking.detail');
    Route::patch('/bookings/{id}/confirm', [AdminController::class, 'confirmBooking'])->name('booking.confirm');
    Route::patch('/bookings/{id}/complete', [AdminController::class, 'completeBooking'])->name('booking.complete');
    Route::patch('/bookings/{id}/cancel', [AdminController::class, 'cancelBooking'])->name('booking.cancel');
    
    // Reports
    Route::get('/financial-report', [AdminController::class, 'financialReport'])->name('financial.report');
    Route::get('/export-report', [AdminController::class, 'exportReport'])->name('export.report');
});

require __DIR__.'/auth.php';
