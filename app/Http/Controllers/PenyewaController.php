<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RentalRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenyewaController extends Controller
{
    // Middleware akan didefinisikan di routes

    /**
     * Dashboard untuk penyewa
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Statistik untuk penyewa
        $totalBookings = Booking::where('user_id', $user->id)->count();
        $activeBookings = Booking::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->where('end_date', '>=', Carbon::now())
            ->count();
        $completedBookings = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $totalSpent = Payment::whereHas('booking', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'paid')->sum('amount');

        // Booking terakhir
        $recentBookings = Booking::where('user_id', $user->id)
            ->with(['motor', 'payment'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('penyewa.dashboard', compact(
            'totalBookings', 
            'activeBookings', 
            'completedBookings', 
            'totalSpent',
            'recentBookings'
        ));
    }

    /**
     * Tampilkan daftar motor yang tersedia
     */
    public function motors(Request $request)
    {
        $query = Motor::where('status', 'available')
            ->with(['rentalRates', 'owner']);

        // Filter berdasarkan CC
        if ($request->has('cc') && $request->cc !== '') {
            $query->where('cc', $request->cc);
        }

        // Filter berdasarkan harga maksimal
        if ($request->has('max_price') && $request->max_price !== '') {
            $query->whereHas('rentalRates', function($q) use ($request) {
                $q->where('rate_per_day', '<=', $request->max_price);
            });
        }

        // Search berdasarkan nama atau merk
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        $motors = $query->paginate(12);

        // Data untuk filter
        $ccOptions = Motor::where('status', 'available')
            ->distinct()
            ->orderBy('cc')
            ->pluck('cc');

        return view('penyewa.motors', compact('motors', 'ccOptions'));
    }

    /**
     * Detail motor
     */
    public function motorDetail($id)
    {
        $motor = Motor::where('status', 'available')
            ->with(['rentalRates', 'owner'])
            ->findOrFail($id);

        return view('penyewa.motor-detail', compact('motor'));
    }

    /**
     * Form booking motor
     */
    public function bookingForm($motorId)
    {
        $motor = Motor::where('status', 'available')
            ->with(['rentalRates'])
            ->findOrFail($motorId);

        return view('penyewa.booking-form', compact('motor'));
    }

    /**
     * Proses booking motor
     */
    public function processBooking(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string|max:500'
        ]);

        $motor = Motor::where('status', 'available')->findOrFail($request->motor_id);
        $rentalRate = $motor->rentalRates()->first();

        if (!$rentalRate) {
            return back()->with('error', 'Tarif sewa belum ditetapkan untuk motor ini.');
        }

        // Cek apakah tanggal sudah dibooking
        $existingBooking = Booking::where('motor_id', $request->motor_id)
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->exists();

        if ($existingBooking) {
            return back()->with('error', 'Motor tidak tersedia pada tanggal yang dipilih.');
        }

        // Hitung total hari dan biaya
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $totalAmount = $totalDays * $rentalRate->rate_per_day;

        // Buat booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'motor_id' => $request->motor_id,
            'rental_rate_id' => $rentalRate->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        // Buat payment record
        Payment::create([
            'booking_id' => $booking->id,
            'amount' => $totalAmount,
            'status' => 'pending'
        ]);

        return redirect()->route('penyewa.booking.detail', $booking->id)
            ->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');
    }

    /**
     * Daftar booking penyewa
     */
    public function bookings(Request $request)
    {
        $query = Booking::where('user_id', Auth::id())
            ->with(['motor', 'payment']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('penyewa.bookings', compact('bookings'));
    }

    /**
     * Detail booking
     */
    public function bookingDetail($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->with(['motor', 'motor.owner', 'payment', 'rentalRate'])
            ->findOrFail($id);

        return view('penyewa.booking-detail', compact('booking'));
    }

    /**
     * Form pembayaran
     */
    public function paymentForm($bookingId)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->with(['motor', 'payment'])
            ->findOrFail($bookingId);

        if ($booking->payment->status === 'paid') {
            return redirect()->route('penyewa.booking.detail', $booking->id)
                ->with('info', 'Pembayaran sudah dilakukan.');
        }

        return view('penyewa.payment-form', compact('booking'));
    }

    /**
     * Proses pembayaran
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|in:bank_transfer,e_wallet,cash',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $booking = Booking::where('user_id', Auth::id())
            ->with('payment')
            ->findOrFail($request->booking_id);

        if ($booking->payment->status === 'paid') {
            return back()->with('error', 'Pembayaran sudah dilakukan.');
        }

        $paymentData = [
            'payment_method' => $request->payment_method,
            'paid_at' => Carbon::now(),
            'status' => 'paid'
        ];

        // Upload bukti pembayaran jika ada
        if ($request->hasFile('proof_image')) {
            $file = $request->file('proof_image');
            $filename = 'payment_' . $booking->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payments'), $filename);
            $paymentData['proof_image'] = $filename;
        }

        $booking->payment->update($paymentData);

        return redirect()->route('penyewa.booking.detail', $booking->id)
            ->with('success', 'Pembayaran berhasil. Menunggu konfirmasi admin.');
    }

    /**
     * Batalkan booking
     */
    public function cancelBooking($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * History pembayaran
     */
    public function paymentHistory()
    {
        $payments = Payment::whereHas('booking', function($query) {
            $query->where('user_id', Auth::id());
        })
        ->with(['booking.motor'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('penyewa.payment-history', compact('payments'));
    }
}