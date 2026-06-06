@extends('layouts.app')
@section('content')

<div class="container mt-4">
    <div id="heroCarousel" class="carousel slide overflow-hidden shadow-sm mb-5 rounded-4" data-bs-ride="carousel">
        <div class="carousel-inner skeleton-wrapper">
            <div class="skeleton skel-img w-100" style="aspect-ratio: 21/9; height: auto;"></div>
        </div>
        <div class="carousel-inner real-content d-none">
            @forelse($banners as $index => $banner)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/'.$banner->image_path) }}" class="d-block w-100" alt="Banner" style="aspect-ratio: 21/9; object-fit: cover; height: auto;">
            </div>
            @empty
            <div class="carousel-item active">
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center w-100" style="aspect-ratio: 21/9; height: auto;">
                    <h2>Welcome to BOYGAME</h2>
                </div>
            </div>
            @endforelse
        </div>
    </div>
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">Akun Terbaru</h4>
            <a href="{{ route('accounts.index') }}" class="text-decoration-none fw-bold text-secondary-custom">Lihat Semua</a>
        </div>
        <div class="row g-2 g-md-4">
            @forelse($latestAccounts as $account)
            <div class="col-6 col-md-3 mb-3 mb-md-4">
                <div class="card modern-card h-100 border-0">
                    <div class="position-relative">
                        <img src="{{ $account->images->first() ? asset('storage/'.$account->images->first()->image_path) : 'https://via.placeholder.com/400x500' }}" class="card-img-top w-100" alt="Image" style="aspect-ratio: 4/5; object-fit:cover;">
                        @if($account->status == 'Sold')
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5);">
                                <span class="badge bg-danger fs-6 px-2 py-1 rounded-pill shadow">SOLD OUT</span>
                            </div>
                        @endif
                        <span class="badge bg-primary position-absolute top-0 end-0 m-2 m-md-3 shadow-sm rounded-pill px-2 py-1 px-md-3 py-md-2" style="font-size: 0.75rem;">{{ $account->category->name ?? 'Premium' }}</span>
                    </div>
                    <div class="card-body p-2 p-md-4 d-flex flex-column">
                        <h6 class="card-title text-truncate fw-bold mb-1" style="font-size: 0.95rem;">{{ $account->title }}</h6>
                        <div class="mb-2 mt-auto">
                            <h6 class="text-primary-custom fw-bold mb-0">Rp {{ number_format($account->price, 0, ',', '.') }}</h6>
                        </div>
                        @if($account->status != 'Sold')
                            <a href="{{ route('accounts.show', $account->slug) }}" class="btn btn-primary-custom w-100 fw-bold shadow-sm" style="font-size: 0.85rem; padding: 6px;">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
                        @else
                            <button class="btn btn-secondary w-100 fw-bold shadow-sm" style="font-size: 0.85rem; padding: 6px;" disabled>Terjual</button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-box-open fa-4x text-secondary opacity-50"></i>
                </div>
                <h5 class="text-muted fw-bold">Belum ada akun terbaru</h5>
                <p class="text-muted small">Akun-akun sultan akan segera hadir di sini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection