<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Booking;
use App\Models\RentalRate;
use App\Models\Notification;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenyewaController extends Controller
{
    /**
     * Check if the authenticated user is verified
     */
    private function checkUserVerification()
    {
        $user = Auth::user();
        
        if (!$user->isVerified()) {
            return redirect()->route('penyewa.dashboard')
                ->with('error', 'Akun Anda belum diverifikasi. Silakan tunggu admin memverifikasi akun Anda sebelum dapat menyewa motor.');
        }
        
        return null;
    }

    /**
     * Dashboard penyewa
     */
    public function dashboard()
    {
        $penyewa = Auth::user();
        
        // Check verification status for display
        $isVerified = $penyewa->isVerified();
        
        // Statistik
        $totalBookings = Booking::where('renter_id', $penyewa->id)->count();
        $activeBookings = Booking::where('renter_id', $penyewa->id)
            ->where('status', 'active')
            ->count();
        $completedBookings = Booking::where('renter_id', $penyewa->id)
            ->where('status', 'completed')
            ->count();

        // Total spent - dari booking yang sudah completed
        $totalSpent = Booking::where('renter_id', $penyewa->id)
            ->where('status', 'completed')
            ->sum('price');

        // Recent bookings untuk dashboard
        $recentBookings = Booking::where('renter_id', $penyewa->id)
            ->with(['motor', 'motor.rentalRate'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Featured motors untuk dashboard
        $featuredMotors = Motor::where('status', 'available')
            ->with(['rentalRate'])
            ->limit(6)
            ->get();

        return view('penyewa.dashboard', compact(
            'totalBookings', 
            'activeBookings', 
            'completedBookings', 
            'totalSpent', 
            'recentBookings', 
            'featuredMotors',
            'isVerified'
        ));
    }

    /**
     * Daftar motor tersedia
     */
    public function motors(Request $request)
    {
        $query = Motor::where('status', 'available')
            ->with(['rentalRate', 'owner']);

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Filter by type_cc
        if ($request->filled('type_cc')) {
            $query->where('type_cc', $request->type_cc);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('plate_number', 'like', "%{$search}%")
                  ->orWhere('color', 'like', "%{$search}%")
                  ->orWhere('year', 'like', "%{$search}%");
            });
        }

        $motors = $query->paginate(12);
        
        // Check user verification status
        $isVerified = Auth::user()->isVerified();

        return view('penyewa.motors', compact('motors', 'isVerified'));
    }

    /**
     * Detail motor
     */
    public function motorDetail($id)
    {
        $motor = Motor::where('status', 'available')
            ->with(['rentalRate', 'owner'])
            ->findOrFail($id);

        return view('penyewa.motor-detail', compact('motor'));
    }

    /**
     * Get motor detail via AJAX for modal
     */
    public function getMotorDetailAjax($id)
    {
        $motor = Motor::where('status', 'available')
            ->with(['rentalRate', 'owner'])
            ->findOrFail($id);

        return response()->json([
            'motor' => $motor
        ]);
    }

    /**
     * Form booking motor
     */
    public function bookingForm($motorId)
    {
        // Check user verification
        $verificationCheck = $this->checkUserVerification();
        if ($verificationCheck) {
            return $verificationCheck;
        }

        $motor = Motor::where('status', 'available')
            ->with(['rentalRate'])
            ->findOrFail($motorId);

        return view('penyewa.booking-form', compact('motor'));
    }

    /**
     * Proses booking motor
     */
    public function processBooking(Request $request)
    {
        // Check user verification
        $verificationCheck = $this->checkUserVerification();
        if ($verificationCheck) {
            return $verificationCheck;
        }

        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'package_type' => 'required|in:daily,weekly,monthly',
            'notes' => 'nullable|string|max:500'
        ]);

        $motor = Motor::where('status', 'available')->findOrFail($request->motor_id);
        $rentalRate = $motor->rentalRate;

        if (!$rentalRate) {
            return back()->with('error', 'Tarif sewa belum ditetapkan untuk motor ini.');
        }

        // Cek availability motor untuk tanggal yang dipilih
        if (!$motor->isAvailableForDates($request->start_date, $request->end_date)) {
            $conflictingBookings = $motor->getConflictingBookings($request->start_date, $request->end_date);
            $nextAvailable = $motor->getNextAvailableDate($request->end_date);
            
            $errorMessage = 'Motor tidak tersedia pada tanggal yang dipilih.';
            if ($conflictingBookings->count() > 0) {
                $booking = $conflictingBookings->first();
                $errorMessage .= ' Motor sudah disewa dari ' . 
                    Carbon::parse($booking->start_date)->format('d M Y') . ' sampai ' . 
                    Carbon::parse($booking->end_date)->format('d M Y') . '.';
            }
            $errorMessage .= ' Tanggal tersedia berikutnya: ' . $nextAvailable->format('d M Y');
            
            return back()->with('error', $errorMessage)->withInput();
        }

        // Hitung total hari dan total harga berdasarkan paket
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1; // +1 karena include kedua tanggal
        
        // Hitung paket duration dan discount
        $packageDurations = [
            'daily' => 1,
            'weekly' => 7,
            'monthly' => 30
        ];
        
        $packageDiscounts = [
            'daily' => 1,        // No discount
            'weekly' => 0.9,     // 10% discount
            'monthly' => 0.8     // 20% discount
        ];
        
        $durationDays = $packageDurations[$request->package_type];
        $discount = $packageDiscounts[$request->package_type];
        $totalAmount = $rentalRate->daily_rate * $totalDays * $discount;

        // Buat booking
        $booking = Booking::create([
            'renter_id' => Auth::id(),
            'motor_id' => $request->motor_id,
            'package_type' => $request->package_type,
            'duration_days' => $totalDays,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration_type' => 'daily',
            'price' => $totalAmount,
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        // Buat notifikasi untuk admin
        $admins = User::where('role', 'admin')->get();
        $packageNames = [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan'
        ];
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'booking_created',
                'title' => 'Pemesanan Motor Baru',
                'message' => 'Pemesanan paket ' . $packageNames[$request->package_type] . ' untuk motor ' . $motor->brand . ' ' . $motor->model . ' oleh ' . Auth::user()->name,
                'data' => [
                    'booking_id' => $booking->id,
                    'motor_id' => $motor->id,
                    'renter_id' => Auth::id()
                ]
            ]);
        }

        return redirect()->route('penyewa.bookings')
            ->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');
    }

    /**
     * Daftar booking penyewa
     */
    public function bookings()
    {
        $bookings = Booking::where('renter_id', Auth::id())
            ->with(['motor', 'motor.rentalRate'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('penyewa.bookings', compact('bookings'));
    }

    /**
     * Detail booking
     */
    public function bookingDetail($id)
    {
        $booking = Booking::where('renter_id', Auth::id())
            ->with(['motor', 'motor.rentalRate', 'motor.owner'])
            ->findOrFail($id);

        return view('penyewa.booking-detail', compact('booking'));
    }

    /**
     * Get booking detail via AJAX
     */
    public function getBookingDetailAjax($id)
    {
        $booking = Booking::where('renter_id', Auth::id())
            ->with(['motor', 'motor.rentalRate'])
            ->findOrFail($id);

        return response()->json([
            'booking' => $booking
        ]);
    }

    /**
     * Cancel booking
     */
    public function cancelBooking($id)
    {
        $booking = Booking::where('renter_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Form pembayaran
     */
    public function paymentForm($bookingId)
    {
        $booking = Booking::where('renter_id', Auth::id())
            ->where('status', 'pending')
            ->with(['motor', 'motor.rentalRate'])
            ->findOrFail($bookingId);

        return view('penyewa.payment-form', compact('booking'));
    }

    /**
     * Proses pembayaran
     */
    public function processPayment(Request $request, $bookingId)
    {
        $request->validate([
            'payment_method' => 'required|in:transfer_bank,e_wallet,cash',
            'payment_notes' => 'nullable|string|max:500'
        ]);

        $booking = Booking::where('renter_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($bookingId);

        $paymentData = [
            'booking_id' => $booking->id,
            'amount' => $booking->price,
            'payment_method' => $request->payment_method,
            'payment_notes' => $request->payment_notes,
            'status' => 'pending'
        ];

        // Buat payment record
        \App\Models\Payment::create($paymentData);

        // Update booking status - tetap pending untuk verifikasi admin
        $booking->update(['status' => 'awaiting_payment_verification']);

        return redirect()->route('penyewa.bookings')
            ->with('success', 'Pembayaran berhasil disubmit. Menunggu verifikasi admin.');
    }

    /**
     * Riwayat pembayaran
     */
    public function paymentHistory()
    {
        $payments = \App\Models\Payment::whereHas('booking', function($query) {
            $query->where('renter_id', Auth::id());
        })
        ->with(['booking', 'booking.motor'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('penyewa.payment-history', compact('payments'));
    }

    /**
     * Get payment detail for AJAX
     */
    public function getPaymentDetailAjax($id)
    {
        $payment = \App\Models\Payment::whereHas('booking', function($query) {
            $query->where('renter_id', Auth::id());
        })
        ->with(['booking', 'booking.motor'])
        ->findOrFail($id);

        return response()->json([
            'payment' => $payment
        ]);
    }

    /**
     * Generate payment invoice
     */
    public function paymentInvoice($id)
    {
        $payment = \App\Models\Payment::whereHas('booking', function($query) {
            $query->where('renter_id', Auth::id());
        })
        ->with(['booking', 'booking.motor', 'booking.motor.owner'])
        ->findOrFail($id);

        if ($payment->status !== 'paid') {
            return redirect()->route('penyewa.payment.history')
                ->with('error', 'Invoice hanya tersedia untuk pembayaran yang sudah lunas.');
        }

        return view('penyewa.payment-invoice', compact('payment'));
    }

    /**
     * Check motor availability for specific dates via AJAX
     */
    public function checkAvailability(Request $request)
    {
        // Check user verification for AJAX request
        $user = Auth::user();
        if (!$user->isVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda belum diverifikasi. Silakan tunggu admin memverifikasi akun Anda sebelum dapat menyewa motor.'
            ], 403);
        }

        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        $motor = Motor::findOrFail($request->motor_id);
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Check if motor is available for the selected dates
        $isAvailable = $motor->isAvailableForDates($startDate, $endDate);
        
        $response = [
            'available' => $isAvailable,
            'motor_id' => $motor->id,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];

        if (!$isAvailable) {
            $conflictingBookings = $motor->getConflictingBookings($startDate, $endDate);
            $nextAvailable = $motor->getNextAvailableDate($endDate);
            
            $response['message'] = 'Motor tidak tersedia pada tanggal yang dipilih.';
            $response['conflicts'] = $conflictingBookings->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'start_date' => Carbon::parse($booking->start_date)->format('d M Y'),
                    'end_date' => Carbon::parse($booking->end_date)->format('d M Y'),
                    'renter_name' => $booking->renter->name ?? 'Unknown'
                ];
            });
            $response['next_available_date'] = $nextAvailable->format('Y-m-d');
            $response['next_available_formatted'] = $nextAvailable->format('d M Y');
        } else {
            $response['message'] = 'Motor tersedia untuk tanggal yang dipilih.';
        }

        return response()->json($response);
    }

    /**
     * Store a new rating for a motor
     */
    public function storeRating(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        // Check if user has actually booked this motor
        $booking = Booking::where('id', $request->booking_id)
            ->where('renter_id', Auth::id())
            ->where('status', 'completed')
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Anda hanya bisa rating motor yang sudah Anda sewa'], 403);
        }

        // Check if user already rated this booking
        $existingRating = Rating::where('user_id', Auth::id())
            ->where('booking_id', $request->booking_id)
            ->first();

        if ($existingRating) {
            return response()->json(['error' => 'Anda sudah memberikan rating untuk booking ini'], 400);
        }

        $rating = Rating::create([
            'user_id' => Auth::id(),
            'motor_id' => $request->motor_id,
            'booking_id' => $request->booking_id,
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        return response()->json([
            'message' => 'Rating berhasil disimpan',
            'rating' => $rating
        ]);
    }

    /**
     * Update an existing rating
     */
    public function updateRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        $rating = Rating::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$rating) {
            return response()->json(['error' => 'Rating tidak ditemukan'], 404);
        }

        if (!$rating->canEdit()) {
            return response()->json(['error' => 'Rating hanya dapat diedit dalam 24 jam pertama'], 400);
        }

        $rating->update([
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        return response()->json([
            'message' => 'Rating berhasil diupdate',
            'rating' => $rating
        ]);
    }

    /**
     * Delete a rating
     */
    public function deleteRating($id)
    {
        $rating = Rating::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$rating) {
            return response()->json(['error' => 'Rating tidak ditemukan'], 404);
        }

        if (!$rating->canEdit()) {
            return response()->json(['error' => 'Rating hanya dapat dihapus dalam 24 jam pertama'], 400);
        }

        $rating->delete();

        return response()->json(['message' => 'Rating berhasil dihapus']);
    }

    /**
     * Get all ratings for a motor
     */
    public function getMotorRatings($motorId)
    {
        $motor = Motor::findOrFail($motorId);
        
        $ratings = Rating::with(['user', 'booking'])
            ->where('motor_id', $motorId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $averageRating = $motor->getAverageRating();
        $totalRatings = $motor->getTotalRatings();

        return response()->json([
            'ratings' => $ratings,
            'average_rating' => $averageRating,
            'total_ratings' => $totalRatings,
            'rating_stars' => $motor->getRatingStars()
        ]);
    }

    /**
     * Show reports page for penyewa
     */
    public function reports()
    {
        $penyewa = Auth::user();
        
        // Get booking statistics
        $totalBookings = Booking::where('renter_id', $penyewa->id)->count();
        $completedBookings = Booking::where('renter_id', $penyewa->id)->where('status', 'completed')->count();
        $activeBookings = Booking::where('renter_id', $penyewa->id)->where('status', 'active')->count();
        $cancelledBookings = Booking::where('renter_id', $penyewa->id)->where('status', 'cancelled')->count();
        
        // Get recent bookings with motor and rating info
        $recentBookings = Booking::with(['motor', 'motor.category', 'payment'])
            ->where('renter_id', $penyewa->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get user's ratings given
        $ratingsGiven = Rating::with(['motor', 'booking'])
            ->where('user_id', $penyewa->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Calculate total spending
        $totalSpending = $penyewa->bookings()
            ->whereIn('status', ['completed', 'active'])
            ->sum('total_price');

        return view('penyewa.reports', compact(
            'totalBookings',
            'completedBookings', 
            'activeBookings',
            'cancelledBookings',
            'recentBookings',
            'ratingsGiven',
            'totalSpending'
        ));
    }

    /**
     * Export reports data
     */
    public function exportReports(Request $request)
    {
        $penyewa = Auth::user();
        $format = $request->get('format', 'pdf'); // pdf or excel
        
        $bookings = Booking::with(['motor', 'motor.category', 'payment'])
            ->where('renter_id', $penyewa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $ratings = Rating::with(['motor', 'booking'])
            ->where('user_id', $penyewa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // For now, just return JSON data
        return response()->json([
            'bookings' => $bookings,
            'ratings' => $ratings,
            'summary' => [
                'total_bookings' => $bookings->count(),
                'completed_bookings' => $bookings->where('status', 'completed')->count(),
                'total_spending' => $bookings->whereIn('status', ['completed', 'active'])->sum('total_price'),
                'average_rating_given' => $ratings->avg('rating')
            ]
        ]);
    }
}
