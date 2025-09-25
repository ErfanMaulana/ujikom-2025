<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="row g-3">
        <!-- Username (Read-only) -->
        <div class="col-12">
            <div class="row align-items-center">
                <div class="col-sm-3">
                    <label class="form-label mb-0">Username</label>
                </div>
                <div class="col-sm-9">
                    <div class="text-muted">{{ strtolower(str_replace(' ', '', $user->name)) }}</div>
                </div>
            </div>
        </div>

        <!-- Name -->
        <div class="col-12">
            <div class="row align-items-center">
                <div class="col-sm-3">
                    <label for="name" class="form-label mb-0">Nama</label>
                </div>
                <div class="col-sm-9">
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required 
                           autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Email -->
        <div class="col-12">
            <div class="row align-items-center">
                <div class="col-sm-3">
                    <label for="email" class="form-label mb-0">Email</label>
                </div>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required 
                               autocomplete="username">
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <button type="button" class="btn btn-outline-secondary" 
                                    onclick="document.getElementById('send-verification').submit()">
                                Ubah
                            </button>
                        @else
                            <span class="input-group-text bg-transparent border-0">
                                <i class="bi bi-check-circle text-success"></i>
                            </span>
                        @endif
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="form-text text-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Email belum diverifikasi
                        </div>
                        @if (session('status') === 'verification-link-sent')
                            <div class="form-text text-success">
                                <i class="bi bi-check-circle me-1"></i>
                                Link verifikasi telah dikirim
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Phone -->
        <div class="col-12">
            <div class="row align-items-center">
                <div class="col-sm-3">
                    <label for="phone" class="form-label mb-0">Nomor Telepon</label>
                </div>
                <div class="col-sm-9">
                    <input type="tel" 
                           class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $user->phone) }}" 
                           placeholder="Masukkan nomor telepon"
                           autocomplete="tel">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Role Display -->
        <div class="col-12">
            <div class="row align-items-center">
                <div class="col-sm-3">
                    <label class="form-label mb-0">Role</label>
                </div>
                <div class="col-sm-9">
                    <span class="badge 
                        @if($user->role === 'admin') bg-danger
                        @elseif($user->role === 'pemilik') bg-success  
                        @else bg-info @endif">
                        {{ ucfirst($user->role) }}
                    </span>
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
                    
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            Profil berhasil diperbarui!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
