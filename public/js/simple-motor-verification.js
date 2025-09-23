// Simple test motor verification
console.log('Simple motor verification loaded');

// Helper functions for status badge
function getStatusBadgeClass(status) {
    switch(status) {
        case 'pending_verification': return 'bg-warning';
        case 'available': return 'bg-success';
        case 'rented': return 'bg-info';
        case 'maintenance': return 'bg-secondary';
        default: return 'bg-secondary';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'pending_verification': return 'Menunggu Verifikasi';
        case 'available': return 'Tersedia';
        case 'rented': return 'Disewa';
        case 'maintenance': return 'Maintenance';
        default: return status;
    }
}

function showMotorDetail(motorId) {
    console.log('showMotorDetail called with ID:', motorId);
    
    // Get modal
    const modal = new bootstrap.Modal(document.getElementById('motorDetailModal'));
    const content = document.getElementById('motorDetailContent');
    
    // Show loading
    content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Memuat detail motor...</p>
        </div>
    `;
    
    // Show modal
    modal.show();
    
    // Fetch data
    fetch(`/admin/motors/${motorId}/ajax`)
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            console.log('Response ok:', response.ok);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error(`Expected JSON but got ${contentType}`);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Motor data received:', data);
            
            const motor = data.motor;
            const photoUrl = motor.photo ? `/storage/${motor.photo}` : null;
            
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        ${photoUrl ? 
                            `<img src="${photoUrl}" class="img-fluid rounded" alt="${motor.brand} ${motor.model || ''}" style="height: 300px; width: 100%; object-fit: cover;">` :
                            `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                <i class="bi bi-motorcycle text-muted" style="font-size: 4rem;"></i>
                            </div>`
                        }
                    </div>
                    <div class="col-md-6">
                        <h3>${motor.brand} ${motor.model || ''}</h3>
                        <div class="mb-3">
                            <span class="badge bg-primary me-2">${motor.type_cc}</span>
                            <span class="badge ${getStatusBadgeClass(motor.status)}">${getStatusText(motor.status)}</span>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Tahun:</strong><br>
                                <span class="text-muted">${motor.year || '-'}</span>
                            </div>
                            <div class="col-6">
                                <strong>Warna:</strong><br>
                                <span class="text-muted">${motor.color || '-'}</span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Plat Nomor:</strong><br>
                                <span class="text-muted">${motor.plate_number}</span>
                            </div>
                            <div class="col-6">
                                <strong>Pemilik:</strong><br>
                                <span class="text-muted">${motor.owner.name}</span>
                            </div>
                        </div>
                        
                        ${motor.description ? `
                            <div class="mb-3">
                                <strong>Deskripsi:</strong><br>
                                <p class="text-muted mb-0">${motor.description}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                ${motor.rental_rate ? `
                    <div class="mt-4">
                        <h5><i class="bi bi-currency-dollar me-2"></i>Harga Sewa</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Harian</h6>
                                        <div class="h5 text-primary">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.daily_rate)}</div>
                                        <small class="text-muted">per hari</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Mingguan</h6>
                                        <div class="h5 text-primary">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.weekly_rate)}</div>
                                        <small class="text-muted">per minggu</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Bulanan</h6>
                                        <div class="h5 text-primary">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.monthly_rate)}</div>
                                        <small class="text-muted">per bulan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ` : `
                    <div class="mt-4">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Harga sewa belum ditetapkan untuk motor ini.
                        </div>
                    </div>
                `}
                
                ${data.stats ? `
                    <div class="mt-4">
                        <h5><i class="bi bi-bar-chart me-2"></i>Statistik Motor</h5>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h4>${data.stats.total_bookings}</h4>
                                        <small>Total Booking</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h4>${data.stats.active_bookings}</h4>
                                        <small>Aktif</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h4>${data.stats.completed_bookings}</h4>
                                        <small>Selesai</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h4>Rp ${new Intl.NumberFormat('id-ID').format(data.stats.total_earnings)}</h4>
                                        <small>Total Earnings</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ` : ''}
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-danger">Gagal Memuat Detail</h5>
                    <p class="text-muted">Error: ${error.message}</p>
                    <button class="btn btn-primary" onclick="showMotorDetail(${motorId})">Coba Lagi</button>
                </div>
            `;
        });
}

function directVerifyMotor(motorId) {
    console.log('directVerifyMotor called with ID:', motorId);
    
    // Show pricing modal instead of simple confirm
    showPricingModal(motorId);
}

function showPricingModal(motorId) {
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
                                               placeholder="Otomatis dihitung">
                                    </div>
                                    <div class="form-text text-success">Auto: 6x tarif harian</div>
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
                                               placeholder="Otomatis dihitung">
                                    </div>
                                    <div class="form-text text-success">Auto: 20x tarif harian</div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-lightbulb text-warning me-2"></i>Tips Penetapan Harga
                                            </h6>
                                            <ul class="mb-2 small">
                                                <li>Motor 100cc-125cc: Rp 50.000 - 80.000/hari</li>
                                                <li>Motor 150cc: Rp 80.000 - 120.000/hari</li>
                                                <li>Motor 250cc+: Rp 120.000 - 200.000/hari</li>
                                                <li>Motor Premium/Sport: Rp 300.000 - 1.000.000/hari</li>
                                                <li>Pertimbangkan kondisi, umur, dan brand motor</li>
                                            </ul>
                                            <div class="alert alert-warning py-2 mb-0">
                                                <small><strong>Catatan:</strong> Tarif mingguan dan bulanan akan otomatis dihitung berdasarkan tarif harian (6x untuk mingguan, 20x untuk bulanan).</small>
                                            </div>
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
    setupPricingCalculation();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('pricingModal'));
    modal.show();
    
    // Remove modal from DOM after hide
    modal._element.addEventListener('hidden.bs.modal', () => {
        modal._element.remove();
    });
}

function setupPricingCalculation() {
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
            
            // Kalkulasi otomatis tarif mingguan (6x tarif harian)
            const weeklyRate = dailyRate * 6;
            weeklyInput.value = weeklyRate;
            
            // Kalkulasi otomatis tarif bulanan (20x tarif harian)
            const monthlyRate = dailyRate * 20;
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
    weeklyInput.setAttribute('placeholder', 'Otomatis dari tarif harian');
    monthlyInput.setAttribute('placeholder', 'Otomatis dari tarif harian');
    weeklyInput.setAttribute('readonly', 'true');
    monthlyInput.setAttribute('readonly', 'true');
}

// Test if bootstrap is available
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
    console.log('CSRF token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
});