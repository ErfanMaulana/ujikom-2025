@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="auth-header">
    <h2 class="auth-title">Reset Password</h2>
    <p class="auth-subtitle">Masukkan password baru Anda untuk melanjutkan</p>
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

<form method="POST" action="{{ route('password.store') }}" id="resetPasswordForm">
    @csrf

    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
            value="{{ old('email', $request->email) }}" 
            required 
            autofocus 
            autocomplete="username"
            placeholder="Masukkan email Anda"
            readonly
        />
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="form-group">
        <label class="form-label" for="password">
            <i class="bi bi-lock me-2"></i>Password Baru
        </label>
        <div class="password-toggle">
            <input 
                id="password" 
                class="form-control @error('password') is-invalid @enderror" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                placeholder="Masukkan password baru"
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
                placeholder="Konfirmasi password baru"
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
    <button type="submit" class="btn-primary" id="resetBtn">
        <i class="bi bi-key me-2"></i>
        Reset Password
    </button>
</form>

<div class="auth-links">
    <div>
        Ingat password Anda?
        <a href="{{ route('login') }}" class="auth-link">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
        </a>
    </div>
</div>

<script>
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
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    const resetBtn = document.getElementById('resetBtn');
    resetBtn.disabled = true;
    resetBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    resetBtn.classList.add('loading');
});

// Password confirmation validation
document.getElementById('password_confirmation').addEventListener('input', function(e) {
    const password = document.getElementById('password').value;
    if (e.target.value && e.target.value !== password) {
        e.target.classList.add('is-invalid');
    } else {
        e.target.classList.remove('is-invalid');
    }
});
</script>
@endsection
