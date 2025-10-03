# Sistem Inventori Gereja HKBP Setia Mekar ⛪✨

Sistem manajemen inventori berbasis web yang canggih untuk mengelola barang-barang gereja dengan fitur multi-role, analisis TOPSIS, dan laporan komprehensif.

## 🚀 Fitur Utama

### 👥 Multi-Role System
- **Admin**: Manajemen user, inventori, kategori, jadwal audit, laporan sistem
- **Pengurus**: Pencatatan barang masuk/keluar, peminjaman, perawatan, audit, pengajuan
- **Bendahara**: Verifikasi pengadaan, manajemen kas, analisis TOPSIS, laporan keuangan

### 📦 Manajemen Inventori
- CRUD barang dengan upload gambar 📸
- Sistem kode barang otomatis (BRG-XXX)
- Tracking stok real-time ✅
- Status barang (Aktif, Rusak, Hilang, Perawatan)
- Pencatatan barang masuk/keluar dengan validasi stok

### 🧮 Analisis TOPSIS
- Sistem pengambilan keputusan multi-kriteria 📊
- Kriteria: Tingkat Urgensi, Ketersediaan Stok, Ketersediaan Dana
- Perankingan otomatis pengajuan pengadaan 🥇
- Visualisasi hasil analisis 📈

### 💰 Manajemen Keuangan
- Pencatatan kas masuk/keluar 💸
- Upload bukti transaksi 🧾
- Laporan keuangan komprehensif
- Tracking saldo real-time

### 📈 Laporan & Export
- Laporan inventori dan keuangan 📄
- Export ke PDF dan Excel 📤
- Filter berdasarkan tanggal, status, kategori

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 12
- **Frontend**: Tailwind CSS, Vite
- **Database**: MySQL
- **PHP**: 8.2+
- **Export**: DomPDF, Maatwebsite Excel
- **Authentication**: Laravel Auth dengan role-based access

## 📋 Persyaratan Sistem

- PHP >= 8.2
- Composer
- MySQL >= 5.7
- Node.js & NPM (untuk Vite)
- Web Server (Apache/Nginx)

## 🚀 Instalasi Cepat

