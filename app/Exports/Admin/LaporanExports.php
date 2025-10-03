<?php

namespace App\Exports\Admin; // Mendefinisikan namespace untuk kelas ini, menunjukkan lokasinya dalam struktur folder aplikasi.

use Illuminate\Contracts\View\View; // Mengimpor interface View dari Laravel untuk tipe hinting.
use Maatwebsite\Excel\Concerns\FromView; // Mengimpor trait FromView dari Maatwebsite/Excel, yang memungkinkan kita mengekspor dari view Blade.
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Mengimpor trait ShouldAutoSize, yang secara otomatis mengatur lebar kolom agar pas dengan kontennya.
use Maatwebsite\Excel\Concerns\WithEvents; // Mengimpor trait WithEvents, yang memungkinkan kita untuk mendaftarkan event untuk kustomisasi lebih lanjut.
use Maatwebsite\Excel\Events\AfterSheet; // Mengimpor event AfterSheet, yang akan dipicu setelah sheet dibuat.

class LaporanExport implements FromView, ShouldAutoSize, WithEvents // Mendefinisikan kelas LaporanExport dan mengimplementasikan tiga interface/trait yang diimpor.
{
    protected $data; // Mendeklarasikan properti protected $data untuk menyimpan data yang akan diekspor.
    protected $request; // Mendeklarasikan properti protected $request untuk menyimpan objek request (misalnya, filter).

    public function __construct($data, $request) // Konstruktor kelas, dipanggil saat objek LaporanExport dibuat.
    {
        $this->data = $data; // Menginisialisasi properti $data dengan data yang diterima.
        $this->request = $request; // Menginisialisasi properti $request dengan objek request yang diterima.
    }

    public function view(): View // Metode yang diwajibkan oleh FromView. Ini mengembalikan view Blade yang akan digunakan untuk membuat laporan Excel.
    {
        return view('admin.laporan.excel.combined', [ // Mengembalikan view Blade yang terletak di 'resources/views/admin/laporan/excel/combined.blade.php'.
            'data' => $this->data, // Meneruskan data yang telah disimpan ke view.
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
                            'rgb' => 'E1F5FE' // Mengatur warna latar belakang menjadi light blue (kode RGB).
                        ]
                    ]
                ]);
            },
        ];
    }
}