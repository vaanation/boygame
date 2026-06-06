@extends('layouts.app')
@section('title', 'Daftar Akun')
@section('content')

@push('styles')
<style>
    .filter-scroll { overflow-x: auto; white-space: nowrap; -ms-overflow-style: none; scrollbar-width: none; }
    .filter-scroll::-webkit-scrollbar { display: none; }
</style>
@endpush

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Semua Akun</h3>
    </div>
    
    <form action="{{ route('accounts.index') }}" method="GET" id="searchForm" class="mb-5">
        <div class="d-flex gap-2">
            <div class="input-group flex-grow-1 shadow-sm rounded-pill overflow-hidden border bg-white">
                <span class="input-group-text bg-white border-0 ps-4 text-muted"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control border-0 shadow-none bg-white py-2" placeholder="Cari nama akun eFootball..." value="{{ request('search') }}">
            </div>
            
            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-sliders-h"></i> <span class="d-none d-md-inline">Filter</span>
            </button>
            
            @if(request()->hasAny(['search', 'category', 'status']))
                <a href="{{ route('accounts.index') }}" class="btn btn-outline-danger rounded-circle shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px;" title="Reset Filter"><i class="fas fa-times"></i></a>
            @endif
        </div>
        
        <!-- Hidden Inputs for Filters -->
        <input type="hidden" name="category" id="hidden_category" value="{{ request('category') }}">
        <input type="hidden" name="status" id="hidden_status" value="{{ request('status') }}">
    </form>
    
    <div class="row g-2 g-md-4">
        @forelse($accounts as $account)
            <div class="col-6 col-md-4 col-lg-3 mb-3 mb-md-4">
                <div class="card modern-card h-100 border-0">
                    <div class="position-relative">
                        <img src="{{ $account->images->first() ? asset('storage/'.$account->images->first()->image_path) : 'https://via.placeholder.com/400x500' }}" class="card-img-top w-100" alt="Image" style="aspect-ratio: 4/5; object-fit:cover;">
                        @if($account->status == 'Sold')
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5);">
                                <span class="badge bg-danger fs-6 px-2 py-1 rounded-pill shadow">SOLD OUT</span>
                            </div>
                        @endif
                        <span class="badge bg-primary position-absolute top-0 end-0 m-2 m-md-3 shadow-sm rounded-pill px-2 py-1 px-md-3 py-md-2" style="font-size: 0.75rem;">{{ $account->category->name ?? 'Premium' }}</span>
                    </div>
                    <div class="card-body p-2 p-md-4 d-flex flex-column">
                        <h6 class="card-title text-truncate fw-bold mb-1" style="font-size: 0.95rem;">{{ $account->title }}</h6>
                        <div class="mb-2 mt-auto">
                            <h6 class="text-primary-custom fw-bold mb-0">Rp {{ number_format($account->price, 0, ',', '.') }}</h6>
                        </div>
                        @if($account->status != 'Sold')
                            <a href="{{ route('accounts.show', $account->slug) }}" class="btn btn-primary-custom w-100 fw-bold shadow-sm" style="font-size: 0.85rem; padding: 6px;">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
                        @else
                            <button class="btn btn-secondary w-100 fw-bold shadow-sm" style="font-size: 0.85rem; padding: 6px;" disabled>Terjual</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
        <div class="col-12 text-center py-5 my-5">
            <div class="mb-4">
                <i class="fas fa-box-open fa-5x text-secondary opacity-50"></i>
            </div>
            <h4 class="text-muted fw-bold">Belum ada akun yang tersedia</h4>
            <p class="text-muted">Silakan cek kembali nanti atau hubungi admin.</p>
        </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $accounts->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-sliders-h me-2 text-primary"></i> Filter Penelusuran</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-6">
                        <h6 class="fw-bold text-muted mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">STATUS</h6>
                        <div class="d-flex flex-column gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="modal_status" id="ms_all" value="" {{ request('status') == '' ? 'checked' : '' }}>
                                <label class="form-check-label" for="ms_all" style="cursor: pointer;">Semua</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="modal_status" id="ms_tersedia" value="Tersedia" {{ request('status') == 'Tersedia' ? 'checked' : '' }}>
                                <label class="form-check-label" for="ms_tersedia" style="cursor: pointer;">Tersedia</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="modal_status" id="ms_sold" value="Sold" {{ request('status') == 'Sold' ? 'checked' : '' }}>
                                <label class="form-check-label" for="ms_sold" style="cursor: pointer;">Terjual</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 border-start ps-4">
                        <h6 class="fw-bold text-muted mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">KATEGORI</h6>
                        <div class="d-flex flex-column gap-3" style="max-height: 250px; overflow-y: auto;">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="modal_category" id="mc_all" value="" {{ request('category') == '' ? 'checked' : '' }}>
                                <label class="form-check-label" for="mc_all" style="cursor: pointer;">Semua Kategori</label>
                            </div>
                            @foreach($categories as $cat)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="modal_category" id="mc_{{ $cat->id }}" value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'checked' : '' }}>
                                <label class="form-check-label" for="mc_{{ $cat->id }}" style="cursor: pointer;">{{ $cat->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm" onclick="applyFilter()">Terapkan Filter</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function applyFilter() {
        document.getElementById('hidden_status').value = document.querySelector('input[name="modal_status"]:checked').value;
        document.getElementById('hidden_category').value = document.querySelector('input[name="modal_category"]:checked').value;
        document.getElementById('searchForm').submit();
    }
</script>
@endpush
@endsection