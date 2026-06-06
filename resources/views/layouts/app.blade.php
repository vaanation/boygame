@php
    $site_settings = \App\Models\Setting::pluck('value', 'key');
    $meta_title = $site_settings['meta_title'] ?? 'BOYGAME - Jual Beli Akun eFootball Terpercaya';
    $meta_desc = $site_settings['meta_description'] ?? 'BOYGAME adalah platform jual beli akun eFootball terpercaya dan termurah. Tersedia ratusan akun eFootball spek sultan dengan harga terjangkau.';
    $meta_keys = $site_settings['meta_keywords'] ?? 'jual akun efootball, beli akun efootball, akun pes, efootball murah, marketplace efootball';
    $meta_author = $site_settings['meta_author'] ?? 'BOYGAME Team';
    $site_name = $site_settings['website_name'] ?? 'BOYGAME';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('title') @yield('title') | {{ $site_name }} @else {{ $meta_title }} @endif</title>
    
    <!-- Primary Meta Tags -->
    <meta name="title" content="@hasSection('title') @yield('title') | {{ $site_name }} @else {{ $meta_title }} @endif">
    <meta name="description" content="@yield('meta_desc', $meta_desc)">
    <meta name="keywords" content="{{ $meta_keys }}">
    <meta name="author" content="{{ $meta_author }}">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@hasSection('title') @yield('title') | {{ $site_name }} @else {{ $meta_title }} @endif">
    <meta property="og:description" content="@yield('meta_desc', $meta_desc)">
    <meta property="og:image" content="{{ asset('img/logo.png') }}">
    <meta property="og:site_name" content="{{ $site_name }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@hasSection('title') @yield('title') | {{ $site_name }} @else {{ $meta_title }} @endif">
    <meta property="twitter:description" content="@yield('meta_desc', $meta_desc)">
    <meta property="twitter:image" content="{{ asset('img/logo.png') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/favicon.jpeg') }}" type="image/jpeg">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('img/logo.png') }}" alt="BOYGAME" style="max-height: 40px; width: auto; object-fit: contain;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-1 gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active fw-bold' : '' }}" href="{{ route('accounts.index') }}">
                            <i class="fas fa-gamepad me-1"></i> Akun
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('topup.*') ? 'active fw-bold' : '' }}" href="{{ route('topup.index') }}">
                            <i class="fas fa-coins me-1"></i> Top Up
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="min-vh-100">
        @yield('content')
    </main>

    <footer class="bg-primary-custom text-white pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row gy-4 text-center text-md-start align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('img/logo.png') }}" alt="{{ $site_name }}" style="max-height: 40px; object-fit: contain;" class="mb-3">
                    <p class="mb-0 text-white-50 small mx-auto mx-md-0" style="max-width: 400px;">{{ $meta_desc }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="fw-bold mb-3 text-uppercase" style="letter-spacing: 1px;">Hubungi Kami</h6>
                    <div class="d-flex gap-3 justify-content-center justify-content-md-end">
                        @php
                            $wa = $site_settings['whatsapp_number'] ?? '';
                            $ig = $site_settings['instagram_username'] ?? '';
                        @endphp
                        @if($ig)
                        <a href="https://instagram.com/{{ $ig }}" target="_blank" class="btn btn-outline-light rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 45px; height: 45px; border-width: 2px;" title="Instagram">
                            <i class="fab fa-instagram fs-4"></i>
                        </a>
                        @endif
                        @if($wa)
                        <a href="https://wa.me/{{ $wa }}" target="_blank" class="btn btn-outline-light rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 45px; height: 45px; border-width: 2px;" title="WhatsApp">
                            <i class="fab fa-whatsapp fs-4"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <hr class="my-4 border-white opacity-25">
            <div class="text-center text-white-50 small fw-bold">
                &copy; {{ date('Y') }} {{ $site_name }}. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(window).on('load', function() {
            $('.real-content').removeClass('d-none');
            $('.skeleton-wrapper').addClass('d-none');
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '<ul class="text-start mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        @endif

        // Global override for "return confirm()" using SweetAlert
        document.addEventListener('click', function(e) {
            let el = e.target.closest('[onclick*="return confirm"]');
            if (el) {
                e.preventDefault();
                e.stopPropagation();
                let onclickAttr = el.getAttribute('onclick');
                let match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
                let msg = match ? match[1] : 'Apakah Anda yakin?';
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: msg,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        el.setAttribute('onclick', onclickAttr.replace(/return confirm\([^)]+\)/, 'return true'));
                        el.click();
                    }
                });
            }
        }, true);
    </script>
    @stack('scripts')
</body>
</html>