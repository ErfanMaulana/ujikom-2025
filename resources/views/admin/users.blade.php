@extends('layouts.fann')

@section('title', 'Kelola Pengguna')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Kelola Pengguna</h1>
    <p>Manajemen semua pengguna platform FannRental</p>
</div>

<!-- Filter & Search -->
<div class="row mb-4">
    <div class="col-md-8">
        <form method="GET" action="{{ route('admin.users') }}" class="row g-3">
            <div class="col-md-4">
                <select class="form-select" name="role">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pemilik" {{ request('role') == 'pemilik' ? 'selected' : '' }}>Pemilik Motor</option>
                    <option value="penyewa" {{ request('role') == 'penyewa' ? 'selected' : '' }}>Penyewa</option>
                </select>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email...">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus me-2"></i>Tambah User
        </button>
    </div>
</div>

<!-- Users Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0">
            <i class="bi bi-people me-2"></i>
            Daftar Pengguna ({{ $users->total() }} total)
        </h5>
    </div>
    <div class="card-body p-0">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Telepon</th>
                            <th>Bergabung</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        <i class="bi bi-person-circle text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                        @if($user->isBlacklisted())
                                            <br><small class="text-danger"><i class="bi bi-shield-x me-1"></i>Blacklisted</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @elseif($user->role === 'pemilik')
                                    <span class="badge bg-primary">Pemilik Motor</span>
                                @else
                                    <span class="badge bg-success">Penyewa</span>
                                @endif
                            </td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                @if($user->status === 'verified')
                                    <span class="badge bg-success">Terverifikasi</span>
                                @elseif($user->status === 'blacklisted')
                                    <span class="badge bg-danger">Blacklist</span>
                                @else
                                    <span class="badge bg-warning">Belum Verifikasi</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" id="dropdownMenuButton{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $user->id }}">
                                        <li>
                                            <button class="dropdown-item" onclick="showUserDetail({{ $user->id }})">
                                                <i class="bi bi-eye me-2"></i>Detail Pengguna
                                            </button>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        @if($user->status !== 'verified' && !$user->isBlacklisted())
                                            <li>
                                                <button class="dropdown-item text-success" onclick="verifyUser({{ $user->id }})">
                                                    <i class="bi bi-check-circle me-2"></i>Verifikasi User
                                                </button>
                                            </li>
                                        @endif
                                        @if(!$user->isBlacklisted() && $user->id !== auth()->id())
                                            <li>
                                                <button class="dropdown-item text-warning" onclick="blacklistUser({{ $user->id }})">
                                                    <i class="bi bi-shield-x me-2"></i>Blacklist User
                                                </button>
                                            </li>
                                        @elseif($user->isBlacklisted())
                                            <li>
                                                <button class="dropdown-item text-info" onclick="removeBlacklist({{ $user->id }})">
                                                    <i class="bi bi-shield-check me-2"></i>Hapus Blacklist
                                                </button>
                                            </li>
                                        @endif
                                        @if($user->id !== auth()->id())
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button class="dropdown-item text-danger" onclick="deleteUser({{ $user->id }})">
                                                    <i class="bi bi-trash me-2"></i>Hapus User
                                                </button>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                <h6 class="mt-3 text-muted">Tidak ada pengguna ditemukan</h6>
                <p class="text-muted">Coba ubah filter pencarian Anda</p>
            </div>
        @endif
    </div>
</div>

<!-- Pagination -->
@include('components.admin-pagination', ['paginator' => $users])

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telepon</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="pemilik">Pemilik Motor</option>
                            <option value="penyewa">Penyewa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- User Detail Modal -->
<div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailModalLabel">Detail Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="userDetailContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verify User Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyModalLabel">Verifikasi Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin memverifikasi pengguna ini?</p>
                <p class="text-muted small">Pengguna yang terverifikasi akan mendapat akses penuh ke platform.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="verifyForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Verifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Blacklist User Modal -->
<div class="modal fade" id="blacklistModal" tabindex="-1" aria-labelledby="blacklistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="blacklistModalLabel">Blacklist Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="blacklistForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Peringatan!</strong> Pengguna yang di-blacklist tidak akan bisa mengakses platform.
                    </div>
                    <div class="mb-3">
                        <label for="blacklist_reason" class="form-label">Alasan Blacklist *</label>
                        <textarea class="form-control" id="blacklist_reason" name="blacklist_reason" rows="3" 
                                  placeholder="Jelaskan alasan pengguna di-blacklist..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-shield-x me-1"></i>Blacklist User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove Blacklist Modal -->
