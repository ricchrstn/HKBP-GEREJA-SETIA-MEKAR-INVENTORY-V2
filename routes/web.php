<?php

use Illuminate\Support\Facades\Route; // Mengimpor fasad Route untuk mendefinisikan rute web.
use App\Http\Controllers\Auth\LoginController; // Mengimpor controller untuk proses otentikasi login.

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController; // Mengimpor DashboardController untuk Admin, di-alias menjadi AdminDashboardController.
use App\Http\Controllers\Admin\BarangController as AdminBarangController;     // Mengimpor BarangController untuk Admin, di-alias menjadi AdminBarangController.
use App\Http\Controllers\Admin\KategoriController;                            // Mengimpor KategoriController untuk Admin.
use App\Http\Controllers\Admin\UserController;                                // Mengimpor UserController untuk Admin.
use App\Http\Controllers\Admin\JadwalAuditController as AdminJadwalAuditController; // Mengimpor JadwalAuditController untuk Admin, di-alias.
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;   // Mengimpor LaporanController untuk Admin, di-alias.

// Pengurus Controllers
use App\Http\Controllers\Pengurus\DashboardController as PengurusDashboardController; // Mengimpor DashboardController untuk Pengurus, di-alias.
use App\Http\Controllers\Pengurus\BarangMasukController;                             // Mengimpor BarangMasukController untuk Pengurus.
use App\Http\Controllers\Pengurus\BarangKeluarController;                            // Mengimpor BarangKeluarController untuk Pengurus.
use App\Http\Controllers\Pengurus\PeminjamanController;                              // Mengimpor PeminjamanController untuk Pengurus.
use App\Http\Controllers\Pengurus\PerawatanController;                               // Mengimpor PerawatanController untuk Pengurus.
use App\Http\Controllers\Pengurus\PengajuanController;                               // Mengimpor PengajuanController untuk Pengurus.
use App\Http\Controllers\Pengurus\AuditController;                                   // Mengimpor AuditController untuk Pengurus.

// Bendahara Controllers
use App\Http\Controllers\Bendahara\DashboardController as BendaharaDashboardController; // Mengimpor DashboardController untuk Bendahara, di-alias.
use App\Http\Controllers\Bendahara\VerifikasiPengadaanController;                      // Mengimpor VerifikasiPengadaanController untuk Bendahara.
use App\Http\Controllers\Bendahara\KasController;                                      // Mengimpor KasController untuk Bendahara.
use App\Http\Controllers\Bendahara\AnalisisTopsisController;                           // Mengimpor AnalisisTopsisController untuk Bendahara.
use App\Http\Controllers\Bendahara\LaporanController;                                  // Mengimpor LaporanController untuk Bendahara.

// Authentication Routes
// Rute untuk menampilkan form login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Rute untuk memproses permintaan login
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
// Rute untuk memproses permintaan logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to login
// Mengarahkan URL root '/' langsung ke halaman login.
Route::get('/', function () {
    return redirect('/login');
});

// Protected Routes
// Grup rute yang hanya bisa diakses oleh pengguna yang sudah terotentikasi.
Route::middleware(['auth'])->group(function () {
    // Rute dashboard default yang akan diarahkan ke dashboard sesuai role.
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard'); // Perhatikan, ini akan menjadi dashboard umum sebelum dipecah per role.

    // Rute API untuk mendapatkan saldo kas, bisa diakses oleh bendahara, pengurus, atau admin.
    Route::get('/kas/get-saldo', function() {
        try {
            // Cek apakah user memiliki akses untuk melihat saldo
            $user = auth()->user(); // Mengambil objek user yang sedang login.
            if (!in_array($user->role, ['bendahara', 'pengurus', 'admin'])) { // Memeriksa apakah role user ada di dalam array yang diizinkan.
                return response()->json([ // Mengembalikan respon JSON jika akses ditolak.
                    'success' => false,
                    'message' => 'Akses ditolak'
                ], 403); // Status HTTP 403 Forbidden.
            }

            $saldo = \App\Models\Kas::getSaldo(); // Memanggil metode statis getSaldo() dari model Kas untuk mendapatkan total saldo.
            return response()->json([ // Mengembalikan respon JSON dengan saldo jika berhasil.
                'success' => true,
                'saldo' => $saldo
            ]);
        } catch (\Exception $e) { // Menangkap exception jika terjadi error.
            return response()->json([ // Mengembalikan respon JSON dengan pesan error.
                'success' => false,
                'message' => 'Gagal mengambil data saldo: ' . $e->getMessage()
            ], 500); // Status HTTP 500 Internal Server Error.
        }
    })->name('kas.get-saldo'); // Memberi nama rute.
});

