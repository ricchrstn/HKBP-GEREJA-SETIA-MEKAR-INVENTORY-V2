<?php

namespace App\Http\Controllers\Bendahara; // Mendefinisikan namespace untuk controller ini, menunjukkan lokasinya dalam struktur folder.

use App\Http\Controllers\Controller; // Mengimpor base Controller dari Laravel.
use App\Models\Kas; // Mengimpor model Kas yang merepresentasikan data transaksi kas.
use App\Models\Pengajuan; // Mengimpor model Pengajuan yang merepresentasikan data pengajuan pengadaan barang/jasa.
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP (misalnya dari form atau URL).
use PDF; // Mengimpor Facade PDF (biasanya dari package barryvdh/laravel-dompdf) untuk membuat file PDF.
use Excel; // Mengimpor Facade Excel (biasanya dari package maatwebsite/excel) untuk membuat file Excel.
use Carbon\Carbon; // Mengimpor kelas Carbon untuk manipulasi tanggal dan waktu.

class LaporanController extends Controller // Mendefinisikan kelas LaporanController yang mewarisi dari base Controller.
{
    public function index(Request $request) // Metode utama untuk menampilkan halaman laporan dan menangani filter serta ekspor.
    {
        $query = null; // Variabel ini sebenarnya tidak digunakan, bisa dihapus atau diganti dengan $builder.
        $builder = null; // Variabel untuk menyimpan query builder Eloquent, akan diisi berdasarkan jenis laporan.

        // Filter berdasarkan jenis laporan
        if ($request->filled('jenis_laporan')) { // Memeriksa apakah parameter 'jenis_laporan' ada di request.
            switch ($request->jenis_laporan) { // Melakukan pengecekan jenis laporan yang diminta.
                case 'kas': // Jika jenis laporan adalah 'kas'.
                    $builder = Kas::with('user'); // Memulai query untuk model Kas, eager load relasi 'user'.

                    if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) { // Memeriksa filter tanggal.
                        $builder->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]); // Menambahkan kondisi WHERE BETWEEN untuk tanggal.
                    }

                    if ($request->filled('jenis')) { // Memeriksa filter jenis kas (masuk/keluar).
                        $builder->where('jenis', $request->jenis); // Menambahkan kondisi WHERE berdasarkan jenis.
                    }
                    break; // Keluar dari switch case.

                case 'pengadaan': // Jika jenis laporan adalah 'pengadaan'.
                    $builder = Pengajuan::with('user'); // Memulai query untuk model Pengajuan, eager load relasi 'user'.

