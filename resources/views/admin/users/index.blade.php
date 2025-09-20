@extends('layouts.fann')

@section('title', 'Kelola Pengguna')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Kelola Pengguna</h1>
    <p>Manajemen pengguna sistem rental motor</p>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i>Daftar Pengguna
                    </h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-plus"></i> Tambah Pengguna
                    </button>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Filter dan Search -->
                <form method="GET" action="{{ route('admin.users') }}">
                    <div class="col-md-3">
                        <label for="role_filter" class="form-label">Filter Role</label>
                        <select class="form-select" id="role_filter" name="role">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="pemilik" {{ request('role') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                            <option value="penyewa" {{ request('role') == 'penyewa' ? 'selected' : '' }}>Penyewa</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="search" class="form-label">Cari Pengguna</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Nama, email, atau telepon..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
                </form>

                <!-- Tabel Pengguna -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Terdaftar</th>
                                <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($user->role == 'admin') bg-danger
                                            @elseif($user->role == 'pemilik') bg-warning
                                            @else bg-info
                                            @endif">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Belum Verifikasi</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewUser({{ $user->id }})" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    onclick="editUser({{ $user->id }})" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            @if($user->id != auth()->id())
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteUser({{ $user->id }})" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada pengguna ditemukan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
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
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="pemilik">Pemilik</option>
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
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Pengguna -->
<div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewUser(userId) {
    fetch(`/admin/users/${userId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('userDetailContent').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nama:</strong> ${data.name}<br>
                        <strong>Email:</strong> ${data.email}<br>
                        <strong>Telepon:</strong> ${data.phone}<br>
                        <strong>Role:</strong> ${data.role}<br>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong> ${data.email_verified_at ? 'Aktif' : 'Belum Verifikasi'}<br>
                        <strong>Terdaftar:</strong> ${new Date(data.created_at).toLocaleDateString('id-ID')}<br>
                        <strong>Update Terakhir:</strong> ${new Date(data.updated_at).toLocaleDateString('id-ID')}<br>
                    </div>
                </div>
            `;
            new bootstrap.Modal(document.getElementById('viewUserModal')).show();
        });
}

function editUser(userId) {
    // Redirect to edit page or open edit modal
    window.location.href = `/admin/users/${userId}/edit`;
}

function deleteUser(userId) {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus pengguna');
            }
        });
    }
}
</script>
@endpush