@extends('admin.layouts.app')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    .img-container { max-height: 400px; width: 100%; }
</style>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Tambah Banner</h5>
                <form action="{{ route('admin.banners.store') }}" method="POST" id="bannerForm">
                    @csrf
                    <input type="hidden" name="cropped_image" id="cropped_image">
                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Gambar Banner</label>
                        <input type="file" id="imageInput" class="form-control image-input" accept="image/*">
                        <small class="text-muted">Akan di-crop otomatis dengan rasio 21:9</small>
                    </div>
                    <div class="mb-3 d-none" id="previewContainer">
                        <label>Preview Hasil Crop</label>
                        <img id="cropPreview" class="img-fluid rounded border" src="">
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
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-nowrap">
                        <thead><tr><th>Gambar</th><th>Judul</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($banners as $banner)
                        <tr>
                            <td><img src="{{ asset('storage/'.$banner->image_path) }}" height="50" class="rounded"></td>
                            <td>{{ $banner->title }}</td>
                            <td>{{ $banner->order }}</td>
                            <td><span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-danger' }}">{{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editModal{{ $banner->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-delete">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit Banner -->
                            <div class="modal fade" id="editModal{{ $banner->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title fw-bold">Edit Banner</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="cropped_image" id="cropped_image_{{ $banner->id }}">
                                            
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Judul</label>
                                                    <input type="text" name="title" class="form-control" value="{{ $banner->title }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Gambar Banner (Opsional)</label>
                                                    <input type="file" class="form-control image-input" data-target="{{ $banner->id }}" accept="image/*">
                                                    <small class="text-muted">Akan di-crop otomatis dengan rasio 21:9</small>
                                                </div>
                                                <div class="mb-3" id="previewContainer_{{ $banner->id }}">
                                                    <label class="form-label">Gambar Saat Ini / Preview Hasil Crop</label>
                                                    <img id="cropPreview_{{ $banner->id }}" class="img-fluid rounded border d-block w-100" src="{{ asset('storage/'.$banner->image_path) }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Link (Opsional)</label>
                                                    <input type="text" name="link" class="form-control" value="{{ $banner->link }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Urutan</label>
                                                    <input type="number" name="order" class="form-control" value="{{ $banner->order }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="is_active" class="form-select">
                                                        <option value="1" {{ $banner->is_active ? 'selected' : '' }}>Aktif</option>
                                                        <option value="0" {{ !$banner->is_active ? 'selected' : '' }}>Nonaktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary fw-bold">Update Banner</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crop -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Crop Banner (21:9)</h5>
        <button type="button" class="btn-close cancel-crop" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center bg-light">
        <div class="img-container mx-auto">
            <img id="imageToCrop" src="">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cancel-crop" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="cropBtn">Potong & Simpan</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    let cropper;
    let currentTargetId = '';
    let currentFileInput = null;
    
    const imageToCrop = document.getElementById('imageToCrop');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
    const cropBtn = document.getElementById('cropBtn');
    
    document.querySelectorAll('.image-input').forEach(input => {
        input.addEventListener('change', function (e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                currentTargetId = this.getAttribute('data-target') || '';
                currentFileInput = this;
                
                const file = files[0];
                const reader = new FileReader();
                reader.onload = function (e) {
                    imageToCrop.src = e.target.result;
                    cropModal.show();
                };
                reader.readAsDataURL(file);
            }
        });
    });

    document.getElementById('cropModal').addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 21 / 9,
            viewMode: 1,
            autoCropArea: 1,
        });
    });

    document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        let hiddenInputId = currentTargetId ? `cropped_image_${currentTargetId}` : 'cropped_image';
        if (document.getElementById(hiddenInputId).value === '' && currentFileInput) {
            currentFileInput.value = '';
        }
    });

    cropBtn.addEventListener('click', function () {
        const canvas = cropper.getCroppedCanvas({
            width: 1200,
            height: 514,
        });
        
        canvas.toBlob(function(blob) {
            const reader = new FileReader();
            reader.readAsDataURL(blob); 
            reader.onloadend = function() {
                const base64data = reader.result;
                
                let hiddenInputId = currentTargetId ? `cropped_image_${currentTargetId}` : 'cropped_image';
                let previewId = currentTargetId ? `cropPreview_${currentTargetId}` : 'cropPreview';
                let containerId = currentTargetId ? `previewContainer_${currentTargetId}` : 'previewContainer';
                
                document.getElementById(hiddenInputId).value = base64data;
                document.getElementById(previewId).src = base64data;
                document.getElementById(containerId).classList.remove('d-none');
                
                cropModal.hide();
            }
        }, 'image/webp', 0.8);
    });

    document.querySelectorAll('.cancel-crop').forEach(btn => {
        btn.addEventListener('click', function() {
            if(currentFileInput) currentFileInput.value = '';
            
            let hiddenInputId = currentTargetId ? `cropped_image_${currentTargetId}` : 'cropped_image';
            let containerId = currentTargetId ? `previewContainer_${currentTargetId}` : 'previewContainer';
            
            document.getElementById(hiddenInputId).value = '';
            if(!currentTargetId) {
                document.getElementById(containerId).classList.add('d-none');
            }
        });
    });
</script>
@endpush
@endsection