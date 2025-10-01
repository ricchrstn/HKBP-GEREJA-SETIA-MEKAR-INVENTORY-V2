<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Perawatan;
use App\Models\Pengajuan;
use App\Models\Audit;
use App\Models\JadwalAudit;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik untuk kartu
        // Total barang masuk bulan ini
        $barangMasukBulanIni = BarangMasuk::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');

        // Total barang keluar bulan ini
        $barangKeluarBulanIni = BarangKeluar::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');

        // Peminjaman aktif
        $peminjamanAktif = Peminjaman::where('status', 'Dipinjam')->count();

        // Perawatan barang (yang sedang dalam perawatan)
        $perawatanBarang = Perawatan::where('status', 'Diproses')->count();

        // Data untuk grafik barang masuk/keluar (6 bulan terakhir)
        $bulanIndo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartLabels = [];
        $barangMasukData = [];
        $barangKeluarData = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = date('n', strtotime("-$i months"));
            $tahun = date('Y', strtotime("-$i months"));
            $chartLabels[] = $bulanIndo[$bulan-1];

            // Data barang masuk per bulan
            $masuk = BarangMasuk::whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');
            $barangMasukData[] = $masuk;

            // Data barang keluar per bulan
            $keluar = BarangKeluar::whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');
            $barangKeluarData[] = $keluar;
        }

        // Jadwal audit untuk pengurus yang sedang login
        $jadwalAudit = JadwalAudit::where('user_id', auth()->id())
            ->whereIn('status', ['terjadwal', 'diproses'])
            ->orderBy('tanggal_audit', 'asc')
            ->take(5)
            ->get();

        // Daftar pengajuan pengadaan oleh pengurus yang sedang login
        $pengajuanPengadaan = Pengajuan::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Data peminjaman untuk tabel
        $peminjamanList = Peminjaman::with('barang')
            ->orderBy('tanggal_pinjam', 'desc')
            ->take(5)
            ->get();

        $perawatanList = Perawatan::with('barang')
            ->orderBy('tanggal_perawatan', 'desc')
            ->take(5)
            ->get();

        return view('pengurus.dashboard.main', compact(
            'barangMasukBulanIni',
            'barangKeluarBulanIni',
            'peminjamanAktif',
            'perawatanBarang',
            'chartLabels',
            'barangMasukData',
            'barangKeluarData',
            'jadwalAudit',
            'pengajuanPengadaan',
            'peminjamanList',
            'perawatanList'
        ));
    }
}
