@extends('layouts.fann')

@section('title', 'Verifikasi Motor')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Verifikasi Motor</h1>
    <p>Kelola dan verifikasi motor yang didaftarkan pemilik</p>
</div>

<!-- Filter & Search -->
<div class="row mb-4">
    <div class="col-md-8">
        <form method="GET" action="{{ route('admin.motors') }}" class="row g-3">
            <div class="col-md-3">
                <select class="form-select" name="status">
                    <option value="">Semua Status</option>
                    <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="cc">
                    <option value="">Semua CC</option>
                    <option value="100" {{ request('cc') == '100' ? 'selected' : '' }}>100cc</option>
                    <option value="125" {{ request('cc') == '125' ? 'selected' : '' }}>125cc</option>
                    <option value="150" {{ request('cc') == '150' ? 'selected' : '' }}>150cc</option>
                    <option value="250" {{ request('cc') == '250' ? 'selected' : '' }}>250cc</option>
                    <option value="500" {{ request('cc') == '500' ? 'selected' : '' }}>500cc</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari brand atau plat nomor...">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.motors') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <div class="row g-2">
            <div class="col-6">
                <div class="card bg-warning text-white">
                    <div class="card-body py-2 text-center">
                        <h6 class="mb-0">{{ $pendingCount ?? 0 }}</h6>
                        <small>Perlu Verifikasi</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card bg-success text-white">
                    <div class="card-body py-2 text-center">
                        <h6 class="mb-0">{{ $verifiedCount ?? 0 }}</h6>
                        <small>Terverifikasi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Motors Grid -->
