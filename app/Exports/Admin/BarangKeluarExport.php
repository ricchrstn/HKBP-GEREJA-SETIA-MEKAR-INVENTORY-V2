<?php

namespace App\Exports\Admin;

use App\Models\BarangKeluar;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class BarangKeluarExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $barangKeluars;
    protected $request;

    public function __construct($barangKeluars, $request)
    {
        $this->barangKeluars = $barangKeluars;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('admin.laporan.excel.barang_keluar', [
            'barangKeluars' => $this->barangKeluars,
            'request' => $this->request
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFEBEE'
                        ]
                    ]
                ]);
            },
        ];
    }
}
