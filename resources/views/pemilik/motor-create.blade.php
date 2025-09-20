@extends('layouts.fann')

@section('title', 'Tambah Motor Baru')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Tambah Motor Baru</h1>
    <p>Daftarkan motor Anda untuk disewakan kepada penyewa</p>
</div>

<!-- Form Card -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    Informasi Motor
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pemilik.motor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Brand Motor -->
                    <div class="mb-4">
                        <label for="brand" class="form-label">Merk Motor <span class="text-danger">*</span></label>
                        <select class="form-select @error('brand') is-invalid @enderror" id="brand" name="brand" required>
                            <option value="">Pilih Merk Motor</option>
                            <option value="Honda" {{ old('brand') == 'Honda' ? 'selected' : '' }}>Honda</option>
                            <option value="Yamaha" {{ old('brand') == 'Yamaha' ? 'selected' : '' }}>Yamaha</option>
                            <option value="Kawasaki" {{ old('brand') == 'Kawasaki' ? 'selected' : '' }}>Kawasaki</option>
                        </select>
                        @error('brand')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- CC Motor -->
                    <div class="mb-4">
                        <label for="type_cc" class="form-label">Kapasitas Mesin <span class="text-danger">*</span></label>
                        <select class="form-select @error('type_cc') is-invalid @enderror" id="type_cc" name="type_cc" required>
                            <option value="">Pilih Kapasitas Mesin</option>
                            <option value="100cc" {{ old('type_cc') == '100cc' ? 'selected' : '' }}>100cc</option>
                            <option value="125cc" {{ old('type_cc') == '125cc' ? 'selected' : '' }}>125cc</option>
                            <option value="150cc" {{ old('type_cc') == '150cc' ? 'selected' : '' }}>150cc</option>
                            <option value="250cc" {{ old('type_cc') == '250cc' ? 'selected' : '' }}>250cc</option>
                            <option value="500cc" {{ old('type_cc') == '500cc' ? 'selected' : '' }}>500cc</option>
                        </select>
                        @error('type_cc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nomor Plat -->
                    <div class="mb-4">
                        <label for="plate_number" class="form-label">Nomor Plat <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('plate_number') is-invalid @enderror" 
                               id="plate_number" 
                               name="plate_number" 
                               value="{{ old('plate_number') }}"
                               placeholder="Contoh: B 1234 ABC"
                               required>
                        @error('plate_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Masukkan nomor plat motor yang valid</div>
                    </div>

                    <!-- Foto Motor -->
                    <div class="mb-4">
                        <label for="photo" class="form-label">Foto Motor</label>
                        <input type="file" 
                               class="form-control @error('photo') is-invalid @enderror" 
                               id="photo" 
                               name="photo"
                               accept="image/*">
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Upload foto motor yang menarik (maksimal 2MB)</div>
                        
                        <!-- Preview Image -->
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Deskripsi Motor</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Deskripsikan kondisi motor, fitur, dan hal menarik lainnya...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Berikan deskripsi yang menarik untuk menarik penyewa</div>
                    </div>

                    <!-- Tarif Sewa Section -->
                    <div class="card bg-light mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-currency-dollar me-2"></i>
                                Tarif Sewa
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="daily_rate" class="form-label">Tarif Harian <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" 
                                               class="form-control money-input @error('daily_rate') is-invalid @enderror" 
                                               id="daily_rate" 
                                               name="daily_rate" 
                                               value="{{ old('daily_rate') }}"
                                               placeholder="150000"
                                               required>
                                        @error('daily_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Ketik angka langsung, contoh: 150000</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="weekly_rate" class="form-label">Tarif Mingguan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" 
                                               class="form-control money-input @error('weekly_rate') is-invalid @enderror" 
                                               id="weekly_rate" 
                                               name="weekly_rate" 
                                               value="{{ old('weekly_rate') }}"
                                               placeholder="900000">
                                        @error('weekly_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Auto-calculate: 6x tarif harian</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="monthly_rate" class="form-label">Tarif Bulanan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" 
                                               class="form-control money-input @error('monthly_rate') is-invalid @enderror" 
                                               id="monthly_rate" 
                                               name="monthly_rate" 
                                               value="{{ old('monthly_rate') }}"
                                               placeholder="3000000">
                                        @error('monthly_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Auto-calculate: 20x tarif harian</div>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Tips Pricing:</strong> Tarif mingguan biasanya 6x tarif harian, tarif bulanan biasanya 20x tarif harian
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pemilik.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Daftarkan Motor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tips Sidebar -->
<div class="row mt-4">
    <div class="col-lg-8">
        <!-- Empty space -->
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>
                    Tips Sukses
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex">
                        <i class="bi bi-camera text-primary me-2 mt-1"></i>
                        <small>Upload foto yang jelas dan menarik dari berbagai sudut</small>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex">
                        <i class="bi bi-pencil text-primary me-2 mt-1"></i>
                        <small>Tulis deskripsi yang detail dan jujur tentang kondisi motor</small>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex">
                        <i class="bi bi-currency-dollar text-primary me-2 mt-1"></i>
                        <small>Set harga yang kompetitif sesuai kondisi dan merk motor</small>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex">
                        <i class="bi bi-shield-check text-primary me-2 mt-1"></i>
                        <small>Pastikan semua data yang dimasukkan benar dan valid</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
});

// Format money input - only allow numbers
document.querySelectorAll('.money-input').forEach(function(input) {
    input.addEventListener('input', function(e) {
        // Remove non-numeric characters
        let value = e.target.value.replace(/[^0-9]/g, '');
        e.target.value = value;
    });
    
    input.addEventListener('keypress', function(e) {
        // Only allow numbers
        if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
            e.preventDefault();
        }
    });
});

// Auto calculate weekly and monthly rates
document.getElementById('daily_rate').addEventListener('input', function(e) {
    const dailyRate = parseInt(e.target.value.replace(/[^0-9]/g, '')) || 0;
    if (dailyRate > 0) {
        document.getElementById('weekly_rate').value = dailyRate * 6;
        document.getElementById('monthly_rate').value = dailyRate * 20;
    } else {
        document.getElementById('weekly_rate').value = '';
        document.getElementById('monthly_rate').value = '';
    }
});
</script>
@endsection