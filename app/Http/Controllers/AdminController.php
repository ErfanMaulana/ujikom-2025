<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Motor;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RentalRate;
use App\Models\RevenueSharing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Middleware akan didefinisikan di routes

    /**
     * Dashboard untuk admin
     */
    public function dashboard()
    {
        // Statistik umum
        $totalUsers = User::count();
        $totalPenyewa = User::where('role', 'penyewa')->count();
        $totalPemilik = User::where('role', 'pemilik')->count();
        $totalMotors = Motor::count();
        $pendingMotors = Motor::where('status', 'pending')->count();
        $availableMotors = Motor::where('status', 'available')->count();
        
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $adminRevenue = RevenueSharing::sum('admin_share');

        // Data untuk chart/grafik (contoh: bookings per bulan)
        $monthlyBookings = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Motor yang perlu verifikasi
        $pendingMotorsList = Motor::where('status', 'pending')
            ->with('owner')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Booking yang perlu konfirmasi
        $pendingBookingsList = Booking::where('status', 'pending')
            ->whereHas('payment', function($query) {
                $query->where('status', 'paid');
            })
            ->with(['motor', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalPenyewa', 'totalPemilik',
            'totalMotors', 'pendingMotors', 'availableMotors',
            'totalBookings', 'pendingBookings', 'confirmedBookings',
            'totalRevenue', 'adminRevenue',
            'monthlyBookings', 'pendingMotorsList', 'pendingBookingsList'
        ));
    }

    /**
     * Manajemen user
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filter berdasarkan role
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        // Search berdasarkan nama atau email
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users', compact('users'));
    }

    /**
     * Detail user
     */
    public function userDetail($id)
    {
        $user = User::findOrFail($id);

        // Data terkait user berdasarkan role
        $relatedData = [];
        
        if ($user->isPenyewa()) {
            $relatedData['bookings'] = Booking::where('user_id', $user->id)
                ->with('motor')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } elseif ($user->isPemilik()) {
            $relatedData['motors'] = Motor::where('owner_id', $user->id)
                ->withCount('bookings')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        return view('admin.user-detail', compact('user', 'relatedData'));
    }

    /**
     * Verifikasi motor
     */
    public function motors(Request $request)
    {
        $query = Motor::with(['owner', 'rentalRates']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $motors = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.motors', compact('motors'));
    }

    /**
     * Detail motor untuk verifikasi
     */
    public function motorDetail($id)
    {
        $motor = Motor::with(['owner', 'rentalRates', 'bookings.user'])
            ->findOrFail($id);

        return view('admin.motor-detail', compact('motor'));
    }

    /**
     * Proses verifikasi motor
     */
    public function verifyMotor(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'rate_per_day' => 'required_if:action,approve|numeric|min:50000',
            'rejection_reason' => 'required_if:action,reject|string|max:500'
        ]);

        $motor = Motor::findOrFail($id);

        if ($request->action === 'approve') {
            // Approve motor dan set harga
            $motor->update([
                'status' => 'available',
                'verified_by' => Auth::id(),
                'verified_at' => Carbon::now()
            ]);

            // Buat atau update rental rate
            RentalRate::updateOrCreate(
                ['motor_id' => $motor->id],
                [
                    'rate_per_day' => $request->rate_per_day,
                    'set_by' => Auth::id()
                ]
            );

            return back()->with('success', 'Motor berhasil diverifikasi dan harga sewa ditetapkan.');
        } else {
            // Reject motor
            $motor->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'verified_by' => Auth::id(),
                'verified_at' => Carbon::now()
            ]);

            return back()->with('success', 'Motor berhasil ditolak.');
        }
    }

    /**
     * Update harga sewa motor
     */
    public function updateRentalRate(Request $request, $id)
    {
        $request->validate([
            'rate_per_day' => 'required|numeric|min:50000'
        ]);

        $motor = Motor::where('status', 'available')->findOrFail($id);

        RentalRate::updateOrCreate(
            ['motor_id' => $motor->id],
            [
                'rate_per_day' => $request->rate_per_day,
                'set_by' => Auth::id()
            ]
        );

        return back()->with('success', 'Harga sewa berhasil diupdate.');
    }

    /**
     * Manajemen booking
     */
    public function bookings(Request $request)
    {
        $query = Booking::with(['motor', 'user', 'payment']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.bookings', compact('bookings'));
    }

    /**
     * Detail booking
     */
    public function bookingDetail($id)
    {
        $booking = Booking::with(['motor.owner', 'user', 'payment', 'rentalRate'])
            ->findOrFail($id);

        return view('admin.booking-detail', compact('booking'));
    }

    /**
     * Konfirmasi booking
     */
    public function confirmBooking(Request $request, $id)
    {
        $booking = Booking::where('status', 'pending')->findOrFail($id);

        if ($booking->payment->status !== 'paid') {
            return back()->with('error', 'Pembayaran belum dilakukan.');
        }

        $booking->update([
            'status' => 'confirmed',
            'confirmed_by' => Auth::id(),
            'confirmed_at' => Carbon::now()
        ]);

        // Buat revenue sharing
        $this->createRevenueSharing($booking);

        return back()->with('success', 'Booking berhasil dikonfirmasi.');
    }

    /**
     * Selesaikan booking
     */
    public function completeBooking($id)
    {
        $booking = Booking::where('status', 'confirmed')
            ->where('end_date', '<', Carbon::now())
            ->findOrFail($id);

        $booking->update(['status' => 'completed']);

        return back()->with('success', 'Booking berhasil diselesaikan.');
    }

    /**
     * Batalkan booking
     */
    public function cancelBooking(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $booking = Booking::findOrFail($id);

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_by' => Auth::id(),
            'cancelled_at' => Carbon::now()
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Laporan keuangan
     */
    public function financialReport(Request $request)
    {
        // Total revenue
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $adminRevenue = RevenueSharing::sum('admin_share');
        $ownerRevenue = RevenueSharing::sum('owner_share');

        // Revenue per bulan
        $monthlyRevenue = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->where('status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top performing motors
        $topMotors = Motor::withCount(['bookings as total_bookings' => function($query) {
            $query->where('status', 'completed');
        }])
        ->with('owner')
        ->orderBy('total_bookings', 'desc')
        ->limit(10)
        ->get();

        return view('admin.financial-report', compact(
            'totalRevenue', 'adminRevenue', 'ownerRevenue',
            'monthlyRevenue', 'topMotors'
        ));
    }

    /**
     * Buat revenue sharing
     */
    private function createRevenueSharing(Booking $booking)
    {
        $totalAmount = $booking->total_amount;
        $ownerShare = $totalAmount * 0.7; // 70% untuk pemilik
        $adminShare = $totalAmount * 0.3; // 30% untuk admin

        RevenueSharing::create([
            'booking_id' => $booking->id,
            'owner_id' => $booking->motor->owner_id,
            'total_amount' => $totalAmount,
            'owner_share' => $ownerShare,
            'admin_share' => $adminShare
        ]);
    }

    /**
     * Export laporan
     */
    public function exportReport(Request $request)
    {
        // Implementation untuk export PDF/Excel
        // Akan diimplementasi jika diperlukan
        return back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }
}