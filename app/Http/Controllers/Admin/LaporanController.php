<?php

namespace App\Http\Controllers\Admin; // Mendefinisikan namespace untuk controller ini, mengindikasikan bahwa ini adalah bagian dari fungsionalitas admin

use App\Http\Controllers\Controller; // Mengimpor base Controller dari Laravel
use App\Models\Barang; // Mengimpor model Barang
use App\Models\BarangMasuk; // Mengimpor model BarangMasuk
use App\Models\BarangKeluar; // Mengimpor model BarangKeluar
use App\Models\Peminjaman; // Mengimpor model Peminjaman
use App\Models\Perawatan; // Mengimpor model Perawatan
use App\Models\Audit; // Mengimpor model Audit
use App\Models\Kategori; // Mengimpor model Kategori
use App\Models\User; // Mengimpor model User
use App\Models\Kas; // Mengimpor model Kas (untuk laporan keuangan)
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani HTTP request dari pengguna
use Carbon\Carbon; // Mengimpor library Carbon untuk manipulasi tanggal dan waktu

use Barryvdh\DomPDF\Facade\Pdf; // Mengimpor facade DomPDF untuk generate PDF
use Maatwebsite\Excel\Facades\Excel; // Mengimpor facade Maatwebsite/Laravel-Excel untuk generate Excel

// Mengimpor kelas-kelas export yang dibuat khusus untuk setiap jenis laporan (untuk Excel)
use App\Exports\Admin\BarangMasukExport;
use App\Exports\Admin\BarangKeluarExport;
use App\Exports\Admin\PeminjamanExport;
use App\Exports\Admin\PerawatanExport;
use App\Exports\Admin\AuditExport;
use App\Exports\Admin\KeuanganExport;
use App\Exports\Admin\AktivitasSistemExport;
use App\Exports\Admin\LaporanExport; // Default export jika tidak ada yang cocok

class LaporanController extends Controller // Mendefinisikan kelas LaporanController yang mewarisi dari base Controller
{
    public function index(Request $request) // Metode utama untuk menampilkan berbagai jenis laporan dan menangani filter/export
    {
        $query = null; // Variabel ini sebenarnya tidak digunakan langsung, bisa dihapus
        $builder = null; // Variabel untuk menyimpan query builder yang akan digunakan
        $jenisLaporan = $request->filled('jenis_laporan') ? $request->jenis_laporan : 'barang_masuk_keluar'; // Menentukan jenis laporan default jika tidak ada di request

        // Filter berdasarkan jenis laporan yang dipilih
        switch ($jenisLaporan) {
            case 'barang_masuk_keluar': // Laporan gabungan barang masuk dan keluar
                // Inisialisasi query untuk BarangMasuk dan BarangKeluar
                $barangMasuk = BarangMasuk::with(['barang', 'user']);
                $barangKeluar = BarangKeluar::with(['barang', 'user']);

                // Filter berdasarkan rentang tanggal untuk kedua query
                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $barangMasuk->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
                    $barangKeluar->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
                }

                // Filter berdasarkan status barang (melalui relasi 'barang')
                if ($request->filled('status')) {
                    $barangMasuk->whereHas('barang', function($q) use ($request) {
                        $q->where('status', $request->status);
                    });
                    $barangKeluar->whereHas('barang', function($q) use ($request) {
                        $q->where('status', $request->status);
                    });
                }

                // Ambil data dari kedua query dan tambahkan identifier 'jenis_laporan'
                $dataMasuk = $barangMasuk->orderBy('tanggal', 'desc')->get()->map(function ($item) {
                    $item->jenis_laporan = 'barang_masuk'; // Menandai sebagai barang masuk
                    return $item;
                });

                $dataKeluar = $barangKeluar->orderBy('tanggal', 'desc')->get()->map(function ($item) {
                    $item->jenis_laporan = 'barang_keluar'; // Menandai sebagai barang keluar
                    return $item;
                });

                // Gabungkan kedua koleksi data dan urutkan kembali berdasarkan tanggal
                $builder = $dataMasuk->concat($dataKeluar)->sortByDesc('tanggal');
                break;

            case 'barang_masuk': // Laporan khusus barang masuk
                $builder = BarangMasuk::with(['barang', 'user']); // Inisialisasi query untuk BarangMasuk

                // Filter berdasarkan rentang tanggal
                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $builder->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
                }

                // Filter berdasarkan status barang
                if ($request->filled('status')) {
                    $builder->whereHas('barang', function($q) use ($request) {
                        $q->where('status', $request->status);
                    });
                }
                break;

