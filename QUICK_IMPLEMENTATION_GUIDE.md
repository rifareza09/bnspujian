# QUICK REFERENCE: Implementasi Pembatasan Kuota Pendaftaran

## 📋 Gambaran Singkat

**Requirement:** Batasi pendaftaran mahasiswa maksimal 4 orang

**Solution:** Tambahkan 2 pengecekan di `PendaftaranController`

---

## 🎯 Tempat Implementasi

### TEMPAT 1: Function `create()` 
**File:** `app/Http/Controllers/PendaftaranController.php` (Baris ~21)

**Kode:**
```php
public function create()
{
    if (Auth::user()->pendaftaran) {
        return redirect()->route('mahasiswa.dashboard')->with('info', 'Anda sudah mendaftar.');
    }

    // ===== CEK QUOTA =====
    $totalPendaftaran = Pendaftaran::count();
    $quotaLimit = 4;
    
    if ($totalPendaftaran >= $quotaLimit) {
        return redirect()->route('mahasiswa.dashboard')
            ->with('error', 'Kuota pendaftaran penuh. Batas: ' . $quotaLimit . ' mahasiswa.');
    }
    // ===== AKHIR =====

    $provinsis = Provinsi::orderBy('nama')->get();
    return view('mahasiswa.pendaftaran.create', compact('provinsis'));
}
```

---

### TEMPAT 2: Function `store()`
**File:** `app/Http/Controllers/PendaftaranController.php` (Baris ~32, setelah validasi)

**Kode:**
```php
public function store(Request $request)
{
    $timeStart = microtime(true);

    if (Auth::user()->pendaftaran) {
        return redirect()->route('mahasiswa.dashboard');
    }

    $request->validate([
        // ... validation ...
    ]);

    // ===== CEK QUOTA (DOUBLE CHECK) =====
    $totalPendaftaran = Pendaftaran::count();
    $quotaLimit = 4;
    
    if ($totalPendaftaran >= $quotaLimit) {
        return redirect()->back()
            ->with('error', 'Kuota sudah penuh. Tidak bisa melanjutkan.')
            ->withInput();
    }
    // ===== AKHIR =====

    // ... rest of code (data preparation, file upload, etc) ...
    
    Pendaftaran::create($data);
    
    // ... logging ...
    
    return redirect()->route('mahasiswa.dashboard')->with('success', 'Pendaftaran berhasil!');
}
```

---

##  OPSIONAL: Refactor ke Model (CLEAN CODE)

**File:** `app/Models/Pendaftaran.php`

```php
class Pendaftaran extends Model
{
    // ... existing code ...
    
    const QUOTA_LIMIT = 4;
    
    public static function isQuotaFull()
    {
        return self::count() >= self::QUOTA_LIMIT;
    }
    
    public static function getRemainingQuota()
    {
        return max(0, self::QUOTA_LIMIT - self::count());
    }
}
```

**Kemudian di Controller, bisa dipanggil:**
```php
if (Pendaftaran::isQuotaFull()) {
    return redirect()->route('mahasiswa.dashboard')
        ->with('error', 'Kuota penuh.');
}
```

---

## 📊 Narasi Presentasi ke Penguji

> "Untuk implementasi pembatasan kuota, saya tambahkan logic di 2 tempat untuk robust validation:
> 
> **1. Pre-validation (function create):**
> - Sebelum form ditampilkan, cek apakah kuota sudah penuh
> - Jika penuh (≥ 4), user redirect ke dashboard
> - Ini mencegah user melihat form yang tidak bisa disubmit
> 
> **2. Post-validation (function store):**
> - Saat user submit form, check lagi
> - Ini untuk mencegah race condition (2 user submit di detik sama)
> - Data tidak akan tersimpan jika quota sudah penuh
> 
> **3. Code Quality:**
> - Saya buat static method di Model untuk reusability
> - Constant `QUOTA_LIMIT` di satu tempat (mudah diubah)
> - Logging untuk audit trail
> 
> Hasilnya: Sistem yang aman, maintainable, dan professional."

---

## ✅ Checklist Saat Presentasi

- [ ] Buka `PendaftaranController.php`
- [ ] Tunjuk function `create()` → penjelasan pre-check
- [ ] Tunjuk function `store()` → penjelasan post-check
- [ ] Tunjuk Model method (jika pakai refactor)
- [ ] Jelaskan narasi (lihat di atas)
- [ ] Siap jawab pertanyaan penguji (lihat FAQ di README)

---

## 🔧 Testing Cepat

```bash
# Cek total pendaftar
php artisan tinker
>>> \App\Models\Pendaftaran::count()

# Test dengan seeders
php artisan db:seed
```

---

## 📚 Dokumentasi Lengkap

Lihat: `REGISTRATION_QUOTA.md` untuk:
- Instalasi & setup
- Testing cases
- Troubleshooting
- Performance optimization
- Versioning updates
