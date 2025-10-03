<?php

namespace App\Exports\Admin; // Mendefinisikan namespace untuk kelas ini, menunjukkan lokasinya dalam struktur folder "Admin" di dalam folder "Exports".

use App\Models\Kas; // Mengimpor model Kas, yang kemungkinan merepresentasikan data transaksi kas di database.
use Illuminate\Contracts\View\View; // Mengimpor interface View dari Laravel, digunakan untuk mengembalikan view.
use Maatwebsite\Excel\Concerns\FromView; // Mengimpor trait FromView dari library Maatwebsite/Excel, yang memungkinkan kita mengekspor data dari view Blade.
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Mengimpor trait ShouldAutoSize, yang secara otomatis menyesuaikan lebar kolom Excel.
use Maatwebsite\Excel\Concerns\WithEvents; // Mengimpor trait WithEvents, yang memungkinkan kita menambahkan event listener untuk kustomisasi ekspor.
use Maatwebsite\Excel\Events\AfterSheet; // Mengimpor event AfterSheet, yang akan dipicu setelah sheet Excel selesai dibuat.

// Mendefinisikan kelas KeuanganExport yang mengimplementasikan tiga interface:
// 1. FromView: Berarti data untuk ekspor akan diambil dari sebuah view.
// 2. ShouldAutoSize: Kolom-kolom di Excel akan disesuaikan ukurannya secara otomatis.
// 3. WithEvents: Memungkinkan penyesuaian lebih lanjut pada file Excel menggunakan event.
class KeuanganExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $kasData; // Properti untuk menyimpan data transaksi kas yang akan diekspor.
    protected $totalMasuk; // Properti untuk menyimpan total uang masuk.
    protected $totalKeluar; // Properti untuk menyimpan total uang keluar.
    protected $saldo; // Properti untuk menyimpan saldo akhir.
    protected $request; // Properti untuk menyimpan objek request, mungkin berisi parameter filter atau rentang tanggal.

    // Konstruktor kelas ini. Akan dipanggil saat objek KeuanganExport dibuat.
    // Menerima data kas, total masuk, total keluar, saldo, dan objek request sebagai parameter.
    public function __construct($kasData, $totalMasuk, $totalKeluar, $saldo, $request)
    {
        $this->kasData = $kasData; // Menginisialisasi properti $kasData dengan data yang diterima.
        $this->totalMasuk = $totalMasuk; // Menginisialisasi properti $totalMasuk.
        $this->totalKeluar = $totalKeluar; // Menginisialisasi properti $totalKeluar.
        $this->saldo = $saldo; // Menginisialisasi properti $saldo.
        $this->request = $request; // Menginisialisasi properti $request.
    }

    // Metode `view()` ini diwajibkan oleh trait FromView.
    // Metode ini bertanggung jawab untuk mengembalikan view Blade yang akan digunakan sebagai template untuk file Excel.
    public function view(): View
    {
        return view('admin.laporan.excel.keuangan', [ // Mengembalikan view Blade yang terletak di 'resources/views/admin/laporan/excel/keuangan.blade.php'.
            'kasData' => $this->kasData, // Meneruskan data transaksi kas ke view.
            'totalMasuk' => $this->totalMasuk, // Meneruskan total uang masuk ke view.
            'totalKeluar' => $this->totalKeluar, // Meneruskan total uang keluar ke view.
            'saldo' => $this->saldo, // Meneruskan saldo akhir ke view.
            'request' => $this->request // Meneruskan objek request ke view, mungkin untuk menampilkan filter yang digunakan.
        ]);
    }

    // Metode `registerEvents()` ini diwajibkan oleh trait WithEvents.
    // Metode ini mengembalikan array event listener yang akan dipicu selama proses ekspor.
    public function registerEvents(): array
    {
        return [
            // Mendaftarkan event AfterSheet, yang akan dijalankan setelah sheet Excel terbentuk.
            AfterSheet::class => function(AfterSheet $event) {
                // Mengatur gaya untuk sel A1 hingga F1 (kemungkinan baris header atau judul laporan).
                $event->sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true, // Mengatur teks menjadi tebal.
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Mengatur tipe isian sel menjadi warna solid.
                        'startColor' => [
                            'rgb' => 'E0F7FA' // Mengatur warna latar belakang sel menjadi biru muda.
                        ]
                    ]
                ]);
            },
        ];
    }
}