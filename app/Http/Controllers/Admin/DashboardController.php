<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Audit;
use App\Models\JadwalAudit;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        // Total barang aktif
        $totalBarang = Barang::where('status', 'Aktif')->count();

        // Stok kritis (barang dengan stok < 5)
        $stokKritis = Barang::where('stok', '<', 5)->where('status', 'Aktif')->count();

        // Barang rusak dan hilang - berdasarkan audit terbaru
        $barangRusak = Audit::where('kondisi', 'rusak')
            ->distinct('barang_id')
            ->count('barang_id');

        $barangHilang = Audit::where('kondisi', 'hilang')
            ->distinct('barang_id')
            ->count('barang_id');

        $totalRusakHilang = $barangRusak + $barangHilang;

        // Barang dalam perawatan
        $barangPerawatan = Barang::where('status', 'Perawatan')->count();

        // Total user aktif
        $totalUser = User::where('is_active', true)->count();

        // Data untuk grafik barang masuk/keluar (6 bulan terakhir)
        $bulanIndo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Ambil data 6 bulan terakhir
        $enamBulanTerakhir = [];
        $barangMasukValues = [];
        $barangKeluarValues = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = date('n', strtotime("-$i months")); // n = bulan dalam angka (1-12)
            $tahun = date('Y', strtotime("-$i months"));
            $enamBulanTerakhir[] = $bulanIndo[$bulan - 1];

            // Data barang masuk per bulan
            $masuk = BarangMasuk::whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');
            $barangMasukValues[] = $masuk;

            // Data barang keluar per bulan
            $keluar = BarangKeluar::whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');
            $barangKeluarValues[] = $keluar;
        }

        // Data peminjaman aktif
        $peminjamanAktif = Peminjaman::where('status', 'Dipinjam')->count();

        // List peminjaman aktif untuk ditampilkan
        $listPeminjaman = Peminjaman::where('status', 'Dipinjam')
            ->with('barang')
            ->orderBy('tanggal_pinjam', 'desc')
            ->take(5)
            ->get();

        // Data barang stok kritis untuk ditampilkan di tabel
        $barangStokKritis = Barang::where('stok', '<', 5)
            ->where('status', 'Aktif')
            ->with('kategori')
            ->take(5)
            ->get();

        // Data jadwal audit terbaru
        $jadwalAuditTerbaru = JadwalAudit::with(['barang', 'user'])
            ->orderBy('tanggal_audit', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.main', compact(
            'totalBarang',
            'stokKritis',
            'totalRusakHilang',
            'totalUser',
            'barangPerawatan',
            'enamBulanTerakhir',
            'barangMasukValues',
            'barangKeluarValues',
            'peminjamanAktif',
            'listPeminjaman',
            'barangStokKritis',
            'jadwalAuditTerbaru'
        ));
    }
}
