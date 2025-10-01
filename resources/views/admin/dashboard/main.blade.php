@extends('admin.dashboard.layouts.app')

@section('title', 'Dashboard - Admin')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Row 1: Cards -->
        <div class="flex flex-wrap -mx-3">
            <!-- Card 1: Total Barang -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal">
                                        Total Barang
                                    </p>
                                    <h5 class="mb-0 font-bold">
                                        {{ $totalBarang }}
                                        <span class="text-sm leading-normal font-weight-bolder text-lime-500">Items</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-blue-600 to-cyan-400">
                                    <i class="fas fa-boxes text-lg relative top-3.5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Stok Kritis -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal">
                                        Stok Kritis
                                    </p>
                                    <h5 class="mb-0 font-bold">
                                        {{ $stokKritis }}
                                        <span class="text-sm leading-normal font-weight-bolder text-red-600">Items</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-red-600 to-orange-500">
                                    <i class="fas fa-exclamation-triangle text-lg relative top-3.5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Barang Rusak/Hilang -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal">
                                        Barang Rusak/Hilang
                                    </p>
                                    <h5 class="mb-0 font-bold">
                                        {{ $totalRusakHilang }}
                                        <span class="text-sm leading-normal text-red-600 font-weight-bolder">Items</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-red-500 to-red-500">
                                    <i class="fas fa-ban text-lg relative top-3.5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: User Aktif -->
            <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal">
                                        User Aktif
                                    </p>
                                    <h5 class="mb-0 font-bold">
                                        {{ $totalUser }}
                                        <span class="text-sm leading-normal font-weight-bolder text-lime-500">Users</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                    <i class="fas fa-users text-lg relative top-3.5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Charts -->
        <div class="flex flex-wrap mt-6 -mx-3">
            <!-- Chart 1: Barang Masuk/Keluar -->
            <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-7/12 lg:flex-none">
                <div
                    class="border-black/12.5 shadow-soft-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                    <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid bg-white p-6 pb-0">
                        <h6 class="text-lg font-semibold">Grafik Barang Masuk/Keluar</h6>
                        <p class="text-sm leading-normal">
                            <i class="fa fa-arrow-up text-lime-500"></i>
                            <span class="font-semibold">5% lebih</span> dari bulan lalu
                        </p>
                    </div>
                    <div class="flex-auto p-4">
                        <div>
                            <canvas id="chart-bar" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart 2: Status Barang -->
            <div class="w-full max-w-full px-3 mt-0 lg:w-5/12 lg:flex-none">
                <div
                    class="border-black/12.5 shadow-soft-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                    <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid bg-white p-6 pb-0">
                        <h6 class="text-lg font-semibold">Status Barang</h6>
                        <p class="text-sm leading-normal">
                            Distribusi status barang inventaris
                        </p>
                    </div>
                    <div class="flex-auto p-4">
                        <div>
                            <canvas id="chart-doughnut" height="300"></canvas>
                        </div>
                        <div class="mt-6 text-center">
                            <div class="flex flex-wrap justify-center">
                                <div class="px-4 text-center">
                                    <div class="inline-block w-3 h-3 mr-2 bg-blue-500 rounded-full"></div>
                                    <span class="text-sm">Aktif: {{ $totalBarang }}</span>
                                </div>
                                <div class="px-4 text-center">
                                    <div class="inline-block w-3 h-3 mr-2 bg-yellow-500 rounded-full"></div>
                                    <span class="text-sm">Perawatan: {{ $barangPerawatan }}</span>
                                </div>
                                <div class="px-4 text-center">
                                    <div class="inline-block w-3 h-3 mr-2 bg-red-500 rounded-full"></div>
                                    <span class="text-sm">Rusak/Hilang: {{ $totalRusakHilang }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Tables -->
        <div class="flex flex-wrap my-6 -mx-3">
            <!-- Table 1: Barang Stok Kritis -->
            <div class="w-full max-w-full px-3 mt-0 mb-6 md:mb-0 md:w-1/2 md:flex-none lg:w-7/12 lg:flex-none">
                <div
                    class="border-black/12.5 shadow-soft-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                    <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid bg-white p-6 pb-0">
                        <div class="flex flex-wrap mt-0 -mx-3">
                            <div class="flex-none w-7/12 max-w-full px-3 mt-0 lg:w-1/2 lg:flex-none">
                                <h6 class="text-lg font-semibold">Barang Stok Kritis</h6>
                                <p class="mb-0 text-sm leading-normal">
                                    <i class="fa fa-exclamation-circle text-orange-500"></i>
                                    <span class="ml-1 font-semibold">{{ $stokKritis }} item</span> perlu pengadaan segera
                                </p>
                            </div>
                            <div class="flex-none w-5/12 max-w-full px-3 my-auto text-right lg:w-1/2 lg:flex-none">
                                <a href="{{ route('admin.inventori.index') }}"
                                    class="inline-block px-4 py-2 mb-0 font-bold text-center text-white uppercase align-middle transition-all bg-transparent border-0 rounded-lg shadow-none cursor-pointer leading-pro text-xs ease-soft-in bg-gradient-to-tl from-blue-600 to-cyan-400 hover:shadow-soft-xs hover:-translate-y-px active:opacity-85">
                                    Lihat Semua
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
                                            Kode/Nama Barang
                                        </th>
                                        <th
                                            class="px-6 py-3 pl-2 font-bold tracking-normal text-left uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Kategori
                                        </th>
                                        <th
                                            class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Stok
                                        </th>
                                        <th
                                            class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barangStokKritis as $barang)
                                        <tr>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                                <div class="flex px-2 py-1">
                                                    <div>
                                                        <div
                                                            class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-soft-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-600 to-cyan-400">
                                                            <i class="fas fa-box"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm leading-normal">{{ $barang->kode_barang }}
                                                        </h6>
                                                        <p class="mb-0 text-xs text-slate-500">{{ $barang->nama }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                                <p class="mb-0 text-sm font-semibold leading-normal">
                                                    {{ $barang->kategori ? $barang->kategori->nama : '-' }}</p>
                                            </td>
                                            <td
                                                class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap">
                                                <span
                                                    class="text-xs font-semibold leading-tight text-red-600">{{ $barang->stok }}
                                                    {{ $barang->satuan }}</span>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                                <a href="{{ route('pengurus.pengajuan.create', ['barang_id' => $barang->id]) }}"
                                                    class="text-xs font-semibold leading-tight text-blue-600">Ajukan
                                                    Pengadaan</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table 2: Peminjaman Aktif -->
            <div class="w-full max-w-full px-3 md:w-1/2 md:flex-none lg:w-5/12 lg:flex-none">
                <div
                    class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                    <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid bg-white p-6 pb-0">
                        <h6 class="text-lg font-semibold">Peminjaman Aktif</h6>
                        <p class="text-sm leading-normal">
                            <i class="fa fa-clock text-blue-500"></i>
                            <span class="font-semibold">{{ $peminjamanAktif }} peminjaman</span> sedang berlangsung
                        </p>
                    </div>
                    <div class="flex-auto p-4">
                        <div
                            class="before:border-r-solid relative before:absolute before:top-0 before:left-4 before:h-full before:border-r-2 before:border-r-slate-100 before:content-[''] before:lg:-ml-px">
                            @foreach ($listPeminjaman as $peminjaman)
                                <div class="relative mb-4 mt-0 after:clear-both after:table after:content-['']">
                                    <span
                                        class="w-6.5 h-6.5 text-base absolute left-4 z-10 inline-flex -translate-x-1/2 items-center justify-center rounded-full bg-white text-center font-semibold">
                                        <i
                                            class="relative z-10 leading-none text-transparent ni ni-bell-55 leading-pro bg-gradient-to-tl from-blue-600 to-cyan-400 bg-clip-text fill-transparent"></i>
                                    </span>
                                    <div class="ml-11.252 pt-1.4 lg:max-w-120 relative -top-1.5 w-auto">
                                        <h6 class="mb-0 text-sm font-semibold leading-normal text-slate-700">
                                            {{ $peminjaman->barang->nama }}
                                        </h6>
                                        <p class="mt-1 mb-0 text-xs font-semibold leading-tight text-slate-400">
                                            Dipinjam oleh: {{ $peminjaman->peminjam }}<br>
                                            Tanggal: {{ $peminjaman->tanggal_pinjam->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Barang Masuk/Keluar
        const ctxBar = document.getElementById('chart-bar').getContext('2d');
        const chartBar = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($enamBulanTerakhir as $bulan)
                        '{{ $bulan }}',
                    @endforeach
                ],
                datasets: [{
                        label: 'Barang Masuk',
                        data: [
                            @foreach ($barangMasukValues as $value)
                                {{ $value }},
                            @endforeach
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Barang Keluar',
                        data: [
                            @foreach ($barangKeluarValues as $value)
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
                        beginAtZero: true
                    }
                }
            }
        });
        // Chart Status Barang
        const ctxDoughnut = document.getElementById('chart-doughnut').getContext('2d');
        const chartDoughnut = new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Perawatan', 'Rusak/Hilang'],
                datasets: [{
                    data: [
                        {{ $totalBarang }},
                        {{ $barangPerawatan }},
                        {{ $totalRusakHilang }}
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(255, 99, 132, 0.8)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
@endsection
