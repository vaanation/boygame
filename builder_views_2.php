<?php

$views = [
    'front/accounts/index.blade.php' => <<<'HTML'
@extends('layouts.app')
@section('title', 'Daftar Akun')
@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Semua Akun eFootball</h3>
    </div>
    
    <div class="row">
        @forelse($accounts as $account)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="modern-card h-100">
                <img src="{{ $account->images->first() ? asset('storage/'.$account->images->first()->image_path) : 'https://via.placeholder.com/300' }}" class="card-img-top" alt="Image" style="height: 200px; object-fit:cover;">
                <div class="card-body">
                    <h6 class="card-title text-truncate">{{ $account->title }}</h6>
                    <h5 class="text-primary-custom fw-bold">Rp {{ number_format($account->price, 0, ',', '.') }}</h5>
                    <div class="d-flex justify-content-between text-muted small mb-3">
                        <span><i class="fas fa-eye"></i> {{ $account->views }} views</span>
                        @if($account->category) <span><i class="fas fa-tag"></i> {{ $account->category->name }}</span> @endif
                    </div>
                    @if($account->status == 'Sold')
                        <span class="badge bg-danger w-100 p-2">SOLD OUT</span>
                    @else
                        <a href="{{ route('accounts.show', $account->slug) }}" class="btn btn-primary-custom w-100">Detail</a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h5 class="text-muted">Belum ada akun yang tersedia.</h5>
        </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $accounts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
HTML,
    'front/topup/index.blade.php' => <<<'HTML'
@extends('layouts.app')
@section('title', 'Top Up Koin')
@section('content')
<div class="container mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Top Up Koin eFootball</h2>
        <p class="text-muted">Pilih paket koin yang sesuai dengan kebutuhan Anda</p>
    </div>
    
    <div class="row justify-content-center">
        @forelse($packages as $pkg)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="modern-card h-100 text-center p-4">
                @if($pkg->image_path)
                    <img src="{{ asset('storage/'.$pkg->image_path) }}" class="img-fluid mb-3" style="max-height: 120px; object-fit:contain;">
                @else
                    <i class="fas fa-coins fa-5x text-warning mb-3"></i>
                @endif
                <h5 class="fw-bold">{{ $pkg->name }}</h5>
                <h6 class="text-muted mb-3">{{ number_format($pkg->coin_amount, 0, ',', '.') }} Koin</h6>
                <h4 class="text-primary-custom fw-bold mb-4">Rp {{ number_format($pkg->price, 0, ',', '.') }}</h4>
                @if($pkg->description)
                    <p class="small text-muted">{{ $pkg->description }}</p>
                @endif
                
                @php
                    $waNumber = $pkg->whatsapp_number ?? (\App\Models\Setting::where('key', 'whatsapp_number')->first()->value ?? '628xxx');
                    $waText = "Halo Admin BOYGAME.%0ASaya ingin membeli paket top up berikut:%0APaket: {$pkg->name}%0AJumlah Koin: {$pkg->coin_amount}%0AHarga: Rp " . number_format($pkg->price, 0, ',', '.') . "%0AMohon informasi lebih lanjut.%0ATerima kasih.";
                @endphp
                <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" class="btn btn-success w-100 rounded-3 mt-auto">
                    <i class="fab fa-whatsapp"></i> Beli Sekarang
                </a>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <p class="text-muted">Paket top up belum tersedia.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
HTML,
    'admin/accounts/edit.blade.php' => <<<'HTML'
@extends('admin.layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Edit Akun</h3>
    <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.accounts.update', $account->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Judul Akun</label>
                    <input type="text" name="title" class="form-control" value="{{ $account->title }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" value="{{ $account->price }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Kategori (Opsional)</label>
                    <select name="category_id" class="form-select">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat) 
                            <option value="{{ $cat->id }}" {{ $account->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option> 
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="Tersedia" {{ $account->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Sold" {{ $account->status == 'Sold' ? 'selected' : '' }}>Sold</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Nomor WhatsApp</label>
                    <input type="text" name="whatsapp_number" class="form-control" value="{{ $account->whatsapp_number }}" required>
                </div>
                <div class="col-12 mb-3">
                    <label>Upload Tambahan Gambar (Maks 2MB/gambar)</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                </div>
                <div class="col-12 mb-3">
                    <label>Gambar Saat Ini</label>
                    <div class="d-flex gap-2 flex-wrap mt-2">
                        @foreach($account->images as $img)
                            <img src="{{ asset('storage/'.$img->image_path) }}" class="rounded border" style="width: 100px; height: 100px; object-fit:cover;">
                        @endforeach
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label>Deskripsi</label>
                    <textarea name="description" rows="5" class="form-control" required>{{ $account->description }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary px-4">Update Akun</button>
        </form>
    </div>
</div>
@endsection
HTML,
    'admin/categories/index.blade.php' => <<<'HTML'
@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Tambah Kategori</h5>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Nama Kategori</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Daftar Kategori</h5>
                <table class="table table-hover">
                    <thead><tr><th>Nama</th><th>Slug</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($categories as $cat)
                        <tr>
                            <td>{{ $cat->name }}</td>
                            <td>{{ $cat->slug }}</td>
                            <td>
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
HTML,
    'admin/banners/index.blade.php' => <<<'HTML'
@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Tambah Banner</h5>
                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Gambar</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label>Link (Opsional)</label>
                        <input type="text" name="link" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Urutan</label>
                        <input type="number" name="order" class="form-control" value="0">
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Banner</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Daftar Banner</h5>
                <table class="table table-hover align-middle">
                    <thead><tr><th>Gambar</th><th>Judul</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($banners as $banner)
                        <tr>
                            <td><img src="{{ asset('storage/'.$banner->image_path) }}" height="50" class="rounded"></td>
                            <td>{{ $banner->title }}</td>
                            <td>{{ $banner->order }}</td>
                            <td><span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-danger' }}">{{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td>
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
HTML,
    'admin/topup/index.blade.php' => <<<'HTML'
@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Tambah Paket Top Up</h5>
                <form action="{{ route('admin.topup-packages.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label>Nama Paket</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Koin</label>
                        <input type="number" name="coin_amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Harga (Rp)</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Gambar (Opsional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label>No WhatsApp (Opsional)</label>
                        <input type="text" name="whatsapp_number" class="form-control" placeholder="Kosongkan jika pakai WA utama">
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Paket</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Daftar Paket Top Up</h5>
                <table class="table table-hover align-middle">
                    <thead><tr><th>Paket</th><th>Koin</th><th>Harga</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($packages as $pkg)
                        <tr>
                            <td>
                                @if($pkg->image_path) <img src="{{ asset('storage/'.$pkg->image_path) }}" height="30" class="me-2"> @endif
                                {{ $pkg->name }}
                            </td>
                            <td>{{ number_format($pkg->coin_amount,0,',','.') }}</td>
                            <td>Rp {{ number_format($pkg->price,0,',','.') }}</td>
                            <td>
                                <form action="{{ route('admin.topup-packages.destroy', $pkg->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
HTML,
    'admin/settings/index.blade.php' => <<<'HTML'
@extends('admin.layouts.app')
@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h4 class="mb-4">Pengaturan Website</h4>
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nama Website</label>
                    <input type="text" name="website_name" class="form-control" value="{{ $settings['website_name'] ?? 'BOYGAME' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Nomor WhatsApp Utama</label>
                    <input type="text" name="whatsapp_number" class="form-control" value="{{ $settings['whatsapp_number'] ?? '' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="3">{{ $settings['meta_description'] ?? '' }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary px-4 mt-3">Simpan Pengaturan</button>
        </form>
    </div>
</div>
@endsection
HTML
];

foreach($views as $path => $content) {
    $fullPath = __DIR__ . '/resources/views/' . $path;
    $dir = dirname($fullPath);
    if(!is_dir($dir)) mkdir($dir, 0755, true);
    file_put_contents($fullPath, $content);
}
echo "All missing views generated.\n";