// ===================== ADMIN ROUTES =====================
// Grup rute khusus untuk user dengan peran 'admin'.
// 'prefix' => 'admin': Semua rute dalam grup ini akan diawali dengan '/admin'.
// 'middleware' => ['auth', 'role:admin']: Hanya user yang sudah login DAN memiliki role 'admin' yang bisa mengakses rute-rute ini.
// 'as' => 'admin.': Semua nama rute dalam grup ini akan diawali dengan 'admin.'.
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin'], 'as' => 'admin.'], function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'adminDashboard'])->name('dashboard'); // Rute untuk dashboard admin.

    // Manajemen Pengguna (CRUD untuk User)
    Route::get('/users', [UserController::class, 'index'])->name('users.index'); // Menampilkan daftar user.
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); // Menampilkan form tambah user.
    Route::post('/users', [UserController::class, 'store'])->name('users.store'); // Menyimpan user baru.
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show'); // Menampilkan detail user.
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit'); // Menampilkan form edit user.
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); // Memperbarui data user.
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // Menghapus user.
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password'); // Mereset password user.

    // Master Barang (Inventori) (CRUD untuk Barang oleh Admin)
    Route::get('/inventori', [AdminBarangController::class, 'index'])->name('inventori.index'); // Menampilkan daftar barang.
    Route::get('/inventori/create', [AdminBarangController::class, 'create'])->name('inventori.create'); // Menampilkan form tambah barang.
    Route::post('/inventori', [AdminBarangController::class, 'store'])->name('inventori.store'); // Menyimpan barang baru.
    Route::get('/inventori/{barang}/edit', [AdminBarangController::class, 'edit'])->name('inventori.edit'); // Menampilkan form edit barang.
    Route::put('/inventori/{barang}', [AdminBarangController::class, 'update'])->name('inventori.update'); // Memperbarui data barang.
    Route::delete('/inventori/{barang}', [AdminBarangController::class, 'destroy'])->name('inventori.destroy'); // Menghapus barang (soft delete).

    // Kategori Management (CRUD untuk Kategori)
    Route::resource('kategori', KategoriController::class); // Menggunakan Route::resource untuk membuat rute CRUD standar untuk Kategori.

    // Manajemen Arsip Barang (Fitur soft delete dan force delete)
    Route::get('/inventori/archived', [AdminBarangController::class, 'archived'])->name('inventori.archived'); // Menampilkan barang yang diarsipkan.
    Route::post('/inventori/{id}/restore', [AdminBarangController::class, 'restore'])->name('inventori.restore'); // Mengembalikan barang dari arsip.
    Route::delete('/inventori/{id}/force-delete', [AdminBarangController::class, 'forceDelete'])->name('inventori.force-delete'); // Menghapus barang secara permanen.

    // Jadwal Audit Management (CRUD untuk Jadwal Audit)
    Route::get('/jadwal-audit', [AdminJadwalAuditController::class, 'index'])->name('jadwal-audit.index'); // Menampilkan daftar jadwal audit.
    Route::get('/jadwal-audit/create', [AdminJadwalAuditController::class, 'create'])->name('jadwal-audit.create'); // Menampilkan form tambah jadwal audit.
    Route::post('/jadwal-audit', [AdminJadwalAuditController::class, 'store'])->name('jadwal-audit.store'); // Menyimpan jadwal audit baru.
    Route::get('/jadwal-audit/{jadwalAudit}', [AdminJadwalAuditController::class, 'show'])->name('jadwal-audit.show'); // Menampilkan detail jadwal audit.
    Route::get('/jadwal-audit/{jadwalAudit}/edit', [AdminJadwalAuditController::class, 'edit'])->name('jadwal-audit.edit'); // Menampilkan form edit jadwal audit.
    Route::put('/jadwal-audit/{jadwalAudit}', [AdminJadwalAuditController::class, 'update'])->name('jadwal-audit.update'); // Memperbarui jadwal audit.
    Route::delete('/jadwal-audit/{jadwalAudit}', [AdminJadwalAuditController::class, 'destroy'])->name('jadwal-audit.destroy'); // Menghapus jadwal audit.

    // Laporan Management (Berbagai jenis laporan untuk Admin)
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index'); // Halaman indeks laporan.
    Route::get('/laporan/inventaris', [AdminLaporanController::class, 'inventaris'])->name('laporan.inventaris'); // Laporan inventaris.
    Route::get('/laporan/barang-masuk', [AdminLaporanController::class, 'barangMasuk'])->name('laporan.barang-masuk'); // Laporan barang masuk.
    Route::get('/laporan/barang-keluar', [AdminLaporanController::class, 'barangKeluar'])->name('laporan.barang-keluar'); // Laporan barang keluar.
    Route::get('/laporan/peminjaman', [AdminLaporanController::class, 'peminjaman'])->name('laporan.peminjaman'); // Laporan peminjaman.
    Route::get('/laporan/perawatan', [AdminLaporanController::class, 'perawatan'])->name('laporan.perawatan'); // Laporan perawatan.
    Route::get('/laporan/audit', [AdminLaporanController::class, 'audit'])->name('laporan.audit'); // Laporan audit.
    Route::get('/laporan/keuangan', [AdminLaporanController::class, 'keuangan'])->name('laporan.keuangan'); // Laporan keuangan.
    Route::get('/laporan/aktivitas-sistem', [AdminLaporanController::class, 'aktivitasSistem'])->name('laporan.aktivitas-sistem'); // Laporan aktivitas sistem.
});