<div class="modal fade" id="removeBlacklistModal" tabindex="-1" aria-labelledby="removeBlacklistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeBlacklistModalLabel">Hapus Blacklist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus blacklist untuk pengguna ini?</p>
                <p class="text-muted small">Pengguna akan kembali mendapat akses ke platform.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="removeBlacklistForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-shield-check me-1"></i>Hapus Blacklist
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteUser(userId) {
    const form = document.getElementById('deleteForm');
    form.action = `/admin/users/${userId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function showUserDetail(userId) {
    const modal = new bootstrap.Modal(document.getElementById('userDetailModal'));
    const content = document.getElementById('userDetailContent');
    
    // Show loading
    content.innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    // Fetch user detail
    fetch(`/admin/users/${userId}/detail`)
    .then(response => response.json())
    .then(data => {
        const user = data.user;
        
        content.innerHTML = `
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle text-muted" style="font-size: 5rem;"></i>
                    </div>
                    <h5>${user.name}</h5>
                    <p class="text-muted">${user.email}</p>
                    ${getUserStatusBadge(user.status)}
                </div>
                <div class="col-md-8">
                    <h6>Informasi Pengguna</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Role:</strong></td>
                            <td>${getRoleBadge(user.role)}</td>
                        </tr>
                        <tr>
                            <td><strong>Telepon:</strong></td>
                            <td>${user.phone || '-'}</td>
                        </tr>
                        <tr>
                            <td><strong>Bergabung:</strong></td>
                            <td>${new Date(user.created_at).toLocaleDateString('id-ID')}</td>
                        </tr>
                        ${user.verified_at ? `
                            <tr>
                                <td><strong>Diverifikasi:</strong></td>
                                <td>${new Date(user.verified_at).toLocaleDateString('id-ID')}</td>
                            </tr>
                        ` : ''}
                        ${user.status === 'blacklisted' && user.blacklist_reason ? `
                            <tr>
                                <td><strong>Alasan Blacklist:</strong></td>
                                <td><span class="text-danger">${user.blacklist_reason}</span></td>
                            </tr>
                        ` : ''}
                    </table>
                    
                    <h6 class="mt-4">Statistik</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4>${user.bookings_count || 0}</h4>
                                    <small>Total Booking</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4>${user.motors_count || 0}</h4>
                                    <small>Motor Dimiliki</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    })
    .catch(error => {
        console.error('Error fetching user detail:', error);
        content.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Gagal memuat detail pengguna.
            </div>
        `;
    });
}

function verifyUser(userId) {
    const form = document.getElementById('verifyForm');
    form.action = `/admin/users/${userId}/verify`;
    
    const modal = new bootstrap.Modal(document.getElementById('verifyModal'));
    modal.show();
}

function blacklistUser(userId) {
    const form = document.getElementById('blacklistForm');
    form.action = `/admin/users/${userId}/blacklist`;
    
    const modal = new bootstrap.Modal(document.getElementById('blacklistModal'));
    modal.show();
}

function removeBlacklist(userId) {
    const form = document.getElementById('removeBlacklistForm');
    form.action = `/admin/users/${userId}/remove-blacklist`;
    
    const modal = new bootstrap.Modal(document.getElementById('removeBlacklistModal'));
    modal.show();
}

function getUserStatusBadge(status) {
    switch(status) {
        case 'verified': return '<span class="badge bg-success">Terverifikasi</span>';
        case 'blacklisted': return '<span class="badge bg-danger">Blacklist</span>';
        default: return '<span class="badge bg-warning">Belum Verifikasi</span>';
    }
}

function getRoleBadge(role) {
    switch(role) {
        case 'admin': return '<span class="badge bg-danger">Admin</span>';
        case 'pemilik': return '<span class="badge bg-primary">Pemilik Motor</span>';
        default: return '<span class="badge bg-success">Penyewa</span>';
    }
}
</script>
@endsection

@push('styles')
<style>
    .pagination-sm .page-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.375rem;
        margin: 0 0.125rem;
        border: 1px solid #dee2e6;
        color: #6c757d;
        transition: all 0.2s ease-in-out;
    }
    
    .pagination-sm .page-link:hover {
        background-color: #f8f9fa;
        border-color: #adb5bd;
        color: #495057;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .pagination-sm .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
    }
    
    .pagination-sm .page-item.disabled .page-link {
        background-color: transparent;
        border-color: #dee2e6;
        color: #6c757d;
        opacity: 0.5;
    }
    
    .pagination-info {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
    }
    
    .table-responsive {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .card.border-0 {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
@endpush