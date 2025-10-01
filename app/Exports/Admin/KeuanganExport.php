<?php

namespace App\Exports\Admin;

use App\Models\Kas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class KeuanganExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $kasData;
    protected $totalMasuk;
    protected $totalKeluar;
    protected $saldo;
    protected $request;

    public function __construct($kasData, $totalMasuk, $totalKeluar, $saldo, $request)
    {
        $this->kasData = $kasData;
        $this->totalMasuk = $totalMasuk;
        $this->totalKeluar = $totalKeluar;
        $this->saldo = $saldo;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('admin.laporan.excel.keuangan', [
            'kasData' => $this->kasData,
            'totalMasuk' => $this->totalMasuk,
            'totalKeluar' => $this->totalKeluar,
            'saldo' => $this->saldo,
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
                            'rgb' => 'E0F7FA'
                        ]
                    ]
                ]);
            },
        ];
    }
}
