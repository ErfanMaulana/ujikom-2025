<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RentalRate;
use App\Models\RevenueSharing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

class PemilikController extends Controller
{
    // Middleware akan didefinisikan di routes

    /**
     * Check if the current user is verified
     */
    private function checkUserVerification()
    {
        $user = Auth::user();
        if (!$user->verified_at || $user->status !== 'verified') {
            return redirect()->route('pemilik.dashboard')
                ->withErrors(['verification' => 'Akun Anda belum diverifikasi. Silakan tunggu admin memverifikasi akun Anda sebelum dapat mendaftarkan motor.']);
        }
        return null;
    }

    /**
     * Dashboard untuk pemilik motor
     */
    public function dashboard()
    {
        $user = Auth::user();
        $isVerified = $user->verified_at && $user->status === 'verified';
        
        // Statistik untuk pemilik (realtime)
        $totalMotors = Motor::where('owner_id', $user->id)->count();
        
        // Hitung status realtime
        $userMotors = Motor::where('owner_id', $user->id)->get();
        $availableMotors = $userMotors->filter(function($motor) {
            return $motor->getCurrentStatus() === 'available';
        })->count();
        
        $rentedMotors = $userMotors->filter(function($motor) {
            return $motor->getCurrentStatus() === 'rented';
        })->count();
        
        $totalRevenue = RevenueSharing::whereHas('booking.motor', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->sum('owner_amount');

        // Motor terbaru
        $recentMotors = Motor::where('owner_id', $user->id)
            ->with('rentalRate')
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
            'recentBookings',
            'isVerified'
        ));
    }

    /**
     * Daftar motor milik pemilik
     */
    public function motors(Request $request)
    {
        $user = Auth::user();
        $isVerified = $user->verified_at && $user->status === 'verified';
        
        $query = Motor::where('owner_id', Auth::id())
            ->with('rentalRate');

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $motors = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pemilik.motors', compact('motors', 'isVerified'));
    }

    /**
     * Form tambah motor
     */
    public function createMotor()
    {
        // Check user verification
        $verificationCheck = $this->checkUserVerification();
        if ($verificationCheck) {
            return $verificationCheck;
        }

        return view('pemilik.motor-create');
    }

    /**
     * Proses tambah motor
     */
    public function storeMotor(Request $request)
    {
        // Check user verification
        $verificationCheck = $this->checkUserVerification();
        if ($verificationCheck) {
            return $verificationCheck;
        }

        $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'type_cc' => 'required|string|in:100cc,110cc,125cc,150cc,160cc,250cc,400cc,500cc,600cc',
            'year' => 'required|integer|min:2000|max:' . date('Y'),
            'color' => 'required|string|max:50',
            'plate_number' => 'required|string|max:20|unique:motors',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'document' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Upload gambar motor
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('motors', 'public');
        }

        // Upload dokumen motor
        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('motor-documents', 'public');
        }

        // Buat motor (tanpa harga sewa - akan diset oleh admin)
        $motor = Motor::create([
            'owner_id' => Auth::id(),
            'brand' => $request->brand,
            'model' => $request->model,
            'type_cc' => $request->type_cc,
            'year' => $request->year,
            'color' => $request->color,
            'plate_number' => strtoupper($request->plate_number),
            'description' => $request->description,
            'photo' => $photoPath,
            'document' => $documentPath,
            'status' => 'pending_verification' // Menunggu verifikasi admin
        ]);

        // Note: Harga sewa akan diset oleh admin saat verifikasi

        return redirect()->route('pemilik.motors')
            ->with('success', 'Motor berhasil didaftarkan! Menunggu verifikasi admin dan penetapan harga sewa.');
    }

    /**
     * Detail motor
     */
    public function motorDetail($id)
    {
        $motor = Motor::where('owner_id', Auth::id())
            ->with(['rentalRate', 'bookings.user'])
            ->findOrFail($id);

        return view('pemilik.motor-detail', compact('motor'));
    }

    /**
     * Get motor detail for AJAX/modal
     */
    public function getMotorDetailAjax($id)
    {
        $motor = Motor::where('owner_id', Auth::id())
            ->with(['rentalRate', 'bookings' => function($query) {
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

    /**
     * Form edit motor
     */
    public function editMotor($id)
    {
        // Check user verification
        $verificationCheck = $this->checkUserVerification();
        if ($verificationCheck) {
            return $verificationCheck;
        }

        Log::info('EditMotor called with ID: ' . $id . ' by user: ' . Auth::id());
        
        $motor = Motor::where('owner_id', Auth::id())
            ->with('rentalRate')
            ->findOrFail($id);

        Log::info('Motor found for edit: ' . $motor->brand . ' - ' . $motor->plate_number);

        return view('pemilik.motor-edit', compact('motor'));
    }

    /**
     * Update motor
     */
    public function updateMotor(Request $request, $id)
    {
        // Check user verification
        $verificationCheck = $this->checkUserVerification();
        if ($verificationCheck) {
            return $verificationCheck;
        }

        $motor = Motor::where('owner_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'type_cc' => 'required|string|in:100cc,110cc,125cc,150cc,160cc,250cc,400cc,500cc,600cc',
            'year' => 'required|integer|min:2000|max:' . date('Y'),
            'color' => 'required|string|max:50',
            'plate_number' => 'required|string|max:20|unique:motors,plate_number,' . $motor->id,
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'document' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Upload gambar jika ada
        $photoPath = $motor->photo;
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($motor->photo && Storage::disk('public')->exists($motor->photo)) {
                Storage::disk('public')->delete($motor->photo);
            }
            $photoPath = $request->file('photo')->store('motors', 'public');
        }

        // Upload dokumen jika ada
        $documentPath = $motor->document;
        if ($request->hasFile('document')) {
            // Hapus dokumen lama jika ada
            if ($motor->document && Storage::disk('public')->exists($motor->document)) {
                Storage::disk('public')->delete($motor->document);
            }
            $documentPath = $request->file('document')->store('motor-documents', 'public');
        }

        // Update motor
        $motor->update([
            'brand' => $request->brand,
            'model' => $request->model,
            'type_cc' => $request->type_cc,
            'year' => $request->year,
            'color' => $request->color,
            'plate_number' => strtoupper($request->plate_number),
            'description' => $request->description,
            'photo' => $photoPath,
            'document' => $documentPath
        ]);

        return redirect()->route('pemilik.motors')
            ->with('success', 'Motor berhasil diperbarui!');
    }

    /**
     * Delete motor
     */
    public function deleteMotor($id)
    {
        // Check user verification
        $verificationCheck = $this->checkUserVerification();
        if ($verificationCheck) {
            return $verificationCheck;
        }

        Log::info('DeleteMotor called with ID: ' . $id . ' by user: ' . Auth::id());
        
        $motor = Motor::where('owner_id', Auth::id())
            ->findOrFail($id);

        Log::info('Motor found: ' . $motor->brand . ' - ' . $motor->plate_number);

        // Cek apakah motor sedang disewa
        $activeBookings = $motor->bookings()
            ->whereIn('status', ['confirmed', 'active'])
            ->count();

        if ($activeBookings > 0) {
            Log::info('Motor has active bookings: ' . $activeBookings);
            return back()->withErrors(['error' => 'Motor tidak dapat dihapus karena sedang ada pesanan aktif.']);
        }

        // Hapus foto jika ada
        if ($motor->photo && Storage::disk('public')->exists($motor->photo)) {
            Storage::disk('public')->delete($motor->photo);
        }

        // Hapus dokumen jika ada
        if ($motor->document && Storage::disk('public')->exists($motor->document)) {
            Storage::disk('public')->delete($motor->document);
        }

        // Hapus rental rate
        if ($motor->rentalRate) {
            $motor->rentalRate->delete();
        }

        // Hapus motor
        $motor->delete();

        Log::info('Motor deleted successfully');

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
        })->with(['motor', 'user', 'payment']);

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
        })->with(['booking.motor', 'booking.user']);

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
        $booking = Booking::with(['motor', 'user', 'payment'])
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
            // Enhanced logging for debugging
            \Log::info("Activate booking attempt", [
                'booking_id' => $id,
                'user_id' => Auth::id(),
                'timestamp' => now()
            ]);

            // Check if booking exists first
            $bookingExists = Booking::find($id);
            if (!$bookingExists) {
                \Log::warning("Booking not found", ['booking_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak ditemukan.'
                ]);
            }

            // Check authorization - booking must belong to motor owned by current user
            $booking = Booking::with('motor')->whereHas('motor', function($q) {
                $q->where('owner_id', Auth::id());
            })->find($id);

            if (!$booking) {
                \Log::warning("Authorization failed", [
                    'booking_id' => $id,
                    'user_id' => Auth::id(),
                    'motor_owner_id' => $bookingExists->motor->owner_id ?? 'unknown'
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak berhak mengakses booking ini. Pastikan Anda adalah pemilik motor.'
                ]);
            }

            // Check booking status
            if ($booking->status !== 'confirmed') {
                \Log::info("Invalid status for activation", [
                    'booking_id' => $id,
                    'current_status' => $booking->status,
                    'required_status' => 'confirmed'
                ]);
                return response()->json([
                    'success' => false,
                    'message' => "Status booking saat ini: {$booking->status}. Booking harus dikonfirmasi terlebih dahulu sebelum diaktifkan."
                ]);
            }

            // Update booking status
            $updateResult = $booking->update(['status' => 'active']);
            
            if (!$updateResult) {
                \Log::error("Failed to update booking status", ['booking_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate status booking. Silakan coba lagi.'
                ]);
            }

            \Log::info("Booking activated successfully", [
                'booking_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Masa sewa berhasil dimulai.'
            ]);

        } catch (ModelNotFoundException $e) {
            \Log::error("Booking not found exception", [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan atau Anda tidak memiliki akses.'
            ]);
        } catch (\Exception $e) {
            \Log::error("Activate booking exception", [
                'booking_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.'
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

            // Update existing revenue sharing record to 'paid' status
            $revenueSharing = RevenueSharing::where('booking_id', $booking->id)->first();
            
            if ($revenueSharing) {
                // Update existing record to 'paid' status
                $revenueSharing->update([
                    'status' => 'paid',
                    'settled_at' => now()
                ]);
                
                \Log::info('Revenue sharing updated to paid', [
                    'booking_id' => $booking->id,
                    'revenue_sharing_id' => $revenueSharing->id
                ]);
            } else {
                // Fallback: Create revenue sharing record if it doesn't exist
                $totalAmount = $booking->price;
                $ownerAmount = $totalAmount * 0.7; // 70% untuk pemilik
                $adminCommission = $totalAmount * 0.3; // 30% untuk admin

                RevenueSharing::create([
                    'booking_id' => $booking->id,
                    'owner_id' => $booking->motor->owner_id,
                    'total_amount' => $totalAmount,
                    'owner_amount' => $ownerAmount,
                    'admin_commission' => $adminCommission,
                    'owner_percentage' => 70.00,
                    'admin_percentage' => 30.00,
                    'status' => 'paid',
                    'settled_at' => now()
                ]);
                
                \Log::warning('Revenue sharing created at completion (should have been created at payment approval)', [
                    'booking_id' => $booking->id
                ]);
            }

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