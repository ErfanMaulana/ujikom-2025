@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-header">
    <h2 class="auth-title">Bergabung Dengan Kami!</h2>
    <p class="auth-subtitle">Buat akun FannRental dan mulai perjalanan Anda</p>
</div>

<!-- Validation Errors -->
@if ($errors->any())
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('register') }}" id="registerForm">
    @csrf

    <!-- Name -->
    <div class="form-group">
        <label class="form-label" for="name">
            <i class="bi bi-person me-2"></i>Nama Lengkap
        </label>
        <input 
            id="name" 
            class="form-control @error('name') is-invalid @enderror" 
            type="text" 
            name="name" 
            value="{{ old('name') }}" 
            required 
            autofocus 
            autocomplete="name"
            placeholder="Masukkan nama lengkap Anda"
        />
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email Address -->
    <div class="form-group">
        <label class="form-label" for="email">
            <i class="bi bi-envelope me-2"></i>Email
        </label>
        <input 
            id="email" 
            class="form-control @error('email') is-invalid @enderror" 
            type="email" 
            name="email" 
            value="{{ old('email') }}" 
            required 
            autocomplete="username"
            placeholder="Masukkan email Anda"
        />
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Phone -->
    <div class="form-group">
        <label class="form-label" for="phone">
            <i class="bi bi-telephone me-2"></i>Nomor Telepon
        </label>
        <input 
            id="phone" 
            class="form-control @error('phone') is-invalid @enderror" 
            type="text" 
            name="phone" 
            value="{{ old('phone') }}" 
            placeholder="08xxxxxxxxxx"
        />
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Role Selection -->
    <div class="form-group">
        <label class="form-label">
            <i class="bi bi-person-badge me-2"></i>Pilih Role Anda
        </label>
        <div class="role-selection">
            <div class="role-card {{ old('role') === 'penyewa' ? 'selected' : '' }}" onclick="selectRole('penyewa')">
                <i class="bi bi-person-check"></i>
                <h6>Penyewa</h6>
                <p>Saya ingin menyewa motor</p>
            </div>
            <div class="role-card {{ old('role') === 'pemilik' ? 'selected' : '' }}" onclick="selectRole('pemilik')">
                <i class="bi bi-shop"></i>
                <h6>Pemilik Motor</h6>
                <p>Saya ingin menyewakan motor</p>
            </div>
        </div>
        <input type="hidden" id="role" name="role" value="{{ old('role') }}" required>
        @error('role')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="form-group">
        <label class="form-label" for="password">
            <i class="bi bi-lock me-2"></i>Password
        </label>
        <div class="password-toggle">
            <input 
                id="password" 
                class="form-control @error('password') is-invalid @enderror" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                placeholder="Masukkan password Anda"
            />
            <button type="button" class="password-toggle-btn" onclick="togglePassword('password')">
                <i id="password-eye" class="bi bi-eye"></i>
                <i id="password-eye-slash" class="bi bi-eye-slash" style="display: none;"></i>
            </button>
        </div>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="form-group">
        <label class="form-label" for="password_confirmation">
            <i class="bi bi-lock-fill me-2"></i>Konfirmasi Password
        </label>
        <div class="password-toggle">
            <input 
                id="password_confirmation" 
                class="form-control @error('password_confirmation') is-invalid @enderror" 
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="Konfirmasi password Anda"
            />
            <button type="button" class="password-toggle-btn" onclick="togglePassword('password_confirmation')">
                <i id="password_confirmation-eye" class="bi bi-eye"></i>
                <i id="password_confirmation-eye-slash" class="bi bi-eye-slash" style="display: none;"></i>
            </button>
        </div>
        @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn-primary" id="registerBtn">
        <i class="bi bi-person-plus me-2"></i>
        Daftar Sekarang
    </button>
</form>

<div class="auth-links">
    <div>
        Sudah punya akun?
        <a href="{{ route('login') }}" class="auth-link">
            <i class="bi bi-box-arrow-in-right me-1"></i>Masuk sekarang
        </a>
    </div>
</div>

<script>
function selectRole(role) {
    // Remove selected class from all cards
    document.querySelectorAll('.role-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selected class to clicked card
    event.target.closest('.role-card').classList.add('selected');
    
    // Set hidden input value
    document.getElementById('role').value = role;
}

function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(fieldId + '-eye');
    const eyeSlashIcon = document.getElementById(fieldId + '-eye-slash');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.style.display = 'none';
        eyeSlashIcon.style.display = 'inline';
    } else {
        passwordField.type = 'password';
        eyeIcon.style.display = 'inline';
        eyeSlashIcon.style.display = 'none';
    }
}

// Form submission with loading state
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const roleInput = document.getElementById('role');
    
    // Validate role selection
    if (!roleInput.value) {
        e.preventDefault();
        alert('Silakan pilih role Anda (Penyewa atau Pemilik Motor)');
        return;
    }
    
    const registerBtn = document.getElementById('registerBtn');
    registerBtn.disabled = true;
    registerBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    registerBtn.classList.add('loading');
});

// Auto-select role if old value exists
document.addEventListener('DOMContentLoaded', function() {
    const oldRole = '{{ old("role") }}';
    if (oldRole) {
        // Find the role card and select it
        const roleCards = document.querySelectorAll('.role-card');
        roleCards.forEach((card, index) => {
            if ((oldRole === 'penyewa' && index === 0) || (oldRole === 'pemilik' && index === 1)) {
                card.classList.add('selected');
                document.getElementById('role').value = oldRole;
            }
        });
    }
});

// Add visual feedback for form validation
document.getElementById('registerForm').addEventListener('input', function(e) {
    const field = e.target;
    if (field.type === 'email') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (field.value && !emailRegex.test(field.value)) {
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    }
    
    if (field.name === 'phone') {
        const phoneRegex = /^08[0-9]{8,13}$/;
        if (field.value && !phoneRegex.test(field.value)) {
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    }
    
    if (field.name === 'password_confirmation') {
        const password = document.getElementById('password').value;
        if (field.value && field.value !== password) {
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    }
});
</script>
@endsection
