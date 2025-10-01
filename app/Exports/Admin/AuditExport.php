<?php

namespace App\Exports\admin;

use App\Models\Audit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AuditExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $audits;
    protected $jadwalAudits;
    protected $request;

    public function __construct($audits, $jadwalAudits, $request)
    {
        $this->audits = $audits;
        $this->jadwalAudits = $jadwalAudits;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('admin.laporan.excel.audit', [
            'audits' => $this->audits,
            'jadwalAudits' => $this->jadwalAudits,
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
                            'rgb' => 'F3E5F5'
                        ]
                    ]
                ]);
            },
        ];
    }
}
