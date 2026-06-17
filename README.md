# Sistem Pendaftaran Mahasiswa Baru (PMB)

Sistem Informasi Pendaftaran Mahasiswa Baru berbasis web. Aplikasi ini dibangun dengan kerangka kerja Laravel dan bertujuan untuk mendigitalisasi proses pendaftaran mahasiswa, validasi dokumen, hingga pencetakan bukti pendaftaran.

## Fitur Utama

- Pendaftaran Mahasiswa: Formulir pendaftaran terpadu dengan validasi input.
- Ekspor Bukti PDF: Sistem otomatis yang membuat (generate) dokumen PDF sebagai bukti pendaftaran yang sah.
- Role-Based Access Control (RBAC): Pembagian akses antara Administrator dan Calon Mahasiswa.
- Dashboard Admin: Pengelolaan pengguna, verifikasi data pendaftar, dan pemantauan status pendaftaran.
- Wilayah Dinamis: Implementasi pemilihan Provinsi dan Kabupaten/Kota menggunakan AJAX.

## Persyaratan Sistem

- PHP ^8.3
- Composer 2.x
- Node.js 18.x atau lebih baru
- Database Server (MySQL/MariaDB/PostgreSQL)
- Ekstensi PHP: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, GD (untuk pemrosesan PDF)

## Panduan Instalasi

1. Clone repositori:
   ```bash
   git clone https://github.com/rifareza09/bnspujian.git
   cd bnspujian
   ```

2. Install dependensi composer dan NPM:
   ```bash
   composer install
   npm install
   npm run build
   ```

3. Konfigurasi environment:
   ```bash
   cp .env.example .env
   ```
   Sesuaikan variabel database pada `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Jalankan migrasi dan seeder:
   Langkah ini akan membuat tabel database dan mengisi data referensi (admin, provinsi, dan kabupaten).
   ```bash
   php artisan migrate --seed
   ```

6. Buat storage link:
   Diperlukan agar file foto dan dokumen yang diunggah mahasiswa dapat diakses dari browser.
   ```bash
   php artisan storage:link
   ```

7. Jalankan local development server:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di http://localhost:8000.

## Penjelasan Kode Utama (Developer Guide)

Bagian ini ditujukan bagi developer yang akan memelihara atau mengembangkan aplikasi lebih lanjut. Berikut adalah beberapa segmen kode krusial yang mengontrol alur aplikasi.

### 1. Middleware Pengecekan Hak Akses (CheckRole)
File: `app/Http/Middleware/CheckRole.php`

Digunakan untuk memastikan pengguna yang login sesuai dengan peran (role) rute yang dituju.
```php
public function handle(Request $request, Closure $next, string $role)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    if (Auth::user()->role !== $role) {
        abort(403, 'Akses ditolak.');
    }

    return $next($request);
}
```
*Penggunaan di route:* `Route::middleware(['auth', 'role:admin'])`

### 2. Generate PDF Bukti Pendaftaran
File: `app/Http/Controllers/PendaftaranController.php` (Metode `exportPdf`)

Menerapkan library `barryvdh/laravel-dompdf`. Controller akan meload view HTML dan merendernya menjadi PDF.
```php
public function exportPdf()
{
    $pendaftaran = Auth::user()->pendaftaran;
    if (!$pendaftaran) {
        return redirect()->route('mahasiswa.dashboard');
    }
    
    // Eager loading relasi yang dibutuhkan pada view PDF
    $pendaftaran->load(['provinsi', 'kabupaten', 'user']);
    $pdf = Pdf::loadView('mahasiswa.pendaftaran.pdf', compact('pendaftaran'));

    return $pdf->download('bukti-pendaftaran-' . $pendaftaran->id . '.pdf');
}
```

### 3. Endpoint AJAX Data Kabupaten
File: `app/Http/Controllers/PendaftaranController.php` (Metode `kabupatenByProvinsi`)

Endpoint API yang digunakan oleh frontend (Javascript) untuk memuat daftar kabupaten secara spesifik berdasarkan ID provinsi yang dipilih pengguna.
```php
public function kabupatenByProvinsi($provinsiId)
{
    $kabupatens = Kabupaten::where('provinsi_id', $provinsiId)
                           ->orderBy('nama')
                           ->get(['id', 'nama']);
                           
    return response()->json($kabupatens);
}
```

## Referensi Pustaka
- Laravel Framework: ^11.x
- barryvdh/laravel-dompdf: Pembuatan dokumen PDF
- UI Framework: Bootstrap / TailwindCSS

---
Aplikasi ini dikembangkan untuk kebutuhan operasional sistem pendaftaran dan evaluasi uji kompetensi.
