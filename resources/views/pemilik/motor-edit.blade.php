@extends('layouts.fann')

@section('title', 'Edit Motor - ' . $motor->brand . ' ' . $motor->model)

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Edit Motor</h1>
            <p>Perbarui informasi motor {{ $motor->brand }} {{ $motor->model }}</p>
        </div>
        <a href="{{ route('pemilik.motors') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<!-- Form Edit Motor -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-pencil me-2"></i>Edit Motor
                </h5>
            </div>
            <div class="card-body">
                <form id="editMotorForm" action="{{ route('pemilik.motor.update', $motor->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Merek -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Merek Motor</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                       name="brand" value="{{ old('brand', $motor->brand) }}" required>
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Model -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Model Motor</label>
                                <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                       name="model" value="{{ old('model', $motor->model) }}" required>
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Tahun -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Tahun</label>
                                <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                       name="year" value="{{ old('year', $motor->year) }}" min="2000" max="2025" required>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- CC -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">CC</label>
                                <select class="form-select @error('type_cc') is-invalid @enderror" name="type_cc" required>
                                    <option value="">Pilih CC</option>
                                    <option value="100cc" {{ old('type_cc', $motor->type_cc) == '100cc' ? 'selected' : '' }}>100cc</option>
                                    <option value="110cc" {{ old('type_cc', $motor->type_cc) == '110cc' ? 'selected' : '' }}>110cc</option>
                                    <option value="125cc" {{ old('type_cc', $motor->type_cc) == '125cc' ? 'selected' : '' }}>125cc</option>
                                    <option value="150cc" {{ old('type_cc', $motor->type_cc) == '150cc' ? 'selected' : '' }}>150cc</option>
                                    <option value="160cc" {{ old('type_cc', $motor->type_cc) == '160cc' ? 'selected' : '' }}>160cc</option>
                                    <option value="250cc" {{ old('type_cc', $motor->type_cc) == '250cc' ? 'selected' : '' }}>250cc</option>
                                    <option value="400cc" {{ old('type_cc', $motor->type_cc) == '400cc' ? 'selected' : '' }}>400cc</option>
                                    <option value="500cc" {{ old('type_cc', $motor->type_cc) == '500cc' ? 'selected' : '' }}>500cc</option>
                                    <option value="600cc" {{ old('type_cc', $motor->type_cc) == '600cc' ? 'selected' : '' }}>600cc</option>
                                </select>
                                @error('type_cc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Nomor Plat -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Nomor Plat</label>
                                <input type="text" class="form-control @error('plate_number') is-invalid @enderror" 
                                       name="plate_number" value="{{ old('plate_number', $motor->plate_number) }}" 
                                       placeholder="Contoh: B 1234 ABC" required>
                                @error('plate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Warna -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Warna</label>
                                <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                       name="color" value="{{ old('color', $motor->color) }}" 
                                       placeholder="Contoh: Merah, Hitam, Putih" required>
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>



                    <!-- Deskripsi -->
                    <div class="form-group mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" rows="4" 
                                  placeholder="Deskripsikan kondisi dan fitur motor...">{{ old('description', $motor->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Upload Foto Motor -->
                    <div class="form-group mb-3">
                        <label class="form-label">Foto Motor</label>
                        
                        @if($motor->photo)
                            <div class="mb-3">
                                <img src="{{ Storage::url($motor->photo) }}" alt="Motor Photo" 
                                     class="img-thumbnail" style="max-width: 200px;">
                                <p class="text-muted mt-1">Foto saat ini</p>
                            </div>
                        @endif
                        
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                               name="photo" accept="image/*">
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Maksimal 2MB.</small>
                    </div>

                    <!-- Upload Foto Dokumen -->
                    <div class="form-group mb-3">
                        <label class="form-label">Foto Dokumen</label>
                        
                        @if($motor->document)
                            <div class="mb-3">
                                <img src="{{ Storage::url($motor->document) }}" alt="Document Photo" 
                                     class="img-thumbnail" style="max-width: 200px;">
                                <p class="text-muted mt-1">Dokumen saat ini</p>
                            </div>
                        @endif
                        
                        <input type="file" class="form-control @error('document') is-invalid @enderror" 
                               name="document" accept="image/*">
                        @error('document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Upload STNK/dokumen motor. Kosongkan jika tidak ingin mengubah. Maksimal 2MB.</small>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pemilik.motors') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update Motor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview image when file is selected
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Add preview functionality for photo and document
    const photoInput = document.querySelector('input[name="photo"]');
    const documentInput = document.querySelector('input[name="document"]');
    
    if (photoInput) {
        photoInput.addEventListener('change', function() {
            previewImage(this, 'photoPreview');
        });
        
        // Add preview image element after the input
        const previewImg = document.createElement('img');
        previewImg.id = 'photoPreview';
        previewImg.className = 'img-thumbnail mt-2';
        previewImg.style.maxWidth = '200px';
        previewImg.style.display = 'none';
        photoInput.parentNode.appendChild(previewImg);
    }
    
    if (documentInput) {
        documentInput.addEventListener('change', function() {
            previewImage(this, 'documentPreview');
        });
        
        // Add preview image element after the input
        const previewImg = document.createElement('img');
        previewImg.id = 'documentPreview';
        previewImg.className = 'img-thumbnail mt-2';
        previewImg.style.maxWidth = '200px';
        previewImg.style.display = 'none';
        documentInput.parentNode.appendChild(previewImg);
    }
});
</script>
@endpush