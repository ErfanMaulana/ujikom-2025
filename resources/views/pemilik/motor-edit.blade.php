@extends('layouts.app')

@section('title', 'Edit Motor - FannRental')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 col-md-3 bg-light sidebar">
            <div class="p-3">
                <h5 class="text-primary">Menu Pemilik</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pemilik.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('pemilik.motors') }}">
                            <i class="fas fa-motorcycle me-2"></i>Motor Saya
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pemilik.bookings') }}">
                            <i class="fas fa-calendar-check me-2"></i>Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pemilik.revenue.report') }}">
                            <i class="fas fa-chart-line me-2"></i>Laporan Pendapatan
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 col-md-9">
            <div class="p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">Edit Motor</h2>
                        <p class="text-muted">Perbarui informasi motor Anda</p>
                    </div>
                    <a href="{{ route('pemilik.motors') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>

                <!-- Form Edit Motor -->
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Form Edit Motor</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('pemilik.motor.update', $motor->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')

                                    <!-- Brand Motor -->
                                    <div class="mb-4">
                                        <label for="brand" class="form-label">Merek Motor <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                               id="brand" name="brand" value="{{ old('brand', $motor->brand) }}" required>
                                        @error('brand')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- CC Motor -->
                                    <div class="mb-4">
                                        <label for="type_cc" class="form-label">Kapasitas Mesin <span class="text-danger">*</span></label>
                                        <select class="form-select @error('type_cc') is-invalid @enderror" id="type_cc" name="type_cc" required>
                                            <option value="">Pilih Kapasitas Mesin</option>
                                            <option value="100cc" {{ old('type_cc', $motor->type_cc) == '100cc' ? 'selected' : '' }}>100cc</option>
                                            <option value="125cc" {{ old('type_cc', $motor->type_cc) == '125cc' ? 'selected' : '' }}>125cc</option>
                                            <option value="150cc" {{ old('type_cc', $motor->type_cc) == '150cc' ? 'selected' : '' }}>150cc</option>
                                            <option value="250cc" {{ old('type_cc', $motor->type_cc) == '250cc' ? 'selected' : '' }}>250cc</option>
                                            <option value="500cc" {{ old('type_cc', $motor->type_cc) == '500cc' ? 'selected' : '' }}>500cc</option>
                                        </select>
                                        @error('type_cc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Nomor Plat -->
                                    <div class="mb-4">
                                        <label for="plate_number" class="form-label">Nomor Plat <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('plate_number') is-invalid @enderror" 
                                               id="plate_number" name="plate_number" value="{{ old('plate_number', $motor->plate_number) }}" required>
                                        @error('plate_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Foto Motor Saat Ini -->
                                    @if($motor->photo)
                                    <div class="mb-4">
                                        <label class="form-label">Foto Motor Saat Ini</label>
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $motor->photo) }}" alt="Motor Photo" 
                                                 class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Upload Foto Baru -->
                                    <div class="mb-4">
                                        <label for="photo" class="form-label">{{ $motor->photo ? 'Ganti Foto Motor' : 'Foto Motor' }}</label>
                                        <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                               id="photo" name="photo" accept="image/*">
                                        <div class="form-text">Format: JPG, PNG, JPEG. Maksimal 2MB.</div>
                                        @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="mb-4">
                                        <label for="description" class="form-label">Deskripsi Motor</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="4" 
                                                  placeholder="Ceritakan tentang motor Anda, kondisi, keunggulan, dll.">{{ old('description', $motor->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Tarif Sewa -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Tarif Sewa</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Tarif Harian -->
                                                <div class="col-md-4 mb-3">
                                                    <label for="daily_rate" class="form-label">Tarif Harian <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control @error('daily_rate') is-invalid @enderror" 
                                                               id="daily_rate" name="daily_rate" 
                                                               value="{{ old('daily_rate', number_format($motor->rentalRate?->daily_rate ?? 0, 0, ',', '.')) }}" 
                                                               required>
                                                    </div>
                                                    @error('daily_rate')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">Minimal Rp 10.000</div>
                                                </div>

                                                <!-- Tarif Mingguan -->
                                                <div class="col-md-4 mb-3">
                                                    <label for="weekly_rate" class="form-label">Tarif Mingguan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control @error('weekly_rate') is-invalid @enderror" 
                                                               id="weekly_rate" name="weekly_rate" 
                                                               value="{{ old('weekly_rate', number_format($motor->rentalRate?->weekly_rate ?? 0, 0, ',', '.')) }}">
                                                    </div>
                                                    @error('weekly_rate')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">Kosongkan untuk otomatis (6x harian)</div>
                                                </div>

                                                <!-- Tarif Bulanan -->
                                                <div class="col-md-4 mb-3">
                                                    <label for="monthly_rate" class="form-label">Tarif Bulanan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control @error('monthly_rate') is-invalid @enderror" 
                                                               id="monthly_rate" name="monthly_rate" 
                                                               value="{{ old('monthly_rate', number_format($motor->rentalRate?->monthly_rate ?? 0, 0, ',', '.')) }}">
                                                    </div>
                                                    @error('monthly_rate')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">Kosongkan untuk otomatis (20x harian)</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Catatan -->
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Catatan:</strong> Setelah diperbarui, motor akan menunggu verifikasi ulang dari admin sebelum dapat disewakan kembali.
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                                        </button>
                                        <a href="{{ route('pemilik.motors') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-2"></i>Batal
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auto Format Number Input -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format number inputs
    const numberInputs = ['daily_rate', 'weekly_rate', 'monthly_rate'];
    
    numberInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                if (value) {
                    e.target.value = new Intl.NumberFormat('id-ID').format(value);
                }
            });
        }
    });
});
</script>
@endsection