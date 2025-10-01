<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BarangController as AdminBarangController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\JadwalAuditController as AdminJadwalAuditController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
// Pengurus Controllers
use App\Http\Controllers\Pengurus\DashboardController as PengurusDashboardController;
use App\Http\Controllers\Pengurus\BarangMasukController;
use App\Http\Controllers\Pengurus\BarangKeluarController;
use App\Http\Controllers\Pengurus\PeminjamanController;
use App\Http\Controllers\Pengurus\PerawatanController;
use App\Http\Controllers\Pengurus\PengajuanController;
use App\Http\Controllers\Pengurus\AuditController;
// Bendahara Controllers
use App\Http\Controllers\Bendahara\DashboardController as BendaharaDashboardController;
use App\Http\Controllers\Bendahara\VerifikasiPengadaanController;
use App\Http\Controllers\Bendahara\KasController;
use App\Http\Controllers\Bendahara\AnalisisTopsisController;
use App\Http\Controllers\Bendahara\LaporanController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/kas/get-saldo', function() {
        try {
            // Cek apakah user memiliki akses untuk melihat saldo
            $user = auth()->user();
            if (!in_array($user->role, ['bendahara', 'pengurus', 'admin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak'
                ], 403);
            }

            $saldo = \App\Models\Kas::getSaldo();
            return response()->json([
                'success' => true,
                'saldo' => $saldo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data saldo: ' . $e->getMessage()
            ], 500);
        }
    })->name('kas.get-saldo');
});

// ===================== ADMIN ROUTES =====================
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin'], 'as' => 'admin.'], function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'adminDashboard'])->name('dashboard');

    // Manajemen Pengguna
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    // Master Barang (Inventori)
    Route::get('/inventori', [AdminBarangController::class, 'index'])->name('inventori.index');
    Route::get('/inventori/create', [AdminBarangController::class, 'create'])->name('inventori.create');
    Route::post('/inventori', [AdminBarangController::class, 'store'])->name('inventori.store');
    Route::get('/inventori/{barang}/edit', [AdminBarangController::class, 'edit'])->name('inventori.edit');
    Route::put('/inventori/{barang}', [AdminBarangController::class, 'update'])->name('inventori.update');
    Route::delete('/inventori/{barang}', [AdminBarangController::class, 'destroy'])->name('inventori.destroy');

    // Kategori Management
    Route::resource('kategori', KategoriController::class);

    // Manajemen Arsip Barang
    Route::get('/inventori/archived', [AdminBarangController::class, 'archived'])->name('inventori.archived');
    Route::post('/inventori/{id}/restore', [AdminBarangController::class, 'restore'])->name('inventori.restore');
    Route::delete('/inventori/{id}/force-delete', [AdminBarangController::class, 'forceDelete'])->name('inventori.force-delete');

    // Jadwal Audit Management
    Route::get('/jadwal-audit', [AdminJadwalAuditController::class, 'index'])->name('jadwal-audit.index');
    Route::get('/jadwal-audit/create', [AdminJadwalAuditController::class, 'create'])->name('jadwal-audit.create');
    Route::post('/jadwal-audit', [AdminJadwalAuditController::class, 'store'])->name('jadwal-audit.store');
    Route::get('/jadwal-audit/{jadwalAudit}', [AdminJadwalAuditController::class, 'show'])->name('jadwal-audit.show');
    Route::get('/jadwal-audit/{jadwalAudit}/edit', [AdminJadwalAuditController::class, 'edit'])->name('jadwal-audit.edit');
    Route::put('/jadwal-audit/{jadwalAudit}', [AdminJadwalAuditController::class, 'update'])->name('jadwal-audit.update');
    Route::delete('/jadwal-audit/{jadwalAudit}', [AdminJadwalAuditController::class, 'destroy'])->name('jadwal-audit.destroy');

    // Laporan Management
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/inventaris', [AdminLaporanController::class, 'inventaris'])->name('laporan.inventaris');
    Route::get('/laporan/barang-masuk', [AdminLaporanController::class, 'barangMasuk'])->name('laporan.barang-masuk');
    Route::get('/laporan/barang-keluar', [AdminLaporanController::class, 'barangKeluar'])->name('laporan.barang-keluar');
    Route::get('/laporan/peminjaman', [AdminLaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
    Route::get('/laporan/perawatan', [AdminLaporanController::class, 'perawatan'])->name('laporan.perawatan');
    Route::get('/laporan/audit', [AdminLaporanController::class, 'audit'])->name('laporan.audit');
    Route::get('/laporan/keuangan', [AdminLaporanController::class, 'keuangan'])->name('laporan.keuangan');
    Route::get('/laporan/aktivitas-sistem', [AdminLaporanController::class, 'aktivitasSistem'])->name('laporan.aktivitas-sistem');
});

