<?php

namespace App\Http\Controllers\Bendahara; // Mendefinisikan namespace untuk controller ini, menandakan bahwa ini adalah controller khusus untuk peran 'Bendahara'.

use App\Http\Controllers\Controller; // Mengimpor kelas Controller dasar Laravel.
use App\Models\Pengajuan; // Mengimpor model Pengajuan, yang merepresentasikan data pengajuan barang.
use App\Models\Kriteria; // Mengimpor model Kriteria, yang merepresentasikan data kriteria penilaian (e.g., urgensi, stok).
use App\Models\NilaiPengadaanKriteria; // Mengimpor model NilaiPengadaanKriteria (meskipun tidak digunakan secara eksplisit di sini, mungkin untuk pengembangan lebih lanjut).
use App\Models\AnalisisTopsis; // Mengimpor model AnalisisTopsis, untuk menyimpan hasil perhitungan TOPSIS ke database.
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani permintaan HTTP.

class AnalisisTopsisController extends Controller // Mendefinisikan kelas controller untuk melakukan analisis TOPSIS.
{
    // Metode ini menampilkan halaman indeks untuk analisis (misalnya, daftar pengajuan yang menunggu dianalisis).
    public function index()
    {
        $kriterias = Kriteria::all(); // Mengambil semua data kriteria dari database.
        $pengajuans = Pengajuan::where('status', 'pending')->get(); // Mengambil semua pengajuan yang statusnya 'pending'.

        // Mengirim data kriteria dan pengajuan ke view 'bendahara.analisis.index'.
        return view('bendahara.analisis.index', compact('kriterias', 'pengajuans'));
    }

    // Metode ini menampilkan hasil perhitungan TOPSIS.
    public function hasil()
    {
        // Ambil semua pengajuan dengan status pending yang sudah memiliki nilai kriteria
        $pengajuans = Pengajuan::where('status', 'pending') // Mencari pengajuan dengan status 'pending'.
            ->whereNotNull('urgensi')           // Memastikan nilai urgensi tidak null (sudah diisi).
            ->whereNotNull('ketersediaan_stok') // Memastikan nilai ketersediaan_stok tidak null (sudah diisi).
            ->whereNotNull('ketersediaan_dana') // Memastikan nilai ketersediaan_dana tidak null (sudah diisi).
            ->get();                           // Mengambil semua pengajuan yang memenuhi kriteria tersebut.

        // Ambil semua kriteria
        $kriterias = Kriteria::all(); // Mengambil semua data kriteria.

        // Jika tidak ada data pengajuan atau kriteria yang ditemukan, kembalikan ke halaman index dengan pesan error.
        if ($pengajuans->isEmpty() || $kriterias->isEmpty()) {
            return redirect()->route('bendahara.analisis.index')
                ->with('error', 'Tidak ada data untuk dianalisis');
        }

        // --- Mulai Perhitungan TOPSIS ---
        $topsisData = $this->hitungTopsis($pengajuans, $kriterias); // Memanggil metode pribadi 'hitungTopsis' untuk melakukan perhitungan.

        // Mengirimkan semua hasil perhitungan TOPSIS ke view 'bendahara.analisis.hasil'.
        return view('bendahara.analisis.hasil', [
            'hasil' => $topsisData['hasil'],             // Hasil akhir perankingan.
            'kriterias' => $kriterias,                   // Data kriteria.
            'matriksKeputusan' => $topsisData['matriksKeputusan'],       // Matriks keputusan asli.
            'matriksNormalisasi' => $topsisData['matriksNormalisasi'],   // Matriks setelah normalisasi.
            'matriksTerbobot' => $topsisData['matriksTerbobot'],         // Matriks setelah dibobot.
            'solusiIdealPositif' => $topsisData['solusiIdealPositif'],   // Solusi ideal positif.
            'solusiIdealNegatif' => $topsisData['solusiIdealNegatif'],   // Solusi ideal negatif.
            'jarakPositif' => $topsisData['jarakPositif'],             // Jarak ke solusi ideal positif.
            'jarakNegatif' => $topsisData['jarakNegatif'],             // Jarak ke solusi ideal negatif.
            'pengajuans' => $pengajuans                 // Data pengajuan yang dianalisis.
        ]);
    }

