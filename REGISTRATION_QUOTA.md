# Fitur: Pembatasan Kuota Pendaftaran Mahasiswa

## Daftar Isi
1. [Deskripsi](#deskripsi)
2. [Requirement](#requirement)
3. [Instalasi & Setup](#instalasi--setup)
4. [Implementasi](#implementasi)
5. [Testing](#testing)
6. [Troubleshooting](#troubleshooting)

---

## Deskripsi

Fitur ini membatasi jumlah mahasiswa yang dapat melakukan pendaftaran dalam sistem PMB Online. Sistem akan **menolak pendaftaran baru** ketika jumlah pendaftar mencapai batas maksimal yang telah ditentukan.

**Fitur Utama:**
- ✅ Validasi kuota sebelum halaman form ditampilkan
- ✅ Validasi kuota ulang saat data disubmit (prevent race condition)
- ✅ Pesan error yang user-friendly
- ✅ Konfigurasi kuota yang mudah diubah
- ✅ Logging untuk audit trail

---

## Requirement

- PHP >= 8.3
- Laravel >= 13.8
- Database: MySQL / Laragon
- Tabel `pendaftarans` sudah ada dan terisi data

**Dependency:**
- Tidak ada dependency tambahan (menggunakan Laravel built-in methods)

---

## Instalasi & Setup

### 1. Database Check

Pastikan tabel `pendaftarans` sudah ada dan berisi data:

```bash
php artisan migrate
```

### 2. Konfigurasi (Optional)

Jika ingin mengubah default quota limit dari 4, ada 2 cara:

**Opsi A: Menggunakan Environment Variable (.env)**
```env
REGISTRATION_QUOTA_LIMIT=4
```

Kemudian di Config (jika Anda membuat config file):
```php
// config/registration.php
return [
    'quota_limit' => env('REGISTRATION_QUOTA_LIMIT', 4),
];
```

**Opsi B: Hardcode di Model (Sederhana)**
```php
// app/Models/Pendaftaran.php
const QUOTA_LIMIT = 4;
```

### 3. Clear Cache (Jika perlu)

```bash
php artisan cache:clear
php artisan config:cache
```

---

## Implementasi

### Langkah 1: Tambahkan Logic di Controller

File: `app/Http/Controllers/PendaftaranController.php`

#### A. Di Function `create()` (Baris ~21)

Tambahkan pengecekan kuota **sebelum** menampilkan form:

```php
public function create()
{
    if (Auth::user()->pendaftaran) {
        return redirect()->route('mahasiswa.dashboard')
            ->with('info', 'Anda sudah mendaftar.');
    }

    // ===== PENGECEKAN KUOTA (TAMBAHAN) =====
    $totalPendaftaran = Pendaftaran::count();
    $quotaLimit = 4;
    
    if ($totalPendaftaran >= $quotaLimit) {
        return redirect()->route('mahasiswa.dashboard')
            ->with('error', 'Maaf, kuota pendaftaran sudah penuh. Batas maksimal adalah ' . $quotaLimit . ' mahasiswa.');
    }
    // ===== AKHIR PENAMBAHAN =====

    $provinsis = Provinsi::orderBy('nama')->get();
    return view('mahasiswa.pendaftaran.create', compact('provinsis'));
}
```

**Penjelasan:**
- `Pendaftaran::count()` → hitung total pendaftar
- Jika `>= 4`, redirect ke dashboard dengan pesan error
- User tidak akan melihat form jika kuota penuh

#### B. Di Function `store()` (Baris ~32, setelah validasi)

Tambahkan pengecekan kuota **saat submit** untuk security:

```php
public function store(Request $request)
{
    $timeStart = microtime(true);

    if (Auth::user()->pendaftaran) {
        return redirect()->route('mahasiswa.dashboard');
    }

    $request->validate([
        'nama_lengkap'    => 'required|string|max:255',
        'alamat_ktp'      => 'required|string',
        // ... rules lainnya ...
    ], [
        // ... custom messages ...
    ]);

    // ===== PENGECEKAN KUOTA (TAMBAHAN) =====
    $totalPendaftaran = Pendaftaran::count();
    $quotaLimit = 4;
    
    if ($totalPendaftaran >= $quotaLimit) {
        return redirect()->back()
            ->with('error', 'Maaf, kuota pendaftaran sudah penuh. Tidak bisa melanjutkan proses pendaftaran.')
            ->withInput();
    }
    // ===== AKHIR PENAMBAHAN =====

    $data = $request->except(['foto', 'dokumen', '_token']);

    if ($request->hasFile('foto')) {
        $data['foto'] = $request->file('foto')->store('foto', 'public');
    }

    if ($request->hasFile('dokumen')) {
        $data['dokumen'] = $request->file('dokumen')->store('dokumen', 'public');
    }

    $data['user_id'] = Auth::id();
    $data['status']  = 'pending';

    Pendaftaran::create($data);

    $timeEnd = microtime(true);
    $executionTime = round(($timeEnd - $timeStart) * 1000, 2);
    \Log::info("Pendaftaran store() execution time: {$executionTime}ms");

    return redirect()->route('mahasiswa.dashboard')
        ->with('success', 'Pendaftaran berhasil dikirim!');
}
```

**Penjelasan:**
- Sebelum `Pendaftaran::create()`, check kuota lagi
- Ini mencegah **race condition** (2 user submit bersamaan di detik sama)
- `withInput()` → form input tetap tersimpan jika ada error

---

### Langkah 2: (OPSIONAL) Refactor ke Model untuk Code Reusability

File: `app/Models/Pendaftaran.php`

Tambahkan methods untuk membuat code lebih clean:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $fillable = [
        'user_id', 'nama_lengkap', 'alamat_ktp', 'alamat_sekarang',
        'kecamatan', 'kabupaten_id', 'provinsi_id', 'telepon', 'hp', 'email',
        'kewarganegaraan', 'negara_asal', 'tanggal_lahir', 'tempat_lahir',
        'negara_lahir', 'jenis_kelamin', 'status_menikah', 'agama',
        'foto', 'dokumen', 'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // ===== PENAMBAHAN: QUOTA MANAGEMENT (OPSIONAL) =====
    const QUOTA_LIMIT = 4;

    /**
     * Check apakah kuota pendaftaran sudah penuh
     * 
     * @return bool
     */
    public static function isQuotaFull()
    {
        return self::count() >= self::QUOTA_LIMIT;
    }

    /**
     * Dapatkan sisa kuota yang tersedia
     * 
     * @return int
     */
    public static function getRemainingQuota()
    {
        return max(0, self::QUOTA_LIMIT - self::count());
    }

    /**
     * Dapatkan total pendaftar saat ini
     * 
     * @return int
     */
    public static function getTotalRegistered()
    {
        return self::count();
    }
    // ===== AKHIR PENAMBAHAN =====

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }
}
```

**Dengan refactor ini, Controller bisa lebih sederhana:**

```php
// Di function create()
if (Pendaftaran::isQuotaFull()) {
    return redirect()->route('mahasiswa.dashboard')
        ->with('error', 'Kuota pendaftaran sudah penuh. Sisa: ' . Pendaftaran::getRemainingQuota());
}

// Di function store()
if (Pendaftaran::isQuotaFull()) {
    return redirect()->back()
        ->with('error', 'Kuota pendaftaran sudah penuh. Tidak bisa lanjut.')
        ->withInput();
}
```

---

### Langkah 3: (OPSIONAL) Update View untuk Menampilkan Status Quota

File: `resources/views/mahasiswa/pendaftaran/create.blade.php`

Tambahkan quota status di halaman form:

```blade
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            
            {{-- TAMBAHAN: Tampilkan Status Quota --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Tampilkan sisa kuota (opsional) --}}
            @php
                $remaining = \App\Models\Pendaftaran::getRemainingQuota();
            @endphp
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                Sisa kuota pendaftaran: <strong>{{ $remaining }} dari 4</strong> tempat
            </div>

            <form action="{{ route('mahasiswa.pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- form fields ... -->
            </form>
        </div>
    </div>
</div>
```

---

## Testing

### Test Case 1: Kuota Belum Penuh
```
Pre-condition: Pendaftar saat ini = 2 orang
1. User login sebagai mahasiswa yang belum mendaftar
2. Akses route: GET /mahasiswa/pendaftaran/buat
3. Expected: Form ditampilkan, tidak ada error
4. Status: ✅ PASS
```

### Test Case 2: Kuota Penuh (Cek di create())
```
Pre-condition: Pendaftar saat ini = 4 orang
1. User login sebagai mahasiswa yang belum mendaftar
2. Akses route: GET /mahasiswa/pendaftaran/buat
3. Expected: Redirect ke dashboard dengan pesan "kuota penuh"
4. Status: ✅ PASS
```

### Test Case 3: Kuota Penuh (Cek di store())
```
Pre-condition: Pendaftar = 3, User A & B submit form bersamaan
1. User A submit form di detik ke-1
2. User B submit form di detik ke-1 juga (sebelum User A's data disimpan)
3. Expected: User A berhasil (total jadi 4), User B gagal dengan error "kuota penuh"
4. Status: ✅ PASS (mencegah race condition)
```

### Test Case 4: User yang Sudah Mendaftar
```
Pre-condition: User sudah punya 1 pendaftaran
1. User akses route: GET /mahasiswa/pendaftaran/buat
2. Expected: Redirect ke dashboard dengan pesan "Anda sudah mendaftar"
3. Status: ✅ PASS
```

### Jalankan Test via Command Line

```bash
# Buat test file
php artisan make:test RegistrationQuotaTest

# Jalankan test
php artisan test tests/Feature/RegistrationQuotaTest.php
```

---

## Troubleshooting

### Problem 1: Error "Kuota penuh" padahal belum 4 pendaftar

**Solusi:**
- Check database: `SELECT COUNT(*) FROM pendaftarans;`
- Mungkin ada pending registrations dari test
- Clear: `php artisan db:seed:fresh` (hati-hati, ini hapus semua data)

### Problem 2: User bisa submit form meskipun kuota penuh

**Solusi:**
- Pastikan kedua pengecekan (create() dan store()) sudah ditambahkan
- Clear cache: `php artisan cache:clear`
- Reload browser (hard refresh): `Ctrl+Shift+R`

### Problem 3: Pesan error tidak muncul di view

**Solusi:**
- Check blade template punya `@if (session('error'))`
- Pastikan form action mengarah ke route yang benar
- Debug dengan `dd(session()->all())` di view

### Problem 4: Kuota bisa diubah setelah production

**Solusi Terbaik:**
- Gunakan config file: `config/registration.php`
- Admin bisa update via environment variable tanpa edit kode
- Atau buat database table `settings` untuk konfigurasi dynamic

Contoh Config File:
```php
// config/registration.php
return [
    'quota_limit' => env('REGISTRATION_QUOTA_LIMIT', 4),
    'quota_warning_percentage' => 0.75, // Ingatkan admin saat 75% quota terisi
];
```

---

## Performance Consideration

### Query Optimization

Jika `Pendaftaran::count()` lambat di production (jutaan records), gunakan:

```php
// SLOW (scans all rows)
$count = Pendaftaran::count();

// BETTER (jika ada cache)
$count = Cache::remember('pending_registrations_count', 3600, function () {
    return Pendaftaran::where('status', 'pending')->count();
});
```

---

## Logging & Monitoring

Aplikasi sudah punya monitoring built-in. Untuk track kuota yang penuh:

```php
// Di function create() atau store()
if (Pendaftaran::isQuotaFull()) {
    \Log::warning('Registration quota full. User: ' . Auth::id());
}
```

Check log:
```bash
tail -f storage/logs/laravel.log
```

---

## Versioning & Updates

**Current Version:** 1.0.0

Jika ingin tambah fitur di masa depan:
- Waitlist mechanism (queue jika kuota penuh)
- Dynamic quota per tahun akademik
- Admin dashboard untuk manage quota

---

## Reference

- [Laravel Query Builder - count()](https://laravel.com/docs/13.x/queries#count-rows)
- [Laravel Sessions](https://laravel.com/docs/13.x/session)
- [Redirect with Flash Data](https://laravel.com/docs/13.x/redirects#redirecting-with-flashed-session-data)
- [Race Condition Prevention](https://en.wikipedia.org/wiki/Race_condition)

---

## FAQ

**Q: Apakah quota bisa diubah per akademik tahun?**
A: Ya, bisa dengan menambahkan kolom `year` di tabel `pendaftarans` dan filter: `Pendaftaran::whereYear('created_at', date('Y'))->count()`

**Q: Bagaimana jika ingin admin bisa set quota dari dashboard?**
A: Buat table `settings` dan baca dari sana: `Settings::getValue('registration_quota')`

**Q: Apakah quota count termasuk status pending/approved/rejected?**
A: Sesuaikan logic - saat ini count semua. Jika hanya pending: `Pendaftaran::where('status', 'pending')->count()`

---

## Kontribusi & Support

Untuk improvement atau bug report, dokumentasikan:
1. Kondisi saat ini
2. Expected behavior
3. Actual behavior
4. Steps to reproduce

---

## License

MIT License - Lihat LICENSE file