// ===================== PENGURUS ROUTES =====================
Route::group(['prefix' => 'pengurus', 'middleware' => ['auth', 'role:pengurus'], 'as' => 'pengurus.'], function () {
    // Dashboard
    Route::get('/dashboard', [PengurusDashboardController::class, 'index'])->name('dashboard');

    // Barang Masuk Management
    Route::controller(BarangMasukController::class)->group(function () {
        Route::get('/barang/masuk', 'index')->name('barang.masuk');
        Route::get('/barang/masuk/create', 'create')->name('barang.masuk.create');
        Route::post('/barang/masuk', 'store')->name('barang.masuk.store');
        Route::get('/barang/masuk/{barangMasuk}', 'show')->name('barang.masuk.show');
        Route::get('/barang/masuk/{barangMasuk}/edit', 'edit')->name('barang.masuk.edit');
        Route::put('/barang/masuk/{barangMasuk}', 'update')->name('barang.masuk.update');
        Route::delete('/barang/masuk/{barangMasuk}', 'destroy')->name('barang.masuk.destroy');
        Route::get('/barang/masuk/get-barang-details/{id}', 'getBarangDetails')->name('barang.masuk.get-barang-details');
        Route::get('/barang/masuk/get-barang-by-kategori/{kategoriId}', 'getBarangByKategori')->name('barang.masuk.get-barang-by-kategori');
    });

    // Barang Keluar Management
    Route::controller(BarangKeluarController::class)->group(function () {
        Route::get('/barang/keluar', 'index')->name('barang.keluar');
        Route::get('/barang/keluar/create', 'create')->name('barang.keluar.create');
        Route::post('/barang/keluar', 'store')->name('barang.keluar.store');
        Route::get('/barang/keluar/{barangKeluar}', 'show')->name('barang.keluar.show');
        Route::get('/barang/keluar/{barangKeluar}/edit', 'edit')->name('barang.keluar.edit');
        Route::put('/barang/keluar/{barangKeluar}', 'update')->name('barang.keluar.update');
        Route::delete('/barang/keluar/{barangKeluar}', 'destroy')->name('barang.keluar.destroy');
        Route::get('/barang/keluar/get-barang-details/{id}', 'getBarangDetails')->name('barang.keluar.get-barang-details');
        Route::get('/barang/keluar/get-barang-by-kategori/{kategoriId}', 'getBarangByKategori')->name('barang.keluar.get-barang-by-kategori');
    });

    // Peminjaman Management
    Route::controller(PeminjamanController::class)->group(function () {
        Route::get('/peminjaman', 'index')->name('peminjaman.index');
        Route::get('/peminjaman/create', 'create')->name('peminjaman.create');
        Route::post('/peminjaman', 'store')->name('peminjaman.store');
        Route::get('/peminjaman/{peminjaman}', 'show')->name('peminjaman.show');
        Route::get('/peminjaman/{peminjaman}/edit', 'edit')->name('peminjaman.edit');
        Route::put('/peminjaman/{peminjaman}', 'update')->name('peminjaman.update');
        Route::delete('/peminjaman/{peminjaman}', 'destroy')->name('peminjaman.destroy');
        Route::post('/peminjaman/{peminjaman}/kembalikan', 'kembalikan')->name('peminjaman.kembalikan');
        Route::get('/peminjaman/get-barang-details/{id}', 'getBarangDetails')->name('peminjaman.get-barang-details');
    });

    // Perawatan Management
    Route::controller(PerawatanController::class)->group(function () {
        Route::get('/perawatan', 'index')->name('perawatan.index');
        Route::get('/perawatan/create', 'create')->name('perawatan.create');
        Route::post('/perawatan', 'store')->name('perawatan.store');
        Route::get('/perawatan/{perawatan}', 'show')->name('perawatan.show');
        Route::get('/perawatan/{perawatan}/edit', 'edit')->name('perawatan.edit');
        Route::put('/perawatan/{perawatan}', 'update')->name('perawatan.update');
        Route::delete('/perawatan/{perawatan}', 'destroy')->name('perawatan.destroy');
        Route::post('/perawatan/{perawatan}/selesaikan', 'selesaikan')->name('perawatan.selesaikan');
        Route::get('/perawatan/get-barang-details/{id}', 'getBarangDetails')->name('perawatan.get-barang-details');
    });

    // Kategori Management
    Route::resource('kategori', KategoriController::class);

    // Pengajuan Management
    Route::resource('pengajuan', PengajuanController::class);

    // Audit Management
    Route::resource('audit', AuditController::class);

    // Tambahkan route untuk jadwal audit
    Route::post('/audit/selesaikan-jadwal/{jadwalAudit}', [AuditController::class, 'selesaikanJadwal'])->name('audit.selesaikan-jadwal');
    Route::get('/audit/show-jadwal/{jadwalAudit}', [AuditController::class, 'showJadwal'])->name('audit.show-jadwal');
});

