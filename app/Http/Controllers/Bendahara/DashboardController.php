<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\Pengajuan;
use App\Models\AnalisisTopsis;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik untuk kartu
        // Total kas masuk bulan ini
        $kasMasukBulanIni = Kas::masuk()
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');

        // Total kas keluar bulan ini
        $kasKeluarBulanIni = Kas::keluar()
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');

        // Total saldo saat ini
        $totalMasuk = Kas::masuk()->sum('jumlah');
        $totalKeluar = Kas::keluar()->sum('jumlah');
        $totalSaldo = $totalMasuk - $totalKeluar;

        // Data untuk grafik kas masuk/keluar (6 bulan terakhir)
        $bulanIndo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartLabels = [];
        $kasMasukData = [];
        $kasKeluarData = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = date('n', strtotime("-$i months"));
            $tahun = date('Y', strtotime("-$i months"));
            $chartLabels[] = $bulanIndo[$bulan-1];

            // Data kas masuk per bulan
            $masuk = Kas::masuk()
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');
            $kasMasukData[] = $masuk;

            // Data kas keluar per bulan
            $keluar = Kas::keluar()
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');
            $kasKeluarData[] = $keluar;
        }

        // Daftar pengajuan pengadaan (status pending)
        $pengajuanPengadaan = Pengajuan::whereIn('status', ['pending', 'proses'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Data analisis TOPSIS
        $analisisTopsis = AnalisisTopsis::with('pengajuan')
            ->orderBy('ranking', 'asc')
            ->take(5)
            ->get();

        // Statistik pengajuan
        $totalPengajuan = Pengajuan::count();
        $pengajuanPending = Pengajuan::where('status', 'pending')->count();
        $pengajuanDisetujui = Pengajuan::where('status', 'disetujui')->count();
        $pengajuanDitolak = Pengajuan::where('status', 'ditolak')->count();

        return view('bendahara.dashboard.main', compact(
            'kasMasukBulanIni',
            'kasKeluarBulanIni',
            'totalSaldo',
            'chartLabels',
            'kasMasukData',
            'kasKeluarData',
            'pengajuanPengadaan',
            'analisisTopsis',
            'totalPengajuan',
            'pengajuanPending',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }
}
