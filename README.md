# Sistem Inventori Gereja HKBP Setia Mekar

Sistem manajemen inventori berbasis web untuk mengelola barang-barang gereja dengan fitur lengkap dan user-friendly.

## 🚀 Fitur Utama

### 📊 Dashboard Informatif
- Statistik real-time inventori
- Grafik transaksi harian
- Notifikasi stok rendah
- Quick actions untuk akses cepat

### 📦 Manajemen Barang
- CRUD barang dengan gambar
- Sistem kode barang otomatis
- Filter dan pencarian advanced
- Tracking stok masuk/keluar
- Kategori barang terorganisir

### 👥 Multi-Role System
- **Admin**: Akses penuh sistem
- **Pengurus**: Manajemen inventori
- **Bendahara**: Laporan keuangan

### 📋 Fitur Transaksi
- Pencatatan barang masuk/keluar
- Manajemen peminjaman
- Jadwal perawatan barang
- Sistem pengajuan barang

### 📈 Laporan & Audit
- Laporan inventori lengkap
- Audit stok berkala
- Export data ke Excel/PDF
- History transaksi detail

## 🛠️ Teknologi

- **Backend**: Laravel 10
- **Frontend**: Bootstrap 5, Chart.js
- **Database**: MySQL
- **Storage**: Local/Cloud Storage
- **Authentication**: Laravel Auth

## 📋 Persyaratan Sistem

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM (opsional)
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

### 7. Run Application
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## 👤 Default Login

### Admin
- Email: `admin@gereja.com`
- Password: `password`

### Pengurus
- Email: `pengurus@gereja.com`
- Password: `password`

### Bendahara
- Email: `bendahara@gereja.com`
- Password: `password`

## 📁 Struktur Project

```
gereja/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   ├── Auth/
│   │   └── Pengurus/
│   ├── Models/
│   └── Http/Middleware/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── admin/
│       ├── auth/
│       ├── layouts/
│       └── pengurus/
└── routes/
    └── web.php
```

## 🔧 Konfigurasi

### Upload File
Maksimal ukuran file gambar: 2MB
Format yang didukung: JPG, PNG, JPEG

### Stok Rendah
Default threshold stok rendah: ≤ 5 unit
Dapat dikonfigurasi di controller

### Backup Database
```bash
php artisan backup:run
```

## 📱 Fitur Mobile-Friendly

Aplikasi responsive dan dapat diakses melalui:
- Desktop
- Tablet  
- Mobile Phone

## 🔒 Keamanan

- CSRF Protection
- SQL Injection Prevention
- XSS Protection
- Role-based Access Control
- Secure File Upload

## 🆘 Troubleshooting

### Error 500
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
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

## 📞 Support

Untuk bantuan teknis atau pertanyaan:
- Email: support@gereja.com
- WhatsApp: +62xxx-xxxx-xxxx

## 📄 License

MIT License - Bebas digunakan untuk keperluan gereja dan organisasi non-profit.

## 🙏 Credits

Dikembangkan dengan ❤️ untuk HKBP Setia Mekar

---

**Sistem Inventori Gereja v1.0**  
© 2024 HKBP Setia Mekar. All rights reserved.