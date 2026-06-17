# Langkah Pengerjaan - Aplikasi PMB Online

## Kondisi Awal
- Laravel 13, fresh install
- DB masih SQLite, harus ganti MySQL
- Belum ada model, controller, views selain default
- Data kabupaten/kota tersedia di `daftarKebutuhanKota.md`

---

## Akun Test
- **Admin**: `admin@pmb.test` | Password: `admin123`
- **Mahasiswa**: `mhs@test.com` | Password: `mhs123`

## TAHAP 1 - Setup Database & Config (BE)
- [x] Ganti .env dari SQLite ke MySQL
- [ ] Buat database `pmb_online` di MySQL
- [ ] Buat migration: `provinsis`, `kabupatens`, `pendaftarans`
- [ ] Tambah kolom `role` di tabel `users` (admin / mahasiswa)
- [ ] Buat Model: Provinsi, Kabupaten, Pendaftaran
- [ ] Buat Seeder untuk import data provinsi & kabupaten dari daftarKebutuhanKota.md
- [ ] Buat Seeder untuk user admin default
- [ ] Jalankan migrate + seed

## TAHAP 2 - Autentikasi / Auth (BE + FE)
- [ ] Buat AuthController (login, logout, register)
- [ ] Buat middleware CheckRole untuk pisah akses admin vs mahasiswa
- [ ] Buat view: login.blade.php, register.blade.php
- [ ] Routing auth (login, logout, register)

## TAHAP 3 - Layout & Template (FE)
- [ ] Buat layout utama `layouts/app.blade.php` pakai Bootstrap 5
- [ ] Sidebar / navbar untuk navigasi
- [ ] Halaman dashboard admin
- [ ] Halaman dashboard mahasiswa

## TAHAP 4 - CRUD User oleh Admin (BE + FE)
- [ ] Buat UserController (index, create, store, edit, update, destroy)
- [ ] View: user/index, user/create, user/edit
- [ ] Routing resource untuk users
- [ ] Proteksi via middleware admin only

## TAHAP 5 - Formulir Pendaftaran Mahasiswa (BE + FE)
- [ ] Buat PendaftaranController (create, store, show)
- [ ] View formulir pendaftaran sesuai soal (semua field lengkap)
- [ ] API endpoint: GET /api/kabupatens?provinsi_id=x (untuk dropdown dinamis)
- [ ] Validasi server-side di controller
- [ ] Validasi client-side pakai JavaScript (email format, HP hanya angka)
- [ ] Upload foto/dokumen (multimedia)
- [ ] Alert JS jika ada field kosong / tidak sesuai format
- [ ] Simpan ke tabel pendaftarans

## TAHAP 6 - Kelola Data Pendaftaran oleh Admin (BE + FE)
- [ ] Buat method index, show, edit, update, destroy di PendaftaranController
- [ ] View: pendaftaran/index (list semua), pendaftaran/show (detail)
- [ ] Admin bisa lihat, edit, hapus data pendaftaran

## TAHAP 7 - Export PDF (BE)
- [ ] Install barryvdh/laravel-dompdf
- [ ] Buat view khusus untuk template PDF bukti pendaftaran
- [ ] Route /pendaftaran/{id}/pdf -> generate & download PDF

## TAHAP 8 - Polish & Testing
- [ ] Cek semua CRUD berjalan
- [ ] Cek validasi JS (email, HP)
- [ ] Cek dropdown provinsi -> kabupaten berjalan
- [ ] Cek export PDF
- [ ] Cek alert notification muncul
- [ ] Review code & cleanup

---

## Pembagian BE vs FE

### Backend (BE)
- Config database, migration, model, seeder
- Controller logic (auth, CRUD, validasi server)
- API endpoint kabupaten
- Export PDF
- Middleware role

### Frontend (FE)
- Blade templates dengan Bootstrap
- Form pendaftaran + validasi JavaScript
- Dropdown dinamis (fetch API kabupaten)
- Alert / notifikasi JS
- Layout responsive

---

## Catatan Gaya Penulisan Code
- Nama variabel bahasa Inggris, komentar boleh bahasa Indonesia
- Tidak pakai pola AI (tidak terlalu verbose, tidak kebanyakan komentar)
- Penamaan file dan method sesuai konvensi Laravel standar
- Struktur code rapi tapi natural, seperti developer biasa nulis
