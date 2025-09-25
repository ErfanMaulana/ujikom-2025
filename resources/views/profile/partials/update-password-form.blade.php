<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="row g-3">
        <!-- Current Password -->
        <div class="col-12">
            <div class="row align-items-center">
                <div class="col-sm-3">
                    <label for="current_password" class="form-label mb-0">Password Saat Ini</label>
                </div>
                <div class="col-sm-9">
                    <input type="password" 
                           class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                           id="current_password" 
                           name="current_password" 
                           autocomplete="current-password"
                           placeholder="Masukkan password saat ini"
                           required>
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- New Password -->
        <div class="col-12">
            <div class="row align-items-center">
                <div class="col-sm-3">
                    <label for="password" class="form-label mb-0">Password Baru</label>
                </div>
                <div class="col-sm-9">
                    <input type="password" 
                           class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           autocomplete="new-password"
                           placeholder="Masukkan password baru"
                           required>
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        Password harus mengandung minimal 8 karakter
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="col-12">
            <div class="row align-items-center">
                <div class="col-sm-3">
                    <label for="password_confirmation" class="form-label mb-0">Konfirmasi Password Baru</label>
                </div>
                <div class="col-sm-9">
                    <input type="password" 
                           class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           autocomplete="new-password"
                           placeholder="Ulangi password baru"
                           required>
                    @error('password_confirmation', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="col-12">
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <button type="submit" class="btn btn-primary px-4">
                        Simpan
                    </button>
                    
                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            Password berhasil diperbarui!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