                    if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) { // Memeriksa filter tanggal.
                        $builder->whereBetween('created_at', [ // Menambahkan kondisi WHERE BETWEEN untuk kolom 'created_at'.
                            $request->tanggal_mulai . ' 00:00:00', // Tanggal mulai dari awal hari.
                            $request->tanggal_selesai . ' 23:59:59' // Tanggal selesai sampai akhir hari.
                        ]);
                    }

                    if ($request->filled('status')) { // Memeriksa filter status pengajuan.
                        $builder->where('status', $request->status); // Menambahkan kondisi WHERE berdasarkan status.
                    }
                    break; // Keluar dari switch case.
            }

            // Urutkan data
            if ($builder) { // Jika query builder sudah diinisialisasi.
                switch ($request->jenis_laporan) { // Mengurutkan berdasarkan jenis laporan.
                    case 'kas':
                        $builder->orderBy('tanggal', 'desc'); // Urutkan kas berdasarkan tanggal terbaru.
                        break;
                    case 'pengadaan':
                        $builder->orderBy('created_at', 'desc'); // Urutkan pengadaan berdasarkan tanggal pembuatan terbaru.
                        break;
                }
            }
        } else {
            // Jika tidak ada filter jenis laporan, gunakan builder untuk kas sebagai default
            $builder = Kas::with('user')->orderBy('tanggal', 'desc'); // Default tampilan adalah laporan kas terbaru.
        }

        // Handle export
        if ($request->has('export')) { // Memeriksa apakah ada parameter 'export' di request.
            $data = $builder ? $builder->get() : collect(); // Jika builder ada, ambil semua data; jika tidak, buat koleksi kosong.

            switch ($request->export) { // Melakukan pengecekan jenis ekspor yang diminta.
                case 'pdf':
                    return $this->exportPDF($data, $request); // Panggil metode untuk ekspor ke PDF.
                case 'excel':
                    return $this->exportExcel($data, $request, $request->jenis_laporan); // Panggil metode untuk ekspor ke Excel.
            }
        }

        // Pagination
        if ($builder) { // Jika query builder sudah diinisialisasi.
            $laporanData = $builder->paginate(10)->withQueryString(); // Lakukan paginasi 10 item per halaman dan pertahankan query string.
        } else {
            // Fallback jika builder tidak ada (misal halaman baru dimuat tanpa filter)
            $laporanData = Kas::with('user')->orderBy('tanggal', 'desc')->paginate(10)->withQueryString(); // Tampilkan kas sebagai default.
        }

        return view('bendahara.laporan.index', compact('laporanData')); // Mengembalikan view 'bendahara.laporan.index' dengan data laporan yang sudah dipaginasi.
    }

    private function exportPDF($data, $request) // Metode pribadi untuk menangani proses ekspor ke PDF.
    {
        $jenisLaporan = $request->jenis_laporan ?? 'kas'; // Ambil jenis laporan dari request, default 'kas'.

        if ($jenisLaporan == 'kas') { // Jika jenis laporan adalah 'kas'.
            $totalMasuk = $data->where('jenis', 'masuk')->sum('jumlah'); // Hitung total pemasukan dari data yang sudah difilter.
            $totalKeluar = $data->where('jenis', 'keluar')->sum('jumlah'); // Hitung total pengeluaran.
            $saldo = $totalMasuk - $totalKeluar; // Hitung saldo.

            $pdf = PDF::loadView('bendahara.laporan.pdf.kas', [ // Load view untuk laporan kas PDF.
                'kasData' => $data, // Data transaksi kas.
                'totalMasuk' => $totalMasuk,
                'totalKeluar' => $totalKeluar,
                'saldo' => $saldo,
                'request' => $request // Sertakan request untuk menampilkan filter di PDF.
            ]);
        } else { // Jika jenis laporan adalah 'pengadaan'.
            $pdf = PDF::loadView('bendahara.laporan.pdf.pengadaan', [ // Load view untuk laporan pengadaan PDF.
                'pengadaanData' => $data, // Data pengajuan pengadaan.
                'request' => $request // Sertakan request untuk menampilkan filter di PDF.
            ]);
        }

        return $pdf->download('laporan_' . $jenisLaporan . '_' . date('Y-m-d') . '.pdf'); // Mengunduh file PDF dengan nama yang dinamis.
    }

    private function exportExcel($data, $request, $jenisLaporan) // Metode pribadi untuk menangani proses ekspor ke Excel.
    {
        $fileName = 'laporan_' . $jenisLaporan . '_' . date('Y-m-d') . '.xlsx'; // Buat nama file Excel.

        switch ($jenisLaporan) { // Pilih kelas export Excel berdasarkan jenis laporan.
            case 'kas':
                $totalMasuk = $data->where('jenis', 'masuk')->sum('jumlah'); // Hitung total pemasukan.
                $totalKeluar = $data->where('jenis', 'keluar')->sum('jumlah'); // Hitung total pengeluaran.
                $saldo = $totalMasuk - $totalKeluar; // Hitung saldo.
                return Excel::download(new \App\Exports\Bendahara\KasExport($data, $totalMasuk, $totalKeluar, $saldo, $request), $fileName); // Unduh Excel menggunakan KasExport.
            case 'pengadaan':
                return Excel::download(new \App\Exports\Bendahara\PengadaanExport($data, $request), $fileName); // Unduh Excel menggunakan PengadaanExport.
            default: // Default jika jenis laporan tidak dikenal.
                return Excel::download(new \App\Exports\Bendahara\LaporanExport($data, $request), $fileName); // Unduh Excel menggunakan LaporanExport generik.
        }
    }

    public function kas(Request $request) // Metode terpisah untuk laporan kas (mungkin untuk URL spesifik laporan kas).
    {
        $query = Kas::with('user'); // Memulai query untuk model Kas, eager load relasi 'user'.

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) { // Memeriksa filter tanggal.
            $query->whereBetween('tanggal', [ // Menambahkan kondisi WHERE BETWEEN untuk tanggal.
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) { // Memeriksa filter jenis kas (masuk/keluar).
            $query->where('jenis', $request->jenis); // Menambahkan kondisi WHERE berdasarkan jenis.
        }

        $kasData = $query->orderBy('tanggal', 'desc')->get(); // Menjalankan query, urutkan berdasarkan tanggal terbaru, dan ambil semua hasilnya.

        // Hitung total masuk dan keluar
        $totalMasuk = $kasData->where('jenis', 'masuk')->sum('jumlah'); // Hitung total pemasukan dari data yang diambil.
        $totalKeluar = $kasData->where('jenis', 'keluar')->sum('jumlah'); // Hitung total pengeluaran.
        $saldo = $totalMasuk - $totalKeluar; // Hitung saldo.

        // Data untuk grafik
        $pemasukanPerBulan = []; // Array untuk menyimpan pemasukan per bulan.
        $pengeluaranPerBulan = []; // Array untuk menyimpan pengeluaran per bulan.

        for ($i = 1; $i <= 12; $i++) { // Looping dari bulan 1 sampai 12.
            $pemasukanPerBulan[] = Kas::masuk() // Hitung total pemasukan untuk bulan $i di tahun ini.
                ->whereMonth('tanggal', $i)
                ->whereYear('tanggal', date('Y'))
                ->sum('jumlah');

            $pengeluaranPerBulan[] = Kas::keluar() // Hitung total pengeluaran untuk bulan $i di tahun ini.
                ->whereMonth('tanggal', $i)
                ->whereYear('tanggal', date('Y'))
                ->sum('jumlah');
        }

        if ($request->has('download') || $request->has('export')) { // Memeriksa apakah ada parameter 'download' atau 'export'.
            if ($request->has('export') && $request->export == 'excel') { // Jika export ke Excel.
                $fileName = 'laporan_kas_' . date('Y-m-d') . '.xlsx'; // Buat nama file Excel.
                return Excel::download(new \App\Exports\Bendahara\KasExport($kasData, $totalMasuk, $totalKeluar, $saldo, $request), $fileName); // Unduh Excel.
            } else { // Jika download atau export ke PDF (default).
                $pdf = PDF::loadView('bendahara.laporan.pdf.kas', compact('kasData', 'totalMasuk', 'totalKeluar', 'saldo', 'request')); // Load view PDF.
                return $pdf->download('laporan_kas_' . date('Y-m-d') . '.pdf'); // Unduh PDF.
            }
        }

        return view('bendahara.laporan.kas', compact( // Mengembalikan view 'bendahara.laporan.kas' dengan data laporan dan grafik.
            'kasData',
            'totalMasuk',
            'totalKeluar',
            'saldo',
            'pemasukanPerBulan',
            'pengeluaranPerBulan'
        ));
    }

    public function pengadaan(Request $request) // Metode terpisah untuk laporan pengadaan.
    {
        $query = Pengajuan::with('user'); // Memulai query untuk model Pengajuan, eager load relasi 'user'.

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) { // Memeriksa filter tanggal.
            $query->whereBetween('created_at', [ // Menambahkan kondisi WHERE BETWEEN untuk 'created_at'.
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59'
            ]);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) { // Memeriksa filter status pengajuan.
            $query->where('status', $request->status); // Menambahkan kondisi WHERE berdasarkan status.
        }

        $pengadaanData = $query->orderBy('created_at', 'desc')->get(); // Menjalankan query, urutkan berdasarkan 'created_at' terbaru, dan ambil semua hasilnya.

        // Hitung total pengajuan per status
        $totalPending = $pengadaanData->where('status', 'pending')->count(); // Menghitung jumlah pengajuan dengan status 'pending'.
        $totalDisetujui = $pengadaanData->where('status', 'disetujui')->count(); // Menghitung jumlah pengajuan dengan status 'disetujui'.
        $totalDitolak = $pengadaanData->where('status', 'ditolak')->count(); // Menghitung jumlah pengajuan dengan status 'ditolak'.
        $totalProses = $pengadaanData->where('status', 'proses')->count(); // Menghitung jumlah pengajuan dengan status 'proses'.

        // Hitung total nilai pengadaan
        $totalNilai = $pengadaanData->sum('estimasi_harga'); // Menghitung total estimasi harga dari semua pengajuan.

        if ($request->has('download') || $request->has('export')) { // Memeriksa apakah ada parameter 'download' atau 'export'.
            if ($request->has('export') && $request->export == 'excel') { // Jika export ke Excel.
                $fileName = 'laporan_pengadaan_' . date('Y-m-d') . '.xlsx'; // Buat nama file Excel.
                return Excel::download(new \App\Exports\Bendahara\PengadaanExport($pengadaanData, $request), $fileName); // Unduh Excel.
            } else { // Jika download atau export ke PDF (default).
                $pdf = PDF::loadView('bendahara.laporan.pdf.pengadaan', compact('pengadaanData', 'request')); // Load view PDF.
                return $pdf->download('laporan_pengadaan_' . date('Y-m-d') . '.pdf'); // Unduh PDF.
            }
        }

        return view('bendahara.laporan.pengadaan', compact( // Mengembalikan view 'bendahara.laporan.pengadaan' dengan data laporan.
            'pengadaanData',
            'totalPending',
            'totalDisetujui',
            'totalDitolak',
            'totalProses',
            'totalNilai'
        ));
    }
}