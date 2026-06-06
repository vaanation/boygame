@extends('admin.layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Tambah Akun Baru</h3>
    <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
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
                <div class="col-md-4 mb-3">
                    <label>Kategori (Opsional)</label>
                    <select name="category_id" class="form-select">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat) 
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option> 
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="Tersedia" selected>Tersedia</option>
                        <option value="Sold">Sold</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Nomor WhatsApp <span class="text-muted fw-normal">(Opsional)</span></label>
                    <input type="text" name="whatsapp_number" class="form-control" placeholder="628xxx">
                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem; line-height: 1.2;">Jika dikosongkan, tombol beli otomatis menggunakan <b>Nomor WA Utama</b> di menu Pengaturan.</small>
                </div>
                
                <div class="col-12 mb-4 mt-3">
                    <label class="fw-bold text-primary d-flex align-items-center gap-2">
                        Upload Gambar 
                        <i class="fas fa-info-circle text-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Gambar paling kiri/depan otomatis akan menjadi Sampul Depan (Thumbnail). Anda bisa menarik (drag and drop) gambar untuk mengubah urutannya." style="cursor: help;"></i>
                    </label>
                    <small class="text-muted d-block mb-3">Format didukung: JPG, PNG, WEBP. Maks: 2MB/gambar.</small>
                    
                    <input type="file" name="images[]" id="imageInput" class="d-none" multiple accept="image/*">
                    
                    <div id="previewContainer" class="d-flex gap-3 flex-wrap p-3 bg-light rounded border border-dashed" style="min-height: 156px;">
                        <label for="imageInput" id="addMoreBtn" class="d-flex flex-column align-items-center justify-content-center bg-white rounded shadow-sm text-primary mb-0" style="width: 120px; height: 120px; cursor: pointer; border: 2px dashed #0d6efd !important; transition: all 0.3s ease;">
                            <i class="fas fa-plus fs-3 mb-2"></i>
                            <span class="small fw-bold">Tambah Foto</span>
                        </label>
                    </div>
                </div>
                
                <div class="col-12 mb-3">
                    <label>Deskripsi</label>
                    <textarea name="description" rows="5" class="form-control" required></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold w-100">Simpan Akun Baru</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .border-dashed { border-style: dashed !important; border-width: 2px !important; border-color: #dee2e6 !important; }
</style>
<script>
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('previewContainer');
    let dataTransfer = new DataTransfer();

    // Initialize SortableJS
    new Sortable(previewContainer, {
        animation: 150,
        ghostClass: 'opacity-50',
        draggable: '.media-item',
        filter: '#addMoreBtn',
        onEnd: function (evt) {
            const dt = new DataTransfer();
            const elements = previewContainer.querySelectorAll('.media-item');
            elements.forEach(el => {
                const name = el.dataset.filename;
                const file = Array.from(dataTransfer.files).find(f => f.name === name);
                if (file) dt.items.add(file);
            });
            dataTransfer = dt;
            imageInput.files = dataTransfer.files;
            updateThumbIndicator();
        }
    });

    function updateThumbIndicator() {
        const elements = previewContainer.querySelectorAll('.media-item');
        elements.forEach((el, index) => {
            const ind = el.querySelector('.thumb-indicator');
            if (index === 0) {
                ind.innerHTML = '<i class="fas fa-star text-warning"></i> Sampul Depan';
                ind.style.display = 'block';
            } else {
                ind.style.display = 'none';
            }
        });
        
        const addBtn = document.getElementById('addMoreBtn');
        if(addBtn) {
            previewContainer.appendChild(addBtn);
        }
    }

    imageInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        files.forEach((file) => {
            if(file.size > 2048 * 1024) {
                alert(`Ukuran file ${file.name} terlalu besar (Maks 2MB).`);
                return;
            }
            // Check for duplicates
            if (!Array.from(dataTransfer.files).find(f => f.name === file.name)) {
                dataTransfer.items.add(file);
                renderPreview(file);
            }
        });
        
        imageInput.files = dataTransfer.files;
        
        setTimeout(updateThumbIndicator, 100);
    });

    function renderPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'position-relative media-item';
            div.style.transition = 'all 0.3s ease';
            div.style.cursor = 'move';
            div.dataset.filename = file.name;

            div.innerHTML = `
                <div class="position-absolute top-0 start-0 m-1 bg-primary text-white rounded px-2 shadow-sm thumb-indicator fw-bold" style="z-index:10; font-size:11px; display:none;"></div>
                <img src="${e.target.result}" class="rounded shadow-sm border border-primary" style="width: 120px; height: 120px; object-fit:cover;">
                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" style="border-radius: 50%; padding: 4px 8px; z-index:10;" onclick="removeNewImage(this, '${file.name}')">
                    <i class="fas fa-times"></i>
                </button>
            `;
            previewContainer.appendChild(div);
            updateThumbIndicator();
        }
        reader.readAsDataURL(file);
    }

    function removeNewImage(buttonElement, fileName) {
        const div = buttonElement.parentElement;
        
        const dt = new DataTransfer();
        Array.from(dataTransfer.files).forEach(file => {
            if (file.name !== fileName) dt.items.add(file);
        });
        dataTransfer = dt;
        imageInput.files = dataTransfer.files;

        div.style.transform = 'scale(0)';
        setTimeout(() => {
            div.remove();
            updateThumbIndicator();
        }, 300);
    }
</script>
@endpush