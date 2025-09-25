@extends('layouts.fann')

@section('title', 'Test Delete Motor Function')

@section('content')
<div class="content-header">
    <h1>Test Delete Motor Function</h1>
    <p>Testing motor deletion functionality with proper safety checks</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-trash me-2"></i>Delete Motor Test</h5>
            </div>
            <div class="card-body">
                <!-- Motor 18 - Safe to Delete -->
                <div class="card mb-3 border-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-success">Motor ID: 18 - Honda Beat (SAFE TO DELETE)</h6>
                                <p class="mb-1"><strong>Plat:</strong> Z B34T 1</p>
                                <p class="mb-1"><strong>CC:</strong> 125cc</p>
                                <p class="mb-1"><strong>Year:</strong> 2024</p>
                                <p class="mb-1"><strong>Color:</strong> Hitam</p>
                                <p class="mb-1"><strong>Owner:</strong> User ID 13 (Eka)</p>
                                <p class="mb-1"><strong>Active Bookings:</strong> 0 (Safe to delete)</p>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-danger btn-sm" onclick="deleteMotor(18, 'Honda Beat Z B34T 1')">
                                    <i class="bi bi-trash me-2"></i>Test Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Motor 11 - Check Before Delete -->
                <div class="card mb-3 border-warning">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-warning">Motor ID: 11 - Yamaha R25 (CHECK BOOKINGS)</h6>
                                <p class="mb-1"><strong>Plat:</strong> Z 123 EKA</p>
                                <p class="mb-1"><strong>CC:</strong> 250cc</p>
                                <p class="mb-1"><strong>Owner:</strong> User ID 13 (Eka)</p>
                                <p class="mb-1"><strong>Status:</strong> Need to check active bookings</p>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-warning btn-sm" onclick="deleteMotor(11, 'Yamaha R25 Z 123 EKA')">
                                    <i class="bi bi-trash me-2"></i>Test Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Delete Function Features</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Verification Check</span>
                        <span class="badge bg-success">✓</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Active Booking Check</span>
                        <span class="badge bg-success">✓</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Photo Deletion</span>
                        <span class="badge bg-success">✓</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Document Deletion</span>
                        <span class="badge bg-success">✓</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Rental Rate Deletion</span>
                        <span class="badge bg-success">✓</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Confirmation Modal</span>
                        <span class="badge bg-success">✓</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Loading State</span>
                        <span class="badge bg-success">✓</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Success/Error Messages</span>
                        <span class="badge bg-success">✓</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h6>Safety Checks</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-shield-check me-2"></i>Built-in Protections:</h6>
                    <ul class="mb-0">
                        <li>Only verified users can delete</li>
                        <li>Only motor owners can delete</li>
                        <li>Cannot delete with active bookings</li>
                        <li>Files are properly cleaned up</li>
                        <li>Database relationships handled</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal (Copy from motors.blade.php) -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center">Apakah Anda yakin ingin menghapus motor <strong id="motorName"></strong>?</p>
                <div class="alert alert-warning">
                    <h6><i class="bi bi-info-circle me-2"></i>Yang akan dihapus:</h6>
                    <ul class="mb-0">
                        <li>Data motor dan informasinya</li>
                        <li>Foto dan dokumen motor</li>
                        <li>Tarif sewa yang telah ditetapkan</li>
                        <li>Riwayat booking (data booking akan diarsipkan)</li>
                    </ul>
                </div>
                <p class="text-danger text-center"><strong>Tindakan ini tidak dapat dibatalkan!</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Motor</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteMotor(motorId, motorName) {
    console.log('Delete motor called with ID:', motorId, 'Name:', motorName);
    
    const form = document.getElementById('deleteForm');
    const deleteUrl = `{{ url('pemilik/motors') }}/${motorId}`;
    
    console.log('Setting form action to:', deleteUrl);
    form.action = deleteUrl;
    
    // Update motor name in modal
    document.getElementById('motorName').textContent = motorName;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Add form submission handler with loading state
document.addEventListener('DOMContentLoaded', function() {
    const deleteForm = document.getElementById('deleteForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...';
                submitBtn.disabled = true;
            }
        });
    }
});
</script>
@endpush
@endsection