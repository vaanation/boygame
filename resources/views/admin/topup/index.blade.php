@extends('admin.layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Kelola Paket Top Up</h3>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Tambah Paket Baru</h5>
                <form action="{{ route('admin.topup-packages.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Paket</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: 100 Koin">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Koin</label>
                        <input type="number" name="coin_amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Paket (Opsional)</label>
                        <input type="file" name="image" id="image-add" class="form-control" accept="image/*" onchange="previewImage(this, 'preview-add')">
                        <div class="mt-3 text-center d-none" id="preview-add-container">
                            <div class="position-relative d-inline-block">
                                <img id="preview-add" src="" class="rounded shadow-sm border" style="width: 100px; height: 100px; object-fit: cover;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-1" onclick="removePreview('image-add', 'preview-add')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No WhatsApp (Opsional)</label>
                        <input type="text" name="whatsapp_number" class="form-control" placeholder="Kosongkan jika pakai WA utama">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Simpan Paket</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Daftar Paket Top Up</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Paket</th>
                                <th>Koin</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($packages as $pkg)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($pkg->image_path) 
                                            <img src="{{ asset('storage/'.$pkg->image_path) }}" class="rounded me-3 shadow-sm" style="width: 40px; height: 40px; object-fit: cover;"> 
                                        @else
                                            <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        @endif
                                        <span class="fw-bold">{{ $pkg->name }}</span>
                                    </div>
                                </td>
                                <td>{{ number_format($pkg->coin_amount,0,',','.') }}</td>
                                <td class="text-success fw-bold">Rp {{ number_format($pkg->price,0,',','.') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $pkg->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.topup-packages.destroy', $pkg->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Yakin ingin menghapus paket ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $pkg->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title fw-bold">Edit Paket Top Up</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.topup-packages.update', $pkg->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Paket</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $pkg->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jumlah Koin</label>
                                                    <input type="number" name="coin_amount" class="form-control" value="{{ $pkg->coin_amount }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Harga (Rp)</label>
                                                    <input type="number" name="price" class="form-control" value="{{ $pkg->price }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Gambar Paket (Opsional)</label>
                                                    <input type="file" name="image" id="image-edit-{{ $pkg->id }}" class="form-control" accept="image/*" onchange="previewImage(this, 'preview-edit-{{ $pkg->id }}')">
                                                    
                                                    <div class="mt-3 text-center {{ $pkg->image_path ? '' : 'd-none' }}" id="preview-edit-{{ $pkg->id }}-container">
                                                        <div class="position-relative d-inline-block">
                                                            <img id="preview-edit-{{ $pkg->id }}" src="{{ $pkg->image_path ? asset('storage/'.$pkg->image_path) : '' }}" class="rounded shadow-sm border" style="width: 100px; height: 100px; object-fit: cover;">
                                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-1" onclick="removePreview('image-edit-{{ $pkg->id }}', 'preview-edit-{{ $pkg->id }}')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                        @if($pkg->image_path)
                                                            <small class="d-block text-muted mt-1" id="info-edit-{{ $pkg->id }}">Gambar saat ini</small>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">No WhatsApp (Opsional)</label>
                                                    <input type="text" name="whatsapp_number" class="form-control" value="{{ $pkg->whatsapp_number }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Deskripsi (Opsional)</label>
                                                    <textarea name="description" class="form-control" rows="3">{{ $pkg->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary fw-bold">Update Paket</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada paket top up tersedia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input, previewId) {
        const container = document.getElementById(previewId + '-container');
        const img = document.getElementById(previewId);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                container.classList.remove('d-none');
                
                // Hide 'Gambar saat ini' text if selecting a new image in edit modal
                const infoText = document.getElementById('info-' + previewId.replace('preview-', ''));
                if(infoText) infoText.innerText = 'Preview gambar baru';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            // If they cancel file selection, we should keep the existing image if it's an edit form.
            // But if it's new, hide it.
        }
    }

    function removePreview(inputId, previewId) {
        const input = document.getElementById(inputId);
        const container = document.getElementById(previewId + '-container');
        const img = document.getElementById(previewId);
        
        // Reset the file input
        input.value = "";
        
        // If it's the ADD form or we want to hide it
        // Wait, for edit forms, if we remove preview, does it delete the old image?
        // Since we don't have a deleted_images logic here, we just visually remove it 
        // But the user might want to completely delete the image without uploading a new one.
        // For now, it resets the input and hides the preview. If they save, no new image is uploaded.
        
        container.style.transform = 'scale(0)';
        container.style.transition = 'all 0.3s ease';
        setTimeout(() => {
            container.classList.add('d-none');
            container.style.transform = 'scale(1)';
            img.src = '';
        }, 300);
    }
</script>
@endpush