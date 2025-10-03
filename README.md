# Sistem Inventori Gereja HKBP Setia Mekar

Sistem manajemen inventori berbasis web yang canggih untuk mengelola barang-barang gereja dengan fitur multi-role, analisis TOPSIS, dan laporan komprehensif.

## 🚀 Fitur Utama

### 👥 Multi-Role System
- **Admin**: Manajemen user, inventori, kategori, jadwal audit, laporan sistem
- **Pengurus**: Pencatatan barang masuk/keluar, peminjaman, perawatan, audit, pengajuan
- **Bendahara**: Verifikasi pengadaan, manajemen kas, analisis TOPSIS, laporan keuangan

### 📦 Manajemen Inventori
- CRUD barang dengan upload gambar
- Sistem kode barang otomatis (BRG-XXX)
- Tracking stok real-time
- Status barang (Aktif, Rusak, Hilang, Perawatan)
- Pencatatan barang masuk/keluar dengan validasi stok

### 🧮 Analisis TOPSIS
- Sistem pengambilan keputusan multi-kriteria
- Kriteria: Tingkat Urgensi, Ketersediaan Stok, Ketersediaan Dana
- Perankingan otomatis pengajuan pengadaan
- Visualisasi hasil analisis

### 💰 Manajemen Keuangan
- Pencatatan kas masuk/keluar
- Upload bukti transaksi
- Laporan keuangan komprehensif
- Tracking saldo real-time

### 📈 Laporan & Export
- Laporan inventori dan keuangan
- Export ke PDF dan Excel
- Filter berdasarkan tanggal, status, kategori

## 🛠️ Teknologi

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

## 🚀 Instalasi

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

## 👤 Default Login

### Admin
- Email: `admin@gmail.com`
- Password: `admin123`

### Pengurus
- Email: `pengurus@mail.com`
- Password: `pengurus123`

### Bendahara
- Email: `bendahara@mail.com`
- Password: `bendahara123`

## 📁 Struktur Project

```
gereja/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/                    # Controller untuk Admin
│   │   │   ├── DashboardController.php      # Dashboard admin
│   │   │   ├── BarangController.php           # Manajemen inventori
│   │   │   ├── UserController.php           # Manajemen pengguna
│   │   │   ├── KategoriController.php        # Manajemen kategori
│   │   │   ├── JadwalAuditController.php    # Jadwal audit
│   │   │   └── LaporanController.php         # Laporan sistem
│   │   ├── Pengurus/                 # Controller untuk Pengurus
│   │   │   ├── DashboardController.php      # Dashboard pengurus
│   │   │   ├── BarangMasukController.php     # Barang masuk
│   │   │   ├── BarangKeluarController.php   # Barang keluar
│   │   │   ├── PeminjamanController.php      # Peminjaman barang
│   │   │   ├── PerawatanController.php       # Perawatan barang
│   │   │   ├── AuditController.php           # Audit barang
│   │   │   └── PengajuanController.php       # Pengajuan barang
│   │   ├── Bendahara/                # Controller untuk Bendahara
│   │   │   ├── DashboardController.php       # Dashboard bendahara
│   │   │   ├── KasController.php            # Manajemen kas
│   │   │   ├── VerifikasiPengadaanController.php # Verifikasi pengadaan
│   │   │   ├── AnalisisTopsisController.php # Analisis TOPSIS
│   │   │   └── LaporanController.php         # Laporan keuangan
│   │   └── Auth/
│   │       └── LoginController.php           # Authentication
│   ├── Models/                       # Eloquent Models
│   │   ├── User.php                  # Model pengguna
│   │   ├── Barang.php                # Model barang
│   │   ├── BarangMasuk.php           # Model barang masuk
│   │   ├── BarangKeluar.php          # Model barang keluar
│   │   ├── Peminjaman.php            # Model peminjaman
│   │   ├── Perawatan.php             # Model perawatan
│   │   ├── Audit.php                 # Model audit
│   │   ├── Pengajuan.php             # Model pengajuan
│   │   ├── Kas.php                   # Model kas
│   │   ├── AnalisisTopsis.php        # Model analisis TOPSIS
│   │   └── Kriteria.php              # Model kriteria
│   └── Http/Middleware/
│       └── RoleMiddleware.php        # Middleware role-based access
├── database/
│   ├── migrations/                   # Database migrations
│   └── seeders/                      # Database seeders
│       ├── UserSeeder.php            # Seeder pengguna
│       ├── KategoriSeeder.php        # Seeder kategori
│       ├── BarangSeeder.php          # Seeder barang
│       └── KriteriaSeeder.php       # Seeder kriteria
├── resources/
│   ├── views/                        # Blade templates
│   │   ├── admin/                    # Views untuk Admin
│   │   │   ├── dashboard/            # Dashboard admin
│   │   │   ├── inventori/            # Manajemen inventori
│   │   │   ├── jadwal-audit/         # Jadwal audit
│   │   │   ├── kategori/             # Manajemen kategori
│   │   │   ├── laporan/              # Laporan sistem
│   │   │   └── pengguna/             # Manajemen pengguna
│   │   ├── pengurus/                 # Views untuk Pengurus
│   │   │   ├── dashboard/            # Dashboard pengurus
│   │   │   ├── barang/               # Manajemen barang
│   │   │   ├── peminjaman/           # Peminjaman
│   │   │   ├── pengajuan/            # Pengajuan
│   │   │   ├── Perawatan/            # Perawatan
│   │   │   └── audit/                # Audit
│   │   ├── bendahara/                # Views untuk Bendahara
│   │   │   ├── dashboard/             # Dashboard bendahara
│   │   │   ├── kas/                  # Manajemen kas
│   │   │   ├── verifikasi/           # Verifikasi pengadaan
│   │   │   ├── analisis/             # Analisis TOPSIS
│   │   │   └── laporan/              # Laporan keuangan
│   │   ├── auth/                      # Views untuk Authentication
│   │   └── layouts/                  # Layout templates
│   ├── css/
│   └── js/
├── routes/
│   └── web.php                       # Web routes
├── storage/
│   └── app/public/                   # File uploads
├── public/
│   └── storage/                      # Symlink ke storage
├── tailwind.config.js                # Tailwind CSS config
├── vite.config.js                    # Vite config
└── package.json                      # Node.js dependencies
```

