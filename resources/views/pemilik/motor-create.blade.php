@extends('layouts.fann')

@section('title', 'Tambah Motor Baru')

@section('content')
<!-- Content Header -->
<div class="content-header mb-4">
    <h1>
        <i class="bi bi-motorcycle me-3"></i>Tambah Motor Baru
    </h1>
    <p class="text-muted">Daftarkan motor Anda untuk disewakan kepada penyewa</p>
</div>

<div class="row">
    <!-- Main Form -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    Informasi Motor
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('pemilik.motor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <!-- Brand Motor -->
                        <div class="col-md-6 mb-4">
                            <label for="brand" class="form-label fw-semibold">
                                Merk Motor <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('brand') is-invalid @enderror" id="brand" name="brand" required>
                                <option value="">Pilih Merk Motor</option>
                                <option value="Honda" {{ old('brand') == 'Honda' ? 'selected' : '' }}>Honda</option>
                                <option value="Yamaha" {{ old('brand') == 'Yamaha' ? 'selected' : '' }}>Yamaha</option>
                                <option value="Kawasaki" {{ old('brand') == 'Kawasaki' ? 'selected' : '' }}>Kawasaki</option>
                                <option value="Suzuki" {{ old('brand') == 'Suzuki' ? 'selected' : '' }}>Suzuki</option>
                            </select>
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Motor -->
                        <div class="col-md-6 mb-4">
                            <label for="model" class="form-label fw-semibold">
                                Nama Motor <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('model') is-invalid @enderror" 
                                   id="model" 
                                   name="model" 
                                   value="{{ old('model') }}"
                                   placeholder="Contoh: Beat, Vario, Ninja"
                                   required>
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Masukkan nama/model motor</div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- CC Motor -->
                        <div class="col-md-4 mb-4">
                            <label for="type_cc" class="form-label fw-semibold">
                                Kapasitas Mesin <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('type_cc') is-invalid @enderror" id="type_cc" name="type_cc" required>
                                <option value="">Pilih CC</option>
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

                        <!-- Tahun Motor -->
                        <div class="col-md-4 mb-4">
                            <label for="year" class="form-label fw-semibold">
                                Tahun <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('year') is-invalid @enderror" id="year" name="year" required>
                                <option value="">Pilih Tahun</option>
                                @for($i = date('Y'); $i >= 2010; $i--)
                                    <option value="{{ $i }}" {{ old('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Warna Motor -->
                        <div class="col-md-4 mb-4">
                            <label for="color" class="form-label fw-semibold">
                                Warna <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('color') is-invalid @enderror" 
                                   id="color" 
                                   name="color" 
                                   value="{{ old('color') }}"
                                   placeholder="Contoh: Merah, Hitam"
                                   required>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Plat Nomor -->
                    <div class="mb-4">
                        <label for="plate_number" class="form-label fw-semibold">
                            Plat Nomor <span class="text-danger">*</span>
                        </label>
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
                        <div class="form-text">Masukkan plat nomor motor yang valid</div>
                    </div>

                    <div class="row">
                        <!-- Foto Motor -->
                        <div class="col-md-6 mb-4">
                            <label for="photo" class="form-label fw-semibold">
                                Foto Motor
                            </label>
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

                        <!-- Foto Dokumen -->
                        <div class="col-md-6 mb-4">
                            <label for="document" class="form-label fw-semibold">
                                Foto Dokumen
                            </label>
                            <input type="file" 
                                   class="form-control @error('document') is-invalid @enderror" 
                                   id="document" 
                                   name="document"
                                   accept="image/*">
                            @error('document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload foto STNK/dokumen motor (maksimal 2MB)</div>
                            
                            <!-- Preview Document -->
                            <div id="documentPreview" class="mt-3" style="display: none;">
                                <img id="docPreview" src="" alt="Document Preview" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">
                            Deskripsi Motor
                        </label>
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

                    <!-- Info Harga Sewa -->
                    <div class="alert alert-info border-0">
                        <div class="d-flex">
                            <i class="bi bi-info-circle text-info me-3 mt-1"></i>
                            <div>
                                <strong>Informasi Penting:</strong> 
                                <p class="mb-0">Harga sewa motor akan ditentukan oleh admin setelah proses verifikasi selesai. Admin akan menetapkan harga yang sesuai dengan kondisi dan spesifikasi motor Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between pt-3">
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

    <!-- Tips Sidebar -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    Tips Sukses
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-camera text-primary"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <small class="fw-medium">Foto Berkualitas</small>
                            <p class="text-muted small mb-0">Upload foto yang jelas dan menarik dari berbagai sudut</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-pencil text-success"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <small class="fw-medium">Deskripsi Detail</small>
                            <p class="text-muted small mb-0">Tulis deskripsi yang detail dan jujur tentang kondisi motor</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-currency-dollar text-info"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <small class="fw-medium">Harga Otomatis</small>
                            <p class="text-muted small mb-0">Harga sewa akan ditentukan oleh admin setelah verifikasi</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-shield-check text-warning"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <small class="fw-medium">Data Valid</small>
                            <p class="text-muted small mb-0">Pastikan semua data yang dimasukkan benar dan valid</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle text-info me-2"></i>
                    Proses Selanjutnya
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <small class="fw-medium">Pendaftaran Motor</small>
                            <p class="text-muted small mb-0">Isi formulir dengan lengkap dan upload foto</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <small class="fw-medium">Verifikasi Admin</small>
                            <p class="text-muted small mb-0">Admin akan mereview dan verifikasi motor Anda</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <small class="fw-medium">Motor Aktif</small>
                            <p class="text-muted small mb-0">Motor siap untuk disewakan setelah disetujui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        left: -1.25rem;
        top: 0.25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #dee2e6;
    }
    
    .timeline-content {
        margin-left: 0.5rem;
    }
    
    .card {
        transition: all 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
</style>
@endpush

@push('scripts')
<script>
    // Preview foto motor
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

    // Preview foto dokumen
    document.getElementById('document').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('docPreview').src = e.target.result;
                document.getElementById('documentPreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('documentPreview').style.display = 'none';
        }
    });

    // Auto-format plat nomor
    document.getElementById('plate_number').addEventListener('input', function(e) {
        let value = e.target.value.toUpperCase();
        // Basic formatting for Indonesian plate number
        value = value.replace(/[^A-Z0-9\s]/g, '');
        e.target.value = value;
    });
</script>
@endpush