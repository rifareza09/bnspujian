<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - PMB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a3a6b 0%, #0d2148 100%); min-height: 100vh; display: flex; align-items: center; padding: 2rem 0; }
        .card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .brand-logo { width: 60px; height: 60px; background: #1a3a6b; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card p-4">
                <div class="card-body">
                    <div class="brand-logo">
                        <i class="bi bi-mortarboard-fill text-white fs-4"></i>
                    </div>
                    <h4 class="text-center fw-bold mb-1">Buat Akun</h4>
                    <p class="text-center text-muted small mb-4">Daftar sebagai calon mahasiswa baru</p>

                    @if($errors->any())
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $err)
                                    <li class="small">{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register.post') }}" method="POST" id="registerForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" name="email" id="regEmail"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="nama@email.com" required>
                            <div class="invalid-feedback" id="emailError">Format email tidak valid.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-person-plus me-2"></i>Buat Akun
                        </button>
                    </form>

                    <hr class="my-3">
                    <p class="text-center small mb-0">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Masuk</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Validasi email real-time
    document.getElementById('regEmail').addEventListener('blur', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailRegex.test(this.value)) {
            this.classList.add('is-invalid');
            document.getElementById('emailError').style.display = 'block';
        } else {
            this.classList.remove('is-invalid');
        }
    });
</script>
</body>
</html>