// ===================== PENGURUS ROUTES =====================
// Grup rute khusus untuk user dengan peran 'pengurus'.
// 'prefix' => 'pengurus': Semua rute dalam grup ini akan diawali dengan '/pengurus'.
// 'middleware' => ['auth', 'role:pengurus']: Hanya user yang sudah login DAN memiliki role 'pengurus' yang bisa mengakses rute-rute ini.
// 'as' => 'pengurus.': Semua nama rute dalam grup ini akan diawali dengan 'pengurus.'.
Route::group(['prefix' => 'pengurus', 'middleware' => ['auth', 'role:pengurus'], 'as' => 'pengurus.'], function () {
    // Dashboard Pengurus
    Route::get('/dashboard', [PengurusDashboardController::class, 'index'])->name('dashboard'); // Rute untuk dashboard pengurus.

    // Barang Masuk Management (CRUD + fitur terkait)
    Route::controller(BarangMasukController::class)->group(function () { // Menggunakan grup controller untuk rute yang berhubungan.
        Route::get('/barang/masuk', 'index')->name('barang.masuk');
        Route::get('/barang/masuk/create', 'create')->name('barang.masuk.create');
        Route::post('/barang/masuk', 'store')->name('barang.masuk.store');
        Route::get('/barang/masuk/{barangMasuk}', 'show')->name('barang.masuk.show');
        Route::get('/barang/masuk/{barangMasuk}/edit', 'edit')->name('barang.masuk.edit');
        Route::put('/barang/masuk/{barangMasuk}', 'update')->name('barang.masuk.update');
        Route::delete('/barang/masuk/{barangMasuk}', 'destroy')->name('barang.masuk.destroy');
        Route::get('/barang/masuk/get-barang-details/{id}', 'getBarangDetails')->name('barang.masuk.get-barang-details'); // API untuk detail barang.
        Route::get('/barang/masuk/get-barang-by-kategori/{kategoriId}', 'getBarangByKategori')->name('barang.masuk.get-barang-by-kategori'); // API untuk filter barang berdasarkan kategori.
    });

    // Barang Keluar Management (CRUD + fitur terkait)
    Route::controller(BarangKeluarController::class)->group(function () { // Menggunakan grup controller untuk rute yang berhubungan.
        Route::get('/barang/keluar', 'index')->name('barang.keluar');
        Route::get('/barang/keluar/create', 'create')->name('barang.keluar.create');
        Route::post('/barang/keluar', 'store')->name('barang.keluar.store');
        Route::get('/barang/keluar/{barangKeluar}', 'show')->name('barang.keluar.show');
        Route::get('/barang/keluar/{barangKeluar}/edit', 'edit')->name('barang.keluar.edit');
        Route::put('/barang/keluar/{barangKeluar}', 'update')->name('barang.keluar.update');
        Route::delete('/barang/keluar/{barangKeluar}', 'destroy')->name('barang.keluar.destroy');
        Route::get('/barang/keluar/get-barang-details/{id}', 'getBarangDetails')->name('barang.keluar.get-barang-details'); // API untuk detail barang.
        Route::get('/barang/keluar/get-barang-by-kategori/{kategoriId}', 'getBarangByKategori')->name('barang.keluar.get-barang-by-kategori'); // API untuk filter barang berdasarkan kategori.
    });

    // Peminjaman Management (CRUD + fitur pengembalian)
    Route::controller(PeminjamanController::class)->group(function () {
        Route::get('/peminjaman', 'index')->name('peminjaman.index');
        Route::get('/peminjaman/create', 'create')->name('peminjaman.create');
        Route::post('/peminjaman', 'store')->name('peminjaman.store');
        Route::get('/peminjaman/{peminjaman}', 'show')->name('peminjaman.show');
        Route::get('/peminjaman/{peminjaman}/edit', 'edit')->name('peminjaman.edit');
        Route::put('/peminjaman/{peminjaman}', 'update')->name('peminjaman.update');
        Route::delete('/peminjaman/{peminjaman}', 'destroy')->name('peminjaman.destroy');
        Route::post('/peminjaman/{peminjaman}/kembalikan', 'kembalikan')->name('peminjaman.kembalikan'); // Rute untuk menandai peminjaman sebagai 'dikembalikan'.
        Route::get('/peminjaman/get-barang-details/{id}', 'getBarangDetails')->name('peminjaman.get-barang-details'); // API untuk detail barang.
    });

    // Perawatan Management (CRUD + fitur selesaikan perawatan)
    Route::controller(PerawatanController::class)->group(function () {
        Route::get('/perawatan', 'index')->name('perawatan.index');
        Route::get('/perawatan/create', 'create')->name('perawatan.create');
        Route::post('/perawatan', 'store')->name('perawatan.store');
        Route::get('/perawatan/{perawatan}', 'show')->name('perawatan.show');
        Route::get('/perawatan/{perawatan}/edit', 'edit')->name('perawatan.edit');
        Route::put('/perawatan/{perawatan}', 'update')->name('perawatan.update');
        Route::delete('/perawatan/{perawatan}', 'destroy')->name('perawatan.destroy');
        Route::post('/perawatan/{perawatan}/selesaikan', 'selesaikan')->name('perawatan.selesaikan'); // Rute untuk menandai perawatan sebagai 'selesai'.
        Route::get('/perawatan/get-barang-details/{id}', 'getBarangDetails')->name('perawatan.get-barang-details'); // API untuk detail barang.
    });

    // Kategori Management (Pengurus juga bisa mengelola kategori)
    Route::resource('kategori', KategoriController::class); // Menggunakan Route::resource untuk Kategori.

    // Pengajuan Management (CRUD untuk Pengajuan)
    Route::resource('pengajuan', PengajuanController::class); // Menggunakan Route::resource untuk Pengajuan.

    // Audit Management (CRUD untuk Audit)
    Route::resource('audit', AuditController::class); // Menggunakan Route::resource untuk Audit.

    // Tambahkan route untuk jadwal audit (khusus untuk Pengurus)
    Route::post('/audit/selesaikan-jadwal/{jadwalAudit}', [AuditController::class, 'selesaikanJadwal'])->name('audit.selesaikan-jadwal'); // Rute untuk menyelesaikan jadwal audit.
    Route::get('/audit/show-jadwal/{jadwalAudit}', [AuditController::class, 'showJadwal'])->name('audit.show-jadwal'); // Rute untuk menampilkan detail jadwal audit.
});

