<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // Jika user sudah login, redirect ke dashboard sesuai role
    if (Auth::check()) {
        $user = Auth::user();
        
        // Pastikan user memiliki role yang valid
        if (!$user->role) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Role user tidak valid');
        }
        
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'pemilik':
                return redirect()->route('pemilik.dashboard');
            case 'penyewa':
                return redirect()->route('penyewa.dashboard');
            default:
                Auth::logout();
                return redirect()->route('login')->with('error', 'Role tidak dikenali');
        }
    }
    
    // Jika belum login, tampilkan halaman welcome
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    
    $user = Auth::user();
    
    switch ($user->role) {
        case 'penyewa':
            return redirect()->route('penyewa.dashboard');
        case 'pemilik':
            return redirect()->route('pemilik.dashboard');
        case 'admin':
            return redirect()->route('admin.dashboard');
        default:
            Auth::logout();
            return redirect()->route('login')->with('error', 'Role tidak valid');
    }
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
    Route::get('/motors/{id}/detail-ajax', [PenyewaController::class, 'getMotorDetailAjax'])->name('motor.detail.ajax');
    
    // Booking routes
    Route::get('/booking/{motorId}', [PenyewaController::class, 'bookingForm'])->name('booking.form');
    Route::post('/booking', [PenyewaController::class, 'processBooking'])->name('booking.store');
    Route::post('/booking/check-availability', [PenyewaController::class, 'checkAvailability'])->name('booking.check-availability');
    Route::get('/bookings', [PenyewaController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{id}', [PenyewaController::class, 'bookingDetail'])->name('booking.detail');
    Route::get('/bookings/{id}/detail', [PenyewaController::class, 'getBookingDetailAjax'])->name('booking.detail.ajax');
    Route::patch('/bookings/{id}/cancel', [PenyewaController::class, 'cancelBooking'])->name('booking.cancel');
    
    // Payment routes
    Route::get('/payment/{bookingId}', [PenyewaController::class, 'paymentForm'])->name('payment.form');
    Route::post('/payment/{bookingId}', [PenyewaController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment-history', [PenyewaController::class, 'paymentHistory'])->name('payment.history');
    Route::get('/payments/{id}/detail', [PenyewaController::class, 'getPaymentDetailAjax'])->name('payment.detail.ajax');
    Route::get('/payments/{id}/invoice', [PenyewaController::class, 'paymentInvoice'])->name('payment.invoice');
    
    // Rating routes
    Route::post('/ratings', [PenyewaController::class, 'storeRating'])->name('rating.store');
    Route::put('/ratings/{id}', [PenyewaController::class, 'updateRating'])->name('rating.update');
    Route::delete('/ratings/{id}', [PenyewaController::class, 'deleteRating'])->name('rating.delete');
    Route::get('/ratings/{motorId}', [PenyewaController::class, 'getMotorRatings'])->name('motor.ratings');
    
    // Report routes
    Route::get('/reports', [PenyewaController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [PenyewaController::class, 'exportReports'])->name('reports.export');
});

// Routes untuk Pemilik Motor
Route::middleware(['auth', 'role:pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard', [PemilikController::class, 'dashboard'])->name('dashboard');
    
    // Motor routes
    Route::get('/motors', [PemilikController::class, 'motors'])->name('motors');
    Route::get('/motors/create', [PemilikController::class, 'createMotor'])->name('motor.create');
    Route::post('/motors', [PemilikController::class, 'storeMotor'])->name('motor.store');
    Route::get('/motors/{id}', [PemilikController::class, 'motorDetail'])->name('motor.detail');
    Route::get('/motors/{id}/ajax', [PemilikController::class, 'getMotorDetailAjax'])->name('motor.detail.ajax');
    Route::get('/motors/{id}/edit', [PemilikController::class, 'editMotor'])->name('motor.edit');
    Route::patch('/motors/{id}', [PemilikController::class, 'updateMotor'])->name('motor.update');
    Route::delete('/motors/{id}', [PemilikController::class, 'deleteMotor'])->name('motor.delete');
    
    // Booking routes
    Route::get('/bookings', [PemilikController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{id}', [PemilikController::class, 'bookingDetail'])->name('booking.detail');
    Route::get('/booking/{id}/detail', [PemilikController::class, 'getBookingDetail'])->name('booking.detail.ajax');
    Route::post('/booking/{id}/confirm', [PemilikController::class, 'confirmBooking'])->name('booking.confirm');
    Route::post('/booking/{id}/cancel', [PemilikController::class, 'cancelBooking'])->name('booking.cancel');
    Route::post('/booking/{id}/activate', [PemilikController::class, 'activateBooking'])->name('booking.activate');
    Route::post('/booking/{id}/complete', [PemilikController::class, 'completeBooking'])->name('booking.complete');
    
    // Revenue routes
    Route::get('/revenue-report', [PemilikController::class, 'revenueReport'])->name('revenue.report');
    Route::get('/revenue-download', [PemilikController::class, 'downloadRevenueReport'])->name('revenue.download');
});

// Routes untuk Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{id}/detail', [AdminController::class, 'getUserDetail'])->name('users.detail');
    Route::patch('/users/{id}/verify', [AdminController::class, 'verifyUser'])->name('users.verify');
    Route::patch('/users/{id}/blacklist', [AdminController::class, 'blacklistUser'])->name('users.blacklist');
    Route::patch('/users/{id}/remove-blacklist', [AdminController::class, 'removeBlacklist'])->name('users.remove-blacklist');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    
    // Motor verification
    Route::get('/motors', [AdminController::class, 'motors'])->name('motors');
    Route::get('/motors/{id}', [AdminController::class, 'motorDetail'])->name('motor.detail');
    Route::get('/motors/{id}/ajax', [AdminController::class, 'getMotorDetailAjax'])->name('motor.detail.ajax');
    Route::patch('/motors/{motor}/verify', [AdminController::class, 'verifyMotor'])->name('motor.verify');
    Route::delete('/motors/{id}', [AdminController::class, 'deleteMotor'])->name('motor.delete');
    
    // Booking management
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{id}', [AdminController::class, 'showBooking'])->name('bookings.show');
    Route::patch('/bookings/{id}/status', [AdminController::class, 'updateBookingStatus'])->name('bookings.status');
    
    // Payment verification
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::get('/payments/{id}', [AdminController::class, 'showPayment'])->name('payments.show');
    Route::get('/payments/{id}/ajax', [AdminController::class, 'getPaymentDetailAjax'])->name('payments.detail.ajax');
    Route::patch('/payments/{id}/verify', [AdminController::class, 'verifyPayment'])->name('payments.verify');
    
    // Reports & Revenue Sharing
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/financial-report', [AdminController::class, 'financialReport'])->name('financial-report');
    Route::get('/export-report', [AdminController::class, 'exportReport'])->name('export.report');
    
    // Notifications
    Route::get('/notifications', [AdminController::class, 'getNotifications'])->name('notifications');
    Route::patch('/notifications/{id}/read', [AdminController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::patch('/notifications/mark-all-read', [AdminController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');
    
    // Motor Status Management
    Route::post('/motors/update-status-realtime', [AdminController::class, 'updateMotorStatusRealtime'])->name('motors.update-status-realtime');
    Route::get('/motors/status-realtime', [AdminController::class, 'getMotorStatusRealtime'])->name('motors.status-realtime');
});

require __DIR__.'/auth.php';

// Simple test route without authentication
Route::get('/test-simple', function () {
    return 'Simple test works!';
});

// Debug route to test authentication
Route::get('/debug-auth', function () {
    return [
        'authenticated' => Auth::check(),
        'user_id' => Auth::id(),
        'user_role' => Auth::check() ? Auth::user()->role : null,
        'session_id' => session()->getId(),
    ];
});

// Test admin login
Route::get('/login-as-admin', function () {
    $admin = \App\Models\User::where('role', 'admin')->first();
    if ($admin) {
        Auth::login($admin);
        return redirect()->route('admin.dashboard');
    }
    return 'No admin user found';
});

// Test penyewa login
Route::get('/login-as-penyewa', function () {
    $penyewa = \App\Models\User::where('role', 'penyewa')->first();
    if ($penyewa) {
        Auth::login($penyewa);
        return redirect()->route('penyewa.dashboard');
    }
    return 'No penyewa user found';
});

// Test pemilik login
Route::get('/login-as-pemilik', function () {
    $pemilik = \App\Models\User::where('role', 'pemilik')->first();
    if ($pemilik) {
        Auth::login($pemilik);
        return redirect()->route('pemilik.dashboard');
    }
    return 'No pemilik user found';
});

// Test routes
Route::get('/test-ajax', function () {
    return view('test-ajax');
})->middleware('auth');

Route::get('/test-login-admin', function () {
    $user = \App\Models\User::where('email', 'admin@fannrental.com')->first();
    if ($user) {
        Auth::login($user);
        return redirect('/test-ajax');
    }
    return 'Admin user not found';
});
