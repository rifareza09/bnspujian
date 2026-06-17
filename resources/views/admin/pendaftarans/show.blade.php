@extends('layouts.app')
@section('title', 'Detail Pendaftaran')
@section('page-title', 'Detail Pendaftaran')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span><i class="bi bi-person-vcard me-2"></i>Data Pribadi</span>
                @if($pendaftaran->status === 'pending')
                    <span class="badge badge-pending fs-6">Pending</span>
                @elseif($pendaftaran->status === 'diterima')
                    <span class="badge badge-diterima fs-6">Diterima</span>
                @else
                    <span class="badge badge-ditolak fs-6">Ditolak</span>
                @endif
            </div>
            <div class="card-body">
                @if($pendaftaran->foto)
                <div class="mb-3 text-center">
                    <img src="{{ asset('storage/' . $pendaftaran->foto) }}" alt="Foto"
                         class="rounded" style="max-height:180px; border:2px solid #eee">
                </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Nama Lengkap</div>
                        <div class="fw-semibold">{{ $pendaftaran->nama_lengkap }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Email</div>
                        <div>{{ $pendaftaran->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">No. HP</div>
                        <div>{{ $pendaftaran->hp }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">No. Telepon</div>
                        <div>{{ $pendaftaran->telepon ?? '-' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Alamat KTP</div>
                        <div>{{ $pendaftaran->alamat_ktp }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Alamat Sekarang</div>
                        <div>{{ $pendaftaran->alamat_sekarang }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Kecamatan</div>
                        <div>{{ $pendaftaran->kecamatan }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Kabupaten/Kota</div>
                        <div>{{ $pendaftaran->kabupaten->nama }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Provinsi</div>
                        <div>{{ $pendaftaran->provinsi->nama }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Kewarganegaraan</div>
                        <div>{{ $pendaftaran->kewarganegaraan }}
                            @if($pendaftaran->negara_asal) ({{ $pendaftaran->negara_asal }}) @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Tanggal Lahir</div>
                        <div>{{ $pendaftaran->tanggal_lahir->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Tempat Lahir</div>
                        <div>{{ $pendaftaran->tempat_lahir }}
                            @if($pendaftaran->negara_lahir) ({{ $pendaftaran->negara_lahir }}) @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Jenis Kelamin</div>
                        <div>{{ $pendaftaran->jenis_kelamin }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Status Menikah</div>
                        <div>{{ $pendaftaran->status_menikah }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Agama</div>
                        <div>{{ $pendaftaran->agama }}</div>
                    </div>
                    @if($pendaftaran->dokumen)
                    <div class="col-12">
                        <div class="text-muted small">Dokumen</div>
                        <a href="{{ asset('storage/' . $pendaftaran->dokumen) }}" target="_blank"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-file-earmark me-1"></i>Lihat Dokumen
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header py-3">
                <i class="bi bi-gear me-2"></i>Ubah Status
            </div>
            <div class="card-body">
                <form action="{{ route('admin.pendaftarans.update', $pendaftaran) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <select name="status" class="form-select">
                            <option value="pending" {{ $pendaftaran->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="diterima" {{ $pendaftaran->status === 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ $pendaftaran->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-1"></i>Simpan Status
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="text-muted small mb-1">Akun Pendaftar</div>
                <div class="fw-semibold">{{ $pendaftaran->user->name }}</div>
                <div class="small text-muted">{{ $pendaftaran->user->email }}</div>
                <hr class="my-2">
                <div class="text-muted small mb-1">Tanggal Daftar</div>
                <div class="small">{{ $pendaftaran->created_at->format('d M Y, H:i') }}</div>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.pendaftarans') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>
@endsection
