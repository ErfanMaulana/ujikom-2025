@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-header">
    <h2 class="auth-title">Selamat Datang!</h2>
    <p class="auth-subtitle">Masuk ke akun FannRental Anda</p>
</div>

<!-- Session Status -->
@if (session('status'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('status') }}
    </div>
@endif

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

<form method="POST" action="{{ route('login') }}" id="loginForm">
    @csrf

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
            autofocus 
            autocomplete="username"
            placeholder="Masukkan email Anda"
        />
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
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
                autocomplete="current-password"
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

    <!-- Remember Me -->
    <div class="form-check">
        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
        <label class="form-check-label" for="remember_me">
            Ingat saya
        </label>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn-primary" id="loginBtn">
        <i class="bi bi-box-arrow-in-right me-2"></i>
        Masuk
    </button>
</form>

<div class="auth-links">
    <div class="mb-3">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="auth-link">
                <i class="bi bi-key me-1"></i>Lupa password?
            </a>
        @endif
    </div>
    
    <div>
        Belum punya akun?
        <a href="{{ route('register') }}" class="auth-link">
            <i class="bi bi-person-plus me-1"></i>Daftar sekarang
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
document.getElementById('loginForm').addEventListener('submit', function() {
    const loginBtn = document.getElementById('loginBtn');
    loginBtn.disabled = true;
    loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    loginBtn.classList.add('loading');
});
</script>
@endsection
