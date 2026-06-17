# 🎓 Sistem Pendaftaran Mahasiswa (BNSP)

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

Sistem Informasi Pendaftaran Mahasiswa Baru berbasis web yang dirancang khusus untuk memenuhi kebutuhan proses pendaftaran, validasi data, dan pencetakan bukti secara otomatis. Aplikasi ini dibangun dengan standar industri menggunakan framework **Laravel**, memberikan performa tinggi, keamanan yang kuat, dan antarmuka yang responsif.

---

## ✨ Fitur Unggulan

- **📝 Pendaftaran Terintegrasi**: Form pendaftaran yang komprehensif dengan validasi data real-time, memastikan integritas data calon mahasiswa.
- **📄 Ekspor Bukti PDF Otomatis**: Menghasilkan dokumen bukti pendaftaran dalam format PDF secara instan setelah proses pendaftaran selesai.
- **🔐 Role-Based Access Control (RBAC)**: Pemisahan hak akses yang tegas antara Administrator (pengelola) dan Mahasiswa (pendaftar).
- **📊 Dashboard Interaktif**: 
  - **Admin**: Fitur monitoring data pendaftar, validasi dokumen, dan manajemen pengguna.
  - **Mahasiswa**: Fitur pelacakan status pendaftaran dan pengunduhan berkas.
- **🗺️ Manajemen Wilayah Dinamis**: Implementasi AJAX untuk pemilihan Provinsi dan Kabupaten/Kota yang responsif dan terintegrasi.
- **📁 Upload Dokumen Aman**: Sistem penyimpanan foto profil dan dokumen pendukung yang terstruktur dan aman.

---

## 🛠️ Persyaratan Sistem (System Requirements)

Sebelum memulai instalasi, pastikan lingkungan server Anda memenuhi spesifikasi berikut:

- **PHP**: Versi `^8.3` atau lebih baru
- **Composer**: Versi `2.x`
- **Node.js**: Versi `18.x` atau lebih baru (beserta NPM)
- **Database**: MySQL `8.0+` / MariaDB `10.4+` / PostgreSQL / SQLite
- **Web Server**: Nginx atau Apache
- **Ekstensi PHP Wajib**: 
  - `OpenSSL`, `PDO`, `Mbstring`, `Tokenizer`, `XML`, `Ctype`, `JSON`, `BCMath`, `GD` (untuk manipulasi gambar/PDF)

---

## 🚀 Panduan Instalasi (Installation Guide)

Ikuti langkah-langkah sistematis berikut untuk mengonfigurasi dan menjalankan proyek di lingkungan pengembangan lokal (*local environment*):

### 1. Kloning Repositori
Ambil salinan kode sumber dari repositori GitHub:
```bash
git clone https://github.com/rifareza09/bnspujian.git
cd bnspujian
```

### 2. Instalasi Dependensi (Backend & Frontend)
Instal semua pustaka PHP dan aset Javascript yang dibutuhkan:
```bash
composer install
npm install
npm run build
```

### 3. Konfigurasi Lingkungan (Environment)
Salin file template environment dan sesuaikan dengan konfigurasi sistem Anda:
```bash
cp .env.example .env
```
Buka file `.env` di teks editor, dan atur bagian koneksi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=password_database_anda
```

### 4. Pembuatan Kunci Aplikasi (App Key)
Generate kunci enkripsi unik untuk keamanan aplikasi (session, hashing, dll):
```bash
php artisan key:generate
```

### 5. Migrasi dan Seeding Database
Bangun struktur tabel database dan isi dengan data awal (*dummy data*, data admin, daftar provinsi/kabupaten):
```bash
php artisan migrate --seed
```

### 6. Konfigurasi Penyimpanan (Storage)
Buat *symlink* agar file yang diunggah (foto dan dokumen pendaftaran) dapat diakses melalui browser:
```bash
php artisan storage:link
```

### 7. Menjalankan Server Lokal
Mulai jalankan development server bawaan Laravel:
```bash
php artisan serve
```
Aplikasi kini dapat diakses melalui browser di alamat: `http://localhost:8000`

---

## 📂 Struktur Direktori Utama

Berikut adalah bagian terpenting dari arsitektur aplikasi ini bagi pengembang:

```text
bnspujian/
├── app/
│   ├── Http/
│   │   ├── Controllers/   # Logika bisnis utama (PendaftaranController, AdminController)
│   │   └── Middleware/    # Filter akses HTTP (CheckRole)
│   └── Models/            # Representasi tabel database (Pendaftaran, User, Provinsi)
├── database/
│   ├── migrations/        # Skema tabel database
│   └── seeders/           # Data awal untuk testing/produksi
├── public/                # Folder root untuk web server (CSS, JS, Images terkompilasi)
├── resources/
│   └── views/             # Template antarmuka pengguna (Blade templates)
├── routes/
│   └── web.php            # Definisi semua rute dan URL aplikasi
└── storage/
    └── app/public/        # Lokasi penyimpanan file unggahan mahasiswa
```

---

## 📚 Teknologi Tambahan yang Digunakan

- **[barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)**: Pustaka handal untuk merender tampilan HTML ke dokumen PDF.
- **[TailwindCSS](https://tailwindcss.com/)** / **[Bootstrap](https://getbootstrap.com/)**: Framework utility untuk mendesain antarmuka secara cepat dan modern.

---

> **Catatan Pengembang**: Aplikasi ini dikembangkan khusus sebagai *Project Final* untuk memenuhi persyaratan Uji Kompetensi Keahlian (BNSP) bidang Web Developer. 
