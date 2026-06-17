@extends('layouts.app')
@section('title', 'Data Pendaftaran')
@section('page-title', 'Data Pendaftaran')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <i class="bi bi-clipboard-check me-2"></i>Semua Data Pendaftaran
    </div>
    <div class="card-body border-bottom pb-3">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="form-control form-control-sm" style="max-width:250px"
                   placeholder="Cari nama / email..." value="{{ request('search') }}">
            <select name="status" class="form-select form-select-sm" style="max-width:160px">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button class="btn btn-sm btn-outline-secondary">Filter</button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('admin.pendaftarans') }}" class="btn btn-sm btn-link text-danger">Reset</a>
            @endif
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Nama Lengkap</th>
                        <th>Email Akun</th>
                        <th>Asal</th>
                        <th>Jenis Kelamin</th>
                        <th>Status</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $p)
                    <tr>
                        <td class="ps-3 text-muted small">{{ $pendaftarans->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">{{ $p->nama_lengkap }}</td>
                        <td class="small text-muted">{{ $p->user->email }}</td>
                        <td class="small">{{ $p->kabupaten->nama ?? '-' }}</td>
                        <td class="small">{{ $p->jenis_kelamin }}</td>
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
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.pendaftarans.show', $p) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.pendaftarans.edit', $p) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit Status">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.pendaftarans.destroy', $p) }}" method="POST"
                                      onsubmit="return confirm('Hapus data pendaftaran ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data pendaftaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pendaftarans->hasPages())
    <div class="card-footer">
        {{ $pendaftarans->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