// ===================== BENDAHARA ROUTES =====================
// Grup rute khusus untuk user dengan peran 'bendahara'.
// 'prefix' => 'bendahara': Semua rute dalam grup ini akan diawali dengan '/bendahara'.
// 'middleware' => ['auth', 'role:bendahara']: Hanya user yang sudah login DAN memiliki role 'bendahara' yang bisa mengakses rute-rute ini.
// 'as' => 'bendahara.': Semua nama rute dalam grup ini akan diawali dengan 'bendahara.'.
Route::group(['prefix' => 'bendahara', 'middleware' => ['auth', 'role:bendahara'], 'as' => 'bendahara.'], function () {
    // Dashboard Bendahara
    Route::get('/dashboard', [BendaharaDashboardController::class, 'index'])->name('dashboard'); // Rute untuk dashboard bendahara.

    // Verifikasi Pengadaan (Pengelolaan pengajuan oleh Bendahara)
    Route::controller(VerifikasiPengadaanController::class)->group(function () {
        Route::get('/verifikasi', 'index')->name('verifikasi.index'); // Menampilkan daftar pengajuan yang perlu diverifikasi.
        Route::get('/verifikasi/{pengajuan}', 'show')->name('verifikasi.show'); // Menampilkan detail pengajuan untuk verifikasi.
        Route::post('/verifikasi/{pengajuan}', 'verifikasi')->name('verifikasi.verifikasi'); // Memproses verifikasi (menyetujui/menolak).
    });

    // Analisis TOPSIS (Fitur SPK untuk Bendahara)
    Route::controller(AnalisisTopsisController::class)->group(function () {
        Route::get('/analisis', 'index')->name('analisis.index'); // Halaman daftar pengajuan untuk analisis TOPSIS.
        Route::post('/analisis/store-nilai', 'storeNilai')->name('analisis.store-nilai'); // Menyimpan nilai kriteria untuk pengajuan.
        Route::get('/analisis/hasil', 'hasil')->name('analisis.hasil'); // Menampilkan hasil analisis TOPSIS.
    });

    // Kas Management (CRUD + Laporan Kas)
    Route::get('/kas', [KasController::class, 'index'])->name('kas.index'); // Menampilkan daftar transaksi kas.
    Route::get('/kas/create', [KasController::class, 'create'])->name('kas.create'); // Menampilkan form tambah transaksi kas.
    Route::post('/kas', [KasController::class, 'store'])->name('kas.store'); // Menyimpan transaksi kas baru.
    Route::get('/kas/{ka}', [KasController::class, 'show'])->name('kas.show'); // Menampilkan detail transaksi kas.
    Route::get('/kas/{ka}/edit', [KasController::class, 'edit'])->name('kas.edit'); // Menampilkan form edit transaksi kas.
    Route::put('/kas/{ka}', [KasController::class, 'update'])->name('kas.update'); // Memperbarui transaksi kas.
    Route::delete('/kas/{ka}', [KasController::class, 'destroy'])->name('kas.destroy'); // Menghapus transaksi kas (soft delete).
    Route::get('/kas/laporan', [KasController::class, 'laporan'])->name('kas.laporan'); // Laporan kas.

    // Laporan (Khusus untuk Bendahara)
    Route::controller(LaporanController::class)->group(function () {
        Route::get('/laporan', 'index')->name('laporan.index'); // Halaman indeks laporan.
        Route::get('/laporan/kas', 'kas')->name('laporan.kas'); // Laporan kas.
        Route::get('/laporan/pengadaan', 'pengadaan')->name('laporan.pengadaan'); // Laporan pengadaan.
    });
});

