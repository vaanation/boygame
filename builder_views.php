<?php

$views = [
    'layouts/app.blade.php' => <<<'HTML'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOYGAME - @yield('title', 'Marketplace eFootball')</title>
    <meta name="description" content="@yield('meta_desc', 'Marketplace jual beli akun eFootball premium dan terpercaya.')">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">BOYGAME</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('accounts.index') }}">Akun</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('topup.index') }}">Top Up</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="min-vh-100">
        @yield('content')
    </main>

    <footer class="bg-primary-custom text-white pt-5 pb-3 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} BOYGAME. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(window).on('load', function() {
            $('.real-content').removeClass('d-none');
            $('.skeleton-wrapper').addClass('d-none');
        });
    </script>
    @stack('scripts')
</body>
</html>
HTML,
    'front/home.blade.php' => <<<'HTML'
@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div id="heroCarousel" class="carousel slide rounded-4 overflow-hidden shadow-sm" data-bs-ride="carousel">
        <div class="carousel-inner skeleton-wrapper">
            <div class="skeleton skel-img" style="height: 400px;"></div>
        </div>
        <div class="carousel-inner real-content d-none">
            @forelse($banners as $index => $banner)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/'.$banner->image_path) }}" class="d-block w-100" alt="Banner" style="height: 400px; object-fit: cover;">
            </div>
            @empty
            <div class="carousel-item active">
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 400px;">
                    <h2>Welcome to BOYGAME</h2>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <div class="row text-center mt-5">
        <div class="col-md-4 mb-3">
            <div class="modern-card p-4">
                <i class="fas fa-gamepad fa-3x text-primary-custom mb-3"></i>
                <h3>{{ $stats['total_accounts'] }}</h3>
                <p class="text-muted mb-0">Total Akun</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="modern-card p-4">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h3>{{ $stats['total_sold'] }}</h3>
                <p class="text-muted mb-0">Akun Terjual</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="modern-card p-4">
                <i class="fas fa-users fa-3x text-secondary-custom mb-3"></i>
                <h3>{{ $stats['total_visitors'] }}</h3>
                <p class="text-muted mb-0">Pengunjung</p>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">Akun Terbaru</h4>
            <a href="{{ route('accounts.index') }}" class="text-decoration-none fw-bold text-secondary-custom">Lihat Semua</a>
        </div>
        <div class="row">
            @foreach($latestAccounts as $account)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="modern-card h-100">
                    <img src="{{ $account->images->first() ? asset('storage/'.$account->images->first()->image_path) : 'https://via.placeholder.com/300' }}" class="card-img-top" alt="Image" style="height: 200px; object-fit:cover;">
                    <div class="card-body">
                        <h6 class="card-title text-truncate">{{ $account->title }}</h6>
                        <h5 class="text-primary-custom fw-bold">Rp {{ number_format($account->price, 0, ',', '.') }}</h5>
                        <p class="small text-muted mb-2"><i class="fas fa-eye"></i> {{ $account->views }} views</p>
                        @if($account->status == 'Sold')
                            <span class="badge bg-danger w-100 p-2">SOLD OUT</span>
                        @else
                            <a href="{{ route('accounts.show', $account->slug) }}" class="btn btn-primary-custom w-100">Detail</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
HTML,
    'front/accounts/show.blade.php' => <<<'HTML'
