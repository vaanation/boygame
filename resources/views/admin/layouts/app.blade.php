<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BOYGAME</title>
    <link rel="icon" href="{{ asset('img/favicon.jpeg') }}" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #001F54;
            --secondary: #005DFF;
            --accent: #FFFFFF;
            --text: #111111;
            --bg: #F4F7FE;
        }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg); color: var(--text); overflow-x: hidden; }
        
        .sidebar {
            min-height: 100vh;
            background: var(--primary);
            color: white;
            transition: all 0.3s;
            box-shadow: 4px 0 20px rgba(0,0,0,0.05);
            z-index: 1040;
        }
        .sidebar-logo {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        .sidebar-logo img {
            max-height: 50px;
            max-width: 100%;
            object-fit: contain;
        }
        .sidebar a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .sidebar a i { margin-right: 15px; width: 20px; text-align: center; font-size: 1.1rem; }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 4px solid var(--secondary);
        }
        
        .main-content {
            flex: 1;
            min-width: 0;
            transition: all 0.3s;
        }
        
        .topbar {
            background: white;
            padding: 15px 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.02);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .btn-loading { position: relative; color: transparent !important; pointer-events: none; }
        .btn-loading::after {
            content: ''; position: absolute; left: 50%; top: 50%;
            width: 20px; height: 20px; margin-left: -10px; margin-top: -10px;
            border: 2px solid white; border-top-color: transparent; border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        .modern-card {
            background: white;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -250px;
                width: 250px;
                height: 100%;
            }
            .sidebar.show { left: 0; }
            .main-content { margin-left: 0 !important; width: 100%; }
            .overlay {
                display: none;
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1030;
            }
            .overlay.show { display: block; }
        }
        @media (min-width: 769px) {
            .sidebar { width: 250px; position: sticky; top: 0; height: 100vh; overflow-y: auto; }
            .mobile-toggle { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <div class="overlay" id="sidebarOverlay"></div>
        
        <div class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('img/logo.png') }}" alt="BOYGAME" onerror="this.outerHTML='<h4 class=\'text-white fw-bold m-0\'>BOYGAME</h4>'">
            </div>
            <div class="pt-3">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('admin.accounts.index') }}" class="{{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}">
                    <i class="fas fa-gamepad"></i> Kelola Akun
                </a>
                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Kategori
                </a>
                <a href="{{ route('admin.banners.index') }}" class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i> Banners
                </a>
                <a href="{{ route('admin.topup-packages.index') }}" class="{{ request()->routeIs('admin.topup-packages.*') ? 'active' : '' }}">
                    <i class="fas fa-coins"></i> Top Up Koin
                </a>
                <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Pengaturan
                </a>
                <a href="{{ route('admin.security.index') }}" class="{{ request()->routeIs('admin.security.*') ? 'active' : '' }}">
                    <i class="fas fa-shield-alt"></i> Keamanan Akun
                </a>
                <a href="{{ route('admin.backup.index') }}" class="{{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
                    <i class="fas fa-database"></i> Backup/Restore
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-4 px-3">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 rounded-3"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                </form>
            </div>
        </div>

        <div class="main-content">
            <div class="topbar">
                <div class="d-flex align-items-center">
                    <button class="btn btn-light mobile-toggle me-3 border-0 shadow-sm" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h5 class="m-0 fw-bold text-primary d-none d-md-block">Admin Panel</h5>
                </div>
                <div class="d-flex align-items-center">
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                        <i class="fas fa-external-link-alt me-2"></i> Lihat Website
                    </a>
                </div>
            </div>

            <div class="container-fluid px-4 pb-5">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        // Initialize Tooltips
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
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
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('show');
            document.getElementById('sidebarOverlay').classList.add('show');
        });
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('show');
            this.classList.remove('show');
        });
        $('form').on('submit', function() {
            if($(this).attr('id') !== 'no-loading') {
                var btn = $(this).find('button[type="submit"]');
                if(btn.length > 0 && !btn.hasClass('cancel-crop')) {
                    btn.addClass('btn-loading');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>