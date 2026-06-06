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
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.accounts.edit', $acc->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.accounts.destroy', $acc->id) }}" method="POST" class="d-inline" id="no-loading">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection