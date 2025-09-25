<div class="alert alert-danger" role="alert">
    <h6 class="alert-heading">
        <i class="bi bi-exclamation-triangle me-2"></i>Hapus Akun Permanen
    </h6>
    <p class="mb-3">
        Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. 
        Sebelum menghapus akun, silakan unduh data atau informasi yang ingin Anda simpan.
    </p>
    <hr>
    <div class="d-flex justify-content-between align-items-center">
        <small class="text-muted">Tindakan ini tidak dapat dibatalkan</small>
        <button type="button" 
                class="btn btn-danger btn-sm" 
                data-bs-toggle="modal" 
                data-bs-target="#deleteAccountModal">
            Hapus Akun
        </button>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>Konfirmasi Hapus Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        <strong>Apakah Anda yakin ingin menghapus akun Anda?</strong>
                    </div>
                    
                    <p class="text-muted mb-3">
                        Masukkan password Anda untuk mengkonfirmasi bahwa Anda ingin menghapus akun secara permanen.
                    </p>

                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Password</label>
                        <input type="password" 
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                               id="delete_password" 
                               name="password" 
                               placeholder="Masukkan password Anda"
                               required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new bootstrap.Modal(document.getElementById('deleteAccountModal')).show();
        });
    </script>
@endif
