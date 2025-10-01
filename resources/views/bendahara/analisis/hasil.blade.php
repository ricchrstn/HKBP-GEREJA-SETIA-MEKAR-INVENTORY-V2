@extends('bendahara.dashboard.layouts.app')
@section('title', 'Hasil Analisis TOPSIS - Bendahara')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header Section -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-5 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Hasil Analisis TOPSIS</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Peringkat prioritas pengadaan barang berdasarkan analisis TOPSIS</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('bendahara.analisis.index') }}" class="inline-block px-6 py-3 text-xs font-bold text-center text-black uppercase bg-gradient-to-tl from-blue-600 to-blue-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hasil Ranking -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Peringkat Pengajuan</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    Berdasarkan nilai preferensi tertinggi
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode & Barang</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengaju</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Kriteria</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jarak (D+)</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jarak (D-)</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Preferensi</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($hasil as $index => $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $rankClass = '';
                                                        if ($index == 0) $rankClass = 'bg-yellow-100 text-yellow-800';
                                                        elseif ($index == 1) $rankClass = 'bg-gray-100 text-gray-800';
                                                        elseif ($index == 2) $rankClass = 'bg-amber-100 text-amber-800';
                                                    @endphp
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $rankClass }}">
                                                        {{ $index + 1 }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item['pengajuan']->kode_pengajuan }}</div>
                                                    <div class="text-sm text-gray-500">{{ $item['pengajuan']->nama_barang }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $item['pengajuan']->user->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <div class="grid grid-cols-3 gap-1">
                                                        <div>
                                                            <span class="text-xs text-gray-400">K1:</span>
                                                            <span class="font-medium">{{ $item['pengajuan']->urgensi }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="text-xs text-gray-400">K2:</span>
                                                            <span class="font-medium">{{ $item['pengajuan']->ketersediaan_stok }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="text-xs text-gray-400">K3:</span>
                                                            <span class="font-medium">{{ $item['pengajuan']->ketersediaan_dana }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ number_format($item['d_plus'], 4) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ number_format($item['d_minus'], 4) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        @if($item['nilai_preferensi'] >= 0.7) bg-green-100 text-green-800
                                                        @elseif($item['nilai_preferensi'] >= 0.4) bg-yellow-100 text-yellow-800
                                                        @else bg-red-100 text-red-800
                                                        @endif">
                                                        {{ number_format($item['nilai_preferensi'], 4) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                    <a href="{{ route('bendahara.verifikasi.show', $item['pengajuan']->id) }}?status=disetujui" class="text-green-600 hover:text-green-900 mr-3">Setujui</a>
                                                    <a href="{{ route('bendahara.verifikasi.show', $item['pengajuan']->id) }}?status=ditolak" class="text-red-600 hover:text-red-900">Tolak</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proses Perhitungan TOPSIS -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Proses Perhitungan TOPSIS</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    Langkah-langkah perhitungan metode TOPSIS
                                </p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button onclick="toggleCalculationDetails()" class="inline-block px-4 py-2 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-calculator mr-2"></i>
                                    <span id="toggleButtonText">Sembunyikan Detail</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div id="calculationDetails" class="p-6">
                            <!-- Langkah 1: Matriks Keputusan -->
                            <div class="mb-8">
                                <h6 class="text-lg font-semibold text-slate-700 mb-3">Langkah 1: Matriks Keputusan (X)</h6>
                                <p class="text-sm text-slate-500 mb-3">Matriks yang berisi nilai kriteria untuk setiap alternatif</p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alternatif</th>
                                                @foreach ($kriterias as $kriteria)
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                        {{ $kriteria->nama }}<br>
                                                        <span class="text-xs {{ $kriteria->tipe == 'benefit' ? 'text-green-600' : 'text-red-600' }}">
                                                            ({{ $kriteria->tipe }})
                                                        </span>
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($pengajuans as $index => $pengajuan)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">
                                                        {{ $pengajuan->nama_barang }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $matriksKeputusan[$index][0] }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $matriksKeputusan[$index][1] }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $matriksKeputusan[$index][2] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Langkah 2: Normalisasi Matriks -->
                            <div class="mb-8">
                                <h6 class="text-lg font-semibold text-slate-700 mb-3">Langkah 2: Normalisasi Matriks (R)</h6>
                                <p class="text-sm text-slate-500 mb-3">Matriks hasil normalisasi dengan rumus: r<sub>ij</sub> = x<sub>ij</sub> / √Σx<sub>ij</sub><sup>2</sup></p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alternatif</th>
                                                @foreach ($kriterias as $kriteria)
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                        {{ $kriteria->nama }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($pengajuans as $index => $pengajuan)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">
                                                        {{ $pengajuan->nama_barang }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ number_format($matriksNormalisasi[$index][0], 4) }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ number_format($matriksNormalisasi[$index][1], 4) }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ number_format($matriksNormalisasi[$index][2], 4) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Langkah 3: Matriks Normalisasi Terbobot -->
                            <div class="mb-8">
                                <h6 class="text-lg font-semibold text-slate-700 mb-3">Langkah 3: Matriks Normalisasi Terbobot (Y)</h6>
                                <p class="text-sm text-slate-500 mb-3">Matriks hasil perkalian matriks normalisasi dengan bobot kriteria: y<sub>ij</sub> = r<sub>ij</sub> × w<sub>j</sub></p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alternatif</th>
                                                @foreach ($kriterias as $kriteria)
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                        {{ $kriteria->nama }}<br>
                                                        <span class="text-xs text-blue-600">(w = {{ $kriteria->bobot }})</span>
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($pengajuans as $index => $pengajuan)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">
                                                        {{ $pengajuan->nama_barang }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ number_format($matriksTerbobot[$index][0], 4) }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ number_format($matriksTerbobot[$index][1], 4) }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ number_format($matriksTerbobot[$index][2], 4) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Langkah 4: Solusi Ideal -->
                            <div class="mb-8">
                                <h6 class="text-lg font-semibold text-slate-700 mb-3">Langkah 4: Solusi Ideal</h6>
                                <p class="text-sm text-slate-500 mb-3">Menentukan solusi ideal positif (A<sup>+</sup>) dan solusi ideal negatif (A<sup>-</sup>)</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-green-50 p-4 rounded-lg">
                                        <h6 class="font-semibold text-green-800 mb-3">Solusi Ideal Positif (A<sup>+</sup>)</h6>
                                        <div class="space-y-2">
                                            @foreach ($kriterias as $index => $kriteria)
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-green-700">{{ $kriteria->nama }}:</span>
                                                    <span class="text-sm font-medium text-green-800">
                                                        {{ number_format($solusiIdealPositif[$index], 4) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="bg-red-50 p-4 rounded-lg">
                                        <h6 class="font-semibold text-red-800 mb-3">Solusi Ideal Negatif (A<sup>-</sup>)</h6>
                                        <div class="space-y-2">
                                            @foreach ($kriterias as $index => $kriteria)
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-red-700">{{ $kriteria->nama }}:</span>
                                                    <span class="text-sm font-medium text-red-800">
                                                        {{ number_format($solusiIdealNegatif[$index], 4) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Langkah 5: Menghitung Jarak -->
                            <div class="mb-8">
                                <h6 class="text-lg font-semibold text-slate-700 mb-3">Langkah 5: Menghitung Jarak</h6>
                                <p class="text-sm text-slate-500 mb-3">Menghitung jarak setiap alternatif ke solusi ideal positif (D<sub>i</sub><sup>+</sup>) dan solusi ideal negatif (D<sub>i</sub><sup>-</sup>)</p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alternatif</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">D<sub>i</sub><sup>+</sup></th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">D<sub>i</sub><sup>-</sup></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($pengajuans as $index => $pengajuan)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">
                                                        {{ $pengajuan->nama_barang }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ number_format($jarakPositif[$index], 4) }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ number_format($jarakNegatif[$index], 4) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Langkah 6: Nilai Preferensi -->
                            <div class="mb-8">
                                <h6 class="text-lg font-semibold text-slate-700 mb-3">Langkah 6: Nilai Preferensi</h6>
                                <p class="text-sm text-slate-500 mb-3">Menghitung nilai preferensi setiap alternatif dengan rumus: V<sub>i</sub> = D<sub>i</sub><sup>-</sup> / (D<sub>i</sub><sup>+</sup> + D<sub>i</sub><sup>-</sup>)</p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alternatif</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nilai Preferensi (V<sub>i</sub>)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($hasil as $item)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">
                                                        {{ $item['pengajuan']->nama_barang }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            @if($item['nilai_preferensi'] >= 0.7) bg-green-100 text-green-800
                                                            @elseif($item['nilai_preferensi'] >= 0.4) bg-yellow-100 text-yellow-800
                                                            @else bg-red-100 text-red-800
                                                            @endif">
                                                            {{ number_format($item['nilai_preferensi'], 4) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Langkah 7: Perankingan -->
                            <div>
                                <h6 class="text-lg font-semibold text-slate-700 mb-3">Langkah 7: Perankingan</h6>
                                <p class="text-sm text-slate-500 mb-3">Mengurutkan alternatif berdasarkan nilai preferensi dari tertinggi ke terendah</p>
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="space-y-3">
                                        @foreach ($hasil as $index => $item)
                                            <div class="flex items-center p-3 bg-white rounded-lg shadow-sm">
                                                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                                    @if($index == 0) bg-yellow-400 text-white
                                                    @elseif($index == 1) bg-gray-300 text-gray-800
                                                    @elseif($index == 2) bg-amber-300 text-white
                                                    @else bg-gray-100 text-gray-600
                                                    @endif">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item['pengajuan']->nama_barang }}</div>
                                                    <div class="text-xs text-gray-500">Nilai Preferensi: {{ number_format($item['nilai_preferensi'], 4) }}</div>
                                                </div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $item['pengajuan']->kode_pengajuan }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kriteria Info -->
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Kriteria yang Digunakan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach ($kriterias as $kriteria)
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center justify-between mb-2">
                                            <h6 class="text-sm font-semibold text-slate-700">{{ $kriteria->nama }}</h6>
                                            <span class="px-2 py-1 text-xs rounded-full font-medium {{ $kriteria->tipe == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $kriteria->tipe == 'benefit' ? 'Benefit' : 'Cost' }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500">Bobot: {{ $kriteria->bobot }}</p>
                                        <p class="text-xs text-slate-500 mt-1">
                                            @if($kriteria->tipe == 'benefit')
                                                Semakin tinggi nilai, semakin baik
                                            @else
                                                Semakin rendah nilai, semakin baik
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function toggleCalculationDetails() {
        const details = document.getElementById('calculationDetails');
        const buttonText = document.getElementById('toggleButtonText');

        if (details.style.display === 'none') {
            details.style.display = 'block';
            buttonText.textContent = 'Sembunyikan Detail';
        } else {
            details.style.display = 'none';
            buttonText.textContent = 'Tampilkan Detail';
        }
    }
</script>
@endpush
