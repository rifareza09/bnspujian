@extends('layouts.app')
@section('title', 'Detail Pendaftaran Saya')
@section('page-title', 'Detail Pendaftaran Saya')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span><i class="bi bi-person-vcard me-2"></i>Data Pendaftaran</span>
                <a href="{{ route('mahasiswa.pendaftaran.pdf') }}" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-file-pdf me-1"></i>Download PDF
                </a>
            </div>
            <div class="card-body">
                @if($pendaftaran->foto)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $pendaftaran->foto) }}" alt="Foto"
                         class="rounded" style="max-height:150px;border:2px solid #eee">
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
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div class="mb-2 text-muted small">Status Pendaftaran</div>
                @if($pendaftaran->status === 'pending')
                    <span class="badge badge-pending" style="font-size:1rem;padding:.5rem 1.2rem">Menunggu Review</span>
                    <p class="text-muted small mt-3">Pendaftaran Anda sedang diproses oleh admin. Pantau terus halaman ini.</p>
                @elseif($pendaftaran->status === 'diterima')
                    <span class="badge badge-diterima" style="font-size:1rem;padding:.5rem 1.2rem">Diterima</span>
                    <p class="text-success small mt-3">Selamat! Pendaftaran Anda diterima. Silakan download bukti di bawah.</p>
                @else
                    <span class="badge badge-ditolak" style="font-size:1rem;padding:.5rem 1.2rem">Ditolak</span>
                    <p class="text-danger small mt-3">Mohon maaf, pendaftaran Anda tidak diterima.</p>
                @endif

                <a href="{{ route('mahasiswa.pendaftaran.pdf') }}" class="btn btn-outline-danger w-100 mt-2">
                    <i class="bi bi-file-pdf me-1"></i>Download Bukti PDF
                </a>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
