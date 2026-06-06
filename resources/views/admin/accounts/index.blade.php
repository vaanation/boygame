@extends('admin.layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Kelola Akun</h3>
    <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Akun</a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.accounts.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari judul akun..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select text-muted">
                    <option value="">Semua Status</option>
                    <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Sold" {{ request('status') == 'Sold' ? 'selected' : '' }}>Sold</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="category" class="form-select text-muted">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-8 col-md-2">
                <select name="type" class="form-select text-muted">
                    <option value="">Semua Tipe</option>
                    <option value="regular" {{ request('type') == 'regular' ? 'selected' : '' }}>Regular</option>
                    <option value="jastip" {{ request('type') == 'jastip' ? 'selected' : '' }}>Jastip</option>
                </select>
            </div>
            <div class="col-4 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-filter d-md-none"></i><span class="d-none d-md-inline">Filter</span></button>
                @if(request()->hasAny(['search', 'status', 'category', 'type']))
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary" title="Reset Filter"><i class="fas fa-sync-alt"></i></a>
                @endif
            </div>
        </form>
    </div>
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
                    @forelse($accounts as $acc)
                    <tr>
                        <td>
                            {{ $acc->title }}
                            @if($acc->is_jastip)
                                <span class="badge bg-info ms-1">Jastip</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($acc->price,0,',','.') }}</td>
                        <td><span class="badge {{ $acc->status == 'Sold' ? 'bg-danger' : 'bg-success' }}">{{ $acc->status }}</span></td>
                        <td>{{ $acc->category->name ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.accounts.edit', $acc->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.accounts.destroy', $acc->id) }}" method="POST" class="d-inline" id="no-loading">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-folder-open mb-2 fs-3 d-block opacity-50"></i> Tidak ada data akun yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($accounts->hasPages())
        <div class="d-flex justify-content-end mt-4">
            {{ $accounts->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection