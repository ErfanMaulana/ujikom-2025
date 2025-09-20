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
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-warning">Belum Verifikasi</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.user.detail', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteUser({{ $user->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
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
@if($users->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links() }}
    </div>
@endif

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.user.store') }}">
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
                            <option value="admin">Admin</option>
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

<script>
function deleteUser(userId) {
    const form = document.getElementById('deleteForm');
    form.action = `/admin/users/${userId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection