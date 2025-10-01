<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use PDF;
use Excel;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = null;
        $builder = null;

        // Filter berdasarkan jenis laporan
        if ($request->filled('jenis_laporan')) {
            switch ($request->jenis_laporan) {
                case 'kas':
                    $builder = Kas::with('user');

                    if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                        $builder->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
                    }

                    if ($request->filled('jenis')) {
                        $builder->where('jenis', $request->jenis);
                    }
                    break;

                case 'pengadaan':
                    $builder = Pengajuan::with('user');

                    if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                        $builder->whereBetween('created_at', [
                            $request->tanggal_mulai . ' 00:00:00',
                            $request->tanggal_selesai . ' 23:59:59'
                        ]);
                    }

                    if ($request->filled('status')) {
                        $builder->where('status', $request->status);
                    }
                    break;
            }

            // Urutkan data
            if ($builder) {
                switch ($request->jenis_laporan) {
                    case 'kas':
                        $builder->orderBy('tanggal', 'desc');
                        break;
                    case 'pengadaan':
                        $builder->orderBy('created_at', 'desc');
                        break;
                }
            }
        } else {
            // Jika tidak ada filter, gunakan builder untuk kas sebagai default
            $builder = Kas::with('user')->orderBy('tanggal', 'desc');
        }

        // Handle export
        if ($request->has('export')) {
            $data = $builder ? $builder->get() : collect();

            switch ($request->export) {
                case 'pdf':
                    return $this->exportPDF($data, $request);
                case 'excel':
                    return $this->exportExcel($data, $request, $request->jenis_laporan);
            }
        }

        // Pagination
        if ($builder) {
            $laporanData = $builder->paginate(10)->withQueryString();
        } else {
            // Fallback jika builder tidak ada
            $laporanData = Kas::with('user')->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();
        }

        return view('bendahara.laporan.index', compact('laporanData'));
    }

    private function exportPDF($data, $request)
    {
        $jenisLaporan = $request->jenis_laporan ?? 'kas';

        if ($jenisLaporan == 'kas') {
            $totalMasuk = $data->where('jenis', 'masuk')->sum('jumlah');
            $totalKeluar = $data->where('jenis', 'keluar')->sum('jumlah');
            $saldo = $totalMasuk - $totalKeluar;

            $pdf = PDF::loadView('bendahara.laporan.pdf.kas', [
                'kasData' => $data,
                'totalMasuk' => $totalMasuk,
                'totalKeluar' => $totalKeluar,
                'saldo' => $saldo,
                'request' => $request
            ]);
        } else {
            $pdf = PDF::loadView('bendahara.laporan.pdf.pengadaan', [
                'pengadaanData' => $data,
                'request' => $request
            ]);
        }

        return $pdf->download('laporan_' . $jenisLaporan . '_' . date('Y-m-d') . '.pdf');
    }

    private function exportExcel($data, $request, $jenisLaporan)
    {
        $fileName = 'laporan_' . $jenisLaporan . '_' . date('Y-m-d') . '.xlsx';

        switch ($jenisLaporan) {
            case 'kas':
                $totalMasuk = $data->where('jenis', 'masuk')->sum('jumlah');
                $totalKeluar = $data->where('jenis', 'keluar')->sum('jumlah');
                $saldo = $totalMasuk - $totalKeluar;
                return Excel::download(new \App\Exports\Bendahara\KasExport($data, $totalMasuk, $totalKeluar, $saldo, $request), $fileName);
            case 'pengadaan':
                return Excel::download(new \App\Exports\Bendahara\PengadaanExport($data, $request), $fileName);
            default:
                return Excel::download(new \App\Exports\Bendahara\LaporanExport($data, $request), $fileName);
        }
    }

    public function kas(Request $request)
    {
        $query = Kas::with('user');

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $kasData = $query->orderBy('tanggal', 'desc')->get();

        // Hitung total masuk dan keluar
        $totalMasuk = $kasData->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = $kasData->where('jenis', 'keluar')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        // Data untuk grafik
        $pemasukanPerBulan = [];
        $pengeluaranPerBulan = [];

        for ($i = 1; $i <= 12; $i++) {
            $pemasukanPerBulan[] = Kas::masuk()
                ->whereMonth('tanggal', $i)
                ->whereYear('tanggal', date('Y'))
                ->sum('jumlah');

            $pengeluaranPerBulan[] = Kas::keluar()
                ->whereMonth('tanggal', $i)
                ->whereYear('tanggal', date('Y'))
                ->sum('jumlah');
        }

        if ($request->has('download') || $request->has('export')) {
            if ($request->has('export') && $request->export == 'excel') {
                $fileName = 'laporan_kas_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new \App\Exports\Bendahara\KasExport($kasData, $totalMasuk, $totalKeluar, $saldo, $request), $fileName);
            } else {
                $pdf = PDF::loadView('bendahara.laporan.pdf.kas', compact('kasData', 'totalMasuk', 'totalKeluar', 'saldo', 'request'));
                return $pdf->download('laporan_kas_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('bendahara.laporan.kas', compact(
            'kasData',
            'totalMasuk',
            'totalKeluar',
            'saldo',
            'pemasukanPerBulan',
            'pengeluaranPerBulan'
        ));
    }

    public function pengadaan(Request $request)
    {
        $query = Pengajuan::with('user');

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59'
            ]);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengadaanData = $query->orderBy('created_at', 'desc')->get();

        // Hitung total pengajuan per status
        $totalPending = $pengadaanData->where('status', 'pending')->count();
        $totalDisetujui = $pengadaanData->where('status', 'disetujui')->count();
        $totalDitolak = $pengadaanData->where('status', 'ditolak')->count();
        $totalProses = $pengadaanData->where('status', 'proses')->count();

        // Hitung total nilai pengadaan
        $totalNilai = $pengadaanData->sum('estimasi_harga');

        if ($request->has('download') || $request->has('export')) {
            if ($request->has('export') && $request->export == 'excel') {
                $fileName = 'laporan_pengadaan_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new \App\Exports\Bendahara\PengadaanExport($pengadaanData, $request), $fileName);
            } else {
                $pdf = PDF::loadView('bendahara.laporan.pdf.pengadaan', compact('pengadaanData', 'request'));
                return $pdf->download('laporan_pengadaan_' . date('Y-m-d') . '.pdf');
            }
        }

        return view('bendahara.laporan.pengadaan', compact(
            'pengadaanData',
            'totalPending',
            'totalDisetujui',
            'totalDitolak',
            'totalProses',
            'totalNilai'
        ));
    }
}