// ===================== BENDAHARA ROUTES =====================
Route::group(['prefix' => 'bendahara', 'middleware' => ['auth', 'role:bendahara'], 'as' => 'bendahara.'], function () {
    // Dashboard
    Route::get('/dashboard', [BendaharaDashboardController::class, 'index'])->name('dashboard');

    // Verifikasi Pengadaan
    Route::controller(VerifikasiPengadaanController::class)->group(function () {
        Route::get('/verifikasi', 'index')->name('verifikasi.index');
        Route::get('/verifikasi/{pengajuan}', 'show')->name('verifikasi.show');
        Route::post('/verifikasi/{pengajuan}', 'verifikasi')->name('verifikasi.verifikasi');
    });

    // Analisis TOPSIS
    Route::controller(AnalisisTopsisController::class)->group(function () {
        Route::get('/analisis', 'index')->name('analisis.index');
        Route::post('/analisis/store-nilai', 'storeNilai')->name('analisis.store-nilai');
        Route::get('/analisis/hasil', 'hasil')->name('analisis.hasil');
    });

    // Kas Management
    Route::get('/kas', [KasController::class, 'index'])->name('kas.index');
    Route::get('/kas/create', [KasController::class, 'create'])->name('kas.create');
    Route::post('/kas', [KasController::class, 'store'])->name('kas.store');
    Route::get('/kas/{ka}', [KasController::class, 'show'])->name('kas.show');
    Route::get('/kas/{ka}/edit', [KasController::class, 'edit'])->name('kas.edit');
    Route::put('/kas/{ka}', [KasController::class, 'update'])->name('kas.update');
    Route::delete('/kas/{ka}', [KasController::class, 'destroy'])->name('kas.destroy');
    Route::get('/kas/laporan', [KasController::class, 'laporan'])->name('kas.laporan');

    // Laporan
    Route::controller(LaporanController::class)->group(function () {
        Route::get('/laporan', 'index')->name('laporan.index');
        Route::get('/laporan/kas', 'kas')->name('laporan.kas');
        Route::get('/laporan/pengadaan', 'pengadaan')->name('laporan.pengadaan');
    });
});

// ===================== STORAGE ROUTE =====================
Route::get('storage/{path}', function ($path) {
    return response()->file(storage_path('app/public/' . $path));
})->where('path', '.*')->name('storage.local');

// Test routes untuk debugging
Route::get('/test-server', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'Laravel server is running',
        'timestamp' => now(),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'environment' => app()->environment()
    ]);
});

Route::get('/test-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user() ? [
            'id' => auth()->user()->id,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role
        ] : null,
        'session_id' => session()->getId()
    ]);
});

Route::post('/test-csrf', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'CSRF token is valid',
        'data' => request()->all()
    ]);
});
