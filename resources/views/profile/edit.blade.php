@extends('layouts.fann')

@section('title', 'Profil Saya')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Profil Saya</h1>
    <p>Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
</div>

<div class="row">
    <!-- Sidebar Profile Menu -->
    <div class="col-lg-3 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <!-- Profile Header -->
                <div class="d-flex align-items-center p-3 border-bottom">
                    <div class="position-relative me-3">
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-person-fill text-white" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ auth()->user()->name }}</h6>
                        <small class="text-muted">
                            <i class="bi bi-pencil me-1"></i>Ubah Profil
                        </small>
                    </div>
                </div>

                <!-- Menu Items -->
                <div class="list-group list-group-flush">
                    <a href="#profile-info" class="list-group-item list-group-item-action active" 
                       onclick="showSection('profile-info', this)">
                        <i class="bi bi-person me-2"></i>Akun Saya
                    </a>
                    <a href="#change-password" class="list-group-item list-group-item-action" 
                       onclick="showSection('change-password', this)">
                        <i class="bi bi-key me-2"></i>Ubah Password
                    </a>
                    <a href="#delete-account" class="list-group-item list-group-item-action text-danger" 
                       onclick="showSection('delete-account', this)">
                        <i class="bi bi-exclamation-triangle me-2"></i>Zona Berbahaya
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="col-lg-9">
        <!-- Profile Information Section -->
        <div id="profile-info" class="profile-section">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Profil Saya</h5>
                    <small class="text-muted">Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Profile Form -->
                        <div class="col-lg-8">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                        
                        <!-- Profile Picture -->
                        <div class="col-lg-4">
                            <div class="text-center">
                                <div class="position-relative d-inline-block mb-3">
                                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center" 
                                         style="width: 120px; height: 120px;">
                                        <i class="bi bi-person-fill text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-camera me-1"></i>Pilih Gambar
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        Ukuran gambar: maks. 1 MB<br>
                                        Format gambar: .JPEG, .PNG
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password Section -->
        <div id="change-password" class="profile-section" style="display: none;">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Ubah Password</h5>
                    <small class="text-muted">Pastikan akun Anda menggunakan password yang panjang dan acak untuk keamanan</small>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Delete Account Section -->
        <div id="delete-account" class="profile-section" style="display: none;">
            <div class="card border-0 shadow-sm border-danger">
                <div class="card-header bg-danger text-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>Zona Berbahaya
                    </h5>
                    <small class="text-white-50">Tindakan berikut bersifat permanen dan tidak dapat dibatalkan</small>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showSection(sectionId, element) {
    // Hide all sections
    document.querySelectorAll('.profile-section').forEach(section => {
        section.style.display = 'none';
    });
    
    // Remove active class from all menu items
    document.querySelectorAll('.list-group-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Show selected section
    document.getElementById(sectionId).style.display = 'block';
    
    // Add active class to clicked menu item
    element.classList.add('active');
}
</script>
@endpush
@endsection