@extends('layouts.app')
@section('title', $account->title)
@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div id="accCarousel" class="carousel slide modern-card overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($account->images as $index => $img)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/'.$img->image_path) }}" class="d-block w-100" style="height:400px; object-fit:cover;">
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#accCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#accCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
        <div class="col-md-6">
            @if($account->status == 'Sold')
                <div class="alert alert-danger fw-bold fs-5 text-center">SOLD OUT</div>
            @endif
            <h2 class="fw-bold">{{ $account->title }}</h2>
            <h3 class="text-primary-custom fw-bold my-3">Rp {{ number_format($account->price, 0, ',', '.') }}</h3>
            <div class="d-flex gap-3 mb-4 text-muted">
                <span><i class="fas fa-eye"></i> {{ $account->views }} Views</span>
                <span><i class="fas fa-share"></i> {{ $account->shares }} Shares</span>
                @if($account->category)
                <span><i class="fas fa-tag"></i> {{ $account->category->name }}</span>
                @endif
            </div>
            <div class="modern-card p-3 mb-4">
                <p class="mb-0">{!! nl2br(e($account->description)) !!}</p>
            </div>
            
            @php
                $waText = "Halo Admin BOYGAME.%0ASaya tertarik dengan akun eFootball berikut:%0AJudul Akun: {$account->title}%0AHarga: Rp " . number_format($account->price, 0, ',', '.') . "%0AApakah akun ini masih tersedia?%0ATerima kasih.";
            @endphp
            @if($account->status == 'Sold')
                <button class="btn btn-secondary w-100 py-3 fw-bold rounded-3" disabled>TIDAK TERSEDIA</button>
            @else
                <a href="https://wa.me/{{ $account->whatsapp_number }}?text={{ $waText }}" target="_blank" class="btn btn-success w-100 py-3 fw-bold rounded-3 fs-5">
                    <i class="fab fa-whatsapp me-2"></i> Beli via WhatsApp
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
HTML,
    'admin/layouts/app.blade.php' => <<<'HTML'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - BOYGAME</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #001F54; color: white; }
        .sidebar a { color: rgba(255,255,255,.8); text-decoration: none; padding: 12px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background: #005DFF; color: white; }
        .main-content { flex: 1; padding: 20px; }
        .btn-loading { position: relative; color: transparent !important; pointer-events: none; }
        .btn-loading::after {
            content: ''; position: absolute; left: 50%; top: 50%;
            width: 20px; height: 20px; margin-left: -10px; margin-top: -10px;
            border: 2px solid white; border-top-color: transparent; border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="sidebar d-none d-md-block" style="width: 250px;">
            <h4 class="text-center py-4 m-0 fw-bold border-bottom border-secondary">BOYGAME</h4>
            <div class="pt-3">
                <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                <a href="{{ route('admin.accounts.index') }}"><i class="fas fa-gamepad me-2"></i> Akun</a>
                <a href="{{ route('admin.categories.index') }}"><i class="fas fa-list me-2"></i> Kategori</a>
                <a href="{{ route('admin.banners.index') }}"><i class="fas fa-image me-2"></i> Banner</a>
                <a href="{{ route('admin.topup-packages.index') }}"><i class="fas fa-coins me-2"></i> Top Up</a>
            </div>
        </div>
        <div class="main-content">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $('form').on('submit', function() {
            var btn = $(this).find('button[type="submit"]');
            btn.addClass('btn-loading');
        });
    </script>
</body>
</html>
HTML,
    'admin/dashboard.blade.php' => <<<'HTML'
@extends('admin.layouts.app')
@section('content')
<h2 class="fw-bold mb-4">Dashboard</h2>
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
                <h5>Total Akun</h5>
                <h2>{{ $stats['total_accounts'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body">
                <h5>Akun Terjual</h5>
                <h2>{{ $stats['total_sold'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 bg-info text-white">
            <div class="card-body">
                <h5>Total Views</h5>
                <h2>{{ $stats['total_views'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 bg-secondary text-white">
            <div class="card-body">
                <h5>Pengunjung Unik</h5>
                <h2>{{ $stats['total_visitors'] }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection
HTML,
    'admin/accounts/index.blade.php' => <<<'HTML'
@extends('admin.layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Kelola Akun</h3>
    <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Akun</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $acc)
                    <tr>
                        <td>{{ $acc->title }}</td>
                        <td>Rp {{ number_format($acc->price,0,',','.') }}</td>
                        <td><span class="badge {{ $acc->status == 'Sold' ? 'bg-danger' : 'bg-success' }}">{{ $acc->status }}</span></td>
                        <td>{{ $acc->category->name ?? '-' }}</td>
                        <td>
                            <form action="{{ route('admin.accounts.destroy', $acc->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
HTML,
    'admin/accounts/create.blade.php' => <<<'HTML'
@extends('admin.layouts.app')
@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h4 class="mb-4">Tambah Akun Baru</h4>
        <form action="{{ route('admin.accounts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Judul Akun</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Kategori (Opsional)</label>
                    <select name="category_id" class="form-select">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Nomor WhatsApp</label>
                    <input type="text" name="whatsapp_number" class="form-control" required placeholder="628xxx">
                </div>
                <div class="col-12 mb-3">
                    <label>Upload Gambar (Maks 30, 2MB/gambar)</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                    <div class="progress mt-2 d-none" id="uploadProgress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" id="progressBar">0%</div>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label>Deskripsi</label>
                    <textarea name="description" rows="5" class="form-control" required></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary px-4">Simpan Akun</button>
        </form>
    </div>
</div>
<script>
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('uploadProgress').classList.remove('d-none');
        let width = 0;
        let pBar = document.getElementById('progressBar');
        setInterval(() => {
            if(width >= 90) return;
            width += Math.random() * 10;
            pBar.style.width = width + '%';
            pBar.innerText = Math.round(width) + '%';
        }, 500);
    });
</script>
@endsection
HTML
];

foreach($views as $path => $content) {
    $fullPath = __DIR__ . '/resources/views/' . $path;
    $dir = dirname($fullPath);
    if(!is_dir($dir)) mkdir($dir, 0755, true);
    file_put_contents($fullPath, $content);
}
echo "Views generated.\n";
