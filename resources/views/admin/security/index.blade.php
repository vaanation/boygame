@extends('admin.layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 text-center">
                <div class="bg-primary-custom text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 70px; height: 70px;">
                    <i class="fas fa-shield-alt fs-2"></i>
                </div>
                <h5 class="fw-bold mb-1">Keamanan Akun</h5>
                <p class="text-muted small">Kelola email dan ubah password akun admin Anda.</p>
            </div>
            
            <div class="card-body p-4 p-md-5 pt-3">
                <form action="{{ route('admin.security.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Email Login</label>
                        <div class="input-group shadow-sm rounded-3 overflow-hidden border">
                            <span class="input-group-text bg-light border-0 text-muted"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control border-0 bg-light py-2 shadow-none" value="{{ old('email', $admin->email) }}" required>
                        </div>
                        @error('email')
                            <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                        @enderror
                    </div>

                    <hr class="my-4 border-light">
                    
                    <h6 class="fw-bold mb-3"><i class="fas fa-key text-warning me-2"></i> Ubah Password <span class="text-muted fw-normal small">(Opsional)</span></h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control py-2 shadow-sm" placeholder="Masukkan password lama Anda">
                        @error('current_password')
                            <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold text-muted small">Password Baru</label>
                            <input type="password" name="password" class="form-control py-2 shadow-sm" placeholder="Minimal 6 karakter">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold text-muted small">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control py-2 shadow-sm" placeholder="Ulangi password baru">
                        </div>
                        @error('password')
                            <div class="col-12 mt-1">
                                <small class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-3 rounded-pill shadow-sm mt-2">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
