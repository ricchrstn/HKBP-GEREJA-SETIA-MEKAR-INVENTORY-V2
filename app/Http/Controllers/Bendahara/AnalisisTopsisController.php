<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Kriteria;
use App\Models\NilaiPengadaanKriteria;
use App\Models\AnalisisTopsis;
use Illuminate\Http\Request;

class AnalisisTopsisController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        $pengajuans = Pengajuan::where('status', 'pending')->get();

        return view('bendahara.analisis.index', compact('kriterias', 'pengajuans'));
    }

    public function hasil()
    {
        // Ambil semua pengajuan dengan status pending yang sudah memiliki nilai kriteria
        $pengajuans = Pengajuan::where('status', 'pending')
            ->whereNotNull('urgensi')
            ->whereNotNull('ketersediaan_stok')
            ->whereNotNull('ketersediaan_dana')
            ->get();

        // Ambil semua kriteria
        $kriterias = Kriteria::all();

        // Jika tidak ada data, kembalikan ke halaman index
        if ($pengajuans->isEmpty() || $kriterias->isEmpty()) {
            return redirect()->route('bendahara.analisis.index')
                ->with('error', 'Tidak ada data untuk dianalisis');
        }

        // Hitung TOPSIS
        $topsisData = $this->hitungTopsis($pengajuans, $kriterias);

        return view('bendahara.analisis.hasil', [
            'hasil' => $topsisData['hasil'],
            'kriterias' => $kriterias,
            'matriksKeputusan' => $topsisData['matriksKeputusan'],
            'matriksNormalisasi' => $topsisData['matriksNormalisasi'],
            'matriksTerbobot' => $topsisData['matriksTerbobot'],
            'solusiIdealPositif' => $topsisData['solusiIdealPositif'],
            'solusiIdealNegatif' => $topsisData['solusiIdealNegatif'],
            'jarakPositif' => $topsisData['jarakPositif'],
            'jarakNegatif' => $topsisData['jarakNegatif'],
            'pengajuans' => $pengajuans
        ]);
    }

    private function hitungTopsis($pengajuans, $kriterias)
    {
        // Langkah 1: Matriks Keputusan (X)
        $matriksKeputusan = [];
        foreach ($pengajuans as $pengajuan) {
            $row = [
                $pengajuan->urgensi,           // K1 - Tingkat Urgensi Barang (Benefit)
                $pengajuan->ketersediaan_stok, // K2 - Ketersediaan Stok Barang (Cost)
                $pengajuan->ketersediaan_dana, // K3 - Ketersediaan Dana Pengadaan (Benefit)
            ];
            $matriksKeputusan[] = $row;
        }

        // Langkah 2: Normalisasi Matriks (R)
        $matriksNormalisasi = [];
        $jumlahKuadrat = [];

        // Hitung jumlah kuadrat setiap kriteria
        for ($j = 0; $j < count($kriterias); $j++) {
            $jumlahKuadrat[$j] = 0;
            for ($i = 0; $i < count($pengajuans); $i++) {
                $jumlahKuadrat[$j] += pow($matriksKeputusan[$i][$j], 2);
            }
            $jumlahKuadrat[$j] = sqrt($jumlahKuadrat[$j]);
        }

        // Normalisasi
        for ($i = 0; $i < count($pengajuans); $i++) {
            $row = [];
            for ($j = 0; $j < count($kriterias); $j++) {
                $row[] = $matriksKeputusan[$i][$j] / $jumlahKuadrat[$j];
            }
            $matriksNormalisasi[] = $row;
        }

        // Langkah 3: Matriks Normalisasi Terbobot (Y)
        $matriksTerbobot = [];
        for ($i = 0; $i < count($pengajuans); $i++) {
            $row = [];
            for ($j = 0; $j < count($kriterias); $j++) {
                $row[] = $matriksNormalisasi[$i][$j] * $kriterias[$j]->bobot;
            }
            $matriksTerbobot[] = $row;
        }

        // Langkah 4: Solusi Ideal
        $solusiIdealPositif = [];
        $solusiIdealNegatif = [];

        for ($j = 0; $j < count($kriterias); $j++) {
            $kolom = [];
            for ($i = 0; $i < count($pengajuans); $i++) {
                $kolom[] = $matriksTerbobot[$i][$j];
            }

            if ($kriterias[$j]->tipe == 'benefit') {
                $solusiIdealPositif[] = max($kolom);
                $solusiIdealNegatif[] = min($kolom);
            } else { // cost
                $solusiIdealPositif[] = min($kolom);
                $solusiIdealNegatif[] = max($kolom);
            }
        }

        // Langkah 5: Menghitung Jarak
        $jarakPositif = [];
        $jarakNegatif = [];

        for ($i = 0; $i < count($pengajuans); $i++) {
            $dPlus = 0;
            $dMinus = 0;

            for ($j = 0; $j < count($kriterias); $j++) {
                $dPlus += pow($matriksTerbobot[$i][$j] - $solusiIdealPositif[$j], 2);
                $dMinus += pow($matriksTerbobot[$i][$j] - $solusiIdealNegatif[$j], 2);
            }

            $jarakPositif[] = sqrt($dPlus);
            $jarakNegatif[] = sqrt($dMinus);
        }

        // Langkah 6: Nilai Preferensi
        $preferensi = [];

        for ($i = 0; $i < count($pengajuans); $i++) {
            // Tambahkan epsilon kecil untuk menghindari pembagian dengan nol
            $epsilon = 0.000001;
            $totalJarak = $jarakPositif[$i] + $jarakNegatif[$i] + $epsilon;

            $nilaiV = $jarakNegatif[$i] / $totalJarak;
            $preferensi[] = $nilaiV;
        }

        // Langkah 7: Perankingan
        $hasil = [];

        for ($i = 0; $i < count($pengajuans); $i++) {
            $hasil[] = [
                'pengajuan' => $pengajuans[$i],
                'nilai_preferensi' => $preferensi[$i],
                'd_plus' => $jarakPositif[$i],
                'd_minus' => $jarakNegatif[$i]
            ];
        }

        // Urutkan berdasarkan nilai preferensi (descending)
        usort($hasil, function ($a, $b) {
            return $b['nilai_preferensi'] <=> $a['nilai_preferensi'];
        });

        // Simpan hasil ke database
        foreach ($hasil as $index => $item) {
            AnalisisTopsis::updateOrCreate(
                ['pengajuan_id' => $item['pengajuan']->id],
                [
                    'nilai_preferensi' => $item['nilai_preferensi'],
                    'ranking' => $index + 1
                ]
            );
        }

        // Kembalikan semua data perhitungan untuk ditampilkan di view
        return [
            'hasil' => $hasil,
            'matriksKeputusan' => $matriksKeputusan,
            'matriksNormalisasi' => $matriksNormalisasi,
            'matriksTerbobot' => $matriksTerbobot,
            'solusiIdealPositif' => $solusiIdealPositif,
            'solusiIdealNegatif' => $solusiIdealNegatif,
            'jarakPositif' => $jarakPositif,
            'jarakNegatif' => $jarakNegatif,
            'kriterias' => $kriterias
        ];
    }
}
