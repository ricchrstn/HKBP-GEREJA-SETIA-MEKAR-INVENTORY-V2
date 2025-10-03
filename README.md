# Sistem Inventori Gereja HKBP Setia Mekar

Sistem manajemen inventori berbasis web yang canggih untuk mengelola barang-barang gereja dengan fitur multi-role, analisis TOPSIS, dan laporan komprehensif.

## 🚀 Fitur Utama

### 📊 Dashboard Multi-Role
- **Admin Dashboard**: Overview sistem, statistik inventori, grafik transaksi
- **Pengurus Dashboard**: Manajemen inventori, peminjaman, perawatan, audit
- **Bendahara Dashboard**: Manajemen keuangan, verifikasi pengadaan, analisis TOPSIS

### 📦 Manajemen Inventori Lengkap
- CRUD barang dengan upload gambar
- Sistem kode barang otomatis (BRG-XXX)
- Kategori barang terorganisir
- Tracking stok real-time
- Status barang (Aktif, Rusak, Hilang, Perawatan)
- Soft delete dengan arsip barang

### 👥 Multi-Role System
- **Admin**: Manajemen user, inventori, kategori, jadwal audit, laporan sistem
- **Pengurus**: Pencatatan barang masuk/keluar, peminjaman, perawatan, audit, pengajuan
- **Bendahara**: Verifikasi pengadaan, manajemen kas, analisis TOPSIS, laporan keuangan

### 📋 Fitur Transaksi & Operasional
- Pencatatan barang masuk/keluar dengan validasi stok
- Manajemen peminjaman dengan tracking status
- Jadwal perawatan barang otomatis
- Sistem pengajuan barang dengan kriteria TOPSIS
- Audit barang mandiri dan terjadwal
- Tracking status peminjaman (Dipinjam, Dikembalikan, Terlambat)

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
- Laporan inventori (masuk/keluar, peminjaman, perawatan, audit)
- Laporan keuangan dengan grafik
- Laporan aktivitas sistem
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
│   │   ├── Admin/           # Controller untuk Admin
│   │   │   ├── DashboardController.php
│   │   │   ├── BarangController.php
│   │   │   ├── UserController.php
│   │   │   ├── KategoriController.php
│   │   │   ├── JadwalAuditController.php
│   │   │   └── LaporanController.php
│   │   ├── Pengurus/        # Controller untuk Pengurus
│   │   │   ├── DashboardController.php
│   │   │   ├── BarangMasukController.php
│   │   │   ├── BarangKeluarController.php
│   │   │   ├── PeminjamanController.php
│   │   │   ├── PerawatanController.php
│   │   │   ├── AuditController.php
│   │   │   └── PengajuanController.php
│   │   ├── Bendahara/       # Controller untuk Bendahara
│   │   │   ├── DashboardController.php
│   │   │   ├── KasController.php
│   │   │   ├── VerifikasiPengadaanController.php
│   │   │   ├── AnalisisTopsisController.php
│   │   │   └── LaporanController.php
│   │   └── Auth/
│   ├── Models/              # Eloquent Models
│   │   ├── User.php
│   │   ├── Barang.php
│   │   ├── BarangMasuk.php
│   │   ├── BarangKeluar.php
│   │   ├── Peminjaman.php
│   │   ├── Perawatan.php
│   │   ├── Audit.php
│   │   ├── Pengajuan.php
│   │   ├── Kas.php
│   │   ├── AnalisisTopsis.php
│   │   └── Kriteria.php
│   └── Http/Middleware/
│       └── RoleMiddleware.php
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
│       ├── UserSeeder.php
│       ├── KategoriSeeder.php
│       ├── BarangSeeder.php
│       └── KriteriaSeeder.php
├── resources/
│   ├── views/               # Blade templates
│   │   ├── admin/           # Views untuk Admin
│   │   ├── pengurus/        # Views untuk Pengurus
│   │   ├── bendahara/       # Views untuk Bendahara
│   │   ├── auth/            # Views untuk Authentication
│   │   └── layouts/         # Layout templates
│   ├── css/
│   └── js/
├── routes/
│   └── web.php              # Web routes
├── storage/
│   └── app/public/          # File uploads
├── public/
│   └── storage/             # Symlink ke storage
├── tailwind.config.js       # Tailwind CSS config
├── vite.config.js           # Vite config
└── package.json             # Node.js dependencies
```

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

## 🚀 Fitur Unggulan

### 🧮 Sistem TOPSIS
- Algoritma pengambilan keputusan multi-kriteria
- Perankingan otomatis pengajuan pengadaan
- Visualisasi hasil analisis yang detail

### 📊 Dashboard Interaktif
- Grafik real-time dengan Chart.js
- Statistik komprehensif per role
- Notifikasi stok rendah otomatis

### 📈 Laporan Komprehensif
- Export PDF dan Excel
- Filter berdasarkan tanggal, status, kategori
- Laporan keuangan dengan grafik
- Laporan aktivitas sistem

### 🔄 Workflow Terintegrasi
- Pengajuan → Verifikasi → Analisis TOPSIS → Persetujuan
- Tracking status real-time
- Audit trail lengkap

## 📄 License

MIT License - Bebas digunakan untuk keperluan gereja dan organisasi non-profit.

## 🙏 Credits

Dikembangkan dengan ❤️ untuk HKBP Setia Mekar

---

**Sistem Inventori Gereja v2.0**  
© 2024 HKBP Setia Mekar. All rights reserved.