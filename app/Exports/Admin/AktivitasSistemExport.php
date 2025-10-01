<?php

namespace App\Exports\admin;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AktivitasSistemExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $barangMasuk;
    protected $barangKeluar;
    protected $peminjaman;
    protected $perawatan;
    protected $userAktif;
    protected $kategori;
    protected $barangPerStatus;
    protected $bulanIni;
    protected $tahunIni;
    protected $request;

    public function __construct($barangMasuk, $barangKeluar, $peminjaman, $perawatan, $userAktif, $kategori, $barangPerStatus, $bulanIni, $tahunIni, $request)
    {
        $this->barangMasuk = $barangMasuk;
        $this->barangKeluar = $barangKeluar;
        $this->peminjaman = $peminjaman;
        $this->perawatan = $perawatan;
        $this->userAktif = $userAktif;
        $this->kategori = $kategori;
        $this->barangPerStatus = $barangPerStatus;
        $this->bulanIni = $bulanIni;
        $this->tahunIni = $tahunIni;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('admin.laporan.excel.aktivitas_sistem', [
            'barangMasuk' => $this->barangMasuk,
            'barangKeluar' => $this->barangKeluar,
            'peminjaman' => $this->peminjaman,
            'perawatan' => $this->perawatan,
            'userAktif' => $this->userAktif,
            'kategori' => $this->kategori,
            'barangPerStatus' => $this->barangPerStatus,
            'bulanIni' => $this->bulanIni,
            'tahunIni' => $this->tahunIni,
            'request' => $this->request
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:B1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'E8EAF6'
                        ]
                    ]
                ]);
            },
        ];
    }
}
