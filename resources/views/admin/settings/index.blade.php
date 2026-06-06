@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Pengaturan Umum -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="fas fa-cog me-2 text-primary"></i> Pengaturan Umum</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Website</label>
                            <input type="text" name="website_name" class="form-control" value="{{ $settings['website_name'] ?? 'BOYGAME' }}" placeholder="Contoh: BOYGAME Marketplace">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Nomor WhatsApp Utama</label>
                            <input type="text" name="whatsapp_number" class="form-control" value="{{ $settings['whatsapp_number'] ?? '' }}" placeholder="Format: 628xxx">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Username Instagram</label>
                            <input type="text" name="instagram_username" class="form-control" value="{{ $settings['instagram_username'] ?? '' }}" placeholder="Contoh: boygame_id">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengaturan SEO -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="fas fa-search me-2 text-success"></i> Pengaturan SEO (Search Engine Optimization)</h5>
                    <p class="text-muted small">Atur meta tag agar website Anda muncul di halaman pertama Google.</p>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Meta Title (Judul Tab & Pencarian Google)</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ $settings['meta_title'] ?? 'BOYGAME - Jual Beli Akun eFootball Terpercaya' }}" placeholder="Sangat direkomendasikan maksimal 60 karakter">
                            <small class="text-muted">Ini adalah judul utama yang muncul besar berwarna biru di hasil pencarian Google.</small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Meta Keywords (Kata Kunci)</label>
                            <input type="text" name="meta_keywords" class="form-control" value="{{ $settings['meta_keywords'] ?? 'jual akun efootball, beli akun efootball, akun pes, efootball murah, marketplace efootball' }}" placeholder="Pisahkan dengan koma (,)">
                            <small class="text-muted">Contoh: jual akun efootball, top up koin efootball, akun efootball sultan murah</small>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-bold">Meta Description (Deskripsi Singkat di Google)</label>
                            <textarea name="meta_description" class="form-control" rows="3" placeholder="Sangat direkomendasikan sekitar 150-160 karakter">{{ $settings['meta_description'] ?? 'BOYGAME adalah platform jual beli akun eFootball terpercaya dan termurah. Tersedia ratusan akun eFootball spek sultan dengan harga terjangkau.' }}</textarea>
                            <small class="text-muted">Teks ini akan muncul di bawah judul biru pada hasil pencarian Google. Buat semenarik mungkin agar orang mau klik!</small>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Meta Author</label>
                            <input type="text" name="meta_author" class="form-control" value="{{ $settings['meta_author'] ?? 'BOYGAME Team' }}">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold w-100 shadow-sm rounded-pill"><i class="fas fa-save me-2"></i> Simpan Semua Pengaturan</button>
        </form>
    </div>
</div>
@endsection