<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - BOYGAME</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #001F54; }
        .login-card { border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .btn-primary-custom { background-color: #005DFF; color: white; border: none; }
        .btn-primary-custom:hover { background-color: #004ecc; }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card border-0 p-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            @php
                                $site_name = \App\Models\Setting::where('key', 'website_name')->value('value') ?? 'BOYGAME';
                            @endphp
                            <img src="{{ asset('img/logoireng.png') }}" alt="{{ $site_name }}" style="max-height: 55px; width: auto; object-fit: contain;" class="mb-3">
                            <h5 class="fw-bold text-dark mb-1">Selamat Datang</h5>
                            <p class="text-muted small mb-0">Silakan login ke Admin Panel</p>
                        </div>
                        
                        @if($errors->any())
                            <div class="alert alert-danger small">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted small">Email</label>
                                <input type="email" name="email" class="form-control py-2" required autofocus value="{{ old('email') }}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted small">Password</label>
                                <input type="password" name="password" class="form-control py-2" required>
                            </div>
                            <div class="mb-4 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label text-muted small" for="remember">Ingat Saya</label>
                            </div>
                            <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-bold rounded-3">Masuk</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
