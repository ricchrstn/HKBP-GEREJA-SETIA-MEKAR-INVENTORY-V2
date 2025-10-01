<?php

namespace App\Exports\Bendahara;

use App\Models\Pengajuan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PengadaanExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $pengadaanData;
    protected $request;

    public function __construct($pengadaanData, $request)
    {
        $this->pengadaanData = $pengadaanData;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('bendahara.laporan.excel.pengadaan', [
            'pengadaanData' => $this->pengadaanData,
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
