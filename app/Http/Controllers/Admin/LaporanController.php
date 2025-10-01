<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Peminjaman;
use App\Models\Perawatan;
use App\Models\Audit;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Kas;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\Admin\BarangMasukExport;
use App\Exports\Admin\BarangKeluarExport;
use App\Exports\Admin\PeminjamanExport;
use App\Exports\Admin\PerawatanExport;
use App\Exports\Admin\AuditExport;
use App\Exports\Admin\KeuanganExport;
use App\Exports\Admin\AktivitasSistemExport;
use App\Exports\Admin\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = null;
        $builder = null;
        $jenisLaporan = $request->filled('jenis_laporan') ? $request->jenis_laporan : 'barang_masuk_keluar';

        // Filter berdasarkan jenis laporan
        switch ($jenisLaporan) {
            case 'barang_masuk_keluar':
                // Gabungan data barang masuk dan keluar
                $barangMasuk = BarangMasuk::with(['barang', 'user']);
                $barangKeluar = BarangKeluar::with(['barang', 'user']);

                // Filter berdasarkan rentang tanggal
                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $barangMasuk->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
                    $barangKeluar->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
                }

                // Filter berdasarkan status
                if ($request->filled('status')) {
                    $barangMasuk->whereHas('barang', function($q) use ($request) {
                        $q->where('status', $request->status);
                    });
                    $barangKeluar->whereHas('barang', function($q) use ($request) {
                        $q->where('status', $request->status);
                    });
                }

                // Ambil data dan gabungkan
                $dataMasuk = $barangMasuk->orderBy('tanggal', 'desc')->get()->map(function ($item) {
                    $item->jenis_laporan = 'barang_masuk';
                    return $item;
                });

                $dataKeluar = $barangKeluar->orderBy('tanggal', 'desc')->get()->map(function ($item) {
                    $item->jenis_laporan = 'barang_keluar';
                    return $item;
                });

                // Gabungkan dan urutkan berdasarkan tanggal
                $builder = $dataMasuk->concat($dataKeluar)->sortByDesc('tanggal');
                break;

            case 'barang_masuk':
                $builder = BarangMasuk::with(['barang', 'user']);

                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $builder->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
                }

                if ($request->filled('status')) {
                    $builder->whereHas('barang', function($q) use ($request) {
                        $q->where('status', $request->status);
                    });
                }
                break;

            case 'barang_keluar':
                $builder = BarangKeluar::with(['barang', 'user']);

                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $builder->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
                }

                if ($request->filled('status')) {
                    $builder->whereHas('barang', function($q) use ($request) {
                        $q->where('status', $request->status);
                    });
                }
                break;

            case 'peminjaman':
                $builder = Peminjaman::with(['barang', 'user']);

                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $builder->whereBetween('tanggal_pinjam', [$request->tanggal_mulai, $request->tanggal_selesai]);
                }

                if ($request->filled('status')) {
                    $builder->where('status', $request->status);
                }
                break;

            case 'perawatan':
                $builder = Perawatan::with(['barang', 'user']);

                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $builder->whereBetween('tanggal_perawatan', [$request->tanggal_mulai, $request->tanggal_selesai]);
                }

                if ($request->filled('status')) {
                    $builder->where('status', $request->status);
                }
                break;

            case 'audit':
                $builder = Audit::with(['barang', 'user']);

                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $builder->whereBetween('tanggal_audit', [$request->tanggal_mulai, $request->tanggal_selesai]);
                }

                if ($request->filled('status')) {
                    $builder->where('status', $request->status);
                }
                break;
        }

        // Handle export
        if ($request->has('export')) {
            $data = $builder ? $builder : collect();

            switch ($request->export) {
                case 'pdf':
                    return $this->exportPDF($data, $request, $jenisLaporan);
                case 'excel':
                    return $this->exportExcel($data, $request, $jenisLaporan);
            }
        }

        // Pagination
        if ($jenisLaporan == 'barang_masuk_keluar') {
            // Untuk gabungan, kita perlu handle pagination manual
            $page = $request->get('page', 1);
            $perPage = 10;
            $laporanData = new \Illuminate\Pagination\LengthAwarePaginator(
                $builder->slice(($page - 1) * $perPage, $perPage),
                $builder->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            if ($builder) {
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
                // Fallback jika builder tidak ada
                $builder = BarangMasuk::with(['barang', 'user'])->orderBy('tanggal', 'desc');
            }

            $laporanData = $builder->paginate(10)->withQueryString();
        }

        return view('admin.laporan.index', compact('laporanData', 'jenisLaporan'));
    }

    private function exportPDF($data, $request, $jenisLaporan)
    {
        $viewName = 'admin.laporan.pdf.combined';

        if ($jenisLaporan == 'barang_masuk_keluar') {
            $viewName = 'admin.laporan.pdf.barang_masuk_keluar';
        }

        $pdf = PDF::loadView($viewName, [
            'data' => $data,
            'request' => $request,
            'jenisLaporan' => $jenisLaporan
        ]);

        return $pdf->download('laporan_' . $jenisLaporan . '_' . date('Y-m-d') . '.pdf');
    }

    private function exportExcel($data, $request, $jenisLaporan)
    {
        $fileName = 'laporan_' . $jenisLaporan . '_' . date('Y-m-d') . '.xlsx';

        switch ($jenisLaporan) {
            case 'barang_masuk':
                return Excel::download(new \App\Exports\Admin\BarangMasukExport($data, $request), $fileName);
            case 'barang_keluar':
                return Excel::download(new \App\Exports\Admin\BarangKeluarExport($data, $request), $fileName);
            case 'peminjaman':
                return Excel::download(new \App\Exports\Admin\PeminjamanExport($data, $request), $fileName);
            case 'perawatan':
                return Excel::download(new \App\Exports\Admin\PerawatanExport($data, $request), $fileName);
            case 'audit':
                return Excel::download(new \App\Exports\Admin\AuditExport($data, collect(), $request), $fileName);
            case 'barang_masuk_keluar':
                return Excel::download(new \App\Exports\Admin\BarangMasukKeluarExport($data, $request), $fileName);
            default:
                return Excel::download(new \App\Exports\Admin\LaporanExport($data, $request), $fileName);
        }
    }

    // ... kode lainnya tetap sama ...

    public function barangMasuk(Request $request)
    {
        $query = BarangMasuk::with(['barang.kategori', 'user']);

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

        $barangMasuks = $query->orderBy('tanggal', 'desc')->get();
        $kategoris = Kategori::all();

        if ($request->has('download') || $request->has('export')) {
            if ($request->has('export') && $request->export == 'excel') {
                $fileName = 'laporan_barang_masuk_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new \App\Exports\Admin\BarangMasukExport($barangMasuks, $request), $fileName);
            } else {
                $pdf = PDF::loadView('admin.laporan.pdf.barang_masuk', compact('barangMasuks', 'request'));
                return $pdf->download('laporan_barang_masuk_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('admin.laporan.barang_masuk', compact('barangMasuks', 'kategoris'));
    }

    public function barangKeluar(Request $request)
    {
        $query = BarangKeluar::with(['barang.kategori', 'user']);

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

        $barangKeluars = $query->orderBy('tanggal', 'desc')->get();
        $kategoris = Kategori::all();

        if ($request->has('download') || $request->has('export')) {
            if ($request->has('export') && $request->export == 'excel') {
                $fileName = 'laporan_barang_keluar_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new \App\Exports\Admin\BarangKeluarExport($barangKeluars, $request), $fileName);
            } else {
                $pdf = PDF::loadView('admin.laporan.pdf.barang_keluar', compact('barangKeluars', 'request'));
                return $pdf->download('laporan_barang_keluar_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('admin.laporan.barang_keluar', compact('barangKeluars', 'kategoris'));
    }

    public function peminjaman(Request $request)
    {
        $query = Peminjaman::with(['barang', 'user']);

        // Filter berdasarkan rentang tanggal
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

        $peminjaman = $query->orderBy('tanggal_pinjam', 'desc')->get();

        if ($request->has('download') || $request->has('export')) {
            if ($request->has('export') && $request->export == 'excel') {
                $fileName = 'laporan_peminjaman_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new \App\Exports\Admin\PeminjamanExport($peminjaman, $request), $fileName);
            } else {
                $pdf = PDF::loadView('admin.laporan.pdf.peminjaman', compact('peminjaman', 'request'));
                return $pdf->download('laporan_peminjaman_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('admin.laporan.peminjaman', compact('peminjaman'));
    }

    public function perawatan(Request $request)
    {
        $query = Perawatan::with(['barang', 'user']);

        // Filter berdasarkan rentang tanggal
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

        $perawatan = $query->orderBy('tanggal_perawatan', 'desc')->get();

        if ($request->has('download') || $request->has('export')) {
            if ($request->has('export') && $request->export == 'excel') {
                $fileName = 'laporan_perawatan_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new \App\Exports\Admin\PerawatanExport($perawatan, $request), $fileName);
            } else {
                $pdf = PDF::loadView('admin.laporan.pdf.perawatan', compact('perawatan', 'request'));
                return $pdf->download('laporan_perawatan_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('admin.laporan.perawatan', compact('perawatan'));
    }

    public function audit(Request $request)
    {
        $query = Audit::with(['barang', 'user']);
        $jadwalAudits = \App\Models\JadwalAudit::with(['barang', 'user'])->get();

        // Filter berdasarkan rentang tanggal
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

        $audits = $query->orderBy('tanggal_audit', 'desc')->get();

        if ($request->has('download') || $request->has('export')) {
            if ($request->has('export') && $request->export == 'excel') {
                $fileName = 'laporan_audit_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new \App\Exports\Admin\AuditExport($audits, $jadwalAudits, $request), $fileName);
            } else {
                $pdf = PDF::loadView('admin.laporan.pdf.audit', compact('audits', 'jadwalAudits', 'request'));
                return $pdf->download('laporan_audit_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('admin.laporan.audit', compact('audits', 'jadwalAudits'));
    }

    public function keuangan(Request $request)
    {
        // Ambil data kas dari model Kas
        $kas = Kas::with('user');

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $kas->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            $kas->where('jenis', $request->jenis);
        }

        $kasData = $kas->orderBy('tanggal', 'desc')->get();

        // Hitung total masuk dan keluar
        $totalMasuk = $kasData->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = $kasData->where('jenis', 'keluar')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        if ($request->has('download') || $request->has('export')) {
            if ($request->has('export') && $request->export == 'excel') {
                $fileName = 'laporan_keuangan_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new \App\Exports\Admin\KeuanganExport($kasData, $totalMasuk, $totalKeluar, $saldo, $request), $fileName);
            } else {
                $pdf = PDF::loadView('admin.laporan.pdf.keuangan', compact('kasData', 'totalMasuk', 'totalKeluar', 'saldo', 'request'));
                return $pdf->download('laporan_keuangan_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('admin.laporan.keuangan', compact('kasData', 'totalMasuk', 'totalKeluar', 'saldo'));
    }

    public function aktivitasSistem(Request $request)
    {
        // Data untuk laporan aktivitas sistem
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // Data barang masuk
        $barangMasuk = BarangMasuk::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->count();

        // Data barang keluar
        $barangKeluar = BarangKeluar::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->count();

        // Data peminjaman
        $peminjaman = Peminjaman::whereMonth('tanggal_pinjam', $bulanIni)
            ->whereYear('tanggal_pinjam', $tahunIni)
            ->count();

        // Data perawatan
        $perawatan = Perawatan::whereMonth('tanggal_perawatan', $bulanIni)
            ->whereYear('tanggal_perawatan', $tahunIni)
            ->count();

        // Data pengguna aktif
        $userAktif = User::where('is_active', true)->count();

        // Data kategori
        $kategori = Kategori::withCount('barangs')->get();

        // Data barang per status
        $barangPerStatus = [
            'Aktif' => Barang::where('status', 'Aktif')->count(),
            'Rusak' => Barang::where('status', 'Rusak')->count(),
            'Hilang' => Barang::where('status', 'Hilang')->count(),
            'Perawatan' => Barang::where('status', 'Perawatan')->count(),
        ];

        if ($request->has('download') || $request->has('export')) {
            if ($request->has('export') && $request->export == 'excel') {
                $fileName = 'laporan_aktivitas_sistem_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new \App\Exports\Admin\AktivitasSistemExport(
                    $barangMasuk, $barangKeluar, $peminjaman, $perawatan, $userAktif, $kategori, $barangPerStatus, $bulanIni, $tahunIni, $request
                ), $fileName);
            } else {
                $pdf = PDF::loadView('admin.laporan.pdf.aktivitas_sistem', compact(
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

                return $pdf->download('laporan_aktivitas_sistem_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('admin.laporan.aktivitas_sistem', compact(
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
