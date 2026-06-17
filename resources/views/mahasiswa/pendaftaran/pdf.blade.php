<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pendaftaran - {{ $pendaftaran->nama_lengkap }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 3px solid #1a3a6b; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { color: #1a3a6b; margin: 0 0 4px; font-size: 16px; }
        .header p { margin: 0; font-size: 11px; color: #666; }
        .badge-status { display: inline-block; padding: 4px 14px; border-radius: 20px; font-weight: bold; font-size: 11px; }
        .pending  { background: #fff3cd; color: #856404; }
        .diterima { background: #d1e7dd; color: #0a3622; }
        .ditolak  { background: #f8d7da; color: #842029; }
        .section-title { background: #1a3a6b; color: #fff; padding: 5px 10px; font-size: 11px; font-weight: bold; margin: 15px 0 8px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 5px 8px; vertical-align: top; }
        .label { color: #666; width: 38%; }
        .value { font-weight: 600; }
        tr:nth-child(even) { background: #f9f9f9; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        .no-pendaftaran { font-size: 20px; font-weight: bold; color: #1a3a6b; text-align: center; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>BUKTI PENDAFTARAN MAHASISWA BARU</h2>
        <p>Sistem PMB Online</p>
        <p style="margin-top:6px">
            Status:
            <span class="badge-status {{ $pendaftaran->status }}">
                {{ strtoupper($pendaftaran->status) }}
            </span>
        </p>
    </div>

    <div class="no-pendaftaran">No. Pendaftaran: PMB-{{ str_pad($pendaftaran->id, 5, '0', STR_PAD_LEFT) }}</div>
    <p style="text-align:center;color:#666;font-size:11px;margin-top:4px">
        Tanggal Daftar: {{ $pendaftaran->created_at->format('d F Y, H:i') }} WIB
    </p>

    <div class="section-title">DATA PRIBADI</div>
    <table>
        <tr><td class="label">Nama Lengkap</td><td class="value">{{ $pendaftaran->nama_lengkap }}</td></tr>
        <tr><td class="label">Email</td><td class="value">{{ $pendaftaran->email }}</td></tr>
        <tr><td class="label">No. HP</td><td class="value">{{ $pendaftaran->hp }}</td></tr>
        <tr><td class="label">No. Telepon</td><td class="value">{{ $pendaftaran->telepon ?? '-' }}</td></tr>
        <tr><td class="label">Alamat KTP</td><td class="value">{{ $pendaftaran->alamat_ktp }}</td></tr>
        <tr><td class="label">Alamat Sekarang</td><td class="value">{{ $pendaftaran->alamat_sekarang }}</td></tr>
        <tr><td class="label">Kecamatan</td><td class="value">{{ $pendaftaran->kecamatan }}</td></tr>
        <tr><td class="label">Kabupaten/Kota</td><td class="value">{{ $pendaftaran->kabupaten->nama }}</td></tr>
        <tr><td class="label">Provinsi</td><td class="value">{{ $pendaftaran->provinsi->nama }}</td></tr>
    </table>

    <div class="section-title">IDENTITAS DIRI</div>
    <table>
        <tr><td class="label">Kewarganegaraan</td><td class="value">{{ $pendaftaran->kewarganegaraan }}{{ $pendaftaran->negara_asal ? ' (' . $pendaftaran->negara_asal . ')' : '' }}</td></tr>
        <tr><td class="label">Tanggal Lahir</td><td class="value">{{ $pendaftaran->tanggal_lahir->format('d F Y') }}</td></tr>
        <tr><td class="label">Tempat Lahir</td><td class="value">{{ $pendaftaran->tempat_lahir }}{{ $pendaftaran->negara_lahir ? ' (' . $pendaftaran->negara_lahir . ')' : '' }}</td></tr>
        <tr><td class="label">Jenis Kelamin</td><td class="value">{{ $pendaftaran->jenis_kelamin }}</td></tr>
        <tr><td class="label">Status Menikah</td><td class="value">{{ $pendaftaran->status_menikah }}</td></tr>
        <tr><td class="label">Agama</td><td class="value">{{ $pendaftaran->agama }}</td></tr>
    </table>

    <div class="section-title">AKUN PENDAFTAR</div>
    <table>
        <tr><td class="label">Nama Akun</td><td class="value">{{ $pendaftaran->user->name }}</td></tr>
        <tr><td class="label">Email Akun</td><td class="value">{{ $pendaftaran->user->email }}</td></tr>
    </table>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh sistem PMB Online pada {{ now()->format('d/m/Y H:i') }} WIB<br>
        Simpan dokumen ini sebagai bukti pendaftaran Anda.
    </div>
</body>
</html>
