<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RentalRate;
use App\Models\RevenueSharing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PemilikController extends Controller
{
    // Middleware akan didefinisikan di routes

    /**
     * Dashboard untuk pemilik motor
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Statistik untuk pemilik
        $totalMotors = Motor::where('owner_id', $user->id)->count();
        $availableMotors = Motor::where('owner_id', $user->id)
            ->where('status', 'available')
            ->count();
        $rentedMotors = Motor::where('owner_id', $user->id)
            ->whereHas('bookings', function($query) {
                $query->where('status', 'confirmed')
                      ->where('start_date', '<=', Carbon::now())
                      ->where('end_date', '>=', Carbon::now());
            })
            ->count();
        
        $totalRevenue = RevenueSharing::whereHas('booking.motor', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->sum('owner_amount');

        // Motor terbaru
        $recentMotors = Motor::where('owner_id', $user->id)
            ->with('rentalRates')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Booking terbaru
        $recentBookings = Booking::whereHas('motor', function($query) use ($user) {
            $query->where('owner_id', $user->id);
        })
        ->with(['motor', 'user'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        return view('pemilik.dashboard', compact(
            'totalMotors',
            'availableMotors', 
            'rentedMotors',
            'totalRevenue',
            'recentMotors',
            'recentBookings'
        ));
    }

    /**
     * Daftar motor milik pemilik
     */
    public function motors(Request $request)
    {
        $query = Motor::where('owner_id', Auth::id())
            ->with('rentalRates');

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $motors = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pemilik.motors', compact('motors'));
    }

    /**
     * Form tambah motor
     */
    public function createMotor()
    {
        return view('pemilik.motor-create');
    }

    /**
     * Proses tambah motor
     */
    public function storeMotor(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:100',
            'type_cc' => 'required|string|in:100cc,125cc,150cc,250cc,500cc',
            'plate_number' => 'required|string|max:20|unique:motors',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'daily_rate' => 'required|string',
            'weekly_rate' => 'nullable|string',
            'monthly_rate' => 'nullable|string'
        ]);

        // Convert string prices to integers
        $dailyRate = (int) preg_replace('/[^0-9]/', '', $request->daily_rate);
        $weeklyRate = $request->weekly_rate ? (int) preg_replace('/[^0-9]/', '', $request->weekly_rate) : ($dailyRate * 6);
        $monthlyRate = $request->monthly_rate ? (int) preg_replace('/[^0-9]/', '', $request->monthly_rate) : ($dailyRate * 20);

        // Validate minimum rates
        if ($dailyRate < 10000) {
            return back()->withErrors(['daily_rate' => 'Tarif harian minimal Rp 10.000'])->withInput();
        }

        // Upload gambar
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('motors', 'public');
        }

        // Buat motor
        $motor = Motor::create([
            'owner_id' => Auth::id(),
            'brand' => $request->brand,
            'type_cc' => $request->type_cc,
            'plate_number' => strtoupper($request->plate_number),
            'description' => $request->description,
            'photo' => $photoPath,
            'status' => 'pending_verification' // Menunggu verifikasi admin
        ]);

        // Buat rental rate
        RentalRate::create([
            'motor_id' => $motor->id,
            'daily_rate' => $dailyRate,
            'weekly_rate' => $weeklyRate,
            'monthly_rate' => $monthlyRate
        ]);

        return redirect()->route('pemilik.motors')
            ->with('success', 'Motor berhasil didaftarkan! Menunggu verifikasi admin untuk dapat disewakan.');
    }

    /**
     * Detail motor
     */
    public function motorDetail($id)
    {
        $motor = Motor::where('owner_id', Auth::id())
            ->with(['rentalRates', 'bookings.user'])
            ->findOrFail($id);

        return view('pemilik.motor-detail', compact('motor'));
    }

    /**
     * Form edit motor
     */
    public function editMotor($id)
    {
        $motor = Motor::where('owner_id', Auth::id())
            ->where('status', '!=', 'rejected') // Tidak bisa edit yang ditolak
            ->findOrFail($id);

        return view('pemilik.motor-edit', compact('motor'));
    }

    /**
     * Update motor
     */
    public function updateMotor(Request $request, $id)
    {
        $motor = Motor::where('owner_id', Auth::id())
            ->where('status', '!=', 'rejected')
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'cc' => 'required|in:100,125,150',
            'color' => 'required|string|max:50',
            'plate_number' => 'required|string|max:15|unique:motors,plate_number,' . $motor->id,
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $updateData = [
            'name' => $request->name,
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'cc' => $request->cc,
            'color' => $request->color,
            'plate_number' => $request->plate_number,
            'description' => $request->description,
        ];

        // Upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($motor->image && file_exists(public_path('uploads/motors/' . $motor->image))) {
                unlink(public_path('uploads/motors/' . $motor->image));
            }

            $file = $request->file('image');
            $imageName = 'motor_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/motors'), $imageName);
            $updateData['image'] = $imageName;
        }

        // Jika motor sudah diverifikasi, status kembali ke pending untuk review ulang
        if ($motor->status === 'available') {
            $updateData['status'] = 'pending';
        }

        $motor->update($updateData);

        return redirect()->route('pemilik.motor.detail', $motor->id)
            ->with('success', 'Motor berhasil diupdate.');
    }

    /**
     * Hapus motor
     */
    public function deleteMotor($id)
    {
        $motor = Motor::where('owner_id', Auth::id())
            ->findOrFail($id);

        // Cek apakah ada booking aktif
        $activeBookings = $motor->bookings()
            ->where('status', 'confirmed')
            ->where('end_date', '>=', Carbon::now())
            ->count();

        if ($activeBookings > 0) {
            return back()->with('error', 'Tidak dapat menghapus motor yang sedang disewa.');
        }

        // Hapus gambar
        if ($motor->image && file_exists(public_path('uploads/motors/' . $motor->image))) {
            unlink(public_path('uploads/motors/' . $motor->image));
        }

        $motor->delete();

        return redirect()->route('pemilik.motors')
            ->with('success', 'Motor berhasil dihapus.');
    }

    /**
     * Daftar booking untuk motor milik pemilik
     */
    public function bookings(Request $request)
    {
        $query = Booking::whereHas('motor', function($q) {
            $q->where('owner_id', Auth::id());
        })->with(['motor', 'renter', 'payment']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pemilik.bookings', compact('bookings'));
    }

    /**
     * Detail booking
     */
    public function bookingDetail($id)
    {
        $booking = Booking::whereHas('motor', function($q) {
            $q->where('owner_id', Auth::id());
        })
        ->with(['motor', 'user', 'payment', 'rentalRate'])
        ->findOrFail($id);

        return view('pemilik.booking-detail', compact('booking'));
    }

    /**
     * Laporan pendapatan
     */
    public function revenueReport(Request $request)
    {
        // Base query for total calculation
        $totalQuery = RevenueSharing::whereHas('booking.motor', function($q) {
            $q->where('owner_id', Auth::id());
        });

        // Base query for paginated results
        $paginatedQuery = RevenueSharing::whereHas('booking.motor', function($q) {
            $q->where('owner_id', Auth::id());
        })->with(['booking.motor', 'booking.renter']);

        // Apply filters to both queries
        if ($request->has('month') && $request->month !== '') {
            $totalQuery->whereMonth('created_at', $request->month);
            $paginatedQuery->whereMonth('created_at', $request->month);
        }

        if ($request->has('year') && $request->year !== '') {
            $totalQuery->whereYear('created_at', $request->year);
            $paginatedQuery->whereYear('created_at', $request->year);
        }

        // Get results
        $totalRevenue = $totalQuery->sum('owner_amount');
        $revenues = $paginatedQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('pemilik.revenue-report', compact('revenues', 'totalRevenue'));
    }

    /**
     * Download laporan pendapatan
     */
    public function downloadRevenueReport(Request $request)
    {
        // Implementation untuk download PDF/Excel
        // Akan diimplementasi jika diperlukan
        return back()->with('info', 'Fitur download sedang dalam pengembangan.');
    }

    /**
     * Get booking detail for AJAX call
     */
    public function getBookingDetail($id)
    {
        $booking = Booking::with(['motor', 'renter', 'payment'])
            ->whereHas('motor', function($q) {
                $q->where('owner_id', Auth::id());
            })
            ->findOrFail($id);

        return view('pemilik.booking-detail', compact('booking'));
    }

    /**
     * Confirm booking
     */
    public function confirmBooking($id)
    {
        try {
            $booking = Booking::whereHas('motor', function($q) {
                $q->where('owner_id', Auth::id());
            })->findOrFail($id);

            if ($booking->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak dapat dikonfirmasi karena status sudah berubah.'
                ]);
            }

            $booking->update(['status' => 'confirmed']);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dikonfirmasi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cancel booking
     */
    public function cancelBooking(Request $request, $id)
    {
        try {
            $booking = Booking::whereHas('motor', function($q) {
                $q->where('owner_id', Auth::id());
            })->findOrFail($id);

            if ($booking->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak dapat dibatalkan karena status sudah berubah.'
                ]);
            }

            $booking->update([
                'status' => 'cancelled',
                'notes' => $request->reason ?? 'Dibatalkan oleh pemilik motor'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Activate booking
     */
    public function activateBooking($id)
    {
        try {
            $booking = Booking::whereHas('motor', function($q) {
                $q->where('owner_id', Auth::id());
            })->findOrFail($id);

            if ($booking->status !== 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking harus dikonfirmasi terlebih dahulu sebelum diaktifkan.'
                ]);
            }

            $booking->update(['status' => 'active']);

            return response()->json([
                'success' => true,
                'message' => 'Masa sewa berhasil dimulai.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Complete booking
     */
    public function completeBooking($id)
    {
        try {
            $booking = Booking::with('motor')->whereHas('motor', function($q) {
                $q->where('owner_id', Auth::id());
            })->findOrFail($id);

            if ($booking->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking harus dalam status aktif untuk dapat diselesaikan.'
                ]);
            }

            $booking->update(['status' => 'completed']);

            // Create revenue sharing record
            $totalAmount = $booking->price;
            $ownerAmount = $totalAmount * 0.9; // 90% untuk pemilik
            $adminCommission = $totalAmount * 0.1; // 10% untuk admin

            RevenueSharing::create([
                'booking_id' => $booking->id,
                'owner_id' => $booking->motor->owner_id,
                'total_amount' => $totalAmount,
                'owner_amount' => $ownerAmount,
                'admin_commission' => $adminCommission
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Masa sewa berhasil diselesaikan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}