@extends('layouts.app')
@section('title', $account->title)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<style>
    .gallery-img { cursor: zoom-in; transition: transform 0.3s ease; }
    .gallery-img:hover { transform: scale(1.02); }
    .thumbnail-img { cursor: pointer; opacity: 0.6; transition: all 0.3s ease; border: 2px solid transparent; }
    .thumbnail-img:hover, .thumbnail-img.active { opacity: 1; border-color: var(--secondary); transform: translateY(-2px); }
    .detail-card { background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); }
    .stat-box { background: var(--bg); border-radius: 12px; padding: 15px; text-align: center; flex: 1; border: 1px solid rgba(0,0,0,0.03); }
    .stat-box i { color: var(--secondary); font-size: 1.2rem; margin-bottom: 5px; }
    .stat-box span { display: block; font-size: 0.85rem; color: #666; font-weight: 500; }
    .stat-box strong { display: block; font-size: 1rem; color: var(--primary); }
    
    @media (max-width: 768px) {
        .detail-title { font-size: 1.3rem !important; }
        .detail-price { font-size: 1.5rem !important; }
        .stat-box { padding: 10px; }
        .stat-box i { font-size: 1rem; }
        .stat-box strong { font-size: 0.9rem; }
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-md-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('accounts.index') }}" class="text-decoration-none text-muted">Akun</a></li>
            <li class="breadcrumb-item active fw-bold" aria-current="page">Detail</li>
        </ol>
    </nav>

    <div class="detail-card p-3 p-md-5">
        <div class="row g-4 g-md-5">
            <!-- Left: Gallery -->
            <div class="col-lg-5">
                <div class="position-relative mb-3 rounded-4 overflow-hidden shadow-sm" style="background: #f8f9fa;">
                    @if($account->status == 'Sold')
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.6); z-index: 10;">
                            <span class="badge bg-danger fs-3 px-4 py-3 rounded-pill shadow-lg border border-2 border-white" style="transform: rotate(-10deg);">SOLD OUT</span>
                        </div>
                    @endif
                    
                    @if($account->category)
                        <span class="badge bg-primary position-absolute top-0 end-0 m-3 shadow-sm rounded-pill px-3 py-2" style="z-index: 5;">
                            {{ $account->category->name }}
                        </span>
                    @endif

                    <div id="mainImageContainer">
                        @foreach($account->images as $index => $img)
                        <a href="{{ asset('storage/'.$img->image_path) }}" data-fancybox="gallery" class="main-img-link {{ $index != 0 ? 'd-none' : '' }}" id="main-img-{{ $index }}">
                            <img src="{{ asset('storage/'.$img->image_path) }}" class="w-100 gallery-img" style="aspect-ratio: 4/5; object-fit: cover;" alt="Account Image">
                        </a>
                        @endforeach
                    </div>
                </div>

                @if($account->images->count() > 1)
                <div class="d-flex gap-2 overflow-auto py-2 px-1" style="scrollbar-width: none;">
                    @foreach($account->images as $index => $img)
                    <img src="{{ asset('storage/'.$img->image_path) }}" 
                         class="rounded-3 thumbnail-img {{ $index == 0 ? 'active' : '' }}" 
                         style="width: 80px; height: 60px; object-fit: cover; flex-shrink: 0;"
                         onclick="changeMainImage({{ $index }})" id="thumb-{{ $index }}">
                    @endforeach
                </div>
                <small class="text-muted d-block text-center mt-2"><i class="fas fa-hand-point-up me-1"></i> Klik gambar untuk melihat ukuran penuh</small>
                @endif
            </div>

            <!-- Right: Details -->
            <div class="col-lg-7 d-flex flex-column">
                <h1 class="fw-bold mb-2 detail-title text-dark">{{ $account->title }}</h1>
                <h2 class="text-primary-custom fw-bold mb-4 detail-price">Rp {{ number_format($account->price, 0, ',', '.') }}</h2>

                <div class="mb-4 flex-grow-1">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Deskripsi Akun</h5>
                    <div class="text-muted" style="line-height: 1.8; font-size: 0.95rem;">
                        {!! nl2br(e($account->description)) !!}
                    </div>
                </div>
                
                <div class="d-flex gap-2 mb-4">
                    <div class="stat-box">
                        <i class="fas fa-eye"></i>
                        <span>Dilihat</span>
                        <strong>{{ $account->views }}x</strong>
                    </div>
                    <button class="stat-box btn shadow-none border" id="btnShare" style="cursor: pointer;">
                        <i class="fas fa-share-alt"></i>
                        <span>Bagikan</span>
                        <strong id="shareCount">{{ $account->shares }}x</strong>
                    </button>
                    <div class="stat-box">
                        <i class="fas fa-box-open"></i>
                        <span>Status</span>
                        @if($account->status == 'Sold')
                            <strong class="text-danger">Terjual</strong>
                        @else
                            <strong class="text-success">Tersedia</strong>
                        @endif
                    </div>
                </div>

                @php
                    $waText = "Halo Admin BOYGAME.%0ASaya tertarik dengan akun eFootball berikut:%0AJudul Akun: {$account->title}%0AHarga: Rp " . number_format($account->price, 0, ',', '.') . "%0AApakah akun ini masih tersedia?%0ATerima kasih.";
                    $targetWa = $account->whatsapp_number ?: (\App\Models\Setting::where('key', 'whatsapp_number')->value('value') ?? '');
                @endphp
                
                <div class="mt-auto pt-3 border-top">
                    @if($account->status == 'Sold')
                        <button class="btn btn-secondary w-100 py-3 fw-bold rounded-pill shadow-sm" disabled>
                            <i class="fas fa-ban me-2"></i> AKUN SUDAH TERJUAL
                        </button>
                    @else
                        @if($targetWa)
                            <a href="https://wa.me/{{ $targetWa }}?text={{ $waText }}" target="_blank" class="btn btn-success w-100 py-3 fw-bold rounded-pill shadow fs-6 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #25D366, #128C7E); border: none;">
                                <i class="fab fa-whatsapp fs-4"></i> <span>Beli via WhatsApp</span>
                            </a>
                        @else
                            <button class="btn btn-success w-100 py-3 fw-bold rounded-pill shadow fs-6 d-flex align-items-center justify-content-center gap-2 opacity-75" disabled>
                                <i class="fab fa-whatsapp fs-4"></i> <span>Nomor WA Belum Diatur</span>
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    Fancybox.bind("[data-fancybox]", {
        Thumbs: { type: "modern" },
        Carousel: { infinite: false }
    });

    function changeMainImage(index) {
        document.querySelectorAll('.main-img-link').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.thumbnail-img').forEach(el => el.classList.remove('active'));
        
        document.getElementById('main-img-' + index).classList.remove('d-none');
        document.getElementById('thumb-' + index).classList.add('active');
    }

    document.getElementById('btnShare').addEventListener('click', function() {
        const shareData = {
            title: '{{ $account->title }}',
            text: 'Cek akun eFootball premium ini di BOYGAME!',
            url: window.location.href
        };
        
        const shareBtn = this;
        const originalHtml = shareBtn.innerHTML;

        shareBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Loading...</span><strong>Wait</strong>';

        const updateShareCount = () => {
            fetch("{{ route('accounts.share', $account->slug) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    shareBtn.innerHTML = '<i class="fas fa-check text-success"></i><span>Berhasil!</span><strong>' + data.shares + 'x</strong>';
                    setTimeout(() => {
                        shareBtn.innerHTML = originalHtml;
                        document.getElementById('shareCount').innerText = data.shares + 'x';
                    }, 2000);
                }
            });
        };

        if (navigator.share) {
            navigator.share(shareData).then(() => {
                updateShareCount();
            }).catch(console.error);
            shareBtn.innerHTML = originalHtml;
        } else {
            navigator.clipboard.writeText(window.location.href).then(() => {
                updateShareCount();
            });
        }
    });
</script>
@endpush