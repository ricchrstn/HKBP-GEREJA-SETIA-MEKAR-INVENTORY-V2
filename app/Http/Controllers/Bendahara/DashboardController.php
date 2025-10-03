<?php

namespace App\Http\Controllers\Bendahara; // Mendefinisikan namespace untuk controller ini, menunjukkan bahwa ini adalah controller khusus untuk peran 'Bendahara'.

use App\Http\Controllers\Controller; // Mengimpor kelas Controller dasar Laravel.
use App\Models\Kas; // Mengimpor model Kas, yang merepresentasikan transaksi kas masuk dan keluar.
use App\Models\Pengajuan; // Mengimpor model Pengajuan, yang merepresentasikan permintaan pengadaan barang.
use App\Models\AnalisisTopsis; // Mengimpor model AnalisisTopsis, yang menyimpan hasil perhitungan TOPSIS (perankingan pengajuan).
use Illuminate\Support\Facades\DB; // Mengimpor facade DB (tidak digunakan secara eksplisit di sini, tapi mungkin untuk kueri raw SQL).
use Carbon\Carbon; // Mengimpor kelas Carbon, pustaka PHP yang kuat untuk manipulasi tanggal dan waktu.

class DashboardController extends Controller // Mendefinisikan kelas DashboardController untuk Bendahara.
{
    // Metode 'index' adalah metode default yang akan dijalankan ketika dashboard diakses.
    public function index()
    {
        // --- Statistik untuk kartu ringkasan di dashboard ---

        // Total kas masuk bulan ini:
        $kasMasukBulanIni = Kas::masuk() // Memanggil scope 'masuk()' dari model Kas (asumsi ada scope ini untuk memfilter transaksi masuk).
            ->whereMonth('tanggal', Carbon::now()->month) // Memfilter berdasarkan bulan saat ini.
            ->whereYear('tanggal', Carbon::now()->year)   // Memfilter berdasarkan tahun saat ini.
            ->sum('jumlah'); // Menjumlahkan kolom 'jumlah' dari hasil filter.

        // Total kas keluar bulan ini:
        $kasKeluarBulanIni = Kas::keluar() // Memanggil scope 'keluar()' dari model Kas.
            ->whereMonth('tanggal', Carbon::now()->month) // Memfilter berdasarkan bulan saat ini.
            ->whereYear('tanggal', Carbon::now()->year)   // Memfilter berdasarkan tahun saat ini.
            ->sum('jumlah'); // Menjumlahkan kolom 'jumlah' dari hasil filter.

        // Total saldo saat ini:
        $totalMasuk = Kas::masuk()->sum('jumlah');    // Total seluruh kas masuk.
        $totalKeluar = Kas::keluar()->sum('jumlah');  // Total seluruh kas keluar.
        $totalSaldo = $totalMasuk - $totalKeluar; // Saldo adalah selisih total masuk dan total keluar.

        // --- Data untuk grafik kas masuk/keluar (6 bulan terakhir) ---

        $bulanIndo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']; // Array nama bulan dalam Bahasa Indonesia untuk label grafik.
        $chartLabels = [];   // Array untuk menyimpan label bulan pada grafik (e.g., "Agu", "Sep").
        $kasMasukData = [];  // Array untuk menyimpan data kas masuk per bulan.
        $kasKeluarData = []; // Array untuk menyimpan data kas keluar per bulan.

        // Loop untuk mengambil data 6 bulan terakhir.
        for ($i = 5; $i >= 0; $i--) { // Mulai dari 5 bulan lalu hingga bulan saat ini (total 6 bulan).
            $bulan = date('n', strtotime("-$i months")); // Mendapatkan nomor bulan (1-12) untuk $i bulan yang lalu.
            $tahun = date('Y', strtotime("-$i months")); // Mendapatkan tahun untuk $i bulan yang lalu.
            $chartLabels[] = $bulanIndo[$bulan-1]; // Menambahkan nama bulan ke label grafik (index bulan dikurangi 1 karena array bulanIndo dimulai dari 0).

            // Data kas masuk per bulan:
            $masuk = Kas::masuk()
                ->whereMonth('tanggal', $bulan) // Filter berdasarkan bulan.
                ->whereYear('tanggal', $tahun)   // Filter berdasarkan tahun.
                ->sum('jumlah');                 // Menjumlahkan.
            $kasMasukData[] = $masuk;            // Menambahkan total kas masuk bulan ini ke array data.

            // Data kas keluar per bulan:
            $keluar = Kas::keluar()
                ->whereMonth('tanggal', $bulan) // Filter berdasarkan bulan.
                ->whereYear('tanggal', $tahun)   // Filter berdasarkan tahun.
                ->sum('jumlah');                 // Menjumlahkan.
            $kasKeluarData[] = $keluar;           // Menambahkan total kas keluar bulan ini ke array data.
        }

        // --- Daftar pengajuan pengadaan (status pending atau proses) ---

        $pengajuanPengadaan = Pengajuan::whereIn('status', ['pending', 'proses']) // Mengambil pengajuan dengan status 'pending' ATAU 'proses'.
            ->orderBy('created_at', 'desc') // Mengurutkan dari yang terbaru.
            ->take(5)                       // Mengambil 5 pengajuan terbaru saja.
            ->get();                        // Mengeksekusi query.

        // --- Data analisis TOPSIS (hasil perankingan) ---

        $analisisTopsis = AnalisisTopsis::with('pengajuan') // Mengambil data analisis TOPSIS dan sekaligus memuat relasi 'pengajuan'.
            ->orderBy('ranking', 'asc')                     // Mengurutkan berdasarkan ranking (terkecil berarti terbaik).
            ->take(5)                                       // Mengambil 5 teratas.
            ->get();                                        // Mengeksekusi query.

        // --- Statistik pengajuan (jumlah total, pending, disetujui, ditolak) ---

        $totalPengajuan = Pengajuan::count();                       // Menghitung total semua pengajuan.
        $pengajuanPending = Pengajuan::where('status', 'pending')->count(); // Menghitung pengajuan yang statusnya 'pending'.
        $pengajuanDisetujui = Pengajuan::where('status', 'disetujui')->count(); // Menghitung pengajuan yang statusnya 'disetujui'.
        $pengajuanDitolak = Pengajuan::where('status', 'ditolak')->count();     // Menghitung pengajuan yang statusnya 'ditolak'.

        // Mengirimkan semua data yang telah dikumpulkan ke view 'bendahara.dashboard.main'.
        return view('bendahara.dashboard.main', compact(
            'kasMasukBulanIni',    // Variabel untuk total kas masuk bulan ini.
            'kasKeluarBulanIni',   // Variabel untuk total kas keluar bulan ini.
            'totalSaldo',          // Variabel untuk total saldo kas.
            'chartLabels',         // Variabel untuk label bulan di grafik.
            'kasMasukData',        // Variabel untuk data kas masuk grafik.
            'kasKeluarData',       // Variabel untuk data kas keluar grafik.
            'pengajuanPengadaan',  // Variabel untuk daftar pengajuan pending/proses.
            'analisisTopsis',      // Variabel untuk hasil ranking TOPSIS.
            'totalPengajuan',      // Variabel untuk total semua pengajuan.
            'pengajuanPending',    // Variabel untuk jumlah pengajuan pending.
            'pengajuanDisetujui',  // Variabel untuk jumlah pengajuan disetujui.
            'pengajuanDitolak'     // Variabel untuk jumlah pengajuan ditolak.
        ));
    }
}