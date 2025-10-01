<?php

namespace App\Exports\Admin;

use App\Models\Perawatan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PerawatanExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $perawatan;
    protected $request;

    public function __construct($perawatan, $request)
    {
        $this->perawatan = $perawatan;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('admin.laporan.excel.perawatan', [
            'perawatan' => $this->perawatan,
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
                            'rgb' => 'FFF8E1'
                        ]
                    ]
                ]);
            },
        ];
    }
}
