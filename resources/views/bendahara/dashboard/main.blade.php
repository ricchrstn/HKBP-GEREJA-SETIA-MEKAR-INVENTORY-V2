@extends('bendahara.dashboard.layouts.app')

@section('title', 'Dashboard - Bendahara')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Stats Cards Row -->
        <div class="flex flex-wrap -mx-3">
            <!-- Card 1: Kas Masuk -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border h-full">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal">Kas Masuk</p>
                                    <h5 class="mb-0 font-bold text-xl">
                                        Rp {{ number_format($kasMasukBulanIni, 0, ',', '.') }}
                                        <span class="text-sm leading-normal font-weight-bolder text-lime-500">Bulan
                                            Ini</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-green-600 to-lime-400">
                                    <i class="fa fa-arrow-down text-lg relative top-3.5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Kas Keluar -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border h-full">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal">
                                        Kas Keluar
                                    </p>
                                    <h5 class="mb-0 font-bold">
                                        Rp. {{ number_format($kasKeluarBulanIni, 0, ',', '.') }}
                                        <span class="text-sm leading-normal font-weight-bolder text-red-500">Bulan Ini</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-red-600 to-rose-400">
                                    <i class="fa fa-arrow-up text-lg relative top-3.5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Saldo -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border h-full">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal">
                                        Total Saldo
                                    </p>
                                    <h5 class="mb-0 font-bold">
                                        Rp. {{ number_format($totalSaldo, 0, ',', '.') }}
                                        <span
                                            class="text-sm leading-normal font-weight-bolder text-blue-500">Tersedia</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-blue-600 to-cyan-400">
                                    <i class="fa fa-wallet text-lg relative top-3.5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Pengajuan Pengadaan -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border h-full">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal">
                                        Pengajuan Pengadaan
                                    </p>
                                    <h5 class="mb-0 font-bold">
                                        {{ $pengajuanPending }}
                                        <span
                                            class="text-sm leading-normal font-weight-bolder text-purple-500">Pending</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                    <i class="fa fa-file-alt text-lg relative top-3.5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="flex flex-wrap mt-6 -mx-3">
            <!-- Chart: Kas Masuk/Keluar -->
            <div class="w-full max-w-full px-3 mt-0 lg:w-12/12 lg:flex-none">
                <div
                    class="border-black/12.5 shadow-soft-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                    <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid bg-white p-6 pb-0">
                        <h6 class="text-lg font-semibold">Grafik Kas Masuk/Keluar (6 Bulan Terakhir)</h6>
                        <p class="text-sm leading-normal">
                            Perbandingan jumlah kas masuk dan keluar per bulan
                        </p>
                    </div>
                    <div class="flex-auto p-4">
                        <div>
                            <canvas id="chart-bar" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="flex flex-wrap my-6 -mx-3">
            <!-- Table 1: Pengajuan Pengadaan -->
            <div class="w-full max-w-full px-3 mt-0 mb-6 md:mb-0 md:w-1/2 md:flex-none lg:w-1/2 lg:flex-none">
                <div
                    class="border-black/12.5 shadow-soft-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                    <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid bg-white p-6 pb-0">
                        <div class="flex flex-wrap mt-0 -mx-3">
                            <div class="flex-none w-7/12 max-w-full px-3 mt-0 lg:w-2/3 lg:flex-none">
                                <h6 class="text-lg font-semibold">Pengajuan Pengadaan</h6>
                                <p class="mb-0 text-sm leading-normal">
                                    <i class="fa fa-file-alt text-purple-500"></i>
                                    <span class="ml-1 font-semibold">{{ $pengajuanPending }} pending</span> dari
                                    {{ $totalPengajuan }} total
                                </p>
                            </div>
                            <div class="flex-none w-5/12 max-w-full px-3 my-auto text-right lg:w-1/3 lg:flex-none">
                                <a href="{{ route('bendahara.verifikasi.index') }}"
                                    class="inline-block px-4 py-2 mb-0 font-bold text-center text-white uppercase align-middle transition-all bg-transparent border-0 rounded-lg shadow-none cursor-pointer leading-pro text-xs ease-soft-in bg-gradient-to-tl from-purple-600 to-pink-400 hover:shadow-soft-xs hover:-translate-y-px active:opacity-85">
                                    Verifikasi Semua
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="flex-auto p-6 px-0 pb-2">
                        <div class="overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th
                                            class="px-6 py-3 font-bold tracking-normal text-left uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Kode
                                        </th>
                                        <th
                                            class="px-6 py-3 pl-2 font-bold tracking-normal text-left uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Nama Barang
                                        </th>
                                        <th
                                            class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Jumlah
                                        </th>
                                        <th
                                            class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pengajuanPengadaan as $pengajuan)
                                        <tr>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex items-center justify-center mr-3 text-sm text-white transition-all duration-200 ease-soft-in-out h-8 w-8 rounded-xl bg-gradient-to-tl from-purple-600 to-pink-400">
                                                        <i class="fa fa-file-alt"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-sm leading-normal">
                                                            {{ $pengajuan->kode_pengajuan }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                                <p class="mb-0 text-sm font-semibold leading-normal">
                                                    {{ $pengajuan->nama_barang }}</p>
                                            </td>
                                            <td
                                                class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap">
                                                <span class="text-xs font-semibold leading-tight">{{ $pengajuan->jumlah }}
                                                    {{ $pengajuan->satuan }}</span>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                                @if ($pengajuan->status == 'Pending')
                                                    <span
                                                        class="px-2 py-1 text-xs font-semibold leading-tight text-yellow-600 bg-yellow-200 rounded-full">Pending</span>
                                                @elseif($pengajuan->status == 'Proses')
                                                    <span
                                                        class="px-2 py-1 text-xs font-semibold leading-tight text-blue-600 bg-blue-200 rounded-full">Proses</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="p-4 text-center text-gray-500">
                                                Tidak ada pengajuan pengadaan
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table 2: Analisis TOPSIS -->
            <div class="w-full max-w-full px-3 md:w-1/2 md:flex-none lg:w-1/2 lg:flex-none">
                <div
                    class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                    <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid bg-white p-6 pb-0">
                        <div class="flex flex-wrap mt-0 -mx-3">
                            <div class="flex-none w-7/12 max-w-full px-3 mt-0 lg:w-2/3 lg:flex-none">
                                <h6 class="text-lg font-semibold">Analisis TOPSIS</h6>
                                <p class="mb-0 text-sm leading-normal">
                                    <i class="fa fa-chart-line text-blue-500"></i>
                                    <span class="ml-1 font-semibold">Prioritas pengadaan</span> barang
                                </p>
                            </div>
                            <div class="flex-none w-5/12 max-w-full px-3 my-auto text-right lg:w-1/3 lg:flex-none">
                                <a href="{{ route('bendahara.analisis.hasil') }}"
                                    class="inline-block px-4 py-2 mb-0 font-bold text-center text-white uppercase align-middle transition-all bg-transparent border-0 rounded-lg shadow-none cursor-pointer leading-pro text-xs ease-soft-in bg-gradient-to-tl from-blue-600 to-cyan-400 hover:shadow-soft-xs hover:-translate-y-px active:opacity-85">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="flex-auto p-6 px-0 pb-2">
                        <div class="overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th
                                            class="px-6 py-3 font-bold tracking-normal text-left uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Ranking
                                        </th>
                                        <th
                                            class="px-6 py-3 pl-2 font-bold tracking-normal text-left uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Nama Barang
                                        </th>
                                        <th
                                            class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Nilai
                                        </th>
                                        <th
                                            class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Prioritas
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($analisisTopsis as $analisis)
                                        <tr>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-soft-in-out h-8 w-8 rounded-xl bg-gradient-to-tl from-blue-600 to-cyan-400">
                                                        {{ $analisis->ranking }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-sm leading-normal">
                                                            {{ $analisis->pengajuan->kode_pengajuan }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                                <p class="mb-0 text-sm font-semibold leading-normal">
                                                    {{ $analisis->pengajuan->nama_barang }}</p>
                                            </td>
                                            <td
                                                class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap">
                                                <span
                                                    class="text-xs font-semibold leading-tight">{{ number_format($analisis->nilai_preferensi, 4) }}</span>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                                @if ($analisis->ranking == 1)
                                                    <span
                                                        class="px-2 py-1 text-xs font-semibold leading-tight text-green-600 bg-green-200 rounded-full">Tinggi</span>
                                                @elseif($analisis->ranking <= 3)
                                                    <span
                                                        class="px-2 py-1 text-xs font-semibold leading-tight text-yellow-600 bg-yellow-200 rounded-full">Sedang</span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 text-xs font-semibold leading-tight text-blue-600 bg-blue-200 rounded-full">Rendah</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="p-4 text-center text-gray-500">
                                                Belum ada analisis TOPSIS
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Chart Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Kas Masuk/Keluar
        const ctxBar = document.getElementById('chart-bar').getContext('2d');
        const chartBar = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($chartLabels as $label)
                        '{{ $label }}',
                    @endforeach
                ],
                datasets: [{
                        label: 'Kas Masuk',
                        data: [
                            @foreach ($kasMasukData as $value)
                                {{ $value }},
                            @endforeach
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Kas Keluar',
                        data: [
                            @foreach ($kasKeluarData as $value)
                                {{ $value }},
                            @endforeach
                        ],
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
