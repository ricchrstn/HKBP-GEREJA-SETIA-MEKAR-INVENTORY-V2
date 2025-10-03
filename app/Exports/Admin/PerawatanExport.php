<?php

namespace App\Exports\Admin; // Mendefinisikan namespace untuk kelas ini, menunjukkan lokasinya dalam struktur folder aplikasi.

use App\Models\Perawatan; // Mengimpor model Perawatan. Ini menunjukkan bahwa kelas ini akan menangani data terkait perawatan.
use Illuminate\Contracts\View\View; // Mengimpor interface View dari Laravel untuk tipe hinting.
use Maatwebsite\Excel\Concerns\FromView; // Mengimpor trait FromView dari Maatwebsite/Excel, yang memungkinkan kita mengekspor dari view Blade.
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Mengimpor trait ShouldAutoSize, yang secara otomatis mengatur lebar kolom agar pas dengan kontennya.
use Maatwebsite\Excel\Concerns\WithEvents; // Mengimpor trait WithEvents, yang memungkinkan kita untuk mendaftarkan event untuk kustomisasi lebih lanjut.
use Maatwebsite\Excel\Events\AfterSheet; // Mengimpor event AfterSheet, yang akan dipicu setelah sheet dibuat.

class PerawatanExport implements FromView, ShouldAutoSize, WithEvents // Mendefinisikan kelas PerawatanExport dan mengimplementasikan tiga interface/trait yang diimpor.
{
    protected $perawatan; // Mendeklarasikan properti protected $perawatan untuk menyimpan koleksi data perawatan yang akan diekspor.
    protected $request; // Mendeklarasikan properti protected $request untuk menyimpan objek request (misalnya, filter).

    public function __construct($perawatan, $request) // Konstruktor kelas, dipanggil saat objek PerawatanExport dibuat.
    {
        $this->perawatan = $perawatan; // Menginisialisasi properti $perawatan dengan data perawatan yang diterima.
        $this->request = $request; // Menginisialisasi properti $request dengan objek request yang diterima.
    }

    public function view(): View // Metode yang diwajibkan oleh FromView. Ini mengembalikan view Blade yang akan digunakan untuk membuat laporan Excel.
    {
        return view('admin.laporan.excel.perawatan', [ // Mengembalikan view Blade yang terletak di 'resources/views/admin/laporan/excel/perawatan.blade.php'.
            'perawatan' => $this->perawatan, // Meneruskan koleksi data perawatan ke view.
            'request' => $this->request // Meneruskan objek request ke view.
        ]);
    }

    public function registerEvents(): array // Metode yang diwajibkan oleh WithEvents. Ini memungkinkan kita untuk mendaftarkan event-event.
    {
        return [ // Mengembalikan array event.
            AfterSheet::class => function(AfterSheet $event) { // Mendaftarkan closure yang akan dijalankan setelah sheet Excel selesai dibuat.
                $event->sheet->getStyle('A1:F1')->applyFromArray([ // Memilih range sel dari A1 hingga F1 (biasanya baris header) dan menerapkan style.
                    'font' => [ // Pengaturan style untuk font.
                        'bold' => true, // Mengatur font menjadi tebal.
                    ],
                    'fill' => [ // Pengaturan style untuk fill (warna latar belakang sel).
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Menentukan tipe fill sebagai solid color.
                        'startColor' => [ // Menentukan warna awal fill.
                            'rgb' => 'FFF8E1' // Mengatur warna latar belakang header menjadi kuning pucat (kode RGB).
                        ]
                    ]
                ]);
            },
        ];
    }
}