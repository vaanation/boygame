@extends('layouts.app')
@section('title', 'Top Up Koin')
@section('content')
<div class="container mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Top Up Koin eFootball</h2>
        <p class="text-muted">Pilih paket koin yang sesuai dengan kebutuhan Anda</p>
    </div>
    
    <div class="row g-3 g-md-4 justify-content-center">
        @forelse($packages as $pkg)
        <div class="col-6 col-md-4 col-lg-3 mb-2 mb-md-4">
            <div class="modern-card h-100 text-center p-3 p-md-4 d-flex flex-column">
                @if($pkg->image_path)
                    <img src="{{ asset('storage/'.$pkg->image_path) }}" class="img-fluid mb-2 mb-md-3 mx-auto" style="height: 60px; object-fit:contain;">
                @else
                    <i class="fas fa-coins fa-3x fa-md-5x text-warning mb-2 mb-md-3"></i>
                @endif
                <h6 class="fw-bold mb-1" style="font-size: 0.95rem;">{{ $pkg->name }}</h6>
                <div class="text-muted mb-2 mb-md-3" style="font-size: 0.8rem;">{{ number_format($pkg->coin_amount, 0, ',', '.') }} Koin</div>
                <h6 class="text-primary-custom fw-bold mb-3 mb-md-4" style="font-size: 1.1rem;">Rp {{ number_format($pkg->price, 0, ',', '.') }}</h6>
                @if($pkg->description)
                    <p class="small text-muted d-none d-md-block">{{ $pkg->description }}</p>
                @endif
                
                @php
                    $waNumber = $pkg->whatsapp_number ?? (\App\Models\Setting::where('key', 'whatsapp_number')->first()->value ?? '628xxx');
                    $waText = "Halo Admin BOYGAME.%0ASaya ingin membeli paket top up berikut:%0APaket: {$pkg->name}%0AJumlah Koin: {$pkg->coin_amount}%0AHarga: Rp " . number_format($pkg->price, 0, ',', '.') . "%0AMohon informasi lebih lanjut.%0ATerima kasih.";
                @endphp
                <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" class="btn btn-success w-100 rounded-3 mt-auto" style="font-size: 0.85rem; padding: 6px;">
                    <i class="fab fa-whatsapp"></i> Beli
                </a>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5 my-5">
            <div class="mb-4">
                <i class="fas fa-coins fa-5x text-secondary opacity-50"></i>
            </div>
            <h4 class="text-muted fw-bold">Paket top up belum tersedia</h4>
            <p class="text-muted">Silakan cek kembali nanti atau hubungi admin.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection