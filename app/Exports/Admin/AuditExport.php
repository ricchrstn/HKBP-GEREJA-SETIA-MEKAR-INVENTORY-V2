<?php

namespace App\Exports\admin; // Mendefinisikan namespace untuk kelas ini, menunjukkan lokasinya dalam struktur folder "admin" di dalam folder "Exports".

use App\Models\Audit; // Mengimpor model Audit, yang kemungkinan merepresentasikan data hasil audit di database.
use Illuminate\Contracts\View\View; // Mengimpor interface View dari Laravel, digunakan untuk mengembalikan view.
use Maatwebsite\Excel\Concerns\FromView; // Mengimpor trait FromView dari library Maatwebsite/Excel, yang memungkinkan kita mengekspor data dari view Blade.
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Mengimpor trait ShouldAutoSize, yang secara otomatis menyesuaikan lebar kolom Excel.
use Maatwebsite\Excel\Concerns\WithEvents; // Mengimpor trait WithEvents, yang memungkinkan kita menambahkan event listener untuk kustomisasi ekspor.
use Maatwebsite\Excel\Events\AfterSheet; // Mengimpor event AfterSheet, yang akan dipicu setelah sheet Excel selesai dibuat.

// Mendefinisikan kelas AuditExport yang mengimplementasikan tiga interface:
// 1. FromView: Berarti data untuk ekspor akan diambil dari sebuah view.
// 2. ShouldAutoSize: Kolom-kolom di Excel akan disesuaikan ukurannya secara otomatis.
// 3. WithEvents: Memungkinkan penyesuaian lebih lanjut pada file Excel menggunakan event.
class AuditExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $audits; // Properti untuk menyimpan data hasil audit yang akan diekspor.
    protected $jadwalAudits; // Properti untuk menyimpan data jadwal audit yang akan diekspor.
    protected $request; // Properti untuk menyimpan objek request, mungkin berisi parameter filter atau tanggal.

    // Konstruktor kelas ini. Akan dipanggil saat objek AuditExport dibuat.
    // Menerima data hasil audit, jadwal audit, dan objek request sebagai parameter.
    public function __construct($audits, $jadwalAudits, $request)
    {
        $this->audits = $audits; // Menginisialisasi properti $audits dengan data hasil audit yang diterima.
        $this->jadwalAudits = $jadwalAudits; // Menginisialisasi properti $jadwalAudits dengan data jadwal audit yang diterima.
        $this->request = $request; // Menginisialisasi properti $request.
    }

    // Metode `view()` ini diwajibkan oleh trait FromView.
    // Metode ini bertanggung jawab untuk mengembalikan view Blade yang akan digunakan sebagai template untuk file Excel.
    public function view(): View
    {
        return view('admin.laporan.excel.audit', [ // Mengembalikan view Blade yang terletak di 'resources/views/admin/laporan/excel/audit.blade.php'.
            'audits' => $this->audits, // Meneruskan data hasil audit ke view.
            'jadwalAudits' => $this->jadwalAudits, // Meneruskan data jadwal audit ke view.
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
                // Mengatur gaya untuk sel A1 hingga G1 (kemungkinan baris header atau judul laporan).
                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true, // Mengatur teks menjadi tebal.
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Mengatur tipe isian sel menjadi warna solid.
                        'startColor' => [
                            'rgb' => 'F3E5F5' // Mengatur warna latar belakang sel menjadi ungu muda.
                        ]
                    ]
                ]);
            },
        ];
    }
}