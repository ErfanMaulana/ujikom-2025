/**
 * Admin Motor Verification JavaScript
 * Handle motor detail modal and verification process
 */

console.log('admin-motor-verification.js loaded');

class MotorVerification {
    constructor() {
        console.log('MotorVerification class initialized');
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupCSRFToken();
        console.log('MotorVerification initialized successfully');
    }

    setupEventListeners() {
        // Event delegation for dynamically added elements
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-action="show-detail"]') || e.target.closest('[data-action="show-detail"]')) {
                e.preventDefault();
                console.log('Detail button clicked via event delegation');
                const button = e.target.closest('[data-action="show-detail"]');
                const motorId = button.dataset.motorId;
                this.showMotorDetail(motorId);
            }

            if (e.target.matches('[data-action="verify-motor"]') || e.target.closest('[data-action="verify-motor"]')) {
                e.preventDefault();
                console.log('Verify button clicked via event delegation');
                const button = e.target.closest('[data-action="verify-motor"]');
                const motorId = button.dataset.motorId;
                this.verifyMotor(motorId);
            }
        });
        console.log('Event listeners set up');
    }

    setupCSRFToken() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!this.csrfToken) {
            console.error('CSRF token not found');
        }
    }

    showMotorDetail(motorId) {
        const modal = new bootstrap.Modal(document.getElementById('motorDetailModal'));
        const content = document.getElementById('motorDetailContent');
        
        // Show loading state
        this.showLoadingState(content);
        
        // Show modal
        modal.show();
        
        // Fetch motor detail
        this.fetchMotorDetail(motorId)
            .then(data => {
                this.renderMotorDetail(data, content, motorId);
            })
            .catch(error => {
                console.error('Error fetching motor detail:', error);
                this.showErrorState(content, motorId);
            });
    }

    showLoadingState(content) {
        content.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Memuat detail motor...</p>
            </div>
        `;
    }

    showErrorState(content, motorId) {
        content.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                <h5 class="mt-3 text-danger">Gagal Memuat Detail</h5>
                <p class="text-muted">Terjadi kesalahan saat memuat detail motor.</p>
                <button class="btn btn-outline-primary" onclick="motorVerification.showMotorDetail(${motorId})">
                    <i class="bi bi-arrow-clockwise me-2"></i>Coba Lagi
                </button>
            </div>
        `;
    }

    async fetchMotorDetail(motorId) {
        console.log('Fetching motor detail for ID:', motorId);
        
        const response = await fetch(`/admin/motors/${motorId}/ajax`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Error response:', errorText);
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }
        
        const data = await response.json();
        console.log('Motor detail data received:', data);
        return data;
    }

    renderMotorDetail(data, content, motorId) {
        const motor = data.motor;
        const stats = data.stats;
        
        // Update verify button in modal footer
        this.updateVerifyButton(motor, motorId);
        
        // Render content
        content.innerHTML = this.buildMotorDetailHTML(motor, stats);
    }

    updateVerifyButton(motor, motorId) {
        const verifyBtn = document.getElementById('verifyMotorFromModal');
        if (motor.status === 'pending_verification') {
            verifyBtn.style.display = 'inline-block';
            verifyBtn.onclick = () => {
                // Hide detail modal first
                const detailModal = bootstrap.Modal.getInstance(document.getElementById('motorDetailModal'));
                detailModal.hide();
                // Then show verification
                this.verifyMotor(motorId);
            };
        } else {
            verifyBtn.style.display = 'none';
        }
    }

    buildMotorDetailHTML(motor, stats) {
        const photoUrl = motor.photo ? `/storage/${motor.photo}` : null;
        const statusConfig = this.getStatusConfig();
        
        return `
            <div class="row">
                <!-- Motor Photo -->
                <div class="col-md-6">
                    <div class="motor-photo mb-4">
                        ${photoUrl ? 
                            `<img src="${photoUrl}" class="img-fluid rounded shadow" alt="${motor.brand}" style="width: 100%; height: 400px; object-fit: cover;">` :
                            `<div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" style="height: 400px;">
                                <i class="bi bi-motorcycle text-muted" style="font-size: 5rem;"></i>
                            </div>`
                        }
                    </div>
                </div>
                
                <!-- Motor Info -->
                <div class="col-md-6">
                    <div class="motor-info">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h3 class="mb-0">${motor.brand}</h3>
                            <span class="badge bg-${statusConfig[motor.status].class} fs-6">
                                <i class="bi ${statusConfig[motor.status].icon} me-1"></i>
                                ${statusConfig[motor.status].text}
                            </span>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="info-item p-3 bg-light rounded">
                                    <i class="bi bi-gear text-primary me-2 fs-5"></i>
                                    <div>
                                        <strong>Kapasitas Mesin</strong>
                                        <div class="text-primary fw-bold">${motor.type_cc}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-item p-3 bg-light rounded">
                                    <i class="bi bi-credit-card text-primary me-2 fs-5"></i>
                                    <div>
                                        <strong>Nomor Plat</strong>
                                        <div class="text-primary fw-bold">${motor.plate_number}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Owner Info -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="bi bi-person-circle text-primary me-2"></i>Informasi Pemilik
                                </h6>
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person text-muted me-2"></i>
                                            <strong class="me-2">Nama:</strong> 
                                            <span>${motor.owner.name}</span>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-envelope text-muted me-2"></i>
                                            <strong class="me-2">Email:</strong> 
                                            <span>${motor.owner.email}</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-telephone text-muted me-2"></i>
                                            <strong class="me-2">Telepon:</strong> 
                                            <span>${motor.owner.phone || 'Tidak tersedia'}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        ${motor.description ? `
                            <div class="info-group mb-4">
                                <h6 class="mb-3">
                                    <i class="bi bi-chat-text text-primary me-2"></i>Deskripsi Motor
                                </h6>
                                <div class="border rounded p-3 bg-light">
                                    ${motor.description}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
            
            ${this.buildRentalRatesSection(motor)}
            ${this.buildStatisticsSection(stats)}
            ${this.buildRegistrationInfoSection(motor)}
        `;
    }

    buildRentalRatesSection(motor) {
        if (!motor.rental_rate) return '';
        
        return `
            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="mb-3">
                        <i class="bi bi-currency-dollar text-primary me-2"></i>Tarif Sewa
                    </h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-4 border rounded bg-gradient" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <i class="bi bi-calendar-day text-primary fs-2 mb-2"></i>
                                <div class="fw-bold fs-4 text-primary">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.daily_rate)}</div>
                                <small class="text-muted fw-semibold">Per Hari</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-4 border rounded bg-gradient" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <i class="bi bi-calendar-week text-primary fs-2 mb-2"></i>
                                <div class="fw-bold fs-4 text-primary">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.weekly_rate)}</div>
                                <small class="text-muted fw-semibold">Per Minggu</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-4 border rounded bg-gradient" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <i class="bi bi-calendar-month text-primary fs-2 mb-2"></i>
                                <div class="fw-bold fs-4 text-primary">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.monthly_rate)}</div>
                                <small class="text-muted fw-semibold">Per Bulan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    buildStatisticsSection(stats) {
        return `
            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="mb-3">
                        <i class="bi bi-graph-up text-primary me-2"></i>Statistik Motor
                    </h6>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                                <div class="card-body text-center text-white">
                                    <i class="bi bi-calendar-check fs-1 mb-2"></i>
                                    <div class="fs-2 fw-bold">${stats.total_bookings}</div>
                                    <small class="opacity-75">Total Pesanan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                                <div class="card-body text-center text-white">
                                    <i class="bi bi-clock fs-1 mb-2"></i>
                                    <div class="fs-2 fw-bold">${stats.active_bookings}</div>
                                    <small class="opacity-75">Pesanan Aktif</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                                <div class="card-body text-center text-white">
                                    <i class="bi bi-check-circle fs-1 mb-2"></i>
                                    <div class="fs-2 fw-bold">${stats.completed_bookings}</div>
                                    <small class="opacity-75">Selesai</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                                <div class="card-body text-center text-white">
                                    <i class="bi bi-currency-dollar fs-1 mb-2"></i>
                                    <div class="fs-5 fw-bold">Rp ${new Intl.NumberFormat('id-ID').format(stats.total_earnings)}</div>
                                    <small class="opacity-75">Total Pendapatan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    buildRegistrationInfoSection(motor) {
        return `
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-info border-0 shadow-sm">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle fs-4 me-3 mt-1"></i>
                            <div>
                                <h6 class="alert-heading">Informasi Pendaftaran</h6>
                                <p class="mb-1">
                                    Motor ini didaftarkan pada <strong>${new Date(motor.created_at).toLocaleDateString('id-ID', {
                                        year: 'numeric',
                                        month: 'long', 
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}</strong>
                                </p>
                                ${motor.verified_at ? `
                                    <p class="mb-0">
                                        <i class="bi bi-check-circle text-success me-1"></i>
                                        Telah diverifikasi pada <strong>${new Date(motor.verified_at).toLocaleDateString('id-ID', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        })}</strong>
                                    </p>
                                ` : `
                                    <p class="mb-0">
                                        <i class="bi bi-clock text-warning me-1"></i>
                                        <strong>Status:</strong> Menunggu verifikasi admin
                                    </p>
                                `}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    getStatusConfig() {
        return {
            'pending_verification': {
                class: 'warning',
                text: 'Menunggu Verifikasi',
                icon: 'bi-clock'
            },
            'available': {
                class: 'success',
                text: 'Tersedia',
                icon: 'bi-check-circle'
            },
            'rented': {
                class: 'info',
                text: 'Disewa',
                icon: 'bi-person-check'
            },
            'maintenance': {
                class: 'secondary',
                text: 'Maintenance',
                icon: 'bi-tools'
            }
        };
    }

    verifyMotor(motorId) {
        // Show pricing modal for verification
        this.showPricingModal(motorId);
    }

    showPricingModal(motorId) {
        const modalHTML = `
            <div class="modal fade" id="pricingModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-check-circle me-2"></i>Verifikasi Motor & Set Harga Sewa
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="verifyPricingForm" action="/admin/motors/${motorId}/verify" method="POST">
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Perhatian:</strong> Setelah motor diverifikasi dan harga ditetapkan, motor akan tersedia untuk disewa oleh penyewa.
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="daily_rate" class="form-label">Tarif Harian <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="daily_rate" 
                                                   name="daily_rate" 
                                                   min="10000"
                                                   max="1000000"
                                                   step="1000"
                                                   placeholder="450000"
                                                   required>
                                        </div>
                                        <div class="form-text">Minimal Rp 10.000 - Maksimal Rp 1.000.000</div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="weekly_rate" class="form-label">Tarif Mingguan</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" 
                                                   class="form-control bg-light" 
                                                   id="weekly_rate" 
                                                   name="weekly_rate"
                                                   readonly
                                                   placeholder="Otomatis dengan diskon 10%">
                                        </div>
                                        <div class="form-text text-success">Auto: diskon 10% dari 7x tarif harian</div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="monthly_rate" class="form-label">Tarif Bulanan</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" 
                                                   class="form-control bg-light" 
                                                   id="monthly_rate" 
                                                   name="monthly_rate"
                                                   readonly
                                                   placeholder="Otomatis dengan diskon 20%">
                                        </div>
                                        <div class="form-text text-success">Auto: diskon 20% dari 30x tarif harian</div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <i class="bi bi-lightbulb text-warning me-2"></i>Tips Penetapan Harga
                                                </h6>
                                                <ul class="mb-0 small">
                                                    <li>Motor 100cc-125cc: Rp 50.000 - 80.000/hari</li>
                                                    <li>Motor 150cc: Rp 80.000 - 120.000/hari</li>
                                                    <li>Motor 250cc+: Rp 120.000 - 200.000/hari</li>
                                                    <li>Pertimbangkan kondisi, umur, dan brand motor</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-2"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-2"></i>Verifikasi & Set Harga
                                </button>
                            </div>
                            
                            <input type="hidden" name="_token" value="${this.csrfToken}">
                            <input type="hidden" name="_method" value="PATCH">
                        </form>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        const existingModal = document.getElementById('pricingModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Setup auto calculation
        this.setupPricingCalculation();
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('pricingModal'));
        modal.show();
        
        // Remove modal from DOM after hide
        modal._element.addEventListener('hidden.bs.modal', () => {
            modal._element.remove();
        });
    }

    setupPricingCalculation() {
        const dailyInput = document.getElementById('daily_rate');
        const weeklyInput = document.getElementById('weekly_rate');
        const monthlyInput = document.getElementById('monthly_rate');
        
        if (!dailyInput || !weeklyInput || !monthlyInput) {
            console.error('Pricing inputs not found');
            return;
        }
        
        function calculateRates() {
            const dailyRate = parseInt(dailyInput.value) || 0;
            
            if (dailyRate > 0) {
                // Validasi maksimal Rp 1.000.000 per hari
                if (dailyRate > 1000000) {
                    dailyInput.value = 1000000;
                    alert('Maksimal harga harian adalah Rp 1.000.000');
                    return;
                }
                
                // Kalkulasi otomatis tarif mingguan (diskon 10%)
                const weeklyRate = Math.floor(dailyRate * 7 * 0.9);
                weeklyInput.value = weeklyRate;
                
                // Kalkulasi otomatis tarif bulanan (diskon 20%)
                const monthlyRate = Math.floor(dailyRate * 30 * 0.8);
                monthlyInput.value = monthlyRate;
                
                console.log('Auto calculated:', {
                    daily: dailyRate,
                    weekly: weeklyRate,
                    monthly: monthlyRate
                });
            } else {
                // Reset jika tarif harian kosong
                weeklyInput.value = '';
                monthlyInput.value = '';
            }
        }
        
        // Event listener untuk input tarif harian
        dailyInput.addEventListener('input', calculateRates);
        dailyInput.addEventListener('change', calculateRates);
        dailyInput.addEventListener('keyup', calculateRates);
        
        // Format angka dengan pemisah ribuan saat blur
        dailyInput.addEventListener('blur', function() {
            if (this.value) {
                const value = parseInt(this.value.replace(/\./g, ''));
                this.value = value;
                calculateRates();
            }
        });
        
        // Mencegah input manual di tarif mingguan dan bulanan
        weeklyInput.addEventListener('focus', function() {
            this.blur();
            dailyInput.focus();
        });
        
        monthlyInput.addEventListener('focus', function() {
            this.blur();
            dailyInput.focus();
        });
        
        // Set placeholder yang jelas
        weeklyInput.setAttribute('placeholder', 'Otomatis dengan diskon 10%');
        monthlyInput.setAttribute('placeholder', 'Otomatis dengan diskon 20%');
        weeklyInput.setAttribute('readonly', 'true');
        monthlyInput.setAttribute('readonly', 'true');
        weeklyInput.classList.add('bg-light');
        monthlyInput.classList.add('bg-light');
    }

    showVerificationError() {
        // Hide loading modal
        const loadingModal = bootstrap.Modal.getInstance(document.getElementById('verifyLoadingModal'));
        if (loadingModal) {
            loadingModal.hide();
            document.getElementById('verifyLoadingModal').remove();
        }

        const errorHTML = `
            <div class="modal fade" id="verifyErrorModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-body text-center p-5">
                            <div class="mx-auto mb-4 d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border-radius: 50%;">
                                <i class="bi bi-exclamation-triangle text-white" style="font-size: 2.5rem;"></i>
                            </div>
                            <h4 class="text-danger mb-3">Verifikasi Gagal!</h4>
                            <p class="text-muted mb-4">
                                Terjadi kesalahan saat memverifikasi motor. Silakan coba lagi.
                            </p>
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-danger btn-lg" data-bs-dismiss="modal">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Coba Lagi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', errorHTML);
        const modal = new bootstrap.Modal(document.getElementById('verifyErrorModal'));
        modal.show();

        // Remove modal from DOM after hide
        modal._element.addEventListener('hidden.bs.modal', () => {
            modal._element.remove();
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - initializing motor verification');
    window.motorVerification = new MotorVerification();
});

// Legacy function support for inline onclick handlers - Make these available immediately
window.showMotorDetail = function(motorId) {
    console.log('showMotorDetail called for motor:', motorId);
    if (window.motorVerification) {
        window.motorVerification.showMotorDetail(motorId);
    } else {
        console.log('motorVerification not ready, initializing...');
        // Initialize if not ready
        window.motorVerification = new MotorVerification();
        window.motorVerification.showMotorDetail(motorId);
    }
};

window.directVerifyMotor = function(motorId) {
    console.log('directVerifyMotor called for motor:', motorId);
    if (window.motorVerification) {
        window.motorVerification.verifyMotor(motorId);
    } else {
        console.log('motorVerification not ready, using fallback...');
        // Use pricing modal fallback
        showPricingModalFallback(motorId);
    }
};

function showPricingModalFallback(motorId) {
    const modalHTML = `
        <div class="modal fade" id="pricingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-check-circle me-2"></i>Verifikasi Motor & Set Harga Sewa
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="/admin/motors/${motorId}/verify" method="POST">
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Perhatian:</strong> Setelah motor diverifikasi dan harga ditetapkan, motor akan tersedia untuk disewa oleh penyewa.
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="daily_rate" class="form-label">Tarif Harian <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" 
                                               class="form-control" 
                                               id="daily_rate" 
                                               name="daily_rate" 
                                               min="10000"
                                               step="1000"
                                               placeholder="150000"
                                               required>
                                    </div>
                                    <div class="form-text">Minimal Rp 10.000</div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="weekly_rate" class="form-label">Tarif Mingguan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" 
                                               class="form-control" 
                                               id="weekly_rate" 
                                               name="weekly_rate"
                                               min="50000"
                                               step="1000"
                                               placeholder="900000">
                                    </div>
                                    <div class="form-text">Auto: diskon 10% dari 7x tarif harian</div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="monthly_rate" class="form-label">Tarif Bulanan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" 
                                               class="form-control" 
                                               id="monthly_rate" 
                                               name="monthly_rate"
                                               min="200000"
                                               step="1000"
                                               placeholder="3000000">
                                    </div>
                                    <div class="form-text">Auto: diskon 20% dari 30x tarif harian</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>Verifikasi & Set Harga
                            </button>
                        </div>
                        
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                        <input type="hidden" name="_method" value="PATCH">
                    </form>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('pricingModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Setup auto calculation
    const dailyInput = document.getElementById('daily_rate');
    const weeklyInput = document.getElementById('weekly_rate');
    const monthlyInput = document.getElementById('monthly_rate');
    
    function calculateRates() {
        const dailyRate = parseInt(dailyInput.value) || 0;
        
        if (dailyRate > 0) {
            // Validasi maksimal Rp 1.000.000 per hari
            if (dailyRate > 1000000) {
                dailyInput.value = 1000000;
                alert('Maksimal harga harian adalah Rp 1.000.000');
                return;
            }
            
            // Kalkulasi otomatis dengan sistem diskon
            weeklyInput.value = Math.floor(dailyRate * 7 * 0.9); // 10% discount for weekly
            monthlyInput.value = Math.floor(dailyRate * 30 * 0.8); // 20% discount for monthly
        } else {
            weeklyInput.value = '';
            monthlyInput.value = '';
        }
    }
    
    dailyInput.addEventListener('input', calculateRates);
    dailyInput.addEventListener('change', calculateRates);
    
    // Make weekly and monthly readonly
    weeklyInput.setAttribute('readonly', 'true');
    monthlyInput.setAttribute('readonly', 'true');
    weeklyInput.classList.add('bg-light');
    monthlyInput.classList.add('bg-light');
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('pricingModal'));
    modal.show();
    
    // Remove modal from DOM after hide
    modal._element.addEventListener('hidden.bs.modal', () => {
        modal._element.remove();
    });
}