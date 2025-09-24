@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div class="auth-header">
    <h2 class="auth-title">Lupa Password?</h2>
    <p class="auth-subtitle">Tidak masalah! Masukkan email Anda dan kami akan mengirimkan link reset password.</p>
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

<form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
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
            placeholder="Masukkan email Anda"
        />
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn-primary" id="sendResetBtn">
        <i class="bi bi-send me-2"></i>
        Kirim Link Reset Password
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
// Form submission with loading state
document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
    const sendResetBtn = document.getElementById('sendResetBtn');
    sendResetBtn.disabled = true;
    sendResetBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
    sendResetBtn.classList.add('loading');
});

// Email validation
document.getElementById('email').addEventListener('input', function(e) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (e.target.value && !emailRegex.test(e.target.value)) {
        e.target.classList.add('is-invalid');
    } else {
        e.target.classList.remove('is-invalid');
    }
});
</script>
@endsection
