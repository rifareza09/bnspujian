@extends('layouts.app')
@section('title', 'Edit Status Pendaftaran')
@section('page-title', 'Edit Status Pendaftaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header py-3">
                <i class="bi bi-pencil me-2"></i>Update Status: {{ $pendaftaran->nama_lengkap }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.pendaftarans.update', $pendaftaran) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Status Pendaftaran</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $pendaftaran->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="diterima" {{ $pendaftaran->status === 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ $pendaftaran->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </button>
                        <a href="{{ route('admin.pendaftarans') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
