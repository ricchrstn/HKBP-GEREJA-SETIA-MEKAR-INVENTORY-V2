<?php

namespace App\Exports\admin; // Mendefinisikan namespace untuk kelas ini, menunjukkan lokasinya dalam struktur folder "admin" di dalam folder "Exports".

use Illuminate\Contracts\View\View; // Mengimpor interface View dari Laravel, digunakan untuk mengembalikan view.
use Maatwebsite\Excel\Concerns\FromView; // Mengimpor trait FromView dari library Maatwebsite/Excel, yang memungkinkan kita mengekspor data dari view Blade.
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Mengimpor trait ShouldAutoSize, yang secara otomatis menyesuaikan lebar kolom Excel.
use Maatwebsite\Excel\Concerns\WithEvents; // Mengimpor trait WithEvents, yang memungkinkan kita menambahkan event listener untuk kustomisasi ekspor.
use Maatwebsite\Excel\Events\AfterSheet; // Mengimpor event AfterSheet, yang akan dipicu setelah sheet Excel selesai dibuat.

// Mendefinisikan kelas AktivitasSistemExport yang mengimplementasikan tiga interface:
// 1. FromView: Berarti data untuk ekspor akan diambil dari sebuah view.
// 2. ShouldAutoSize: Kolom-kolom di Excel akan disesuaikan ukurannya secara otomatis.
// 3. WithEvents: Memungkinkan penyesuaian lebih lanjut pada file Excel menggunakan event.
class AktivitasSistemExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $barangMasuk;    // Properti untuk menyimpan data barang masuk.
    protected $barangKeluar;   // Properti untuk menyimpan data barang keluar.
    protected $peminjaman;     // Properti untuk menyimpan data peminjaman.
    protected $perawatan;      // Properti untuk menyimpan data perawatan.
    protected $userAktif;      // Properti untuk menyimpan data pengguna aktif.
    protected $kategori;       // Properti untuk menyimpan data kategori.
    protected $barangPerStatus; // Properti untuk menyimpan data barang berdasarkan statusnya.
    protected $bulanIni;       // Properti untuk menyimpan informasi bulan ini (misalnya nama bulan atau angka).
    protected $tahunIni;       // Properti untuk menyimpan informasi tahun ini.
    protected $request;        // Properti untuk menyimpan objek request, mungkin berisi parameter filter atau tanggal.

    // Konstruktor kelas ini. Akan dipanggil saat objek AktivitasSistemExport dibuat.
    // Menerima berbagai data terkait aktivitas sistem sebagai parameter.
    public function __construct($barangMasuk, $barangKeluar, $peminjaman, $perawatan, $userAktif, $kategori, $barangPerStatus, $bulanIni, $tahunIni, $request)
    {
        $this->barangMasuk = $barangMasuk;          // Menginisialisasi properti $barangMasuk.
        $this->barangKeluar = $barangKeluar;        // Menginisialisasi properti $barangKeluar.
        $this->peminjaman = $peminjaman;            // Menginisialisasi properti $peminjaman.
        $this->perawatan = $perawatan;              // Menginisialisasi properti $perawatan.
        $this->userAktif = $userAktif;              // Menginisialisasi properti $userAktif.
        $this->kategori = $kategori;                // Menginisialisasi properti $kategori.
        $this->barangPerStatus = $barangPerStatus;  // Menginisialisasi properti $barangPerStatus.
        $this->bulanIni = $bulanIni;                // Menginisialisasi properti $bulanIni.
        $this->tahunIni = $tahunIni;                // Menginisialisasi properti $tahunIni.
        $this->request = $request;                  // Menginisialisasi properti $request.
    }

    // Metode `view()` ini diwajibkan oleh trait FromView.
    // Metode ini bertanggung jawab untuk mengembalikan view Blade yang akan digunakan sebagai template untuk file Excel.
    public function view(): View
    {
        return view('admin.laporan.excel.aktivitas_sistem', [ // Mengembalikan view Blade yang terletak di 'resources/views/admin/laporan/excel/aktivitas_sistem.blade.php'.
            'barangMasuk' => $this->barangMasuk,            // Meneruskan data barang masuk ke view.
            'barangKeluar' => $this->barangKeluar,          // Meneruskan data barang keluar ke view.
            'peminjaman' => $this->peminjaman,              // Meneruskan data peminjaman ke view.
            'perawatan' => $this->perawatan,                // Meneruskan data perawatan ke view.
            'userAktif' => $this->userAktif,                // Meneruskan data pengguna aktif ke view.
            'kategori' => $this->kategori,                  // Meneruskan data kategori ke view.
            'barangPerStatus' => $this->barangPerStatus,    // Meneruskan data barang per status ke view.
            'bulanIni' => $this->bulanIni,                  // Meneruskan informasi bulan ini ke view.
            'tahunIni' => $this->tahunIni,                  // Meneruskan informasi tahun ini ke view.
            'request' => $this->request                     // Meneruskan objek request ke view.
        ]);
    }

    // Metode `registerEvents()` ini diwajibkan oleh trait WithEvents.
    // Metode ini mengembalikan array event listener yang akan dipicu selama proses ekspor.
    public function registerEvents(): array
    {
        return [
            // Mendaftarkan event AfterSheet, yang akan dijalankan setelah sheet Excel terbentuk.
            AfterSheet::class => function(AfterSheet $event) {
                // Mengatur gaya untuk sel A1 hingga B1 (kemungkinan baris judul atau ringkasan).
                $event->sheet->getStyle('A1:B1')->applyFromArray([
                    'font' => [
                        'bold' => true, // Mengatur teks menjadi tebal.
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Mengatur tipe isian sel menjadi warna solid.
                        'startColor' => [
                            'rgb' => 'E8EAF6' // Mengatur warna latar belakang sel menjadi biru keunguan muda.
                        ]
                    ]
                ]);
            },
        ];
    }
}