## 👥 Penjelasan Role & Fitur

### 🔧 Admin
**Akses Penuh Sistem**
- **Dashboard**: Overview sistem, statistik inventori, grafik transaksi
- **Manajemen Pengguna**: CRUD user, reset password, role management
- **Master Inventori**: CRUD barang, kategori, status barang
- **Jadwal Audit**: Buat jadwal audit, assign auditor
- **Laporan Sistem**: Laporan inventori, keuangan, aktivitas sistem
- **Arsip Barang**: Restore/force delete barang yang dihapus

**Fitur Khusus Admin:**
- Soft delete dengan arsip barang
- Manajemen kategori barang
- Jadwal audit terjadwal
- Laporan komprehensif semua modul
- Export PDF/Excel untuk semua laporan

### 📦 Pengurus
**Manajemen Operasional Inventori**
- **Dashboard**: Statistik inventori, notifikasi stok rendah
- **Barang Masuk**: Pencatatan barang masuk dengan validasi stok
- **Barang Keluar**: Pencatatan barang keluar dengan validasi stok
- **Peminjaman**: Manajemen peminjaman barang dengan tracking status
- **Perawatan**: Jadwal dan tracking perawatan barang
- **Audit**: Audit barang mandiri dan terjadwal
- **Pengajuan**: Pengajuan barang baru dengan kriteria TOPSIS

**Fitur Khusus Pengurus:**
- Validasi stok real-time
- Tracking status peminjaman (Dipinjam, Dikembalikan, Terlambat)
- Perawatan otomatis berdasarkan jadwal
- Pengajuan dengan kriteria TOPSIS
- Audit mandiri dan terjadwal

### 💰 Bendahara
**Manajemen Keuangan & Verifikasi**
- **Dashboard**: Statistik keuangan, saldo kas, grafik transaksi
- **Manajemen Kas**: Pencatatan kas masuk/keluar, upload bukti
- **Verifikasi Pengadaan**: Verifikasi pengajuan dari pengurus
- **Analisis TOPSIS**: Perankingan pengajuan berdasarkan kriteria
- **Laporan Keuangan**: Laporan kas, pengadaan, analisis

**Fitur Khusus Bendahara:**
- Analisis TOPSIS multi-kriteria
- Verifikasi pengajuan pengadaan
- Manajemen kas dengan bukti transaksi
- Laporan keuangan komprehensif
- Perankingan otomatis pengajuan

## 🔧 Konfigurasi

### Upload File
- Maksimal ukuran file gambar: 2MB
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
- Desktop
- Tablet  
- Mobile Phone

## 🔒 Keamanan

- CSRF Protection
- SQL Injection Prevention (Eloquent ORM)
- XSS Protection
- Role-based Access Control (Middleware)
- Secure File Upload dengan validasi
- Password Hashing (bcrypt)
- Session Management

## 🆘 Troubleshooting

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
- Email: support@gereja.com
- WhatsApp: +62xxx-xxxx-xxxx

## 📄 License

MIT License - Bebas digunakan untuk keperluan gereja dan organisasi non-profit.

## 🙏 Credits

Dikembangkan dengan ❤️ untuk HKBP Setia Mekar

---

**Sistem Inventori Gereja v2.0**  
© 2024 HKBP Setia Mekar. All rights reserved.