@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon" style="background:#e8f0fe">
                    <i class="bi bi-people text-primary"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Mahasiswa</div>
                    <div class="fw-bold fs-4">{{ $stats['total_mahasiswa'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon" style="background:#e6f4ea">
                    <i class="bi bi-clipboard-check text-success"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Pendaftaran</div>
                    <div class="fw-bold fs-4">{{ $stats['total_pendaftaran'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon" style="background:#fff8e1">
                    <i class="bi bi-hourglass-split text-warning"></i>
                </div>
                <div>
                    <div class="text-muted small">Menunggu Review</div>
                    <div class="fw-bold fs-4">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon" style="background:#e6f4ea">
                    <i class="bi bi-check-circle text-success"></i>
                </div>
                <div>
                    <div class="text-muted small">Diterima</div>
                    <div class="fw-bold fs-4">{{ $stats['diterima'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-3">
        <span><i class="bi bi-clock-history me-2"></i>Pendaftaran Terbaru</span>
        <a href="{{ route('admin.pendaftarans') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nama</th>
                        <th>Email</th>
                        <th>Asal Daerah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftaranTerbaru as $p)
                    <tr>
                        <td class="ps-3 fw-semibold">{{ $p->nama_lengkap }}</td>
                        <td class="text-muted small">{{ $p->user->email }}</td>
                        <td class="small">{{ $p->kabupaten->nama ?? '-' }}, {{ $p->provinsi->nama ?? '-' }}</td>
                        <td>
                            @if($p->status === 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @elseif($p->status === 'diterima')
                                <span class="badge badge-diterima">Diterima</span>
                            @else
                                <span class="badge badge-ditolak">Ditolak</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $p->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.pendaftarans.show', $p) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada pendaftaran masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
