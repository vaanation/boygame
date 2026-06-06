@extends('admin.layouts.app')
@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h4 class="mb-4">Backup & Restore Database</h4>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="p-4 border rounded bg-light text-center">
                    <i class="fas fa-download fa-3x mb-3 text-primary"></i>
                    <h5>Backup Database</h5>
                    <p class="text-muted small">Download seluruh data dan struktur database saat ini ke dalam format .sql</p>
                    <a href="{{ route('admin.backup.download') }}" class="btn btn-primary w-100">Download SQL</a>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="p-4 border rounded bg-light text-center">
                    <i class="fas fa-upload fa-3x mb-3 text-secondary-custom"></i>
                    <h5>Restore Database</h5>
                    <p class="text-muted small">Upload file .sql untuk mengembalikan data (Data saat ini akan tertimpa)</p>
                    <form action="{{ route('admin.backup.restore') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="backup_file" class="form-control mb-2" required accept=".sql">
                        <button type="submit" class="btn btn-secondary w-100">Restore Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
