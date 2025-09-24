@extends('layouts.fann')@extends('layouts.fann')@extends('layouts.fann')@extends('layouts.fann')



@section('title', 'Edit Motor')



@section('content')@section('title', 'Edit Motor - ' . $motor->brand . ' ' . $motor->model)

<div class="container-fluid">

    <div class="row">

        <div class="col-lg-12">

            <div class="card">@section('content')@section('title', 'Edit Motor - ' . $motor->brand . ' ' . $motor->model)@section('title', 'Edit Motor - ' . $motor->brand . ' ' . $motor->model)

                <div class="card-header">

                    <h4 class="card-title">Edit Motor</h4><!-- Content Header -->

                </div>

                <div class="card-body"><div class="content-header">

                    <form id="editMotorForm" action="{{ route('pemilik.motor.update', $motor->id) }}" method="POST" enctype="multipart/form-data">

                        @csrf    <div class="d-flex justify-content-between align-items-center">

                        @method('PUT')

                                <div>@section('content')@section('content')

                        <div class="row">

                            <!-- Merek -->            <h1>Edit Motor</h1>

                            <div class="col-md-6">

                                <div class="form-group">            <p>Perbarui informasi motor {{ $motor->brand }} {{ $motor->model }}</p><!-- Content Header --><!-- Content Header -->

                                    <label>Merek Motor</label>

                                    <input type="text" class="form-control @error('brand') is-invalid @enderror"         </div>

                                           name="brand" value="{{ old('brand', $motor->brand) }}" required>

                                    @error('brand')        <a href="{{ route('pemilik.motors') }}" class="btn btn-outline-secondary"><div class="content-header"><div class="content-header">

                                        <div class="invalid-feedback">{{ $message }}</div>

                                    @enderror            <i class="bi bi-arrow-left me-2"></i>Kembali

                                </div>

                            </div>        </a>    <div class="d-flex justify-content-between align-items-center">    <div class="d-flex justify-content-between align-items-center">



                            <!-- Model -->    </div>

                            <div class="col-md-6">

                                <div class="form-group"></div>        <div>        <div>

                                    <label>Model Motor</label>

                                    <input type="text" class="form-control @error('model') is-invalid @enderror" 

                                           name="model" value="{{ old('model', $motor->model) }}" required>

                                    @error('model')<!-- Form Edit Motor -->            <h1>Edit Motor</h1>            <h1>Edit Motor</h1>

                                        <div class="invalid-feedback">{{ $message }}</div>

                                    @enderror<div class="row justify-content-center">

                                </div>

                            </div>    <div class="col-lg-8">            <p>Perbarui informasi motor {{ $motor->brand }} {{ $motor->model }}</p>            <p>Perbarui informasi motor {{ $motor->brand }} {{ $motor->model }}</p>

                        </div>

        <div class="card">

                        <div class="row">

                            <!-- Tahun -->            <div class="card-header">        </div>        </div>

                            <div class="col-md-6">

                                <div class="form-group">                <h5 class="mb-0">

                                    <label>Tahun</label>

                                    <input type="number" class="form-control @error('year') is-invalid @enderror"                     <i class="bi bi-pencil me-2"></i>        <a href="{{ route('pemilik.motors') }}" class="btn btn-outline-secondary">        <a href="{{ route('pemilik.motors') }}" class="btn btn-outline-secondary">

                                           name="year" value="{{ old('year', $motor->year) }}" min="2000" max="2025" required>

                                    @error('year')                    Form Edit Motor

                                        <div class="invalid-feedback">{{ $message }}</div>

                                    @enderror                </h5>            <i class="bi bi-arrow-left me-2"></i>Kembali            <i class="bi bi-arrow-left me-2"></i>Kembali

                                </div>

                            </div>            </div>



                            <!-- Plat Nomor -->            <div class="card-body">        </a>        </a>

                            <div class="col-md-6">

                                <div class="form-group">                <form action="{{ route('pemilik.motor.update', $motor->id) }}" method="POST" enctype="multipart/form-data">

                                    <label>Plat Nomor</label>

                                    <input type="text" class="form-control @error('license_plate') is-invalid @enderror"                     @csrf    </div>    </div>

                                           name="license_plate" value="{{ old('license_plate', $motor->license_plate) }}" required>

                                    @error('license_plate')                    @method('PATCH')

                                        <div class="invalid-feedback">{{ $message }}</div>

                                    @enderror</div></div>

                                </div>

                            </div>                    <div class="row">

                        </div>

                        <!-- Brand Motor -->

                        <div class="row">

                            <!-- Tipe -->                        <div class="col-md-6 mb-3">

                            <div class="col-md-6">

                                <div class="form-group">                            <label for="brand" class="form-label">Merek Motor <span class="text-danger">*</span></label><!-- Form Edit Motor --><!-- Form Edit Motor -->

                                    <label>Tipe Motor</label>

                                    <select class="form-control @error('type') is-invalid @enderror" name="type" required>                            <input type="text" class="form-control @error('brand') is-invalid @enderror" 

                                        <option value="">Pilih Tipe Motor</option>

                                        <option value="matic" {{ old('type', $motor->type) == 'matic' ? 'selected' : '' }}>Matic</option>                                   id="brand" name="brand" value="{{ old('brand', $motor->brand) }}" required><div class="row justify-content-center"><div class="row justify-content-center">

                                        <option value="manual" {{ old('type', $motor->type) == 'manual' ? 'selected' : '' }}>Manual</option>

                                        <option value="sport" {{ old('type', $motor->type) == 'sport' ? 'selected' : '' }}>Sport</option>                            @error('brand')

                                    </select>

                                    @error('type')                                <div class="invalid-feedback">{{ $message }}</div>    <div class="col-lg-8">    <div class="col-lg-8">

                                        <div class="invalid-feedback">{{ $message }}</div>

                                    @enderror                            @enderror

                                </div>

                            </div>                        </div>        <div class="card">        <div class="card">



                            <!-- CC -->

                            <div class="col-md-6">

                                <div class="form-group">                        <!-- Model Motor -->            <div class="card-header">            <div class="card-header">

                                    <label>CC Motor</label>

                                    <select class="form-control @error('cc') is-invalid @enderror" name="cc" required>                        <div class="col-md-6 mb-3">

                                        <option value="">Pilih CC Motor</option>

                                        <option value="110" {{ old('cc', $motor->cc) == '110' ? 'selected' : '' }}>110 CC</option>                            <label for="model" class="form-label">Model Motor <span class="text-danger">*</span></label>                <h5 class="mb-0">                <h5 class="mb-0">

                                        <option value="125" {{ old('cc', $motor->cc) == '125' ? 'selected' : '' }}>125 CC</option>

                                        <option value="150" {{ old('cc', $motor->cc) == '150' ? 'selected' : '' }}>150 CC</option>                            <input type="text" class="form-control @error('model') is-invalid @enderror" 

                                        <option value="250" {{ old('cc', $motor->cc) == '250' ? 'selected' : '' }}>250 CC</option>

                                        <option value="400" {{ old('cc', $motor->cc) == '400' ? 'selected' : '' }}>400 CC</option>                                   id="model" name="model" value="{{ old('model', $motor->model) }}" required>                    <i class="bi bi-pencil me-2"></i>                    <i class="bi bi-pencil me-2"></i>

                                    </select>

                                    @error('cc')                            @error('model')

                                        <div class="invalid-feedback">{{ $message }}</div>

                                    @enderror                                <div class="invalid-feedback">{{ $message }}</div>                    Form Edit Motor                    Form Edit Motor

                                </div>

                            </div>                            @enderror

                        </div>

                        </div>                </h5>                </h5>

                        <!-- Deskripsi -->

                        <div class="form-group">                    </div>

                            <label>Deskripsi</label>

                            <textarea class="form-control @error('description') is-invalid @enderror"             </div>            </div>

                                      name="description" rows="4">{{ old('description', $motor->description) }}</textarea>

                            @error('description')                    <div class="row">

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror                        <!-- CC Motor -->            <div class="card-body">            <div class="card-body">

                        </div>

                        <div class="col-md-6 mb-3">

                        <!-- Status -->

                        <div class="form-group">                            <label for="type_cc" class="form-label">Kapasitas Mesin <span class="text-danger">*</span></label>                <form action="{{ route('pemilik.motor.update', $motor->id) }}" method="POST" enctype="multipart/form-data">                <form action="{{ route('pemilik.motor.update', $motor->id) }}" method="POST" enctype="multipart/form-data">

                            <label>Status Motor</label>

                            <select class="form-control @error('status') is-invalid @enderror" name="status" required>                            <select class="form-select @error('type_cc') is-invalid @enderror" id="type_cc" name="type_cc" required>

                                <option value="available" {{ old('status', $motor->status) == 'available' ? 'selected' : '' }}>Tersedia</option>

                                <option value="rented" {{ old('status', $motor->status) == 'rented' ? 'selected' : '' }}>Disewa</option>                                <option value="">Pilih Kapasitas Mesin</option>                    @csrf                    @csrf

                                <option value="maintenance" {{ old('status', $motor->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>

                            </select>                                <option value="100cc" {{ old('type_cc', $motor->type_cc) == '100cc' ? 'selected' : '' }}>100cc</option>

                            @error('status')

                                <div class="invalid-feedback">{{ $message }}</div>                                <option value="125cc" {{ old('type_cc', $motor->type_cc) == '125cc' ? 'selected' : '' }}>125cc</option>                    @method('PATCH')                    @method('PATCH')

                            @enderror

                        </div>                                <option value="150cc" {{ old('type_cc', $motor->type_cc) == '150cc' ? 'selected' : '' }}>150cc</option>



                        <!-- Foto Motor Saat Ini -->                                <option value="250cc" {{ old('type_cc', $motor->type_cc) == '250cc' ? 'selected' : '' }}>250cc</option>

                        @if($motor->photo_path)

                        <div class="form-group">                                <option value="500cc" {{ old('type_cc', $motor->type_cc) == '500cc' ? 'selected' : '' }}>500cc</option>

                            <label>Foto Motor Saat Ini</label>

                            <div class="mb-2">                            </select>                    <div class="row">                    <div class="row">

                                <img src="{{ Storage::url($motor->photo_path) }}" alt="Foto Motor" class="img-thumbnail" style="max-width: 300px;">

                            </div>                            @error('type_cc')

                        </div>

                        @endif                                <div class="invalid-feedback">{{ $message }}</div>                        <!-- Brand Motor -->                        <!-- Brand Motor -->



                        <!-- Upload Foto Baru -->                            @enderror

                        <div class="form-group">

                            <label>{{ $motor->photo_path ? 'Ganti Foto Motor (Opsional)' : 'Foto Motor' }}</label>                        </div>                        <div class="col-md-6 mb-3">                        <div class="col-md-6 mb-3">

                            <input type="file" class="form-control-file @error('photo') is-invalid @enderror" 

                                   name="photo" accept="image/*">

                            <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>

                            @error('photo')                        <!-- Tahun -->                            <label for="brand" class="form-label">Merek Motor <span class="text-danger">*</span></label>                            <label for="brand" class="form-label">Merek Motor <span class="text-danger">*</span></label>

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror                        <div class="col-md-6 mb-3">

                        </div>

                            <label for="year" class="form-label">Tahun <span class="text-danger">*</span></label>                            <input type="text" class="form-control @error('brand') is-invalid @enderror"                             <input type="text" class="form-control @error('brand') is-invalid @enderror" 

                        <!-- Dokumen STNK Saat Ini -->

                        @if($motor->stnk_path)                            <input type="number" class="form-control @error('year') is-invalid @enderror" 

                        <div class="form-group">

                            <label>Dokumen STNK Saat Ini</label>                                   id="year" name="year" value="{{ old('year', $motor->year) }}"                                    id="brand" name="brand" value="{{ old('brand', $motor->brand) }}" required>                                   id="brand" name="brand" value="{{ old('brand', $motor->brand) }}" required>

                            <div class="mb-2">

                                <a href="{{ Storage::url($motor->stnk_path) }}" target="_blank" class="btn btn-info btn-sm">                                   min="2010" max="{{ date('Y') }}" required>

                                    <i class="fa fa-eye"></i> Lihat STNK

                                </a>                            @error('year')                            @error('brand')                            @error('brand')

                            </div>

                        </div>                                <div class="invalid-feedback">{{ $message }}</div>

                        @endif

                            @enderror                                <div class="invalid-feedback">{{ $message }}</div>                                <div class="invalid-feedback">{{ $message }}</div>

                        <!-- Upload STNK Baru -->

                        <div class="form-group">                        </div>

                            <label>{{ $motor->stnk_path ? 'Ganti Dokumen STNK (Opsional)' : 'Upload Dokumen STNK' }}</label>

                            <input type="file" class="form-control-file @error('stnk') is-invalid @enderror"                     </div>                            @enderror                            @enderror

                                   name="stnk" accept=".pdf,.jpg,.jpeg,.png">

                            <small class="form-text text-muted">Format: PDF, JPG, JPEG, PNG. Maksimal 5MB.</small>

                            @error('stnk')

                                <div class="invalid-feedback">{{ $message }}</div>                    <div class="row">                        </div>                        </div>

                            @enderror

                        </div>                        <!-- Warna -->



                        <div class="form-group">                        <div class="col-md-6 mb-3">

                            <button type="submit" class="btn btn-primary">

                                <i class="fa fa-save"></i> Update Motor                            <label for="color" class="form-label">Warna <span class="text-danger">*</span></label>

                            </button>

                            <a href="{{ route('pemilik.motors') }}" class="btn btn-secondary">                            <input type="text" class="form-control @error('color') is-invalid @enderror"                         <!-- Model Motor -->                        <!-- Model Motor -->

                                <i class="fa fa-arrow-left"></i> Kembali

                            </a>                                   id="color" name="color" value="{{ old('color', $motor->color) }}" required>

                        </div>

                    </form>                            @error('color')                        <div class="col-md-6 mb-3">                        <div class="col-md-6 mb-3">

                </div>

            </div>                                <div class="invalid-feedback">{{ $message }}</div>

        </div>

    </div>                            @enderror                            <label for="model" class="form-label">Model Motor <span class="text-danger">*</span></label>                            <label for="model" class="form-label">Model Motor <span class="text-danger">*</span></label>

</div>

@endsection                        </div>



@push('scripts')                            <input type="text" class="form-control @error('model') is-invalid @enderror"                             <input type="text" class="form-control @error('model') is-invalid @enderror" 

<script>

$(document).ready(function() {                        <!-- Plat Nomor -->

    // Preview foto yang diupload

    $('input[name="photo"]').change(function() {                        <div class="col-md-6 mb-3">                                   id="model" name="model" value="{{ old('model', $motor->model) }}" required>                                   id="model" name="model" value="{{ old('model', $motor->model) }}" required>

        const file = this.files[0];

        if (file) {                            <label for="plate_number" class="form-label">Plat Nomor <span class="text-danger">*</span></label>

            const reader = new FileReader();

            reader.onload = function(e) {                            <input type="text" class="form-control @error('plate_number') is-invalid @enderror"                             @error('model')                            @error('model')

                // Hapus preview sebelumnya jika ada

                $('.photo-preview').remove();                                   id="plate_number" name="plate_number" value="{{ old('plate_number', $motor->plate_number) }}" 

                

                // Tambah preview baru                                   style="text-transform: uppercase;" required>                                <div class="invalid-feedback">{{ $message }}</div>                                <div class="invalid-feedback">{{ $message }}</div>

                const preview = $('<div class="photo-preview mt-2"><img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 300px;"><small class="d-block text-muted">Preview foto baru</small></div>');

                $('input[name="photo"]').parent().append(preview);                            @error('plate_number')

            };

            reader.readAsDataURL(file);                                <div class="invalid-feedback">{{ $message }}</div>                            @enderror                            @enderror

        }

    });                            @enderror



    // Validasi form sebelum submit                        </div>                        </div>                        </div>

    $('#editMotorForm').submit(function(e) {

        let isValid = true;                    </div>

        

        // Reset previous validation                    </div>                    </div>

        $('.is-invalid').removeClass('is-invalid');

                            <!-- Deskripsi -->

        // Validasi required fields

        $(this).find('[required]').each(function() {                    <div class="mb-3">

            if (!$(this).val()) {

                $(this).addClass('is-invalid');                        <label for="description" class="form-label">Deskripsi</label>

                isValid = false;

            }                        <textarea class="form-control @error('description') is-invalid @enderror"                     <div class="row">                    <div class="row">

        });

                                          id="description" name="description" rows="3" 

        // Validasi tahun

        const year = parseInt($('input[name="year"]').val());                                  placeholder="Deskripsi tambahan tentang motor...">{{ old('description', $motor->description) }}</textarea>                        <!-- CC Motor -->                        <!-- CC Motor -->

        if (year && (year < 2000 || year > 2025)) {

            $('input[name="year"]').addClass('is-invalid');                        @error('description')

            isValid = false;

        }                            <div class="invalid-feedback">{{ $message }}</div>                        <div class="col-md-6 mb-3">                        <div class="col-md-6 mb-3">

        

        if (!isValid) {                        @enderror

            e.preventDefault();

            alert('Mohon lengkapi semua field yang wajib diisi dengan benar!');                    </div>                            <label for="type_cc" class="form-label">Kapasitas Mesin <span class="text-danger">*</span></label>                            <label for="type_cc" class="form-label">Kapasitas Mesin <span class="text-danger">*</span></label>

        }

    });

});

</script>                    <!-- Upload Foto -->                            <select class="form-select @error('type_cc') is-invalid @enderror" id="type_cc" name="type_cc" required>                            <select class="form-select @error('type_cc') is-invalid @enderror" id="type_cc" name="type_cc" required>

@endpush
                    <div class="mb-3">

                        <label for="photo" class="form-label">Foto Motor</label>                                <option value="">Pilih Kapasitas Mesin</option>                                <option value="">Pilih Kapasitas Mesin</option>

                        @if($motor->photo)

                            <div class="mb-2">                                <option value="100cc" {{ old('type_cc', $motor->type_cc) == '100cc' ? 'selected' : '' }}>100cc</option>                                <option value="100cc" {{ old('type_cc', $motor->type_cc) == '100cc' ? 'selected' : '' }}>100cc</option>

                                <img src="{{ Storage::url($motor->photo) }}" class="img-thumbnail" style="max-height: 200px;" alt="Current Photo">

                                <small class="text-muted d-block">Foto saat ini</small>                                <option value="125cc" {{ old('type_cc', $motor->type_cc) == '125cc' ? 'selected' : '' }}>125cc</option>                                <option value="125cc" {{ old('type_cc', $motor->type_cc) == '125cc' ? 'selected' : '' }}>125cc</option>

                            </div>

                        @endif                                <option value="150cc" {{ old('type_cc', $motor->type_cc) == '150cc' ? 'selected' : '' }}>150cc</option>                                <option value="150cc" {{ old('type_cc', $motor->type_cc) == '150cc' ? 'selected' : '' }}>150cc</option>

                        <input type="file" class="form-control @error('photo') is-invalid @enderror" 

                               id="photo" name="photo" accept="image/*">                                <option value="250cc" {{ old('type_cc', $motor->type_cc) == '250cc' ? 'selected' : '' }}>250cc</option>                                <option value="250cc" {{ old('type_cc', $motor->type_cc) == '250cc' ? 'selected' : '' }}>250cc</option>

                        <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</small>

                        @error('photo')                                <option value="500cc" {{ old('type_cc', $motor->type_cc) == '500cc' ? 'selected' : '' }}>500cc</option>                                <option value="500cc" {{ old('type_cc', $motor->type_cc) == '500cc' ? 'selected' : '' }}>500cc</option>

                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror                            </select>                            </select>

                    </div>

                            @error('type_cc')                            @error('type_cc')

                    <!-- Upload Dokumen -->

                    <div class="mb-4">                                <div class="invalid-feedback">{{ $message }}</div>                                <div class="invalid-feedback">{{ $message }}</div>

                        <label for="document" class="form-label">Dokumen Motor (STNK/BPKB)</label>

                        @if($motor->document)                            @enderror                            @enderror

                            <div class="mb-2">

                                <img src="{{ Storage::url($motor->document) }}" class="img-thumbnail" style="max-height: 200px;" alt="Current Document">                        </div>                        </div>

                                <small class="text-muted d-block">Dokumen saat ini</small>

                            </div>

                        @endif

                        <input type="file" class="form-control @error('document') is-invalid @enderror"                         <!-- Tahun -->                        <!-- Tahun -->

                               id="document" name="document" accept="image/*">

                        <small class="text-muted">Upload foto STNK atau BPKB. Format: JPG, PNG, JPEG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah dokumen.</small>                        <div class="col-md-6 mb-3">                        <div class="col-md-6 mb-3">

                        @error('document')

                            <div class="invalid-feedback">{{ $message }}</div>                            <label for="year" class="form-label">Tahun <span class="text-danger">*</span></label>                            <label for="year" class="form-label">Tahun <span class="text-danger">*</span></label>

                        @enderror

                    </div>                            <input type="number" class="form-control @error('year') is-invalid @enderror"                             <input type="number" class="form-control @error('year') is-invalid @enderror" 



                    <!-- Submit Button -->                                   id="year" name="year" value="{{ old('year', $motor->year) }}"                                    id="year" name="year" value="{{ old('year', $motor->year) }}" 

                    <div class="d-flex justify-content-end">

                        <a href="{{ route('pemilik.motors') }}" class="btn btn-secondary me-2">Batal</a>                                   min="2010" max="{{ date('Y') }}" required>                                   min="2010" max="{{ date('Y') }}" required>

                        <button type="submit" class="btn btn-primary">

                            <i class="bi bi-check-circle me-2"></i>Perbarui Motor                            @error('year')                            @error('year')

                        </button>

                    </div>                                <div class="invalid-feedback">{{ $message }}</div>                                <div class="invalid-feedback">{{ $message }}</div>

                </form>

            </div>                            @enderror                            @enderror

        </div>

    </div>                        </div>                        </div>

</div>

                    </div>                    </div>

@endsection



@push('scripts')

<script>                    <div class="row">                    <div class="row">

// Auto uppercase plate number

document.getElementById('plate_number').addEventListener('input', function() {                        <!-- Warna -->                        <!-- Warna -->

    this.value = this.value.toUpperCase();

});                        <div class="col-md-6 mb-3">                        <div class="col-md-6 mb-3">

</script>

@endpush                            <label for="color" class="form-label">Warna <span class="text-danger">*</span></label>                            <label for="color" class="form-label">Warna <span class="text-danger">*</span></label>

                            <input type="text" class="form-control @error('color') is-invalid @enderror"                             <input type="text" class="form-control @error('color') is-invalid @enderror" 

                                   id="color" name="color" value="{{ old('color', $motor->color) }}" required>                                   id="color" name="color" value="{{ old('color', $motor->color) }}" required>

                            @error('color')                            @error('color')

                                <div class="invalid-feedback">{{ $message }}</div>                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror                            @enderror

                        </div>                        </div>



                        <!-- Plat Nomor -->                        <!-- Plat Nomor -->

                        <div class="col-md-6 mb-3">                        <div class="col-md-6 mb-3">

                            <label for="plate_number" class="form-label">Plat Nomor <span class="text-danger">*</span></label>                            <label for="plate_number" class="form-label">Plat Nomor <span class="text-danger">*</span></label>

                            <input type="text" class="form-control @error('plate_number') is-invalid @enderror"                             <input type="text" class="form-control @error('plate_number') is-invalid @enderror" 

                                   id="plate_number" name="plate_number" value="{{ old('plate_number', $motor->plate_number) }}"                                    id="plate_number" name="plate_number" value="{{ old('plate_number', $motor->plate_number) }}" 

                                   style="text-transform: uppercase;" required>                                   style="text-transform: uppercase;" required>

                            @error('plate_number')                            @error('plate_number')

                                <div class="invalid-feedback">{{ $message }}</div>                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror                            @enderror

                        </div>                        </div>

                    </div>                    </div>



                    <!-- Deskripsi -->                    <!-- Deskripsi -->

                    <div class="mb-3">                    <div class="mb-3">

                        <label for="description" class="form-label">Deskripsi</label>                        <label for="description" class="form-label">Deskripsi</label>

                        <textarea class="form-control @error('description') is-invalid @enderror"                         <textarea class="form-control @error('description') is-invalid @enderror" 

                                  id="description" name="description" rows="3"                                   id="description" name="description" rows="3" 

                                  placeholder="Deskripsi tambahan tentang motor...">{{ old('description', $motor->description) }}</textarea>                                  placeholder="Deskripsi tambahan tentang motor...">{{ old('description', $motor->description) }}</textarea>

                        @error('description')                        @error('description')

                            <div class="invalid-feedback">{{ $message }}</div>                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror                        @enderror

                    </div>                    </div>



                    <!-- Upload Foto -->                    <!-- Upload Foto -->

                    <div class="mb-3">                    <div class="mb-3">

                        <label for="photo" class="form-label">Foto Motor</label>                        <label for="photo" class="form-label">Foto Motor</label>

                        @if($motor->photo)                        @if($motor->photo)

                            <div class="mb-2">                            <div class="mb-2">

                                <img src="{{ Storage::url($motor->photo) }}" class="img-thumbnail" style="max-height: 200px;" alt="Current Photo">                                <img src="{{ Storage::url($motor->photo) }}" class="img-thumbnail" style="max-height: 200px;" alt="Current Photo">

                                <small class="text-muted d-block">Foto saat ini</small>                                <small class="text-muted d-block">Foto saat ini</small>

                            </div>                            </div>

                        @endif                        @endif

                        <input type="file" class="form-control @error('photo') is-invalid @enderror"                         <input type="file" class="form-control @error('photo') is-invalid @enderror" 

                               id="photo" name="photo" accept="image/*">                               id="photo" name="photo" accept="image/*">

                        <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</small>                        <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</small>

                        @error('photo')                        @error('photo')

                            <div class="invalid-feedback">{{ $message }}</div>                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror                        @enderror

                    </div>                    </div>



                    <!-- Upload Dokumen -->                    <!-- Upload Dokumen -->

                    <div class="mb-4">                    <div class="mb-4">

                        <label for="document" class="form-label">Dokumen Motor (STNK/BPKB)</label>                        <label for="document" class="form-label">Dokumen Motor (STNK/BPKB)</label>

                        @if($motor->document)                        @if($motor->document)

                            <div class="mb-2">                            <div class="mb-2">

                                <img src="{{ Storage::url($motor->document) }}" class="img-thumbnail" style="max-height: 200px;" alt="Current Document">                                <img src="{{ Storage::url($motor->document) }}" class="img-thumbnail" style="max-height: 200px;" alt="Current Document">

                                <small class="text-muted d-block">Dokumen saat ini</small>                                <small class="text-muted d-block">Dokumen saat ini</small>

                            </div>                            </div>

                        @endif                        @endif

                        <input type="file" class="form-control @error('document') is-invalid @enderror"                         <input type="file" class="form-control @error('document') is-invalid @enderror" 

                               id="document" name="document" accept="image/*">                               id="document" name="document" accept="image/*">

                        <small class="text-muted">Upload foto STNK atau BPKB. Format: JPG, PNG, JPEG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah dokumen.</small>                        <small class="text-muted">Upload foto STNK atau BPKB. Format: JPG, PNG, JPEG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah dokumen.</small>

                        @error('document')                        @error('document')

                            <div class="invalid-feedback">{{ $message }}</div>                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror                        @enderror

                    </div>                    </div>



                    <!-- Submit Button -->                    <!-- Submit Button -->

                    <div class="d-flex justify-content-end">                    <div class="d-flex justify-content-end">

                        <a href="{{ route('pemilik.motors') }}" class="btn btn-secondary me-2">Batal</a>                        <a href="{{ route('pemilik.motors') }}" class="btn btn-secondary me-2">Batal</a>

                        <button type="submit" class="btn btn-primary">                        <button type="submit" class="btn btn-primary">

                            <i class="bi bi-check-circle me-2"></i>Perbarui Motor                            <i class="bi bi-check-circle me-2"></i>Perbarui Motor

                        </button>                        </button>

                    </div>                    </div>

                </form>                </form>

            </div>            </div>

        </div>        </div>

    </div>    </div>

</div></div>



@endsection@endsection



@section('scripts')@section('scripts')

<script><script>

// Auto uppercase plate number// Auto uppercase plate number

document.getElementById('plate_number').addEventListener('input', function() {document.getElementById('plate_number').addEventListener('input', function() {

    this.value = this.value.toUpperCase();    this.value = this.value.toUpperCase();

});});

</script></script>

@endsection@endsection
                                            <option value="250cc" {{ old('type_cc', $motor->type_cc) == '250cc' ? 'selected' : '' }}>250cc</option>
                                            <option value="500cc" {{ old('type_cc', $motor->type_cc) == '500cc' ? 'selected' : '' }}>500cc</option>
                                        </select>
                                        @error('type_cc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Plat Nomor -->
                                    <div class="mb-4">
                                        <label for="plate_number" class="form-label">Plat Nomor <span class="text-danger">*</span></label>
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
                                                    <div class="form-text">Kosongkan untuk otomatis (diskon 10%)</div>
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
                                                    <div class="form-text">Kosongkan untuk otomatis (diskon 20%)</div>
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
    
    // Auto-calculation functions
    function calculateWeeklyRate() {
        const dailyInput = document.getElementById('daily_rate');
        const weeklyInput = document.getElementById('weekly_rate');
        
        if (dailyInput && weeklyInput) {
            const dailyValue = parseFloat(dailyInput.value.replace(/[^\d]/g, '')) || 0;
            // Weekly rate = daily rate * 7 days * 0.9 (10% discount)
            const weeklyValue = Math.min(dailyValue * 7 * 0.9, 1000000);
            weeklyInput.value = new Intl.NumberFormat('id-ID').format(weeklyValue);
        }
    }
    
    function calculateMonthlyRate() {
        const dailyInput = document.getElementById('daily_rate');
        const monthlyInput = document.getElementById('monthly_rate');
        
        if (dailyInput && monthlyInput) {
            const dailyValue = parseFloat(dailyInput.value.replace(/[^\d]/g, '')) || 0;
            // Monthly rate = daily rate * 30 days * 0.8 (20% discount)
            const monthlyValue = Math.min(dailyValue * 30 * 0.8, 1000000);
            monthlyInput.value = new Intl.NumberFormat('id-ID').format(monthlyValue);
        }
    }
    
    // Auto-calculate when daily rate changes
    const dailyRateInput = document.getElementById('daily_rate');
    if (dailyRateInput) {
        dailyRateInput.addEventListener('input', function() {
            calculateWeeklyRate();
            calculateMonthlyRate();
        });
    }
    
    numberInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                if (value) {
                    // Limit to 1,000,000
                    value = Math.min(parseInt(value), 1000000);
                    e.target.value = new Intl.NumberFormat('id-ID').format(value);
                }
            });
        }
    });
    
    // Initial calculation
    calculateWeeklyRate();
    calculateMonthlyRate();
});
</script>
@endsection