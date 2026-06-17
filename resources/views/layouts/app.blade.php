<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PMB Online') - Pendaftaran Mahasiswa Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6fb; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, #1a3a6b 0%, #0d2148 100%);
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            padding-top: 1rem;
        }
        .sidebar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 1rem 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: block;
            text-decoration: none;
        }
        .sidebar-brand small { font-size: 0.7rem; opacity: 0.6; display: block; font-weight: 400; }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 0.65rem 1.5rem;
            border-radius: 0;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.12);
        }
        .sidebar .nav-link i { margin-right: 8px; width: 18px; }
        .sidebar-section { font-size: 0.7rem; color: rgba(255,255,255,0.35); text-transform: uppercase; letter-spacing: 1px; padding: 1rem 1.5rem 0.4rem; }
        .main-content { margin-left: 250px; padding: 0; }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e9f0;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        .content-area { padding: 1.5rem; }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 8px rgba(0,0,0,0.06); }
        .card-header { background: transparent; border-bottom: 1px solid #f0f2f5; font-weight: 600; }
        .stat-card { border-radius: 12px; border: none; }
        .stat-card .icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
        .btn-primary { background: #1a3a6b; border-color: #1a3a6b; }
        .btn-primary:hover { background: #0d2148; border-color: #0d2148; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-diterima { background: #d1e7dd; color: #0a3622; }
        .badge-ditolak { background: #f8d7da; color: #842029; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2"></i>PMB Online
            <small>Pendaftaran Mahasiswa Baru</small>
        </a>

        @auth
            <div class="sidebar-section">Menu</div>
            @if(auth()->user()->isAdmin())
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
                       href="{{ route('admin.users') }}">
                        <i class="bi bi-people"></i> Kelola User
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.pendaftarans*') ? 'active' : '' }}"
                       href="{{ route('admin.pendaftarans') }}">
                        <i class="bi bi-clipboard-check"></i> Data Pendaftaran
                    </a>
                </nav>
            @else
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}"
                       href="{{ route('mahasiswa.dashboard') }}">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                    @if(!auth()->user()->pendaftaran)
                        <a class="nav-link {{ request()->routeIs('mahasiswa.pendaftaran.create') ? 'active' : '' }}"
                           href="{{ route('mahasiswa.pendaftaran.create') }}">
                            <i class="bi bi-pencil-square"></i> Daftar Sekarang
                        </a>
                    @else
                        <a class="nav-link {{ request()->routeIs('mahasiswa.pendaftaran.show') ? 'active' : '' }}"
                           href="{{ route('mahasiswa.pendaftaran.show') }}">
                            <i class="bi bi-file-earmark-text"></i> Status Pendaftaran
                        </a>
                    @endif
                </nav>
            @endif

            <div class="sidebar-section mt-3">Akun</div>
            <nav class="nav flex-column">
                <span class="nav-link text-white-50">
                    <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-start w-100"
                            style="color:rgba(255,255,255,0.75)">
                        <i class="bi bi-box-arrow-left"></i> Logout
                    </button>
                </form>
            </nav>
        @endauth
    </div>

    <div class="main-content">
        <div class="topbar">
            <h6 class="mb-0 fw-semibold text-dark">@yield('page-title', 'Dashboard')</h6>
            @auth
                <span class="badge bg-light text-dark border">
                    <i class="bi bi-person me-1"></i>{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Calon Mahasiswa' }}
                </span>
            @endauth
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
