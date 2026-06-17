@extends('layouts.app')
@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #1a3a6b, #0d2148); color:#fff; border:none;">
            <div class="card-body d-flex align-items-center gap-4 py-4">
                <div style="width:64px;height:64px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div>
                    <h5 class="mb-1">Selamat datang, {{ auth()->user()->name }}!</h5>
                    <p class="mb-0 opacity-75 small">
                        @if($pendaftaran)
                            Pendaftaran Anda sudah masuk. Pantau statusnya di bawah ini.
                        @else
                            Anda belum melakukan pendaftaran. Segera daftarkan diri Anda.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if($pendaftaran)
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <span><i class="bi bi-file-earmark-text me-2"></i>Status Pendaftaran Anda</span>
                    <a href="{{ route('mahasiswa.pendaftaran.pdf') }}" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-file-pdf me-1"></i>Download Bukti PDF
                    </a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Nama Lengkap</div>
                            <div class="fw-semibold">{{ $pendaftaran->nama_lengkap }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Status</div>
                            <div>
                                @if($pendaftaran->status === 'pending')
                                    <span class="badge badge-pending fs-6">Menunggu Review</span>
                                @elseif($pendaftaran->status === 'diterima')
                                    <span class="badge badge-diterima fs-6">Diterima</span>
                                @else
                                    <span class="badge badge-ditolak fs-6">Ditolak</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Asal Daerah</div>
                            <div>{{ $pendaftaran->kabupaten->nama ?? '-' }}, {{ $pendaftaran->provinsi->nama ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Tanggal Daftar</div>
                            <div>{{ $pendaftaran->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('mahasiswa.pendaftaran.show') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-1"></i>Lihat Detail Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="bi bi-clipboard-plus text-muted" style="font-size:3rem"></i>
                    <h5 class="mt-3">Belum Ada Pendaftaran</h5>
                    <p class="text-muted">Silakan isi formulir pendaftaran mahasiswa baru untuk memulai.</p>
                    <a href="{{ route('mahasiswa.pendaftaran.create') }}" class="btn btn-primary px-4">
                        <i class="bi bi-pencil-square me-2"></i>Isi Formulir Pendaftaran
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
