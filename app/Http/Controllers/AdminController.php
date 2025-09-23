<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Motor;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\RentalRate;
use App\Models\RevenueSharing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalPenyewa = User::where('role', 'penyewa')->count();
        $totalPemilik = User::where('role', 'pemilik')->count();
        $totalMotors = Motor::count();
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('status', 'completed')->sum('price');
        $pendingMotorsCount = Motor::where('status', 'pending_verification')->count();
        $pendingMotors = Motor::where('status', 'pending_verification')->with('owner')->latest()->take(5)->get();
        $pendingBookingsList = Booking::where('status', 'pending')->with(['renter', 'motor'])->latest()->take(5)->get();
        $availableMotors = Motor::where('status', 'available')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();

        // Data untuk grafik pendapatan bulanan
        $monthlyRevenue = Booking::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(price) as total_revenue, SUM(price) * 0.3 as admin_commission, SUM(price) * 0.7 as owner_share')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Format data untuk Chart.js
        $chartLabels = $monthlyRevenue->map(function($item) {
            return date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year));
        })->toArray();

        $chartData = [
            'labels' => $chartLabels,
            'total_revenue' => $monthlyRevenue->pluck('total_revenue')->toArray(),
            'admin_commission' => $monthlyRevenue->pluck('admin_commission')->toArray(),
            'owner_share' => $monthlyRevenue->pluck('owner_share')->toArray()
        ];

        return view('admin.dashboard', compact(
            'totalUsers', 'totalPenyewa', 'totalPemilik', 'totalMotors', 
            'totalBookings', 'totalRevenue', 'pendingMotorsCount', 'pendingMotors',
            'pendingBookingsList', 'availableMotors', 'pendingBookings', 'confirmedBookings',
            'chartData'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Append query parameters to pagination links
        $users->appends($request->query());

        return view('admin.users', compact('users'));
    }

    public function getUserDetail($id)
    {
        try {
            $user = User::with(['verifier'])->findOrFail($id);
            
            // Add additional statistics
            $user->bookings_count = $user->bookings()->count();
            $user->motors_count = $user->ownedMotors()->count();
            
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }
    }

    public function verifyUser($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->status === 'verified') {
                return back()->with('error', 'User sudah terverifikasi.');
            }
            
            $user->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => Auth::id()
            ]);
            
            // Create notification for user
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Akun Terverifikasi',
                'message' => 'Selamat! Akun Anda telah diverifikasi oleh admin. Anda sekarang memiliki akses penuh ke platform.',
                'type' => 'account_verification',
                'data' => json_encode([
                    'verified_by' => Auth::user()->name,
                    'verified_at' => now()
                ])
            ]);
            
            return back()->with('success', 'User berhasil diverifikasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memverifikasi user.');
        }
    }

    public function blacklistUser(Request $request, $id)
    {
        $request->validate([
            'blacklist_reason' => 'required|string|max:500'
        ]);
        
        try {
            $user = User::findOrFail($id);
            
            if ($user->id === Auth::id()) {
                return back()->with('error', 'Tidak dapat memblacklist akun sendiri.');
            }
            
            if ($user->status === 'blacklisted') {
                return back()->with('error', 'User sudah dalam blacklist.');
            }
            
            $user->update([
                'status' => 'blacklisted',
                'blacklist_reason' => $request->blacklist_reason,
                'verified_by' => Auth::id(),
                'verified_at' => now()
            ]);
            
            // Create notification for user
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Akun Di-blacklist',
                'message' => 'Akun Anda telah di-blacklist oleh admin. Alasan: ' . $request->blacklist_reason,
                'type' => 'account_blacklist',
                'data' => json_encode([
                    'blacklisted_by' => Auth::user()->name,
                    'reason' => $request->blacklist_reason,
                    'blacklisted_at' => now()
                ])
            ]);
            
            return back()->with('success', 'User berhasil di-blacklist.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memblacklist user.');
        }
    }

    public function removeBlacklist($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->status !== 'blacklisted') {
                return back()->with('error', 'User tidak dalam blacklist.');
            }
            
            $user->update([
                'status' => 'unverified',
                'blacklist_reason' => null,
                'verified_by' => Auth::id(),
                'verified_at' => now()
            ]);
            
            // Create notification for user
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Blacklist Dihapus',
                'message' => 'Blacklist pada akun Anda telah dihapus oleh admin. Anda dapat menggunakan platform kembali.',
                'type' => 'blacklist_removal',
                'data' => json_encode([
                    'removed_by' => Auth::user()->name,
                    'removed_at' => now()
                ])
            ]);
            
            return back()->with('success', 'Blacklist berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus blacklist.');
        }
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'role' => 'required|in:admin,pemilik,penyewa',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function showUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function destroyUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        try {
            // Check if user has active bookings or motors
            $activeBookings = $user->bookings()->whereIn('status', ['pending', 'confirmed', 'active'])->count();
            $ownedMotors = $user->ownedMotors()->count();
            
            if ($activeBookings > 0) {
                return back()->with('error', 'Tidak dapat menghapus user yang memiliki booking aktif.');
            }
            
            if ($ownedMotors > 0) {
                return back()->with('error', 'Tidak dapat menghapus user yang memiliki motor terdaftar.');
            }
            
            $user->delete();
            return back()->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus user.');
        }
    }

    public function bookings(Request $request)
    {
        $query = Booking::with(['renter', 'motor']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('renter', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('motor', function($motorQ) use ($search) {
                      $motorQ->where('brand', 'like', "%{$search}%")
                             ->orWhere('plate_number', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'ongoing' => Booking::where('status', 'ongoing')->count(),
            'completed' => Booking::where('status', 'completed')->count()
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function showBooking($id)
    {
        $booking = Booking::with(['renter', 'motor'])->findOrFail($id);
        return response()->json($booking);
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,ongoing,completed,cancelled'
        ]);

        $booking->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status pemesanan berhasil diupdate']);
    }

    public function reports(Request $request)
    {
        $query = Booking::with(['renter', 'motor'])->where('status', 'completed');

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        $completedBookings = $query->get();
        $totalRevenue = $completedBookings->sum('price');
        $adminCommission = $totalRevenue * 0.3; // 30% untuk admin
        $ownerShare = $totalRevenue * 0.7; // 70% untuk pemilik

        $summary = [
            'total_revenue' => $totalRevenue,
            'admin_commission' => $adminCommission,
            'owner_amount' => $ownerShare,
            'total_bookings' => $completedBookings->count()
        ];

        $topMotors = collect();
        
        // Real chart data from database
        $monthlyRevenue = Booking::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(price) as total_revenue')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $chartData = [
            'labels' => $monthlyRevenue->map(function($item) {
                return date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year));
            })->toArray(),
            'revenue' => $monthlyRevenue->pluck('total_revenue')->toArray(),
            'admin_commission' => $monthlyRevenue->map(function($item) {
                return $item->total_revenue * 0.3; // 30% admin commission
            })->toArray(),
            'owner_share' => $monthlyRevenue->map(function($item) {
                return $item->total_revenue * 0.7; // 70% owner share
            })->toArray()
        ];
        
        $ownerSummary = collect();

        // Additional variables for the view
        $commissionRate = 30; // New commission rate 30%
        $ownerRevenue = $ownerShare;
        $totalTransactions = $completedBookings->count();

        // Get revenue sharing data
        $revenueSharing = RevenueSharing::with(['owner', 'booking.motor'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.reports', compact(
            'transactions', 'summary', 'topMotors', 'chartData', 'ownerSummary',
            'totalRevenue', 'adminCommission', 'ownerRevenue', 'commissionRate',
            'totalTransactions', 'revenueSharing'
        ));
    }

    public function motors(Request $request)
    {
        $query = Motor::with(['owner', 'rentalRate']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by CC
        if ($request->filled('cc')) {
            $ccValue = $request->cc . 'cc'; // Add 'cc' suffix to match database enum format
            $query->where('type_cc', $ccValue);
        }

        // Search by brand or plate number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('plate_number', 'like', "%{$search}%");
            });
        }

        $motors = $query->orderBy('created_at', 'desc')->paginate(10);

        // Add query parameters to pagination links
        $motors->appends($request->query());

        // Count statistics for badges
        $pendingCount = Motor::where('status', 'pending_verification')->count();
        $verifiedCount = Motor::whereIn('status', ['available', 'rented', 'maintenance'])->count();

        return view('admin.motors', compact('motors', 'pendingCount', 'verifiedCount'));
    }

    /**
     * Show motor detail for admin
     */
    public function motorDetail($id)
    {
        $motor = Motor::with(['owner', 'rentalRate', 'bookings.user'])
            ->findOrFail($id);

        return view('admin.motor-detail', compact('motor'));
    }

    /**
     * Get motor detail for AJAX/modal (admin)
     */
    public function getMotorDetailAjax($id)
    {
        try {
            Log::info("AdminController getMotorDetailAjax called with ID: " . $id);
            
            $motor = Motor::with(['owner', 'rentalRate', 'bookings' => function($query) {
                    $query->with('user')->latest()->limit(5);
                }])
                ->findOrFail($id);

            Log::info("Motor found: " . $motor->brand . " " . $motor->model);

            // Calculate some statistics
            $totalBookings = $motor->bookings()->count();
            $activeBookings = $motor->bookings()->whereIn('status', ['confirmed', 'active'])->count();
            $completedBookings = $motor->bookings()->where('status', 'completed')->count();
            
            // Calculate total earnings
            $totalEarnings = $motor->bookings()
                ->where('bookings.status', 'completed')
                ->join('payments', 'bookings.id', '=', 'payments.booking_id')
                ->where('payments.status', 'completed')
                ->sum('payments.amount');

            $response = [
                'motor' => $motor,
                'stats' => [
                    'total_bookings' => $totalBookings,
                    'active_bookings' => $activeBookings,
                    'completed_bookings' => $completedBookings,
                    'total_earnings' => $totalEarnings
                ]
            ];

            Log::info("Returning JSON response", $response);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error("Error in getMotorDetailAjax: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function verifyMotor(Request $request, Motor $motor)
    {
        // Validate pricing if provided
        $request->validate([
            'daily_rate' => 'required|numeric|min:10000',
            'weekly_rate' => 'nullable|numeric|min:50000',
            'monthly_rate' => 'nullable|numeric|min:200000'
        ]);

        // Calculate rates if not provided
        $dailyRate = $request->daily_rate;
        $weeklyRate = $request->weekly_rate ?: ($dailyRate * 6);
        $monthlyRate = $request->monthly_rate ?: ($dailyRate * 20);

        // Update motor status
        $motor->update([
            'status' => 'available',
            'verified_by' => Auth::id(),
            'verified_at' => Carbon::now()
        ]);

        // Create or update rental rate
        $motor->rentalRate()->updateOrCreate(
            ['motor_id' => $motor->id],
            [
                'daily_rate' => $dailyRate,
                'weekly_rate' => $weeklyRate,
                'monthly_rate' => $monthlyRate
            ]
        );
        
        return redirect()->back()->with('success', 'Motor berhasil diverifikasi dan harga sewa telah ditetapkan');
    }

    public function financialReport(Request $request)
    {
        // Base query untuk completed bookings
        $query = Booking::with(['renter', 'motor', 'motor.owner'])->where('status', 'completed');

        // Filter berdasarkan tanggal jika ada
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Get transactions dengan pagination
        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);
        $transactions->appends($request->query());

        // Hitung summary dari semua completed bookings (tidak terbatas pagination)
        $allCompletedBookings = Booking::where('status', 'completed')->get();
        $totalRevenue = $allCompletedBookings->sum('price');
        $adminCommission = $totalRevenue * 0.3; // 30% untuk admin
        $ownerShare = $totalRevenue * 0.7; // 70% untuk pemilik

        $summary = [
            'total_revenue' => $totalRevenue,
            'admin_commission' => $adminCommission,
            'owner_amount' => $ownerShare,
            'total_bookings' => $allCompletedBookings->count()
        ];

        // Data untuk chart - revenue per bulan (12 bulan terakhir)
        $monthlyRevenue = Booking::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(price) as total')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $chartData = [
            'labels' => $monthlyRevenue->map(function($item) {
                return date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year));
            })->toArray(),
            'revenue' => $monthlyRevenue->pluck('total')->toArray(),
            'admin_commission' => $monthlyRevenue->map(function($item) {
                return $item->total * 0.3; // 30% admin commission
            })->toArray(),
            'owner_share' => $monthlyRevenue->map(function($item) {
                return $item->total * 0.7; // 70% owner share
            })->toArray()
        ];

        // Top motors yang paling banyak disewa
        $topMotors = Booking::select('motor_id', DB::raw('COUNT(*) as booking_count'), DB::raw('SUM(price) as total_revenue'))
            ->with('motor')
            ->where('status', 'completed')
            ->groupBy('motor_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();

        // Revenue sharing per pemilik
        $ownerSummary = RevenueSharing::select('owner_id', DB::raw('COUNT(*) as transaction_count'), 
                                               DB::raw('SUM(total_amount) as total_revenue'),
                                               DB::raw('SUM(owner_amount) as owner_earned'),
                                               DB::raw('SUM(admin_commission) as admin_earned'))
            ->with('owner')
            ->groupBy('owner_id')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return view('admin.financial-report', compact('transactions', 'summary', 'chartData', 'topMotors', 'ownerSummary'));
    }

    /**
     * Get notifications for admin
     */
    public function getNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $notifications->where('read_at', null)->count()
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Display payments for verification
     */
    public function payments(Request $request)
    {
        $query = \App\Models\Payment::with(['booking', 'booking.renter', 'booking.motor', 'verifiedBy']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'verified') {
                $query->whereNotNull('verified_at');
            } elseif ($request->status === 'unverified') {
                $query->whereNull('verified_at');
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method !== '') {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by penyewa name or booking ID
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('booking.renter', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('booking', function($q2) use ($search) {
                      $q2->where('id', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        // Summary data
        $summary = [
            'total_payments' => \App\Models\Payment::count(),
            'unverified_payments' => \App\Models\Payment::whereNull('verified_at')->count(),
            'verified_payments' => \App\Models\Payment::whereNotNull('verified_at')->count(),
            'pending_amount' => \App\Models\Payment::whereNull('verified_at')->sum('amount'),
            'verified_amount' => \App\Models\Payment::whereNotNull('verified_at')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'summary'));
    }

    /**
     * Show payment detail for verification
     */
    public function showPayment($id)
    {
        $payment = \App\Models\Payment::with([
            'booking', 
            'booking.renter', 
            'booking.motor', 
            'booking.motor.owner',
            'verifiedBy'
        ])->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Verify payment
     */
    public function verifyPayment(Request $request, $id)
    {
        $request->validate([
            'verification_notes' => 'nullable|string|max:500',
            'action' => 'required|in:approve,reject'
        ]);

        $payment = \App\Models\Payment::with(['booking'])->findOrFail($id);

        if ($payment->verified_at) {
            return back()->with('error', 'Pembayaran sudah diverifikasi sebelumnya.');
        }

        $payment->update([
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'payment_notes' => $request->verification_notes,
            'status' => $request->action === 'approve' ? 'paid' : 'failed'
        ]);

        // Update booking status based on verification
        if ($request->action === 'approve') {
            $payment->booking->update(['status' => 'confirmed']);
            $message = 'Pembayaran berhasil diverifikasi dan disetujui.';
        } else {
            $payment->booking->update(['status' => 'payment_rejected']);
            $message = 'Pembayaran ditolak.';
        }

        // Create notification for renter
        \App\Models\Notification::create([
            'user_id' => $payment->booking->renter_id,
            'title' => 'Status Pembayaran Diupdate',
            'message' => $request->action === 'approve' 
                ? 'Pembayaran Anda telah diverifikasi dan disetujui. Booking dikonfirmasi.'
                : 'Pembayaran Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.',
            'type' => 'payment_verification',
            'data' => json_encode([
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking->id,
                'action' => $request->action
            ])
        ]);

        return redirect()->route('admin.payments')
            ->with('success', $message);
    }

    /**
     * Get payment detail for AJAX modal
     */
    public function getPaymentDetailAjax($id)
    {
        try {
            $payment = \App\Models\Payment::with([
                'booking', 
                'booking.renter', 
                'booking.motor', 
                'booking.motor.owner',
                'verifiedBy'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'payment' => $payment,
                'booking' => $payment->booking,
                'renter' => $payment->booking->renter,
                'motor' => $payment->booking->motor,
                'owner' => $payment->booking->motor->owner,
                'verified_by' => $payment->verifiedBy,
                'payment_proof_url' => $payment->payment_proof ? asset('storage/' . $payment->payment_proof) : null,
                'formatted_amount' => 'Rp ' . number_format((float)$payment->amount, 0, ',', '.'),
                'formatted_payment_method' => $payment->formatted_payment_method,
                'is_verified' => $payment->is_verified,
                'can_verify' => !$payment->verified_at
            ]);
        } catch (\Exception $e) {
            Log::error("Error getting payment detail: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil data pembayaran.'], 500);
        }
    }

    /**
     * Delete motor by admin
     */
    public function deleteMotor($id)
    {
        try {
            $motor = Motor::with(['bookings', 'rentalRate', 'owner'])->findOrFail($id);
            
            // Check if motor has active bookings
            $activeBookings = $motor->bookings()
                ->whereIn('status', ['pending', 'confirmed', 'ongoing'])
                ->count();
                
            if ($activeBookings > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Motor tidak dapat dihapus karena masih memiliki booking aktif.'
                ], 400);
            }
            
            // Check if motor has completed bookings (for data integrity)
            $completedBookings = $motor->bookings()
                ->where('status', 'completed')
                ->count();
                
            if ($completedBookings > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Motor tidak dapat dihapus karena memiliki riwayat booking yang sudah selesai. Hal ini diperlukan untuk menjaga integritas data keuangan.'
                ], 400);
            }
            
            // Store motor info for notification
            $motorInfo = [
                'owner_id' => $motor->owner_id,
                'brand' => $motor->brand,
                'model' => $motor->model,
                'license_plate' => $motor->license_plate
            ];
            
            // Delete related files if exist
            if ($motor->photo) {
                Storage::disk('public')->delete($motor->photo);
            }
            if ($motor->document) {
                Storage::disk('public')->delete($motor->document);
            }
            
            // Delete related records (with foreign key constraints)
            // Delete payments first (they reference bookings)
            DB::table('payments')->whereIn('booking_id', $motor->bookings->pluck('id'))->delete();
            
            // Delete bookings
            $motor->bookings()->delete();
            
            // Delete rental rate
            if ($motor->rentalRate) {
                $motor->rentalRate->delete();
            }
            
            // Delete motor
            $motor->delete();
            
            // Create notification for motor owner
            Notification::create([
                'user_id' => $motorInfo['owner_id'],
                'title' => 'Motor Dihapus oleh Admin',
                'message' => "Motor {$motorInfo['brand']} {$motorInfo['model']} dengan plat nomor {$motorInfo['license_plate']} telah dihapus oleh admin.",
                'type' => 'motor_deleted',
                'is_read' => false
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Motor berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error deleting motor: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus motor: ' . $e->getMessage()
            ], 500);
        }
    }
}