### 1. Clone Repository
```bash
git clone https://github.com/your-repo/gereja-inventori.git
cd gereja-inventori
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gereja_inventori
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Database Migration & Seeding
```bash
php artisan migrate
php artisan db:seed
```

### 6. Storage Link
```bash
php artisan storage:link
```

### 7. Build Assets
```bash
npm run build
# atau untuk development:
npm run dev
```

### 8. Run Application
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## 👤 Login Default

### Admin
- Email: `admin@gmail.com`
- Password: `admin123`

### Pengurus
- Email: `pengurus@mail.com`
- Password: `pengurus123`

### Bendahara
- Email: `bendahara@mail.com`
- Password: `bendahara123`

## 📁 Struktur Project ✨

Berikut adalah gambaran struktur direktori utama proyek, yang dirancang untuk keterorganisasian dan kemudahan pengembangan dengan pola MVC (Model-View-Controller) Laravel:

```
gereja/                                     # Direktori akar proyek Laravel
├── app/                                    # Berisi kode inti aplikasi (Model, Controller, Middleware, dll.)
│   ├── Http/                               # Direktori HTTP terkait (Controller, Middleware, Kernel)
│   │   ├── Controllers/                    # Berisi semua kelas Controller
│   │   │   ├── Admin/                      # Controller khusus untuk peran Admin
│   │   │   │   ├── DashboardController.php # Mengelola logika dashboard Admin
│   │   │   │   ├── BarangController.php    # Mengelola operasi CRUD barang oleh Admin
│   │   │   │   ├── UserController.php      # Mengelola operasi CRUD pengguna oleh Admin
│   │   │   │   ├── KategoriController.php  # Mengelola operasi CRUD kategori barang oleh Admin
│   │   │   │   ├── JadwalAuditController.php # Mengelola jadwal audit oleh Admin
│   │   │   │   └── LaporanController.php   # Mengelola logika pembuatan laporan oleh Admin
│   │   │   ├── Pengurus/                   # Controller khusus untuk peran Pengurus
│   │   │   │   ├── DashboardController.php # Mengelola logika dashboard Pengurus
│   │   │   │   ├── BarangMasukController.php # Mengelola pencatatan barang masuk oleh Pengurus
│   │   │   │   ├── BarangKeluarController.php # Mengelola pencatatan barang keluar oleh Pengurus
│   │   │   │   ├── PeminjamanController.php # Mengelola peminjaman barang oleh Pengurus
│   │   │   │   ├── PerawatanController.php # Mengelola perawatan barang oleh Pengurus
│   │   │   │   ├── AuditController.php     # Mengelola pelaksanaan audit oleh Pengurus
│   │   │   │   └── PengajuanController.php # Mengelola pengajuan pengadaan barang oleh Pengurus
│   │   │   ├── Bendahara/                  # Controller khusus untuk peran Bendahara
│   │   │   │   ├── DashboardController.php # Mengelola logika dashboard Bendahara
│   │   │   │   ├── KasController.php       # Mengelola pencatatan kas masuk/keluar oleh Bendahara
│   │   │   │   ├── VerifikasiPengadaanController.php # Mengelola verifikasi pengajuan pengadaan oleh Bendahara
│   │   │   │   ├── AnalisisTopsisController.php # Mengelola logika analisis TOPSIS oleh Bendahara
│   │   │   │   └── LaporanController.php   # Mengelola logika pembuatan laporan keuangan oleh Bendahara
│   │   │   └── Auth/                       # Controller terkait otentikasi
│   │   │       └── LoginController.php     # Mengelola proses login pengguna
│   │   └── Models/                         # Berisi semua definisi Model Eloquent (merepresentasikan tabel database)
│   │       ├── User.php                    # Model untuk tabel pengguna
│   │       ├── Barang.php                  # Model untuk tabel barang inventori
│   │       ├── BarangMasuk.php             # Model untuk tabel transaksi barang masuk
│   │       ├── BarangKeluar.php            # Model untuk tabel transaksi barang keluar
│   │       ├── Peminjaman.php              # Model untuk tabel peminjaman barang
│   │       ├── Perawatan.php               # Model untuk tabel perawatan barang
│   │       ├── Audit.php                   # Model untuk tabel audit barang
│   │       ├── Pengajuan.php               # Model untuk tabel pengajuan pengadaan
│   │       ├── Kas.php                     # Model untuk tabel kas (keuangan)
│   │       ├── AnalisisTopsis.php          # Model untuk hasil analisis TOPSIS
│   │       └── Kriteria.php                # Model untuk kriteria TOPSIS
│   └── Http/Middleware/                    # Berisi middleware HTTP kustom
│       └── RoleMiddleware.php              # Middleware untuk otorisasi berdasarkan peran pengguna
├── database/                               # Berisi file terkait database
│   ├── migrations/                         # Skema database (pembuatan dan modifikasi tabel)
│   └── seeders/                            # Data awal untuk diisi ke database (misal: user default, kategori)
│       ├── UserSeeder.php                  # Seeder untuk data pengguna default
│       ├── KategoriSeeder.php              # Seeder untuk data kategori barang default
│       ├── BarangSeeder.php                # Seeder untuk data barang contoh
│       └── KriteriaSeeder.php              # Seeder untuk data kriteria TOPSIS
├── resources/                              # Berisi aset frontend (view, CSS, JS)
│   ├── views/                              # Template Blade untuk antarmuka pengguna
│   │   ├── admin/                          # View khusus untuk peran Admin
│   │   │   ├── dashboard/                  # View untuk dashboard Admin
│   │   │   ├── inventori/                  # View untuk manajemen inventori oleh Admin
│   │   │   ├── jadwal-audit/               # View untuk jadwal audit Admin
│   │   │   ├── kategori/                   # View untuk manajemen kategori Admin
│   │   │   ├── laporan/                    # View untuk laporan Admin
│   │   │   └── pengguna/                   # View untuk manajemen pengguna Admin
│   │   ├── pengurus/                       # View khusus untuk peran Pengurus
│   │   │   ├── dashboard/                  # View untuk dashboard Pengurus
│   │   │   ├── barang/                     # View untuk barang masuk/keluar Pengurus
│   │   │   ├── peminjaman/                 # View untuk peminjaman Pengurus
│   │   │   ├── pengajuan/                  # View untuk pengajuan Pengurus
│   │   │   ├── Perawatan/                  # View untuk perawatan Pengurus
│   │   │   └── audit/                      # View untuk audit Pengurus
│   │   ├── bendahara/                      # View khusus untuk peran Bendahara
│   │   │   ├── dashboard/                  # View untuk dashboard Bendahara
│   │   │   ├── kas/                        # View untuk manajemen kas Bendahara
│   │   │   ├── verifikasi/                 # View untuk verifikasi pengadaan Bendahara
│   │   │   ├── analisis/                   # View untuk analisis TOPSIS Bendahara
│   │   │   └── laporan/                    # View untuk laporan keuangan Bendahara
│   │   ├── auth/                           # View untuk otentikasi (login, register)
│   │   └── layouts/                        # Template layout dasar (misal: header, sidebar, footer)
│   ├── css/                                # File CSS mentah (akan diproses oleh Vite)
│   └── js/                                 # File JavaScript mentah (akan diproses oleh Vite)
├── routes/                                 # Definisi rute aplikasi
│   └── web.php                             # Rute web untuk semua URL aplikasi
├── storage/                                # Direktori untuk file yang diunggah, cache, log
│   └── app/public/                         # Lokasi penyimpanan file publik yang diunggah (misal: gambar barang)
├── public/                                 # Direktori yang dapat diakses publik oleh web server
│   └── storage/                            # Symlink ke `storage/app/public` untuk akses publik
├── tailwind.config.js                      # Konfigurasi Tailwind CSS
├── vite.config.js                          # Konfigurasi bundler aset Vite
└── package.json                            # Daftar dependensi Node.js dan script NPM
```

## 👥 Penjelasan Role & Fitur 🌟

### 🔧 Admin - Pengelola Utama Sistem
**Akses Penuh Sistem**
- **Dashboard**: Overview sistem, statistik inventori, grafik transaksi 📈
- **Manajemen Pengguna**: CRUD user, reset password, role management
- **Master Inventori**: CRUD barang, kategori, status barang
- **Jadwal Audit**: Buat jadwal audit, assign auditor 🗓️
- **Laporan Sistem**: Laporan inventori, keuangan, aktivitas sistem
- **Arsip Barang**: Restore/force delete barang yang dihapus

**Fitur Khusus Admin:**
- Soft delete dengan arsip barang
- Manajemen kategori barang
- Jadwal audit terjadwal
- Laporan komprehensif semua modul
- Export PDF/Excel untuk semua laporan 📑

### 📦 Pengurus - Manajer Operasional Inventori
**Manajemen Operasional Inventori**
- **Dashboard**: Statistik inventori, notifikasi stok rendah 🚨
- **Barang Masuk**: Pencatatan barang masuk dengan validasi stok
- **Barang Keluar**: Pencatatan barang keluar dengan validasi stok
- **Peminjaman**: Manajemen peminjaman barang dengan tracking status 🔄
- **Perawatan**: Jadwal dan tracking perawatan barang
- **Audit**: Audit barang mandiri dan terjadwal
- **Pengajuan**: Pengajuan barang baru dengan kriteria TOPSIS

**Fitur Khusus Pengurus:**
- Validasi stok real-time
- Tracking status peminjaman (Dipinjam, Dikembalikan, Terlambat) ⏰
- Perawatan otomatis berdasarkan jadwal
- Pengajuan dengan kriteria TOPSIS
- Audit mandiri dan terjadwal

### 💰 Bendahara - Manajer Keuangan & Verifikator
**Manajemen Keuangan & Verifikasi**
- **Dashboard**: Statistik keuangan, saldo kas, grafik transaksi 💰
- **Manajemen Kas**: Pencatatan kas masuk/keluar, upload bukti 📂
- **Verifikasi Pengadaan**: Verifikasi pengajuan dari pengurus ✅
- **Analisis TOPSIS**: Perankingan pengajuan berdasarkan kriteria
- **Laporan Keuangan**: Laporan kas, pengadaan, analisis

**Fitur Khusus Bendahara:**
- Analisis TOPSIS multi-kriteria
- Verifikasi pengajuan pengadaan
- Manajemen kas dengan bukti transaksi
- Laporan keuangan komprehensif
- Perankingan otomatis pengajuan 🏆

## 🔄 Cara Kerja Sistem (Step-by-Step)
(… bagian detail alur kerja dapat tetap dipertahankan sesuai kebutuhan, seperti di versi baru)

## 🔧 Konfigurasi Penting

### Upload File
- Maksimal ukuran file gambar: 2MB 📏
- Format yang didukung: JPG, PNG, JPEG
- Lokasi penyimpanan: `storage/app/public/`

### Stok Rendah
- Default threshold stok rendah: ≤ 5 unit
- Dapat dikonfigurasi di controller

### TOPSIS Configuration
- Kriteria 1: Tingkat Urgensi Barang (Benefit) - Bobot: 0.3
- Kriteria 2: Ketersediaan Stok Barang (Cost) - Bobot: 0.25
- Kriteria 3: Ketersediaan Dana Pengadaan (Benefit) - Bobot: 0.45

### Role-Based Access
- Admin: Akses penuh sistem
- Pengurus: Manajemen inventori dan operasional
- Bendahara: Manajemen keuangan dan verifikasi pengadaan

## 📱 Fitur Mobile-Friendly

Aplikasi responsive dengan Tailwind CSS dan dapat diakses melalui:
- Desktop 🖥️
- Tablet 📱
- Mobile Phone 🤳

## 🔒 Keamanan Terjamin

- CSRF Protection
- SQL Injection Prevention (Eloquent ORM)
- XSS Protection
- Role-based Access Control (Middleware)
- Secure File Upload dengan validasi
- Password Hashing (bcrypt)
- Session Management

## 🆘 Troubleshooting Umum

### Error 500
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Permission Error
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Database Connection Error
- Pastikan MySQL service berjalan
- Cek konfigurasi database di `.env`
- Pastikan database sudah dibuat
- Jalankan `php artisan migrate` jika tabel belum ada

### Asset Not Loading
```bash
npm run build
# atau untuk development:
npm run dev
```

### Storage Link Error
```bash
php artisan storage:link
```

## 📞 Support

Untuk bantuan teknis atau pertanyaan:
- Instagram: [rickychristians](https://www.instagram.com/rickychristians/) 💬

## 📄 License

MIT License - Bebas digunakan untuk keperluan gereja dan organisasi non-profit.

## 🙏 Credits

Dikembangkan dengan ❤️ untuk HKBP Setia Mekar

---

**Sistem Inventori Gereja v2.0**
© 2025 HKBP Setia Mekar. All rights reserved.
```