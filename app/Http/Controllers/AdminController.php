<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Motor;
use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        return view('admin.dashboard', compact(
            'totalUsers', 'totalPenyewa', 'totalPemilik', 'totalMotors', 
            'totalBookings', 'totalRevenue', 'pendingMotorsCount', 'pendingMotors',
            'pendingBookingsList', 'availableMotors', 'pendingBookings', 'confirmedBookings'
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

        return view('admin.users.index', compact('users'));
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
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus akun sendiri'], 400);
        }

        $user->delete();
        return response()->json(['success' => true, 'message' => 'Pengguna berhasil dihapus']);
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
        $adminCommission = $totalRevenue * 0.1;
        $ownerShare = $totalRevenue * 0.9;

        $summary = [
            'total_revenue' => $totalRevenue,
            'admin_commission' => $adminCommission,
            'owner_amount' => $ownerShare,
            'total_bookings' => $completedBookings->count()
        ];

        $topMotors = collect();
        $chartData = [
            'labels' => [],
            'revenue' => [],
            'commission' => []
        ];
        $ownerSummary = collect();

        return view('admin.reports.index', compact(
            'transactions', 'summary', 'topMotors', 'chartData', 'ownerSummary'
        ));
    }

    public function motors(Request $request)
    {
        $query = Motor::with(['owner']);
        $motors = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.motors', compact('motors'));
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
        $motor = Motor::with(['owner', 'rentalRate', 'bookings' => function($query) {
                $query->with('user')->latest()->limit(5);
            }])
            ->findOrFail($id);

        // Calculate some statistics
        $totalBookings = $motor->bookings()->count();
        $activeBookings = $motor->bookings()->whereIn('status', ['confirmed', 'active'])->count();
        $completedBookings = $motor->bookings()->where('status', 'completed')->count();
        
        // Calculate total earnings
        $totalEarnings = $motor->bookings()
            ->where('status', 'completed')
            ->join('payments', 'bookings.id', '=', 'payments.booking_id')
            ->where('payments.status', 'completed')
            ->sum('payments.amount');

        return response()->json([
            'motor' => $motor,
            'stats' => [
                'total_bookings' => $totalBookings,
                'active_bookings' => $activeBookings,
                'completed_bookings' => $completedBookings,
                'total_earnings' => $totalEarnings
            ]
        ]);
    }

    public function verifyMotor(Motor $motor)
    {
        $motor->update([
            'status' => 'available',
            'verified_by' => Auth::id(),
            'verified_at' => Carbon::now()
        ]);
        
        return redirect()->back()->with('success', 'Motor berhasil diverifikasi');
    }

    public function financialReport(Request $request)
    {
        $query = Booking::with(['renter', 'motor'])->where('status', 'completed');

        // Filter berdasarkan tanggal jika ada
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        // Hitung summary
        $completedBookings = $query->get();
        $totalRevenue = $completedBookings->sum('price');
        $adminCommission = $totalRevenue * 0.1;
        $ownerShare = $totalRevenue * 0.9;

        $summary = [
            'total_revenue' => $totalRevenue,
            'admin_commission' => $adminCommission,
            'owner_amount' => $ownerShare,
            'total_bookings' => $completedBookings->count()
        ];

        // Data untuk chart (revenue per bulan)
        $monthlyRevenue = Booking::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(price) as total')
            ->where('status', 'completed')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $chartData = [
            'labels' => $monthlyRevenue->map(function($item) {
                return date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year));
            })->reverse(),
            'revenue' => $monthlyRevenue->pluck('total')->reverse()
        ];

        return view('admin.reports.index', compact('transactions', 'summary', 'chartData'));
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
}