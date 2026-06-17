<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PMB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a3a6b 0%, #0d2148 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .brand-logo { width: 60px; height: 60px; background: #1a3a6b; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card p-4">
                <div class="card-body">
                    <div class="brand-logo">
                        <i class="bi bi-mortarboard-fill text-white fs-4"></i>
                    </div>
                    <h4 class="text-center fw-bold mb-1">PMB Online</h4>
                    <p class="text-center text-muted small mb-4">Masuk ke akun Anda</p>

                    @if($errors->any())
                        <div class="alert alert-danger py-2">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••" required>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Ingat saya</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                        </button>
                    </form>

                    <hr class="my-3">
                    <p class="text-center small mb-0">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Daftar di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
