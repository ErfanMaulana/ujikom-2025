<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Booking;
use App\Models\RentalRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenyewaController extends Controller
{
    /**
     * Dashboard penyewa
     */
    public function dashboard()
    {
        $penyewa = Auth::user();
        
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
            'featuredMotors'
        ));
    }

    /**
     * Daftar motor tersedia
     */
    public function motors(Request $request)
    {
        $query = Motor::where('status', 'available')
            ->with(['rentalRate']);

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('brand', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%')
                  ->orWhere('year', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan harga
        if ($request->has('max_price') && $request->max_price) {
            $query->whereHas('rentalRate', function($q) use ($request) {
                $q->where('daily_rate', '<=', $request->max_price);
            });
        }

        $motors = $query->paginate(12);

        return view('penyewa.motors', compact('motors'));
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
        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string|max:500'
        ]);

        $motor = Motor::where('status', 'available')->findOrFail($request->motor_id);
        $rentalRate = $motor->rentalRate;

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
            return back()->with('error', 'Motor sudah dibooking pada tanggal tersebut.');
        }

        // Hitung total hari dan total harga
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1; // +1 karena include kedua tanggal
        $totalAmount = $totalDays * $rentalRate->daily_rate;

        // Buat booking
        $booking = Booking::create([
            'renter_id' => Auth::id(),
            'motor_id' => $request->motor_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration_type' => 'daily',
            'price' => $totalAmount,
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        return redirect()->route('penyewa.payment.form', $booking->id)
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
            'payment_method' => 'required|in:transfer,cash',
            'payment_proof' => 'required_if:payment_method,transfer|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $booking = Booking::where('renter_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($bookingId);

        $paymentData = [
            'booking_id' => $booking->id,
            'amount' => $booking->price,
            'payment_method' => $request->payment_method,
            'status' => 'pending'
        ];

        // Upload bukti pembayaran jika transfer
        if ($request->payment_method === 'transfer' && $request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('payment-proofs', $filename, 'public');
            $paymentData['payment_proof'] = $filename;
        }

        // Buat payment record
        \App\Models\Payment::create($paymentData);

        // Update booking status
        $booking->update(['status' => 'confirmed']);

        return redirect()->route('penyewa.bookings')
            ->with('success', 'Pembayaran berhasil disubmit. Menunggu konfirmasi admin.');
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
}
