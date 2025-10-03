<?php

namespace App\Http\Controllers\Admin; // Mendefinisikan namespace untuk controller ini, mengindikasikan bahwa ini adalah bagian dari fungsionalitas admin

use App\Http\Controllers\Controller; // Mengimpor base Controller dari Laravel
use App\Models\Barang; // Mengimpor model Barang untuk berinteraksi dengan tabel barang
use App\Models\User; // Mengimpor model User untuk berinteraksi dengan tabel user
use App\Models\Peminjaman; // Mengimpor model Peminjaman untuk berinteraksi dengan tabel peminjaman
use App\Models\BarangMasuk; // Mengimpor model BarangMasuk untuk data transaksi barang masuk
use App\Models\BarangKeluar; // Mengimpor model BarangKeluar untuk data transaksi barang keluar
use App\Models\Audit; // Mengimpor model Audit untuk data hasil audit barang
use App\Models\JadwalAudit; // Mengimpor model JadwalAudit untuk data jadwal audit

class DashboardController extends Controller // Mendefinisikan kelas DashboardController yang mewarisi dari base Controller
{
    public function adminDashboard() // Metode untuk menampilkan dashboard admin
    {
        // Total barang aktif
        $totalBarang = Barang::where('status', 'Aktif')->count(); // Menghitung jumlah barang yang berstatus 'Aktif'

        // Stok kritis (barang dengan stok < 5)
        $stokKritis = Barang::where('stok', '<', 5)->where('status', 'Aktif')->count(); // Menghitung jumlah barang aktif yang stoknya kurang dari 5

        // Barang rusak dan hilang - berdasarkan audit terbaru
        // Perhatikan bahwa ini menghitung jumlah BARANG (distinct) yang pernah tercatat 'rusak' atau 'hilang' di tabel Audit.
        // Bukan total kejadian rusak/hilang, tapi berapa banyak item barang yang berbeda yang punya kondisi tersebut.
        $barangRusak = Audit::where('kondisi', 'rusak') // Mencari di tabel Audit yang kondisinya 'rusak'
            ->distinct('barang_id') // Menghitung hanya barang_id yang unik (jadi setiap barang hanya dihitung sekali meski rusak berkali-kali)
            ->count('barang_id'); // Menghitung jumlah barang_id yang unik tersebut

        $barangHilang = Audit::where('kondisi', 'hilang') // Mencari di tabel Audit yang kondisinya 'hilang'
            ->distinct('barang_id') // Menghitung hanya barang_id yang unik
            ->count('barang_id'); // Menghitung jumlah barang_id yang unik tersebut

        $totalRusakHilang = $barangRusak + $barangHilang; // Menjumlahkan total barang yang rusak atau hilang

        // Barang dalam perawatan
        $barangPerawatan = Barang::where('status', 'Perawatan')->count(); // Menghitung jumlah barang yang berstatus 'Perawatan'

        // Total user aktif
        $totalUser = User::where('is_active', true)->count(); // Menghitung jumlah user yang berstatus 'is_active' = true

        // Data untuk grafik barang masuk/keluar (6 bulan terakhir)
        $bulanIndo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']; // Array nama bulan dalam Bahasa Indonesia

        // Ambil data 6 bulan terakhir
        $enamBulanTerakhir = []; // Array untuk menyimpan nama bulan (misal: ['Agu', 'Sep', 'Okt', 'Nov', 'Des', 'Jan'])
        $barangMasukValues = []; // Array untuk menyimpan total barang masuk per bulan
        $barangKeluarValues = []; // Array untuk menyimpan total barang keluar per bulan

        for ($i = 5; $i >= 0; $i--) { // Loop mundur dari 5 ke 0 (untuk 6 bulan terakhir, termasuk bulan saat ini)
            $bulan = date('n', strtotime("-$i months")); // Mengambil nomor bulan (1-12) dari bulan $i yang lalu
            $tahun = date('Y', strtotime("-$i months")); // Mengambil tahun dari bulan $i yang lalu
            $enamBulanTerakhir[] = $bulanIndo[$bulan - 1]; // Menambahkan nama bulan ke array (misal: bulan 1 (Januari) -> index 0 di $bulanIndo)

            // Data barang masuk per bulan
            $masuk = BarangMasuk::whereMonth('tanggal', $bulan) // Mencari barang masuk berdasarkan bulan
                ->whereYear('tanggal', $tahun) // Dan berdasarkan tahun
                ->sum('jumlah'); // Menjumlahkan kolom 'jumlah' untuk bulan tersebut
            $barangMasukValues[] = $masuk; // Menambahkan total barang masuk ke array

            // Data barang keluar per bulan
            $keluar = BarangKeluar::whereMonth('tanggal', $bulan) // Mencari barang keluar berdasarkan bulan
                ->whereYear('tanggal', $tahun) // Dan berdasarkan tahun
                ->sum('jumlah'); // Menjumlahkan kolom 'jumlah' untuk bulan tersebut
            $barangKeluarValues[] = $keluar; // Menambahkan total barang keluar ke array
        }

        // Data peminjaman aktif
        $peminjamanAktif = Peminjaman::where('status', 'Dipinjam')->count(); // Menghitung jumlah peminjaman yang berstatus 'Dipinjam'

        // List peminjaman aktif untuk ditampilkan (misal: di widget dashboard)
        $listPeminjaman = Peminjaman::where('status', 'Dipinjam') // Mengambil peminjaman yang berstatus 'Dipinjam'
            ->with('barang') // Memuat relasi 'barang' (agar bisa mengakses detail barang yang dipinjam)
            ->orderBy('tanggal_pinjam', 'desc') // Mengurutkan berdasarkan tanggal pinjam terbaru
            ->take(5) // Mengambil hanya 5 data teratas
            ->get(); // Menjalankan query

        // Data barang stok kritis untuk ditampilkan di tabel (misal: di widget peringatan)
        $barangStokKritis = Barang::where('stok', '<', 5) // Mengambil barang dengan stok kurang dari 5
            ->where('status', 'Aktif') // Dan berstatus 'Aktif'
            ->with('kategori') // Memuat relasi 'kategori'
            ->take(5) // Mengambil hanya 5 data teratas
            ->get(); // Menjalankan query

        // Data jadwal audit terbaru
        $jadwalAuditTerbaru = JadwalAudit::with(['barang', 'user']) // Mengambil jadwal audit dengan memuat relasi 'barang' dan 'user'
            ->orderBy('tanggal_audit', 'desc') // Mengurutkan berdasarkan tanggal audit terbaru
            ->take(5) // Mengambil hanya 5 data teratas
            ->get(); // Menjalankan query

        return view('admin.dashboard.main', compact( // Mengembalikan view 'admin.dashboard.main' dengan semua data yang telah disiapkan
            'totalBarang', // Total barang aktif
            'stokKritis', // Jumlah barang stok kritis
            'totalRusakHilang', // Total barang rusak atau hilang
            'totalUser', // Total user aktif
            'barangPerawatan', // Jumlah barang dalam perawatan
            'enamBulanTerakhir', // Label bulan untuk grafik
            'barangMasukValues', // Data barang masuk untuk grafik
            'barangKeluarValues', // Data barang keluar untuk grafik
            'peminjamanAktif', // Jumlah peminjaman aktif
            'listPeminjaman', // List peminjaman aktif
            'barangStokKritis', // List barang stok kritis
            'jadwalAuditTerbaru' // List jadwal audit terbaru
        ));
    }
}