    // Metode pribadi yang berisi seluruh logika perhitungan TOPSIS.
    private function hitungTopsis($pengajuans, $kriterias)
    {
        // --- Langkah 1: Matriks Keputusan (X) ---
        // Membuat matriks di mana baris adalah pengajuan dan kolom adalah nilai kriteria.
        $matriksKeputusan = [];
        foreach ($pengajuans as $pengajuan) {
            $row = [
                $pengajuan->urgensi,           // K1 - Tingkat Urgensi Barang (Benefit: nilai lebih tinggi lebih baik)
                $pengajuan->ketersediaan_stok, // K2 - Ketersediaan Stok Barang (Cost: nilai lebih rendah lebih baik)
                $pengajuan->ketersediaan_dana, // K3 - Ketersediaan Dana Pengadaan (Benefit: nilai lebih tinggi lebih baik)
            ];
            $matriksKeputusan[] = $row; // Menambahkan baris ini ke matriks keputusan.
        }

        // --- Langkah 2: Normalisasi Matriks (R) ---
        // Tujuannya adalah menghilangkan dimensi unit dari kriteria dan mengubahnya ke skala yang sama.
        $matriksNormalisasi = [];
        $jumlahKuadrat = []; // Akan menyimpan jumlah kuadrat setiap kolom (kriteria).

        // Hitung jumlah kuadrat setiap kriteria (kolom)
        for ($j = 0; $j < count($kriterias); $j++) { // Iterasi setiap kolom (kriteria).
            $jumlahKuadrat[$j] = 0; // Inisialisasi jumlah kuadrat untuk kolom saat ini.
            for ($i = 0; $i < count($pengajuans); $i++) { // Iterasi setiap baris (pengajuan).
                $jumlahKuadrat[$j] += pow($matriksKeputusan[$i][$j], 2); // Tambahkan kuadrat nilai ke jumlah.
            }
            $jumlahKuadrat[$j] = sqrt($jumlahKuadrat[$j]); // Akarkan total jumlah kuadrat.
        }

        // Lakukan Normalisasi
        for ($i = 0; $i < count($pengajuans); $i++) { // Iterasi setiap baris (pengajuan).
            $row = []; // Baris baru untuk matriks normalisasi.
            for ($j = 0; $j < count($kriterias); $j++) { // Iterasi setiap kolom (kriteria).
                // Normalisasi = nilai_asli / akar_jumlah_kuadrat_kolom_terkait.
                $row[] = $matriksKeputusan[$i][$j] / $jumlahKuadrat[$j];
            }
            $matriksNormalisasi[] = $row; // Tambahkan baris normalisasi.
        }

        // --- Langkah 3: Matriks Normalisasi Terbobot (Y) ---
        // Mengalikan matriks normalisasi dengan bobot setiap kriteria.
        $matriksTerbobot = [];
        for ($i = 0; $i < count($pengajuans); $i++) { // Iterasi setiap baris.
            $row = []; // Baris baru untuk matriks terbobot.
            for ($j = 0; $j < count($kriterias); $j++) { // Iterasi setiap kolom.
                // Nilai terbobot = nilai_normalisasi * bobot_kriteria.
                $row[] = $matriksNormalisasi[$i][$j] * $kriterias[$j]->bobot;
            }
            $matriksTerbobot[] = $row; // Tambahkan baris terbobot.
        }

        // --- Langkah 4: Solusi Ideal Positif (A+) dan Solusi Ideal Negatif (A-) ---
        // A+ adalah nilai terbaik untuk setiap kriteria, A- adalah nilai terburuk.
        // Perhatikan tipe kriteria (benefit atau cost).
        $solusiIdealPositif = [];
        $solusiIdealNegatif = [];

        for ($j = 0; $j < count($kriterias); $j++) { // Iterasi setiap kolom (kriteria).
            $kolom = []; // Menyimpan semua nilai untuk kolom kriteria saat ini.
            for ($i = 0; $i < count($pengajuans); $i++) { // Iterasi setiap baris.
                $kolom[] = $matriksTerbobot[$i][$j]; // Kumpulkan nilai dari kolom saat ini.
            }

            if ($kriterias[$j]->tipe == 'benefit') { // Jika kriteria adalah 'benefit' (semakin besar semakin baik).
                $solusiIdealPositif[] = max($kolom); // A+ adalah nilai maksimum di kolom tersebut.
                $solusiIdealNegatif[] = min($kolom); // A- adalah nilai minimum di kolom tersebut.
            } else { // Jika kriteria adalah 'cost' (semakin kecil semakin baik).
                $solusiIdealPositif[] = min($kolom); // A+ adalah nilai minimum di kolom tersebut.
                $solusiIdealNegatif[] = max($kolom); // A- adalah nilai maksimum di kolom tersebut.
            }
        }

        // --- Langkah 5: Menghitung Jarak (D+ dan D-) ---
        // D+ adalah jarak setiap alternatif ke solusi ideal positif.
        // D- adalah jarak setiap alternatif ke solusi ideal negatif.
        $jarakPositif = [];
        $jarakNegatif = [];

        for ($i = 0; $i < count($pengajuans); $i++) { // Iterasi setiap alternatif (pengajuan).
            $dPlus = 0;  // Inisialisasi jarak ke A+.
            $dMinus = 0; // Inisialisasi jarak ke A-.

            for ($j = 0; $j < count($kriterias); $j++) { // Iterasi setiap kriteria.
                // Hitung kuadrat selisih antara nilai terbobot dan solusi ideal.
                $dPlus += pow($matriksTerbobot[$i][$j] - $solusiIdealPositif[$j], 2);
                $dMinus += pow($matriksTerbobot[$i][$j] - $solusiIdealNegatif[$j], 2);
            }

            $jarakPositif[] = sqrt($dPlus);  // Akarkan total kuadrat selisih untuk mendapatkan jarak Euclidean D+.
            $jarakNegatif[] = sqrt($dMinus); // Akarkan total kuadrat selisih untuk mendapatkan jarak Euclidean D-.
        }

        // --- Langkah 6: Nilai Preferensi (V) ---
        // Menghitung nilai preferensi (kedekatan relatif terhadap solusi ideal).
        // V = D- / (D+ + D-)
        $preferensi = [];

        for ($i = 0; $i < count($pengajuans); $i++) { // Iterasi setiap alternatif.
            // Tambahkan epsilon kecil untuk menghindari pembagian dengan nol
            $epsilon = 0.000001; // Epsilon ditambahkan untuk menangani kasus D+ + D- = 0.
            $totalJarak = $jarakPositif[$i] + $jarakNegatif[$i] + $epsilon; // Jumlah D+ dan D-.

            $nilaiV = $jarakNegatif[$i] / $totalJarak; // Rumus nilai preferensi.
            $preferensi[] = $nilaiV; // Tambahkan nilai preferensi untuk alternatif ini.
        }

        // --- Langkah 7: Perankingan ---
        // Menggabungkan hasil dan mengurutkannya.
        $hasil = [];

        for ($i = 0; $i < count($pengajuans); $i++) { // Iterasi setiap alternatif.
            $hasil[] = [
                'pengajuan' => $pengajuans[$i],       // Objek pengajuan.
                'nilai_preferensi' => $preferensi[$i], // Nilai preferensi yang dihitung.
                'd_plus' => $jarakPositif[$i],         // Jarak ke solusi ideal positif.
                'd_minus' => $jarakNegatif[$i]         // Jarak ke solusi ideal negatif.
            ];
        }

        // Urutkan hasil berdasarkan nilai preferensi secara descending (dari terbesar ke terkecil).
        usort($hasil, function ($a, $b) {
            return $b['nilai_preferensi'] <=> $a['nilai_preferensi']; // Menggunakan operator perbandingan tiga arah.
        });

        // Simpan hasil perankingan ke database
        foreach ($hasil as $index => $item) {
            AnalisisTopsis::updateOrCreate( // Mencoba mencari berdasarkan pengajuan_id, jika tidak ada, buat baru.
                ['pengajuan_id' => $item['pengajuan']->id], // Kriteria pencarian.
                [
                    'nilai_preferensi' => $item['nilai_preferensi'], // Simpan nilai preferensi.
                    'ranking' => $index + 1                           // Simpan ranking (dimulai dari 1).
                ]
            );
        }

        // Kembalikan semua data perhitungan untuk ditampilkan di view.
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