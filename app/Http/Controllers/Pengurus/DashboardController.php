<?php

namespace App\Http\Controllers\Pengurus; // Mendefinisikan namespace untuk controller ini, menempatkannya di bawah direktori Pengurus.

use App\Http\Controllers\Controller; // Mengimpor kelas Controller dasar dari Laravel.
use App\Models\Barang;             // Mengimpor model Barang.
use App\Models\Kategori;           // Mengimpor model Kategori.
use App\Models\Peminjaman;         // Mengimpor model Peminjaman.
use App\Models\BarangMasuk;        // Mengimpor model BarangMasuk.
use App\Models\BarangKeluar;       // Mengimpor model BarangKeluar.
use App\Models\Perawatan;          // Mengimpor model Perawatan.
use App\Models\Pengajuan;          // Mengimpor model Pengajuan.
use App\Models\Audit;              // Mengimpor model Audit.
use App\Models\JadwalAudit;        // Mengimpor model JadwalAudit.
use Illuminate\Support\Facades\DB; // Mengimpor facade DB (meskipun tidak digunakan secara langsung di sini, mungkin untuk potensi di masa depan).
use Carbon\Carbon;                 // Mengimpor kelas Carbon untuk manipulasi tanggal dan waktu.

class DashboardController extends Controller // Mendefinisikan kelas DashboardController yang merupakan turunan dari Controller.
{
    public function index() // Metode index untuk menampilkan halaman dashboard.
    {
        // Statistik untuk kartu
        // Total barang masuk bulan ini
        $barangMasukBulanIni = BarangMasuk::whereMonth('tanggal', Carbon::now()->month) // Mengambil data BarangMasuk yang tanggalnya di bulan ini.
            ->whereYear('tanggal', Carbon::now()->year) // Dan tahunnya di tahun ini.
            ->sum('jumlah'); // Menjumlahkan semua kolom 'jumlah' dari hasil filter tersebut.

        // Total barang keluar bulan ini
        $barangKeluarBulanIni = BarangKeluar::whereMonth('tanggal', Carbon::now()->month) // Mengambil data BarangKeluar yang tanggalnya di bulan ini.
            ->whereYear('tanggal', Carbon::now()->year) // Dan tahunnya di tahun ini.
            ->sum('jumlah'); // Menjumlahkan semua kolom 'jumlah' dari hasil filter tersebut.

        // Peminjaman aktif
        $peminjamanAktif = Peminjaman::where('status', 'Dipinjam')->count(); // Menghitung jumlah Peminjaman dengan status 'Dipinjam'.

        // Perawatan barang (yang sedang dalam perawatan)
        $perawatanBarang = Perawatan::where('status', 'Diproses')->count(); // Menghitung jumlah Perawatan dengan status 'Diproses'.

        // Data untuk grafik barang masuk/keluar (6 bulan terakhir)
        $bulanIndo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']; // Array nama bulan dalam Bahasa Indonesia (singkat).
        $chartLabels = []; // Array untuk menyimpan label bulan pada grafik.
        $barangMasukData = []; // Array untuk menyimpan data jumlah barang masuk per bulan.
        $barangKeluarData = []; // Array untuk menyimpan data jumlah barang keluar per bulan.

        for ($i = 5; $i >= 0; $i--) { // Loop untuk mengambil data 6 bulan terakhir (dari 5 bulan lalu hingga bulan ini).
            $bulan = date('n', strtotime("-$i months")); // Mendapatkan nomor bulan (1-12) untuk $i bulan yang lalu.
            $tahun = date('Y', strtotime("-$i months")); // Mendapatkan tahun untuk $i bulan yang lalu.
            $chartLabels[] = $bulanIndo[$bulan-1]; // Menambahkan nama bulan ke label grafik (bulan-1 karena array dimulai dari 0).

            // Data barang masuk per bulan
            $masuk = BarangMasuk::whereMonth('tanggal', $bulan) // Mengambil data barang masuk untuk bulan tersebut.
                ->whereYear('tanggal', $tahun) // Dan tahun tersebut.
                ->sum('jumlah'); // Menjumlahkan total jumlah.
            $barangMasukData[] = $masuk; // Menambahkan total jumlah barang masuk ke array data.

            // Data barang keluar per bulan
            $keluar = BarangKeluar::whereMonth('tanggal', $bulan) // Mengambil data barang keluar untuk bulan tersebut.
                ->whereYear('tanggal', $tahun) // Dan tahun tersebut.
                ->sum('jumlah'); // Menjumlahkan total jumlah.
            $barangKeluarData[] = $keluar; // Menambahkan total jumlah barang keluar ke array data.
        }

        // Jadwal audit untuk pengurus yang sedang login
        $jadwalAudit = JadwalAudit::where('user_id', auth()->id()) // Mengambil jadwal audit yang user_id-nya sesuai dengan user yang sedang login.
            ->whereIn('status', ['terjadwal', 'diproses']) // Memfilter hanya status 'terjadwal' atau 'diproses'.
            ->orderBy('tanggal_audit', 'asc') // Mengurutkan berdasarkan tanggal audit secara ascending.
            ->take(5) // Mengambil 5 jadwal teratas.
            ->get(); // Menjalankan query.

        // Daftar pengajuan pengadaan oleh pengurus yang sedang login
        $pengajuanPengadaan = Pengajuan::where('user_id', auth()->id()) // Mengambil pengajuan pengadaan yang user_id-nya sesuai dengan user yang sedang login.
            ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan tanggal dibuat terbaru.
            ->take(5) // Mengambil 5 pengajuan teratas.
            ->get(); // Menjalankan query.

        // Data peminjaman untuk tabel (5 peminjaman terbaru)
        $peminjamanList = Peminjaman::with('barang') // Mengambil daftar peminjaman dengan eager loading relasi 'barang'.
            ->orderBy('tanggal_pinjam', 'desc') // Mengurutkan berdasarkan tanggal pinjam terbaru.
            ->take(5) // Mengambil 5 peminjaman teratas.
            ->get(); // Menjalankan query.

        // Data perawatan untuk tabel (5 perawatan terbaru)
        $perawatanList = Perawatan::with('barang') // Mengambil daftar perawatan dengan eager loading relasi 'barang'.
            ->orderBy('tanggal_perawatan', 'desc') // Mengurutkan berdasarkan tanggal perawatan terbaru.
            ->take(5) // Mengambil 5 perawatan teratas.
            ->get(); // Menjalankan query.

        return view('pengurus.dashboard.main', compact( // Mengembalikan view 'pengurus.dashboard.main' dengan semua variabel yang sudah disiapkan.
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