// ===================== STORAGE ROUTE =====================
// Rute untuk mengakses file yang disimpan di folder 'storage/app/public'.
Route::get('storage/{path}', function ($path) {
    return response()->file(storage_path('app/public/' . $path)); // Mengembalikan file dari folder public di storage.
})->where('path', '.*')->name('storage.local'); // '.where('path', '.*')' memungkinkan path mengandung karakter apa saja. Memberi nama rute 'storage.local'.

// Test routes untuk debugging
// Rute untuk menguji apakah server Laravel berjalan.
Route::get('/test-server', function () {
    return response()->json([ // Mengembalikan respon JSON dengan informasi server.
        'status' => 'OK',
        'message' => 'Laravel server is running',
        'timestamp' => now(),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'environment' => app()->environment()
    ]);
});

// Rute untuk menguji status otentikasi user.
Route::get('/test-auth', function () {
    return response()->json([ // Mengembalikan respon JSON dengan status otentikasi dan info user (jika login).
        'authenticated' => auth()->check(), // Memeriksa apakah user sedang login.
        'user' => auth()->user() ? [ // Jika user login, tampilkan detail user.
            'id' => auth()->user()->id,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role
        ] : null,
        'session_id' => session()->getId() // Menampilkan ID sesi.
    ]);
});

// Rute untuk menguji validitas token CSRF.
Route::post('/test-csrf', function () {
    return response()->json([ // Mengembalikan respon JSON jika token CSRF valid.
        'status' => 'OK',
        'message' => 'CSRF token is valid',
        'data' => request()->all() // Menampilkan semua data yang dikirim dalam request.
    ]);
});