@extends('layouts.fann')

@section('title', 'Form Pemesanan Motor')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>
        <i class="bi bi-calendar-plus me-3"></i>Form Pemesanan Motor
    </h1>
    <p>Lengkapi informasi pemesanan motor Anda</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pemesanan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('penyewa.booking.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="motor_id" value="{{ $motor->id }}">
                    <input type="hidden" name="start_date" id="hidden_start_date">
                    <input type="hidden" name="end_date" id="hidden_end_date">
                    <input type="hidden" name="package_type" id="hidden_package_type">
                    
                    <!-- Step 1: Pilih Paket -->
                    <div class="mb-4" id="step-package">
                        <h6 class="mb-3">
                            <span class="badge bg-primary me-2">1</span>Pilih Paket Sewa
                        </h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card package-card h-100" data-package="daily">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-calendar-day text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                        <h6>Paket Harian</h6>
                                        <p class="text-muted small mb-3">Sewa per hari (1-6 hari)</p>
                                        @if($motor->rentalRate)
                                            <div class="fw-bold text-primary">
                                                Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}/hari
                                            </div>
                                        @endif
                                        <div class="mt-3">
                                            <small class="text-success">✓ Fleksibel</small><br>
                                            <small class="text-success">✓ Cocok untuk jarak dekat</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card package-card h-100" data-package="weekly">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-calendar-week text-success" style="font-size: 2rem;"></i>
                                        </div>
                                        <h6>Paket Mingguan</h6>
                                        <p class="text-muted small mb-3">Sewa per minggu (1-4 minggu)</p>
                                        @if($motor->rentalRate)
                                            <div class="fw-bold text-success">
                                                Rp {{ number_format($motor->rentalRate->daily_rate * 7 * 0.9, 0, ',', '.') }}/minggu
                                            </div>
                                            <small class="text-success">Hemat 10%!</small>
                                        @endif
                                        <div class="mt-3">
                                            <small class="text-success">✓ Lebih hemat</small><br>
                                            <small class="text-success">✓ Cocok untuk liburan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card package-card h-100" data-package="monthly">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-calendar-month text-warning" style="font-size: 2rem;"></i>
                                        </div>
                                        <h6>Paket Bulanan</h6>
                                        <p class="text-muted small mb-3">Sewa sebulan penuh</p>
                                        @if($motor->rentalRate)
                                            <div class="fw-bold text-warning">
                                                Rp {{ number_format($motor->rentalRate->daily_rate * 30 * 0.8, 0, ',', '.') }}/bulan
                                            </div>
                                            <small class="text-warning">Hemat 20%!</small>
                                        @endif
                                        <div class="mt-3">
                                            <small class="text-success">✓ Paling hemat</small><br>
                                            <small class="text-success">✓ Cocok untuk jangka panjang</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 1.5: Pilih Durasi -->
                    <div class="mb-4 d-none" id="step-duration">
                        <h6 class="mb-3">
                            <span class="badge bg-primary me-2">1</span>Pilih Durasi <span id="package-name"></span>
                        </h6>
                        <div class="row" id="duration-options">
                            <!-- Durasi options akan ditampilkan di sini -->
                        </div>
                    </div>
                    
                    <!-- Step 2: Pilih Tanggal -->
                    <div class="mb-4 d-none" id="step-date">
                        <h6 class="mb-3">
                            <span class="badge bg-primary me-2">2</span>Pilih Tanggal Mulai
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" 
                                       value="{{ old('start_date') }}"
                                       min="{{ date('Y-m-d') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="end_date_display"
                                       readonly>
                                <small class="text-muted">Otomatis dihitung berdasarkan paket yang dipilih</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 3: Catatan -->
                    <div class="mb-4 d-none" id="step-notes">
                        <h6 class="mb-3">
                            <span class="badge bg-primary me-2">3</span>Catatan Tambahan (Opsional)
                        </h6>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  placeholder="Tuliskan catatan atau permintaan khusus (opsional)">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Step 4: Ringkasan -->
                    <div class="mb-4 d-none" id="step-summary">
                        <h6 class="mb-3">
                            <span class="badge bg-primary me-2">4</span>Ringkasan Pemesanan
                        </h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Paket Dipilih</label>
                                            <div id="summary-package" class="text-muted">-</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Periode Sewa</label>
                                            <div id="summary-period" class="text-muted">-</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Durasi</label>
                                            <div id="summary-duration" class="text-muted">-</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Total Harga</label>
                                            <div id="summary-total" class="text-primary fw-bold fs-5">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Availability Status -->
                        <div class="mt-3" id="availability-status">
                            <!-- Availability check result will be shown here -->
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('penyewa.motors') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <div>
                            <button type="button" class="btn btn-outline-primary d-none" id="btn-prev">
                                <i class="bi bi-arrow-left me-1"></i>Sebelumnya
                            </button>
                            <button type="button" class="btn btn-primary d-none ms-2" id="btn-next">
                                Selanjutnya<i class="bi bi-arrow-right ms-1"></i>
                            </button>
                            <button type="submit" class="btn btn-success d-none" id="btn-submit">
                                <i class="bi bi-check-circle me-1"></i>Konfirmasi Pemesanan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Detail Motor</h6>
            </div>
            <div class="card-body">
                @if($motor->photo)
                    <img src="{{ Storage::url($motor->photo) }}" 
                         alt="{{ $motor->brand }} {{ $motor->model }}"
                         class="img-fluid rounded mb-3">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" 
                         style="height: 200px;">
                        <i class="bi bi-motorcycle text-muted" style="font-size: 3rem;"></i>
                    </div>
                @endif
                
                <h5>{{ $motor->brand }} {{ $motor->model }}</h5>
                <p class="text-muted mb-2">{{ $motor->type_cc }} • {{ $motor->year }}</p>
                
                @if($motor->description)
                    <p class="small text-muted">{{ $motor->description }}</p>
                @endif
                
                @if($motor->rentalRate)
                    <div class="border-top pt-3">
                        <h6>Tarif Sewa:</h6>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>Harian:</span>
                                <span class="fw-bold">Rp {{ number_format((float)$motor->rentalRate->daily_rate, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>Mingguan:</span>
                                <span class="fw-bold text-success">
                                    Rp {{ number_format($motor->rentalRate->daily_rate * 7 * 0.9, 0, ',', '.') }}
                                    <small class="text-success">(Hemat 10%)</small>
                                </span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>Bulanan:</span>
                                <span class="fw-bold text-warning">
                                    Rp {{ number_format($motor->rentalRate->daily_rate * 30 * 0.8, 0, ',', '.') }}
                                    <small class="text-warning">(Hemat 20%)</small>
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Tarif sewa belum ditetapkan
                    </div>
                @endif
                
                <div class="border-top pt-3 mt-3">
                    <h6>Fitur Motor:</h6>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-check-circle text-success me-1"></i>Kondisi Prima</li>
                        <li><i class="bi bi-check-circle text-success me-1"></i>Surat Lengkap</li>
                        <li><i class="bi bi-check-circle text-success me-1"></i>Asuransi</li>
                        <li><i class="bi bi-check-circle text-success me-1"></i>Helm Gratis</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.package-card, .duration-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.package-card:hover, .duration-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.package-card.selected, .duration-card.selected {
    border: 2px solid #0d6efd;
    background: rgba(13, 110, 253, 0.05);
}

.package-card.selected .card-body, .duration-card.selected .card-body {
    background: rgba(13, 110, 253, 0.05);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    let selectedPackage = null;
    let selectedDuration = 0;
    let dailyRate = {{ $motor->rentalRate ? $motor->rentalRate->daily_rate : 0 }};
    
    const packageCards = document.querySelectorAll('.package-card');
    const btnNext = document.getElementById('btn-next');
    const btnPrev = document.getElementById('btn-prev');
    const btnSubmit = document.getElementById('btn-submit');
    
    // Duration options untuk setiap paket
    const durationOptions = {
        daily: [
            { days: 1, label: '1 Hari', price: dailyRate * 1 },
            { days: 2, label: '2 Hari', price: dailyRate * 2 },
            { days: 3, label: '3 Hari', price: dailyRate * 3 },
            { days: 4, label: '4 Hari', price: dailyRate * 4 },
            { days: 5, label: '5 Hari', price: dailyRate * 5 },
            { days: 6, label: '6 Hari', price: dailyRate * 6 }
        ],
        weekly: [
            { days: 7, label: '1 Minggu', price: dailyRate * 7 * 0.9 },
            { days: 14, label: '2 Minggu', price: dailyRate * 14 * 0.9 },
            { days: 21, label: '3 Minggu', price: dailyRate * 21 * 0.9 },
            { days: 28, label: '4 Minggu', price: dailyRate * 28 * 0.9 }
        ],
        monthly: [
            { days: 30, label: '1 Bulan', price: dailyRate * 30 * 0.8 }
        ]
    };
    
    // Package selection
    packageCards.forEach(card => {
        card.addEventListener('click', function() {
            console.log('Step 1: Package selected:', this.dataset.package);
            
            // Remove previous selection
            packageCards.forEach(c => c.classList.remove('selected'));
            
            // Select current
            this.classList.add('selected');
            selectedPackage = this.dataset.package;
            
            // Update hidden field
            document.getElementById('hidden_package_type').value = selectedPackage;
            
            console.log('Hidden package type updated:', selectedPackage);
            
            // Show duration step
            showDurationOptions(selectedPackage);
            showStep(1.5);
        });
    });
    
    // Duration selection
    function showDurationOptions(packageType) {
        const container = document.getElementById('duration-options');
        const packageNameEl = document.getElementById('package-name');
        
        const packageNames = {
            'daily': 'Harian',
            'weekly': 'Mingguan',
            'monthly': 'Bulanan'
        };
        
        packageNameEl.textContent = packageNames[packageType];
        
        const options = durationOptions[packageType];
        container.innerHTML = '';
        
        options.forEach(option => {
            const colClass = packageType === 'monthly' ? 'col-12' : 
                           packageType === 'weekly' ? 'col-md-6 col-lg-3' : 'col-md-4 col-lg-2';
            
            const card = `
                <div class="${colClass} mb-3">
                    <div class="card duration-card h-100" data-days="${option.days}" data-price="${option.price}">
                        <div class="card-body text-center">
                            <h6 class="mb-2">${option.label}</h6>
                            <div class="fw-bold text-primary">
                                Rp ${new Intl.NumberFormat('id-ID').format(option.price)}
                            </div>
                            ${packageType !== 'daily' ? '<small class="text-success">Sudah diskon!</small>' : ''}
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML += card;
        });
        
        // Add event listeners to duration cards
        document.querySelectorAll('.duration-card').forEach(durationCard => {
            durationCard.addEventListener('click', function() {
                console.log('Step 1.5: Duration selected:', this.dataset.days, 'days');
                
                // Remove previous selection
                document.querySelectorAll('.duration-card').forEach(c => c.classList.remove('selected'));
                
                // Select current
                this.classList.add('selected');
                selectedDuration = parseInt(this.dataset.days);
                
                console.log('Selected duration updated:', selectedDuration);
                
                // Show next button
                btnNext.classList.remove('d-none');
                
                updateSummary();
            });
        });
    }
    
    // Next button
    btnNext.addEventListener('click', function() {
        if (currentStep === 1.5 && selectedDuration > 0) {
            showStep(2);
        } else if (currentStep === 2 && document.getElementById('start_date').value) {
            showStep(3);
        } else if (currentStep === 3) {
            showStep(4);
        }
    });
    
    // Previous button
    btnPrev.addEventListener('click', function() {
        if (currentStep === 2) {
            showStep(1.5);
        } else if (currentStep === 1.5) {
            showStep(1);
        } else if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    });
    
    // Date change
    document.getElementById('start_date').addEventListener('change', function() {
        console.log('Step 2: Start date selected:', this.value);
        
        const startDate = new Date(this.value);
        const endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + selectedDuration - 1);
        
        document.getElementById('end_date_display').value = endDate.toISOString().split('T')[0];
        document.getElementById('hidden_start_date').value = this.value;
        document.getElementById('hidden_end_date').value = endDate.toISOString().split('T')[0];
        
        console.log('Hidden dates updated:', {
            start: this.value,
            end: endDate.toISOString().split('T')[0]
        });
        
        if (this.value) {
            btnNext.classList.remove('d-none');
        }
        
        updateSummary();
    });
    
    function showStep(step) {
        console.log('Showing step:', step);
        
        // Hide all steps
        document.getElementById('step-package').classList.add('d-none');
        document.getElementById('step-duration').classList.add('d-none');
        document.getElementById('step-date').classList.add('d-none');
        document.getElementById('step-notes').classList.add('d-none');
        document.getElementById('step-summary').classList.add('d-none');
        
        // Show current step
        currentStep = step;
        
        if (step === 1) {
            document.getElementById('step-package').classList.remove('d-none');
            btnNext.classList.add('d-none');
        } else if (step === 1.5) {
            document.getElementById('step-duration').classList.remove('d-none');
            btnNext.classList.toggle('d-none', selectedDuration === 0);
        } else if (step === 2) {
            document.getElementById('step-date').classList.remove('d-none');
        } else if (step === 3) {
            document.getElementById('step-notes').classList.remove('d-none');
        } else if (step === 4) {
            document.getElementById('step-summary').classList.remove('d-none');
        }
        
        // Update buttons
        btnPrev.classList.toggle('d-none', step === 1);
        btnNext.classList.toggle('d-none', step === 4);
        btnSubmit.classList.toggle('d-none', step !== 4);
        
        if (step === 4) {
            updateSummary();
            // Check availability when showing summary
            setTimeout(checkAvailability, 100);
        }
    }
    
    function updateSummary() {
        if (!selectedPackage || !selectedDuration) return;
        
        const packageNames = {
            'daily': 'Paket Harian',
            'weekly': 'Paket Mingguan', 
            'monthly': 'Paket Bulanan'
        };
        
        const discounts = {
            'daily': 1,
            'weekly': 0.9,
            'monthly': 0.8
        };
        
        const totalPrice = dailyRate * selectedDuration * discounts[selectedPackage];
        
        document.getElementById('summary-package').textContent = packageNames[selectedPackage];
        document.getElementById('summary-duration').textContent = selectedDuration + ' hari';
        document.getElementById('summary-total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice);
        
        const startDate = document.getElementById('start_date').value;
        if (startDate) {
            const endDate = document.getElementById('end_date_display').value;
            document.getElementById('summary-period').textContent = 
                new Date(startDate).toLocaleDateString('id-ID') + ' - ' + 
                new Date(endDate).toLocaleDateString('id-ID');
        }
    }
    
    // Availability checking function
    function checkAvailability() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('hidden_end_date').value;
        const motorId = document.querySelector('input[name="motor_id"]').value;
        
        if (!startDate || !endDate) return;
        
        // Show loading
        const availabilityDiv = document.getElementById('availability-status');
        if (availabilityDiv) {
            availabilityDiv.innerHTML = '<div class="text-info"><i class="bi bi-hourglass-split me-2"></i>Mengecek ketersediaan...</div>';
        }
        
        fetch('{{ route("penyewa.booking.check-availability") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                motor_id: motorId,
                start_date: startDate,
                end_date: endDate
            })
        })
        .then(response => {
            console.log('Response status:', response.status, response.statusText);
            
            if (!response.ok) {
                // Try to get JSON error, fallback to text if it's HTML
                return response.text().then(responseText => {
                    console.log('Error response:', responseText);
                    
                    try {
                        const errorData = JSON.parse(responseText);
                        throw new Error(errorData.message || 'Gagal mengecek ketersediaan');
                    } catch (parseError) {
                        // Response is likely HTML error page
                        if (responseText.includes('<html>')) {
                            throw new Error('Server mengembalikan error page. Periksa console untuk detail.');
                        }
                        throw new Error('Response tidak valid dari server');
                    }
                });
            }
            return response.json();
        })
        .then(data => {
            displayAvailabilityStatus(data);
        })
        .catch(error => {
            console.error('Error:', error);
            if (availabilityDiv) {
                availabilityDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Error:</strong><br>
                        ${error.message}
                    </div>
                `;
            }
        });
    }
    
    function displayAvailabilityStatus(data) {
        const availabilityDiv = document.getElementById('availability-status');
        const submitBtn = document.getElementById('btn-submit');
        
        if (!availabilityDiv) return;
        
        if (data.available) {
            availabilityDiv.innerHTML = `
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>Motor Tersedia!</strong><br>
                    ${data.message}
                </div>
            `;
            submitBtn.disabled = false;
        } else {
            let conflictInfo = '';
            if (data.conflicts && data.conflicts.length > 0) {
                conflictInfo = '<br><small>Sudah disewa: ';
                data.conflicts.forEach((conflict, index) => {
                    if (index > 0) conflictInfo += ', ';
                    conflictInfo += `${conflict.start_date} - ${conflict.end_date}`;
                });
                conflictInfo += '</small>';
            }
            
            availabilityDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle me-2"></i>
                    <strong>Motor Tidak Tersedia!</strong><br>
                    ${data.message}${conflictInfo}
                    ${data.next_available_formatted ? `<br><small class="text-muted">Tersedia kembali: ${data.next_available_formatted}</small>` : ''}
                </div>
            `;
            submitBtn.disabled = true;
        }
    }
    
    // Add event listener for date changes to check availability
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + selectedDuration - 1);
        
        document.getElementById('end_date_display').value = endDate.toISOString().split('T')[0];
        document.getElementById('hidden_start_date').value = this.value;
        document.getElementById('hidden_end_date').value = endDate.toISOString().split('T')[0];
        
        if (this.value) {
            btnNext.classList.remove('d-none');
            // Check availability when date is selected
            setTimeout(checkAvailability, 500); // Delay to ensure end date is set
        }
        
        updateSummary();
    });

    // Add form validation before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        console.log('Form submit triggered');
        
        // Validate required hidden fields
        const motorId = document.getElementById('hidden_motor_id') || document.querySelector('input[name="motor_id"]');
        const startDate = document.getElementById('hidden_start_date');
        const endDate = document.getElementById('hidden_end_date');
        const packageType = document.getElementById('hidden_package_type');
        const csrfToken = document.querySelector('input[name="_token"]');
        
        const validationErrors = [];
        
        if (!motorId || !motorId.value) {
            validationErrors.push('Motor ID tidak ditemukan');
        }
        
        if (!startDate || !startDate.value) {
            validationErrors.push('Tanggal mulai belum dipilih');
        }
        
        if (!endDate || !endDate.value) {
            validationErrors.push('Tanggal selesai belum dihitung');
        }
        
        if (!packageType || !packageType.value) {
            validationErrors.push('Tipe paket belum dipilih');
        }
        
        if (!csrfToken || !csrfToken.value) {
            validationErrors.push('CSRF token tidak ditemukan');
        }
        
        if (validationErrors.length > 0) {
            e.preventDefault();
            console.error('Form validation errors:', validationErrors);
            alert('Error: ' + validationErrors.join(', ') + '\n\nSilakan lengkapi semua langkah booking terlebih dahulu.');
            return false;
        }
        
        console.log('Form validation passed:', {
            motor_id: motorId.value,
            start_date: startDate.value,
            end_date: endDate.value,
            package_type: packageType.value,
            csrf_token: csrfToken.value.substring(0, 10) + '...'
        });
        
        // Show loading state
        const submitBtn = document.getElementById('btn-submit');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Processing...';
            submitBtn.disabled = true;
        }
    });
});
</script>
@endsection