<div class="row">
    @if($motors->count() > 0)
        @foreach($motors as $motor)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm motor-card" data-motor-id="{{ $motor->id }}">
                <!-- Motor Image -->
                <div class="position-relative">
                    @if($motor->photo)
                        <img src="{{ Storage::url($motor->photo) }}" 
                             class="card-img-top" 
                             alt="{{ $motor->brand }}" 
                             style="height: 250px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 250px;">
                            <i class="bi bi-motorcycle text-muted" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="position-absolute top-0 end-0 m-3">
                        @if($motor->status === 'pending_verification')
                            <span class="badge bg-warning fs-6">
                                <i class="bi bi-clock me-1"></i>Menunggu Verifikasi
                            </span>
                        @elseif($motor->status === 'available')
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>Tersedia
                            </span>
                        @elseif($motor->status === 'rented')
                            <span class="badge bg-warning text-dark fs-6">
                                <i class="bi bi-person-check me-1"></i>Sedang Disewa
                            </span>
                        @elseif($motor->status === 'maintenance')
                            <span class="badge bg-secondary fs-6">
                                <i class="bi bi-tools me-1"></i>Maintenance
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Motor Info -->
                <div class="card-body">
                    <h5 class="card-title d-flex align-items-center">
                        <i class="bi bi-motorcycle text-primary me-2"></i>
                        {{ $motor->brand }}
                    </h5>
                    <p class="text-muted mb-2">
                        <i class="bi bi-gear me-1"></i>{{ $motor->type_cc }}
                        <span class="ms-3">
                            <i class="bi bi-credit-card me-1"></i>{{ $motor->plate_number }}
                        </span>
                    </p>
                    
                    <!-- Owner Info -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle me-2 text-muted"></i>
                            <div>
                                <div class="fw-bold">{{ $motor->owner->name }}</div>
                                <small class="text-muted">{{ $motor->owner->email }}</small>
                            </div>
                        </div>
                        
                        <!-- Document Photo -->
                        @if($motor->document)
                            <div class="mt-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-text me-2 text-muted"></i>
                                    <div>
                                        <small class="text-muted d-block">Dokumen Motor:</small>
                                        <img src="{{ Storage::url($motor->document) }}" 
                                             alt="Dokumen Motor" 
                                             class="img-thumbnail cursor-pointer"
                                             style="width: 60px; height: 40px; object-fit: cover;"
                                             onclick="showDocumentPreview('{{ Storage::url($motor->document) }}')"
                                             title="Klik untuk memperbesar - {{ $motor->document }}"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <small class="text-danger" style="display: none;">Error loading: {{ $motor->document }}</small>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mt-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-x me-2 text-muted"></i>
                                    <small class="text-muted">Dokumen belum diupload</small>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Rental Rates -->
                    @if($motor->rentalRate)
                        <div class="mb-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <small class="text-muted">Harian</small>
                                    <div class="fw-bold text-primary small">Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Mingguan</small>
                                    <div class="fw-bold text-primary small">Rp {{ number_format($motor->rentalRate->weekly_rate, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Bulanan</small>
                                    <div class="fw-bold text-primary small">Rp {{ number_format($motor->rentalRate->monthly_rate, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Card Footer with Action Buttons -->
                <div class="card-footer bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $motor->created_at->format('d M Y') }}
                        </small>
                        <div class="btn-group">
                            <!-- Detail Button -->
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary"
                                    data-action="show-detail"
                                    data-motor-id="{{ $motor->id }}"
                                    onclick="showMotorDetail({{ $motor->id }})"
                                    title="Lihat Detail Motor">
                                <i class="bi bi-eye me-1"></i>Detail
                            </button>
                            
                            <!-- Verification Button (only for pending motors) -->
                            @if($motor->status === 'pending_verification')
                                <button type="button" 
                                        class="btn btn-sm btn-success"
                                        data-action="verify-motor"
                                        data-motor-id="{{ $motor->id }}"
                                        onclick="directVerifyMotor({{ $motor->id }})"
                                        title="Verifikasi Motor">
                                    <i class="bi bi-check-circle me-1"></i>Verifikasi
                                </button>
                            @else
                                <span class="btn btn-sm btn-outline-success disabled">
                                    <i class="bi bi-check-circle me-1"></i>Terverifikasi
                                </span>
                            @endif
                            
                            <!-- Delete Button -->
                            @php
                                $hasActiveBookings = $motor->bookings()
                                    ->whereIn('status', ['pending', 'confirmed', 'ongoing'])
                                    ->count() > 0;
                                $hasCompletedBookings = $motor->bookings()
                                    ->where('status', 'completed')
                                    ->count() > 0;
                                $canDelete = !$hasActiveBookings && !$hasCompletedBookings;
                            @endphp
                            
                            @if($canDelete)
                                <button type="button" 
                                        class="btn btn-sm btn-danger"
                                        data-action="delete-motor"
                                        data-motor-id="{{ $motor->id }}"
                                        data-motor-brand="{{ $motor->brand }}"
                                        data-motor-model="{{ $motor->model }}"
                                        data-motor-plate="{{ $motor->license_plate }}"
                                        data-motor-owner="{{ $motor->owner->name }}"
                                        onclick="confirmDeleteMotor({{ $motor->id }}, '{{ $motor->brand }}', '{{ $motor->model }}', '{{ $motor->license_plate }}', '{{ $motor->owner->name }}')"
                                        title="Hapus Motor">
                                    <i class="bi bi-trash me-1"></i>Hapus
                                </button>
                            @else
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger disabled"
                                        title="{{ $hasActiveBookings ? 'Motor memiliki booking aktif' : 'Motor memiliki riwayat booking' }}">
                                    <i class="bi bi-shield-exclamation me-1"></i>Tidak Dapat Dihapus
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-motorcycle text-muted" style="font-size: 5rem;"></i>
                <h4 class="mt-3 text-muted">Tidak ada motor ditemukan</h4>
                <p class="text-muted">Coba ubah filter pencarian Anda</p>
            </div>
        </div>
    @endif
</div>

<!-- Pagination -->
@if($motors->hasPages())
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Info Pagination -->
                        <div class="pagination-info">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle me-2 text-muted"></i>
                                <div>
                                    <small class="text-muted fw-medium">
                                        Menampilkan {{ $motors->firstItem() ?? 0 }} - {{ $motors->lastItem() ?? 0 }} 
                                        dari {{ $motors->total() }} motor
                                    </small>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        Halaman {{ $motors->currentPage() }} dari {{ $motors->lastPage() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pagination Links -->
                        <div class="pagination-wrapper">
                            <nav aria-label="Motor pagination">
                                <ul class="pagination pagination-sm mb-0 custom-pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($motors->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="bi bi-chevron-left"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $motors->previousPageUrl() }}" rel="prev">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($motors->getUrlRange(1, $motors->lastPage()) as $page => $url)
                                        @if ($page == $motors->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($motors->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $motors->nextPageUrl() }}" rel="next">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="bi bi-chevron-right"></i>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                        
                        <!-- Quick Jump -->
                        @if($motors->lastPage() > 5)
                            <div class="quick-jump">
                                <div class="input-group input-group-sm" style="width: 120px;">
                                    <input type="number" 
                                           class="form-control form-control-sm" 
                                           id="pageJump" 
                                           min="1" 
                                           max="{{ $motors->lastPage() }}" 
                                           value="{{ $motors->currentPage() }}"
                                           placeholder="Page">
                                    <button class="btn btn-outline-primary btn-sm" 
                                            type="button" 
                                            onclick="jumpToPage()">
                                        <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Motor Detail Modal -->
<div class="modal fade" id="motorDetailModal" tabindex="-1" aria-labelledby="motorDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 bg-primary text-white">
                <h5 class="modal-title" id="motorDetailModalLabel">
                    <i class="bi bi-motorcycle me-2"></i>Detail Motor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="motorDetailContent">
                    <!-- Content will be loaded here by JavaScript -->
                </div>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Tutup
                </button>
                <button type="button" class="btn btn-success" id="verifyMotorFromModal" style="display: none;">
                    <i class="bi bi-check-circle me-2"></i>Verifikasi Motor
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Document Preview Modal -->
<div class="modal fade" id="documentPreviewModal" tabindex="-1" aria-labelledby="documentPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 bg-dark text-white">
                <h5 class="modal-title" id="documentPreviewModalLabel">
                    <i class="bi bi-file-earmark-text me-2"></i>Preview Dokumen Motor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center bg-dark">
                <img id="documentPreviewImage" 
                     src="" 
                     alt="Dokumen Motor" 
                     class="img-fluid"
                     style="max-height: 80vh; object-fit: contain;">
            </div>
            <div class="modal-footer border-top-0 bg-dark">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Motor Confirmation Modal -->
<div class="modal fade" id="deleteMotorModal" tabindex="-1" aria-labelledby="deleteMotorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 bg-danger text-white">
                <h5 class="modal-title" id="deleteMotorModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus Motor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <div class="text-danger mb-3">
                        <i class="bi bi-trash display-1"></i>
                    </div>
                    <h6 class="fw-bold">Apakah Anda yakin ingin menghapus motor ini?</h6>
                    <p class="text-muted mb-3">Tindakan ini tidak dapat dibatalkan dan akan menghapus:</p>
                </div>
                
                <div class="bg-light rounded p-3 mb-3">
                    <div class="row">
                        <div class="col-4 text-muted small">Brand/Model:</div>
                        <div class="col-8 fw-bold" id="deleteMotorBrand">-</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-muted small">Plat Nomor:</div>
                        <div class="col-8 fw-bold" id="deleteMotorPlate">-</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-muted small">Pemilik:</div>
                        <div class="col-8 fw-bold" id="deleteMotorOwner">-</div>
                    </div>
                </div>
                
                <div class="alert alert-warning border-0 bg-warning bg-opacity-10">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    <small>
                        <strong>Peringatan:</strong> Motor yang dihapus akan menghilangkan semua data terkait 
                        termasuk booking, pembayaran, dan riwayat lainnya.
                    </small>
                </div>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteMotor">
                    <i class="bi bi-trash me-1"></i>Ya, Hapus Motor
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.motor-card {
    transition: all 0.3s ease;
}

.motor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.btn-group .btn {
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: scale(1.05);
}

.info-item {
    transition: all 0.2s ease;
}

.info-item:hover {
    background-color: #f8f9fa !important;
}

.badge {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card-img-top {
    transition: all 0.3s ease;
}

.motor-card:hover .card-img-top {
    transform: scale(1.02);
}

.cursor-pointer {
    cursor: pointer;
}

.img-thumbnail:hover {
    opacity: 0.8;
    transform: scale(1.05);
    transition: all 0.2s ease;
}

#documentPreviewModal .modal-content {
    background-color: #212529;
}

#documentPreviewImage {
    transition: all 0.3s ease;
}

/* Custom Pagination Styles */
.custom-pagination {
    --bs-pagination-padding-x: 0.75rem;
    --bs-pagination-padding-y: 0.5rem;
    --bs-pagination-font-size: 0.875rem;
    --bs-pagination-color: #6c757d;
    --bs-pagination-bg: #fff;
    --bs-pagination-border-width: 1px;
    --bs-pagination-border-color: #dee2e6;
    --bs-pagination-border-radius: 0.375rem;
    --bs-pagination-hover-color: #0d6efd;
    --bs-pagination-hover-bg: #e9ecef;
    --bs-pagination-hover-border-color: #dee2e6;
    --bs-pagination-focus-color: #0d6efd;
    --bs-pagination-focus-bg: #e9ecef;
    --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    --bs-pagination-active-color: #fff;
    --bs-pagination-active-bg: #0d6efd;
    --bs-pagination-active-border-color: #0d6efd;
    --bs-pagination-disabled-color: #6c757d;
    --bs-pagination-disabled-bg: #fff;
    --bs-pagination-disabled-border-color: #dee2e6;
}

.custom-pagination .page-link {
    border-radius: 0.375rem !important;
    margin: 0 2px;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.custom-pagination .page-link:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    color: white;
    border-color: #0d6efd;
}

.custom-pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    border-color: #0d6efd;
    color: white;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    transform: translateY(-1px);
}

.custom-pagination .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-wrapper {
    flex-grow: 1;
    display: flex;
    justify-content: center;
}

.quick-jump {
    display: flex;
    align-items: center;
}

.quick-jump .input-group {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.quick-jump .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.quick-jump .btn {
    border-color: #0d6efd;
    color: #0d6efd;
}

.quick-jump .btn:hover {
    background-color: #0d6efd;
    color: white;
    transform: translateY(-1px);
}

/* Responsive pagination */
@media (max-width: 768px) {
    .pagination-info,
    .quick-jump {
        display: none;
    }
    
    .pagination-wrapper {
        justify-content: center;
        width: 100%;
    }
    
    .custom-pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .custom-pagination .page-link {
        padding: 0.5rem 0.65rem;
        font-size: 0.8rem;
        margin: 1px;
    }
}

@media (max-width: 576px) {
    .custom-pagination .page-item:not(.active):not(:first-child):not(:last-child) {
        display: none;
    }
    
    .custom-pagination .page-item:nth-child(2):not(.active),
    .custom-pagination .page-item:nth-last-child(2):not(.active) {
        display: inline-block;
    }
/* Loading state for pagination */
.pagination-loading .page-link {
    pointer-events: none;
    opacity: 0.6;
}

.pagination-loading .spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

/* Hover effects for cards */
.motor-card {
    transition: all 0.3s ease;
}

.motor-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

/* Smooth transitions */
.card, .btn, .page-link {
    transition: all 0.2s ease-in-out;
}

/* Pagination card styling */
.pagination-wrapper .card {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border: 1px solid rgba(0,0,0,0.08);
}

.pagination-wrapper .card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}
</style>
@endpush

@push('scripts')
<script>
console.log('Motor admin page loaded');
console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
console.log('CSRF token exists:', !!document.querySelector('meta[name="csrf-token"]'));

// Function to show document preview
function showDocumentPreview(imageUrl) {
    const modal = new bootstrap.Modal(document.getElementById('documentPreviewModal'));
    const previewImage = document.getElementById('documentPreviewImage');
    
    previewImage.src = imageUrl;
    modal.show();
}

// Function for pagination quick jump
function jumpToPage() {
    const pageInput = document.getElementById('pageJump');
    const pageNumber = parseInt(pageInput.value);
    const maxPages = parseInt(pageInput.getAttribute('max'));
    
    if (pageNumber && pageNumber >= 1 && pageNumber <= maxPages) {
        // Get current URL and update page parameter
        const url = new URL(window.location.href);
        url.searchParams.set('page', pageNumber);
        window.location.href = url.toString();
    } else {
        alert(`Masukkan nomor halaman antara 1 - ${maxPages}`);
        pageInput.focus();
    }
}

// Delete Motor Functions
let motorToDelete = null;

function confirmDeleteMotor(motorId, brand, model, plate, owner) {
    motorToDelete = motorId;
    
    // Update modal content
    document.getElementById('deleteMotorBrand').textContent = `${brand} ${model}`;
    document.getElementById('deleteMotorPlate').textContent = plate;
    document.getElementById('deleteMotorOwner').textContent = owner;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('deleteMotorModal'));
    modal.show();
}

function deleteMotor(motorId) {
    const confirmBtn = document.getElementById('confirmDeleteMotor');
    const originalText = confirmBtn.innerHTML;
    
    // Show loading state
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...';
    
    // Send delete request
    fetch(`{{ route('admin.motors') }}/${motorId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide modal
            bootstrap.Modal.getInstance(document.getElementById('deleteMotorModal')).hide();
            
            // Show success message
            showAlert('success', 'Motor berhasil dihapus!');
            
            // Reload page after short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message || 'Gagal menghapus motor!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat menghapus motor!');
    })
    .finally(() => {
        // Reset button state
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = originalText;
    });
}

function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert-dismissible');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create alert element
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
            <i class="${iconClass} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Add to body
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert-dismissible');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}

// Handle Enter key in page jump input
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to existing document thumbnails
    const docThumbnails = document.querySelectorAll('.img-thumbnail[onclick*="showDocumentPreview"]');
    docThumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // Handle delete motor confirmation
    const confirmDeleteBtn = document.getElementById('confirmDeleteMotor');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (motorToDelete) {
                deleteMotor(motorToDelete);
            }
        });
    }
    
    // Handle Enter key for page jump
    const pageJumpInput = document.getElementById('pageJump');
    if (pageJumpInput) {
        pageJumpInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                jumpToPage();
            }
        });
        
        // Auto-select text when focused
        pageJumpInput.addEventListener('focus', function() {
            this.select();
        });
    }
    
    // Add smooth scroll to pagination
    const paginationLinks = document.querySelectorAll('.custom-pagination .page-link');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Add loading state
            if (!this.closest('.page-item').classList.contains('disabled')) {
                this.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';
            }
        });
    });
});
</script>
<script src="{{ asset('js/simple-motor-verification.js') }}"></script>
@endpush