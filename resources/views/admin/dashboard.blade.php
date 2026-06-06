@extends('admin.layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold m-0 text-primary">Dashboard Overview</h3>
        <p class="text-muted m-0">Ringkasan performa marketplace Anda hari ini.</p>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="modern-card p-4 h-100 d-flex align-items-center" style="border-left: 5px solid var(--primary);">
            <div class="flex-grow-1">
                <p class="text-muted fw-bold mb-1">Total Akun</p>
                <h2 class="fw-bold m-0 text-dark">{{ $stats['total_accounts'] }}</h2>
            </div>
            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="fas fa-gamepad fa-2x text-primary"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="modern-card p-4 h-100 d-flex align-items-center" style="border-left: 5px solid #198754;">
            <div class="flex-grow-1">
                <p class="text-muted fw-bold mb-1">Akun Terjual</p>
                <h2 class="fw-bold m-0 text-dark">{{ $stats['total_sold'] }}</h2>
            </div>
            <div class="bg-success bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="fas fa-check-circle fa-2x text-success"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="modern-card p-4 h-100 d-flex align-items-center" style="border-left: 5px solid var(--secondary);">
            <div class="flex-grow-1">
                <p class="text-muted fw-bold mb-1">Total Views</p>
                <h2 class="fw-bold m-0 text-dark">{{ $stats['total_views'] }}</h2>
            </div>
            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="fas fa-eye fa-2x" style="color: var(--secondary);"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="modern-card p-4 h-100 d-flex align-items-center" style="border-left: 5px solid #ffc107;">
            <div class="flex-grow-1">
                <p class="text-muted fw-bold mb-1">Pengunjung Unik</p>
                <h2 class="fw-bold m-0 text-dark">{{ $stats['total_visitors'] }}</h2>
            </div>
            <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="fas fa-users fa-2x text-warning"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6 mb-4">
        <div class="modern-card p-4 h-100">
            <h5 class="fw-bold mb-4">Akses Cepat</h5>
            <div class="d-grid gap-3">
                <a href="{{ route('admin.accounts.create') }}" class="btn btn-outline-primary text-start p-3 fw-bold rounded-3">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Akun Baru
                </a>
                <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary text-start p-3 fw-bold rounded-3">
                    <i class="fas fa-image me-2"></i> Kelola Banner Homepage
                </a>
                <a href="{{ route('admin.topup-packages.index') }}" class="btn btn-outline-success text-start p-3 fw-bold rounded-3">
                    <i class="fas fa-coins me-2"></i> Kelola Produk Top Up
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="modern-card p-4 h-100 bg-primary text-white" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
            <h4 class="fw-bold mb-3">Selamat Datang di BOYGAME</h4>
            <p class="mb-4 text-white-50">Sistem manajemen marketplace eFootball Anda beroperasi dengan baik. Pantau penjualan dan kelola konten website Anda dari panel admin ini.</p>
            <div class="d-flex align-items-center gap-3 mt-auto">
                <div class="text-center">
                    <h3 class="fw-bold m-0">{{ $stats['total_categories'] }}</h3>
                    <small class="text-white-50">Kategori</small>
                </div>
                <div class="text-center border-start border-light ps-3">
                    <h3 class="fw-bold m-0">{{ $stats['total_topup'] }}</h3>
                    <small class="text-white-50">Produk Koin</small>
                </div>
                <div class="text-center border-start border-light ps-3">
                    <h3 class="fw-bold m-0">{{ $stats['total_banners'] }}</h3>
                    <small class="text-white-50">Banners</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection