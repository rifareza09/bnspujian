@extends('layouts.app')
@section('title', 'Formulir Pendaftaran')
@section('page-title', 'Formulir Pendaftaran Mahasiswa Baru')

@push('styles')
<style>
    .section-title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #1a3a6b; border-bottom: 2px solid #1a3a6b; padding-bottom: 6px; margin-bottom: 1.2rem; }
    .form-label { font-size: 0.85rem; font-weight: 600; color: #444; }
    .alert-field { display: none; font-size: 0.78rem; color: #dc3545; margin-top: 4px; }
    .alert-field.show { display: block; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header py-3">
        <i class="bi bi-pencil-square me-2"></i>Isi Formulir Pendaftaran
        <small class="text-muted ms-2">Semua field bertanda * wajib diisi</small>
    </div>
    <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i><strong>Terdapat kesalahan input:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $err)
                    <li class="small">{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('mahasiswa.pendaftaran.store') }}" method="POST"
              enctype="multipart/form-data" id="formPendaftaran">
            @csrf

            {{-- DATA PRIBADI --}}
            <div class="section-title">Data Pribadi</div>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label">Nama Lengkap (sesuai ijazah) *</label>
                    <input type="text" name="nama_lengkap" class="form-control"
                           value="{{ old('nama_lengkap') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat KTP *</label>
                    <textarea name="alamat_ktp" class="form-control" rows="2" required>{{ old('alamat_ktp') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat Lengkap Saat Ini *</label>
                    <textarea name="alamat_sekarang" class="form-control" rows="2" required>{{ old('alamat_sekarang') }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kecamatan *</label>
                    <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Provinsi *</label>
                    <select name="provinsi_id" id="provinsiSelect" class="form-select" required>
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinsis as $p)
                            <option value="{{ $p->id }}" {{ old('provinsi_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kabupaten/Kota *</label>
                    <select name="kabupaten_id" id="kabupatenSelect" class="form-select" required>
                        <option value="">-- Pilih dulu Provinsi --</option>
                    </select>
                    <div class="alert-field" id="kabupatenAlert">Pilih kabupaten/kota terlebih dahulu.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="telepon" id="teleponInput" class="form-control"
                           value="{{ old('telepon') }}" placeholder="021xxxxxxx">
                    <div class="alert-field" id="teleponAlert">Nomor telepon harus berupa angka.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor HP *</label>
                    <input type="text" name="hp" id="hpInput" class="form-control"
                           value="{{ old('hp') }}" placeholder="08xxxxxxxxxx" required>
                    <div class="alert-field" id="hpAlert">Nomor HP harus berupa angka (10-15 digit).</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="text" name="email" id="emailInput" class="form-control"
                           value="{{ old('email') }}" required>
                    <div class="alert-field" id="emailAlert">Format email tidak valid.</div>
                </div>
            </div>

            {{-- IDENTITAS --}}
            <div class="section-title">Identitas Diri</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Kewarganegaraan *</label>
                    <select name="kewarganegaraan" class="form-select" required id="kewargaSelect">
                        <option value="WNI Asli" {{ old('kewarganegaraan', 'WNI Asli') === 'WNI Asli' ? 'selected' : '' }}>WNI Asli</option>
                        <option value="WNI Keturunan" {{ old('kewarganegaraan') === 'WNI Keturunan' ? 'selected' : '' }}>WNI Keturunan</option>
                        <option value="WNA" {{ old('kewarganegaraan') === 'WNA' ? 'selected' : '' }}>WNA</option>
                    </select>
                </div>
                <div class="col-md-6" id="negaraAsalWrap" style="display:none">
                    <label class="form-label">Negara Asal (jika WNA)</label>
                    <input type="text" name="negara_asal" class="form-control" value="{{ old('negara_asal') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir *</label>
                    <input type="date" name="tanggal_lahir" class="form-control"
                           value="{{ old('tanggal_lahir') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tempat Lahir *</label>
                    <input type="text" name="tempat_lahir" class="form-control"
                           value="{{ old('tempat_lahir') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Negara Lahir (jika luar negeri)</label>
                    <input type="text" name="negara_lahir" class="form-control" value="{{ old('negara_lahir') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis Kelamin *</label>
                    <div class="d-flex gap-3 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" value="Pria"
                                   id="jenisP" {{ old('jenis_kelamin', 'Pria') === 'Pria' ? 'checked' : '' }}>
                            <label class="form-check-label" for="jenisP">Pria</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" value="Wanita"
                                   id="jenisW" {{ old('jenis_kelamin') === 'Wanita' ? 'checked' : '' }}>
                            <label class="form-check-label" for="jenisW">Wanita</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status Menikah *</label>
                    <select name="status_menikah" class="form-select" required>
                        @foreach(['Belum Menikah', 'Menikah', 'Lain-lain'] as $s)
                            <option value="{{ $s }}" {{ old('status_menikah', 'Belum Menikah') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Agama *</label>
                    <select name="agama" class="form-select" required>
                        @foreach(['Islam', 'Katolik', 'Kristen', 'Hindu', 'Budha', 'Lain-lain'] as $a)
                            <option value="{{ $a }}" {{ old('agama') === $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- DOKUMEN & MULTIMEDIA --}}
            <div class="section-title">Foto & Dokumen</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Foto (jpg/png, maks 2MB)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" id="fotoInput">
                    <div id="fotoPreview" class="mt-2"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dokumen Pendukung (pdf/jpg/png, maks 5MB)</label>
                    <input type="file" name="dokumen" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                    <i class="bi bi-send me-2"></i>Kirim Pendaftaran
                </button>
                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Dropdown kabupaten dinamis via fetch
    const provinsiSelect   = document.getElementById('provinsiSelect');
    const kabupatenSelect  = document.getElementById('kabupatenSelect');
    const oldKabupaten     = '{{ old("kabupaten_id") }}';

    function loadKabupatens(provinsiId, selectedId = null) {
        kabupatenSelect.innerHTML = '<option value="">Memuat...</option>';
        fetch('/api/kabupatens/' + provinsiId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
            data.forEach(k => {
                const opt = document.createElement('option');
                opt.value = k.id;
                opt.textContent = k.nama;
                if (selectedId && k.id == selectedId) opt.selected = true;
                kabupatenSelect.appendChild(opt);
            });
        })
        .catch(() => {
            kabupatenSelect.innerHTML = '<option value="">Gagal memuat data</option>';
        });
    }

    provinsiSelect.addEventListener('change', function() {
        if (this.value) {
            loadKabupatens(this.value);
        } else {
            kabupatenSelect.innerHTML = '<option value="">-- Pilih dulu Provinsi --</option>';
        }
    });

    // Load kabupaten kalau ada old value
    if (provinsiSelect.value && oldKabupaten) {
        loadKabupatens(provinsiSelect.value, oldKabupaten);
    }

    // Validasi HP - hanya angka
    document.getElementById('hpInput').addEventListener('input', function() {
        const alert = document.getElementById('hpAlert');
        if (this.value && !/^\d{10,15}$/.test(this.value)) {
            alert.classList.add('show');
            this.classList.add('is-invalid');
        } else {
            alert.classList.remove('show');
            this.classList.remove('is-invalid');
        }
    });

    // Validasi telepon - hanya angka
    document.getElementById('teleponInput').addEventListener('input', function() {
        const alert = document.getElementById('teleponAlert');
        if (this.value && !/^\d+$/.test(this.value)) {
            alert.classList.add('show');
            this.classList.add('is-invalid');
        } else {
            alert.classList.remove('show');
            this.classList.remove('is-invalid');
        }
    });

    // Validasi email real-time
    document.getElementById('emailInput').addEventListener('blur', function() {
        const alert = document.getElementById('emailAlert');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailRegex.test(this.value)) {
            alert.classList.add('show');
            this.classList.add('is-invalid');
        } else {
            alert.classList.remove('show');
            this.classList.remove('is-invalid');
        }
    });

    // Tampilkan/sembunyikan field negara asal (WNA)
    document.getElementById('kewargaSelect').addEventListener('change', function() {
        document.getElementById('negaraAsalWrap').style.display = this.value === 'WNA' ? '' : 'none';
    });
    // cek nilai awal
    if (document.getElementById('kewargaSelect').value === 'WNA') {
        document.getElementById('negaraAsalWrap').style.display = '';
    }

    // Preview foto
    document.getElementById('fotoInput').addEventListener('change', function() {
        const preview = document.getElementById('fotoPreview');
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML = `<img src="${e.target.result}" class="rounded" style="max-height:120px;border:2px solid #eee">`;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Submit validation: cek kabupaten dipilih
    document.getElementById('formPendaftaran').addEventListener('submit', function(e) {
        let valid = true;

        if (!kabupatenSelect.value) {
            document.getElementById('kabupatenAlert').classList.add('show');
            kabupatenSelect.classList.add('is-invalid');
            valid = false;
        }

        const hp = document.getElementById('hpInput');
        if (hp.value && !/^\d{10,15}$/.test(hp.value)) {
            document.getElementById('hpAlert').classList.add('show');
            hp.classList.add('is-invalid');
            valid = false;
        }

        const email = document.getElementById('emailInput');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email.value && !emailRegex.test(email.value)) {
            document.getElementById('emailAlert').classList.add('show');
            email.classList.add('is-invalid');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            // scroll ke error pertama
            document.querySelector('.is-invalid')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endpush