            case 'barang_keluar': // Laporan khusus barang keluar
                $builder = BarangKeluar::with(['barang', 'user']); // Inisialisasi query untuk BarangKeluar

                // Filter berdasarkan rentang tanggal
                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $builder->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
                }

                // Filter berdasarkan status barang
                if ($request->filled('status')) {
                    $builder->whereHas('barang', function($q) use ($request) {
                        $q->where('status', $request->status);
                    });
                }
                break;

            case 'peminjaman': // Laporan peminjaman
                $builder = Peminjaman::with(['barang', 'user']); // Inisialisasi query untuk Peminjaman

                // Filter berdasarkan rentang tanggal pinjam
                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $builder->whereBetween('tanggal_pinjam', [$request->tanggal_mulai, $request->tanggal_selesai]);
                }

                // Filter berdasarkan status peminjaman
                if ($request->filled('status')) {
                    $builder->where('status', $request->status);
                }
                break;

            case 'perawatan': // Laporan perawatan
                $builder = Perawatan::with(['barang', 'user']); // Inisialisasi query untuk Perawatan

                // Filter berdasarkan rentang tanggal perawatan
                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $builder->whereBetween('tanggal_perawatan', [$request->tanggal_mulai, $request->tanggal_selesai]);
                }

                // Filter berdasarkan status perawatan
                if ($request->filled('status')) {
                    $builder->where('status', $request->status);
                }
                break;

            case 'audit': // Laporan audit
                $builder = Audit::with(['barang', 'user']); // Inisialisasi query untuk Audit

                // Filter berdasarkan rentang tanggal audit
                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $builder->whereBetween('tanggal_audit', [$request->tanggal_mulai, $request->tanggal_selesai]);
                }

                // Filter berdasarkan status audit
                if ($request->filled('status')) {
                    $builder->where('status', $request->status);
                }
                break;
        }

        // Handle export (PDF atau Excel)
        if ($request->has('export')) { // Jika ada parameter 'export' di request
            $data = $builder ? $builder : collect(); // Ambil data dari builder atau koleksi kosong jika builder null

            switch ($request->export) { // Menentukan jenis export (pdf/excel)
                case 'pdf':
                    return $this->exportPDF($data, $request, $jenisLaporan); // Memanggil metode untuk export PDF
                case 'excel':
                    return $this->exportExcel($data, $request, $jenisLaporan); // Memanggil metode untuk export Excel
            }
        }

        // Logika Pagination untuk tampilan di web
        if ($jenisLaporan == 'barang_masuk_keluar') {
            // Untuk laporan gabungan (koleksi), kita perlu manual pagination
            $page = $request->get('page', 1); // Ambil nomor halaman dari request, default 1
            $perPage = 10; // Jumlah item per halaman
            $laporanData = new \Illuminate\Pagination\LengthAwarePaginator( // Membuat objek paginator manual
                $builder->slice(($page - 1) * $perPage, $perPage), // Ambil slice data sesuai halaman
                $builder->count(), // Total jumlah item
                $perPage, // Item per halaman
                $page, // Halaman saat ini
                ['path' => $request->url(), 'query' => $request->query()] // Konfigurasi path dan query untuk link paginasi
            );
        } else {
            if ($builder) { // Jika builder ada (bukan laporan gabungan)
                // Tentukan urutan default untuk setiap jenis laporan
                switch ($jenisLaporan) {
                    case 'barang_masuk':
                    case 'barang_keluar':
                        $builder->orderBy('tanggal', 'desc');
                        break;
                    case 'peminjaman':
                        $builder->orderBy('tanggal_pinjam', 'desc');
                        break;
                    case 'perawatan':
                        $builder->orderBy('tanggal_perawatan', 'desc');
                        break;
                    case 'audit':
                        $builder->orderBy('tanggal_audit', 'desc');
                        break;
                }
            } else {
                // Fallback jika builder tidak ada (misalnya, jenis laporan tidak dikenal), tampilkan barang masuk sebagai default
                $builder = BarangMasuk::with(['barang', 'user'])->orderBy('tanggal', 'desc');
            }
            $laporanData = $builder->paginate(10)->withQueryString(); // Lakukan paginasi untuk query builder Eloquent
        }

        return view('admin.laporan.index', compact('laporanData', 'jenisLaporan')); // Tampilkan view laporan dengan data dan jenis laporan
    }

    private function exportPDF($data, $request, $jenisLaporan) // Metode privat untuk menangani export ke PDF
    {
        $viewName = 'admin.laporan.pdf.combined'; // Default view untuk PDF, mungkin ini perlu disesuaikan

        if ($jenisLaporan == 'barang_masuk_keluar') { // Jika laporan gabungan
            $viewName = 'admin.laporan.pdf.barang_masuk_keluar'; // Gunakan view spesifik untuk gabungan
        }
        // Catatan: Sepertinya ada kode berulang untuk setiap jenis laporan di bawah.
        // Sebaiknya, buat view spesifik untuk setiap jenis laporan di sini juga.

        $pdf = PDF::loadView($viewName, [ // Load view untuk PDF
            'data' => $data,
            'request' => $request,
            'jenisLaporan' => $jenisLaporan
        ]);

        return $pdf->download('laporan_' . $jenisLaporan . '_' . date('Y-m-d') . '.pdf'); // Download file PDF
    }

    private function exportExcel($data, $request, $jenisLaporan) // Metode privat untuk menangani export ke Excel
    {
        $fileName = 'laporan_' . $jenisLaporan . '_' . date('Y-m-d') . '.xlsx'; // Nama file Excel

        switch ($jenisLaporan) { // Memilih kelas Export yang sesuai berdasarkan jenis laporan
            case 'barang_masuk':
                return Excel::download(new \App\Exports\Admin\BarangMasukExport($data, $request), $fileName);
            case 'barang_keluar':
                return Excel::download(new \App\Exports\Admin\BarangKeluarExport($data, $request), $fileName);
            case 'peminjaman':
                return Excel::download(new \App\Exports\Admin\PeminjamanExport($data, $request), $fileName);
            case 'perawatan':
                return Excel::download(new \App\Exports\Admin\PerawatanExport($data, $request), $fileName);
            case 'audit':
                // Di sini ada `$jadwalAudits` yang dilewatkan sebagai `collect()`, ini mungkin perlu disesuaikan jika laporan audit butuh data JadwalAudit
                return Excel::download(new \App\Exports\Admin\AuditExport($data, collect(), $request), $fileName);
            case 'barang_masuk_keluar':
                return Excel::download(new \App\Exports\Admin\BarangMasukKeluarExport($data, $request), $fileName);
            default:
                return Excel::download(new \App\Exports\Admin\LaporanExport($data, $request), $fileName);
        }
    }

    // --- Berikut adalah metode-metode laporan individual yang sepertinya duplikasi dari logika di `index` ---
    // Idealnya, logika filter, pengambilan data, dan export di `index` sudah cukup.
    // Metode-metode di bawah ini mungkin bisa dihilangkan atau diubah menjadi fungsi helper jika `index` tidak menangani semua skenario.

    public function barangMasuk(Request $request) // Metode untuk laporan barang masuk (terpisah)
    {
        $query = BarangMasuk::with(['barang.kategori', 'user']); // Query dengan relasi kategori barang

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->whereHas('barang', function($q) use ($request) {
                $q->where('kategori_id', $request->kategori_id);
            });
        }

        $barangMasuks = $query->orderBy('tanggal', 'desc')->get(); // Ambil semua data setelah filter
        $kategoris = Kategori::all(); // Ambil semua kategori untuk filter dropdown

        if ($request->has('download') || $request->has('export')) { // Jika ada request download/export
            if ($request->has('export') && $request->export == 'excel') { // Export Excel
                $fileName = 'laporan_barang_masuk_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new \App\Exports\Admin\BarangMasukExport($barangMasuks, $request), $fileName);
            } else { // Export PDF
                $pdf = PDF::loadView('admin.laporan.pdf.barang_masuk', compact('barangMasuks', 'request'));
                return $pdf->download('laporan_barang_masuk_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('admin.laporan.barang_masuk', compact('barangMasuks', 'kategoris')); // Tampilkan view laporan
    }

    public function barangKeluar(Request $request) // Metode untuk laporan barang keluar (terpisah)
    {
        $query = BarangKeluar::with(['barang.kategori', 'user']); // Query dengan relasi kategori barang

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->whereHas('barang', function($q) use ($request) {
                $q->where('kategori_id', $request->kategori_id);
            });
        }

        $barangKeluars = $query->orderBy('tanggal', 'desc')->get(); // Ambil semua data setelah filter
        $kategoris = Kategori::all(); // Ambil semua kategori untuk filter dropdown

        if ($request->has('download') || $request->has('export')) { // Jika ada request download/export
            if ($request->has('export') && $request->export == 'excel') { // Export Excel
                $fileName = 'laporan_barang_keluar_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new \App\Exports\Admin\BarangKeluarExport($barangKeluars, $request), $fileName);
            } else { // Export PDF
                $pdf = PDF::loadView('admin.laporan.pdf.barang_keluar', compact('barangKeluars', 'request'));
                return $pdf->download('laporan_barang_keluar_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('admin.laporan.barang_keluar', compact('barangKeluars', 'kategoris')); // Tampilkan view laporan
    }

    public function peminjaman(Request $request) // Metode untuk laporan peminjaman (terpisah)
    {
        $query = Peminjaman::with(['barang', 'user']); // Query dengan relasi barang dan user

        // Filter berdasarkan rentang tanggal pinjam
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_pinjam', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->orderBy('tanggal_pinjam', 'desc')->get(); // Ambil semua data setelah filter

        if ($request->has('download') || $request->has('export')) { // Jika ada request download/export
            if ($request->has('export') && $request->export == 'excel') { // Export Excel
                $fileName = 'laporan_peminjaman_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new \App\Exports\Admin\PeminjamanExport($peminjaman, $request), $fileName);
            } else { // Export PDF
                $pdf = PDF::loadView('admin.laporan.pdf.peminjaman', compact('peminjaman', 'request'));
                return $pdf->download('laporan_peminjaman_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('admin.laporan.peminjaman', compact('peminjaman')); // Tampilkan view laporan
    }

    public function perawatan(Request $request) // Metode untuk laporan perawatan (terpisah)
    {
        $query = Perawatan::with(['barang', 'user']); // Query dengan relasi barang dan user

        // Filter berdasarkan rentang tanggal perawatan
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_perawatan', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perawatan = $query->orderBy('tanggal_perawatan', 'desc')->get(); // Ambil semua data setelah filter

        if ($request->has('download') || $request->has('export')) { // Jika ada request download/export
            if ($request->has('export') && $request->export == 'excel') { // Export Excel
                $fileName = 'laporan_perawatan_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new \App\Exports\Admin\PerawatanExport($perawatan, $request), $fileName);
            } else { // Export PDF
                $pdf = PDF::loadView('admin.laporan.pdf.perawatan', compact('perawatan', 'request'));
                return $pdf->download('laporan_perawatan_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('admin.laporan.perawatan', compact('perawatan')); // Tampilkan view laporan
    }

    public function audit(Request $request) // Metode untuk laporan audit (terpisah)
    {
        $query = Audit::with(['barang', 'user']); // Query dengan relasi barang dan user
        $jadwalAudits = \App\Models\JadwalAudit::with(['barang', 'user'])->get(); // Mengambil semua jadwal audit (ini akan digunakan di view atau export)

        // Filter berdasarkan rentang tanggal audit
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_audit', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $audits = $query->orderBy('tanggal_audit', 'desc')->get(); // Ambil semua data setelah filter

        if ($request->has('download') || $request->has('export')) { // Jika ada request download/export
            if ($request->has('export') && $request->export == 'excel') { // Export Excel
                $fileName = 'laporan_audit_' . date('Y-m-d') . '.xlsx';
                // Perhatikan: `AuditExport` menerima `$jadwalAudits` sebagai parameter kedua
                return Excel::download(new \App\Exports\Admin\AuditExport($audits, $jadwalAudits, $request), $fileName);
            } else { // Export PDF
                $pdf = PDF::loadView('admin.laporan.pdf.audit', compact('audits', 'jadwalAudits', 'request'));
                return $pdf->download('laporan_audit_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('admin.laporan.audit', compact('audits', 'jadwalAudits')); // Tampilkan view laporan
    }

    public function keuangan(Request $request) // Metode untuk laporan keuangan
    {
        // Ambil data kas dari model Kas
        $kas = Kas::with('user'); // Query dengan relasi user (siapa yang mencatat transaksi kas)

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $kas->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        // Filter berdasarkan jenis transaksi kas (masuk/keluar)
        if ($request->filled('jenis')) {
            $kas->where('jenis', $request->jenis);
        }

        $kasData = $kas->orderBy('tanggal', 'desc')->get(); // Ambil semua data kas setelah filter

        // Hitung total masuk dan keluar
        $totalMasuk = $kasData->where('jenis', 'masuk')->sum('jumlah'); // Menjumlahkan semua transaksi jenis 'masuk'
        $totalKeluar = $kasData->where('jenis', 'keluar')->sum('jumlah'); // Menjumlahkan semua transaksi jenis 'keluar'
        $saldo = $totalMasuk - $totalKeluar; // Menghitung saldo akhir

        if ($request->has('download') || $request->has('export')) { // Jika ada request download/export
            if ($request->has('export') && $request->export == 'excel') { // Export Excel
                $fileName = 'laporan_keuangan_' . date('Y-m-d') . '.xlsx';
                // Meneruskan data kas, total masuk, total keluar, saldo, dan request ke kelas export
                return Excel::download(new \App\Exports\Admin\KeuanganExport($kasData, $totalMasuk, $totalKeluar, $saldo, $request), $fileName);
            } else { // Export PDF
                $pdf = PDF::loadView('admin.laporan.pdf.keuangan', compact('kasData', 'totalMasuk', 'totalKeluar', 'saldo', 'request'));
                return $pdf->download('laporan_keuangan_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('admin.laporan.keuangan', compact('kasData', 'totalMasuk', 'totalKeluar', 'saldo')); // Tampilkan view laporan keuangan
    }

    public function aktivitasSistem(Request $request) // Metode untuk laporan aktivitas sistem (ringkasan)
    {
        // Data untuk laporan aktivitas sistem, biasanya untuk bulan ini
        $bulanIni = Carbon::now()->month; // Mengambil bulan saat ini
        $tahunIni = Carbon::now()->year; // Mengambil tahun saat ini

        // Data barang masuk (bulan ini)
        $barangMasuk = BarangMasuk::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->count(); // Menghitung jumlah barang masuk di bulan ini

        // Data barang keluar (bulan ini)
        $barangKeluar = BarangKeluar::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->count(); // Menghitung jumlah barang keluar di bulan ini

        // Data peminjaman (bulan ini)
        $peminjaman = Peminjaman::whereMonth('tanggal_pinjam', $bulanIni)
            ->whereYear('tanggal_pinjam', $tahunIni)
            ->count(); // Menghitung jumlah peminjaman di bulan ini

        // Data perawatan (bulan ini)
        $perawatan = Perawatan::whereMonth('tanggal_perawatan', $bulanIni)
            ->whereYear('tanggal_perawatan', $tahunIni)
            ->count(); // Menghitung jumlah perawatan di bulan ini

        // Data pengguna aktif
        $userAktif = User::where('is_active', true)->count(); // Menghitung jumlah user yang aktif

        // Data kategori dengan jumlah barangnya
        $kategori = Kategori::withCount('barangs')->get(); // Mengambil semua kategori dan menghitung jumlah barang di setiap kategori

        // Data barang per status
        $barangPerStatus = [ // Array asosiatif untuk menghitung barang berdasarkan statusnya
            'Aktif' => Barang::where('status', 'Aktif')->count(),
            'Rusak' => Barang::where('status', 'Rusak')->count(),
            'Hilang' => Barang::where('status', 'Hilang')->count(),
            'Perawatan' => Barang::where('status', 'Perawatan')->count(),
        ];

        if ($request->has('download') || $request->has('export')) { // Jika ada request download/export
            if ($request->has('export') && $request->export == 'excel') { // Export Excel
                $fileName = 'laporan_aktivitas_sistem_' . date('Y-m-d') . '.xlsx';
                // Meneruskan semua data ringkasan ke kelas export
                return Excel::download(new \App\Exports\Admin\AktivitasSistemExport(
                    $barangMasuk, $barangKeluar, $peminjaman, $perawatan, $userAktif, $kategori, $barangPerStatus, $bulanIni, $tahunIni, $request
                ), $fileName);
            } else { // Export PDF
                $pdf = PDF::loadView('admin.laporan.pdf.aktivitas_sistem', compact( // Load view PDF
                    'barangMasuk',
                    'barangKeluar',
                    'peminjaman',
                    'perawatan',
                    'userAktif',
                    'kategori',
                    'barangPerStatus',
                    'bulanIni',
                    'tahunIni'
                ));

                return $pdf->download('laporan_aktivitas_sistem_' . date('Y-m-d') . '.pdf'); // Download file PDF
            }
        }

        return view('admin.laporan.aktivitas_sistem', compact( // Tampilkan view laporan aktivitas sistem
            'barangMasuk',
            'barangKeluar',
            'peminjaman',
            'perawatan',
            'userAktif',
            'kategori',
            'barangPerStatus',
            'bulanIni',
            'tahunIni'
        ));
    }
}