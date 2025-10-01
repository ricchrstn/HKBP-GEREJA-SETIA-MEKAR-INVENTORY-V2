<?php

namespace App\Exports\Admin;

use App\Models\Peminjaman;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PeminjamanExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $peminjaman;
    protected $request;

    public function __construct($peminjaman, $request)
    {
        $this->peminjaman = $peminjaman;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('admin.laporan.excel.peminjaman', [
            'peminjaman' => $this->peminjaman,
            'request' => $this->request
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'E8F5E9'
                        ]
                    ]
                ]);
            },
        ];
    }
}
