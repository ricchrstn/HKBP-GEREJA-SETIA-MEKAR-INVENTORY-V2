<?php

namespace App\Exports\Admin; // Mendefinisikan namespace untuk kelas ini, menunjukkan lokasinya dalam struktur folder aplikasi.

use App\Models\Peminjaman; // Mengimpor model Peminjaman. Meskipun tidak secara langsung digunakan di sini, ini menunjukkan konteks data yang mungkin diekspor.
use Illuminate\Contracts\View\View; // Mengimpor interface View dari Laravel untuk tipe hinting.
use Maatwebsite\Excel\Concerns\FromView; // Mengimpor trait FromView dari Maatwebsite/Excel, yang memungkinkan kita mengekspor dari view Blade.
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Mengimpor trait ShouldAutoSize, yang secara otomatis mengatur lebar kolom agar pas dengan kontennya.
use Maatwebsite\Excel\Concerns\WithEvents; // Mengimpor trait WithEvents, yang memungkinkan kita untuk mendaftarkan event untuk kustomisasi lebih lanjut.
use Maatwebsite\Excel\Events\AfterSheet; // Mengimpor event AfterSheet, yang akan dipicu setelah sheet dibuat.

class PeminjamanExport implements FromView, ShouldAutoSize, WithEvents // Mendefinisikan kelas PeminjamanExport dan mengimplementasikan tiga interface/trait yang diimpor.
{
    protected $peminjaman; // Mendeklarasikan properti protected $peminjaman untuk menyimpan koleksi data peminjaman yang akan diekspor.
    protected $request; // Mendeklarasikan properti protected $request untuk menyimpan objek request (misalnya, filter tanggal).

    public function __construct($peminjaman, $request) // Konstruktor kelas, dipanggil saat objek PeminjamanExport dibuat.
    {
        $this->peminjaman = $peminjaman; // Menginisialisasi properti $peminjaman dengan data peminjaman yang diterima.
        $this->request = $request; // Menginisialisasi properti $request dengan objek request yang diterima.
    }

    public function view(): View // Metode yang diwajibkan oleh FromView. Ini mengembalikan view Blade yang akan digunakan untuk membuat laporan Excel.
    {
        return view('admin.laporan.excel.peminjaman', [ // Mengembalikan view Blade yang terletak di 'resources/views/admin/laporan/excel/peminjaman.blade.php'.
            'peminjaman' => $this->peminjaman, // Meneruskan koleksi data peminjaman ke view.
            'request' => $this->request // Meneruskan objek request ke view.
        ]);
    }

    public function registerEvents(): array // Metode yang diwajibkan oleh WithEvents. Ini memungkinkan kita untuk mendaftarkan event-event.
    {
        return [ // Mengembalikan array event.
            AfterSheet::class => function(AfterSheet $event) { // Mendaftarkan closure yang akan dijalankan setelah sheet Excel selesai dibuat.
                $event->sheet->getStyle('A1:G1')->applyFromArray([ // Memilih range sel dari A1 hingga G1 (biasanya baris header) dan menerapkan style.
                    'font' => [ // Pengaturan style untuk font.
                        'bold' => true, // Mengatur font menjadi tebal.
                    ],
                    'fill' => [ // Pengaturan style untuk fill (warna latar belakang sel).
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Menentukan tipe fill sebagai solid color.
                        'startColor' => [ // Menentukan warna awal fill.
                            'rgb' => 'E8F5E9' // Mengatur warna latar belakang header menjadi hijau muda (kode RGB).
                        ]
                    ]
                ]);
            },
        ];
    }
}