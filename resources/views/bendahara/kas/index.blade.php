@extends('bendahara.dashboard.layouts.app')
@section('title', 'Manajemen Kas - Bendahara')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header Section -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-5 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Manajemen Kas</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Kelola pemasukan dan pengeluaran kas
                                    gereja</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('bendahara.kas.laporan') }}"
                                    class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-purple-600 to-purple-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-file-pdf mr-2"></i>
                                    Laporan
                                </a>
                                <a href="{{ route('bendahara.kas.create') }}"
                                    class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Transaksi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="w-full px-6 py-6 mx-auto">
            <!-- Stats Cards Row -->
            <div class="flex flex-wrap justify-center -mx-3">
                <!-- Card 1: Kas Masuk -->
                <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 lg:w-1/3 xl:w-1/4">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-4">
                            <div class="flex flex-row -mx-3">
                                <div class="flex-none w-2/3 max-w-full px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold leading-normal">
                                            Kas Masuk
                                        </p>
                                        <h5 class="mb-0 font-bold">
                                            Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-green-600 to-green-400">
                                        <i class="fa fa-arrow-down text-lg relative top-3.5 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Kas Keluar -->
                <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 lg:w-1/3 xl:w-1/4">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-4">
                            <div class="flex flex-row -mx-3">
                                <div class="flex-none w-2/3 max-w-full px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold leading-normal">
                                            Kas Keluar
                                        </p>
                                        <h5 class="mb-0 font-bold">
                                            Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-red-600 to-red-400">
                                        <i class="fa fa-arrow-up text-lg relative top-3.5 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Total Saldo -->
                <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 lg:w-1/3 xl:w-1/4">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-4">
                            <div class="flex flex-row -mx-3">
                                <div class="flex-none w-2/3 max-w-full px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold leading-normal">
                                            Total Saldo
                                        </p>
                                        <h5 class="mb-0 font-bold {{ $saldo >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                            Rp {{ number_format($saldo, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-lg {{ $saldo >= 0 ? 'bg-gradient-to-tl from-blue-600 to-cyan-400' : 'bg-gradient-to-tl from-red-600 to-rose-400' }}">
                                        <i
                                            class="fa {{ $saldo >= 0 ? 'fa-wallet' : 'fa-exclamation-triangle' }} text-lg relative top-3.5 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Filter Section -->
            <div class="flex flex-wrap items-center justify-between px-6 pt-4 pb-2 bg-gray-50 rounded-t-xl mb-6">
                <!-- Search di kiri -->
                <form id="searchForm" method="GET" action="{{ route('bendahara.kas.index') }}" class="flex items-center">
                    <div class="relative flex flex-wrap items-stretch transition-all rounded-lg ease-soft">
                        <span class="absolute z-50 flex items-center h-full pl-2 text-slate-500">
                            <i class="fas fa-search text-sm"></i>
                        </span>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                            class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                            placeholder="Cari transaksi..." />
                    </div>
                </form>
                <!-- Filter di kanan -->
                <form id="filterForm" method="GET" action="{{ route('bendahara.kas.index') }}"
                    class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[150px]">
                        <select name="jenis" id="jenisFilter"
                            class="w-full px-3 py-2.5 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out appearance-none bg-white bg-no-repeat bg-right bg-[length:16px] pr-10"
                            style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e');">
                            <option value="">Semua Jenis</option>
                            <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Pengeluaran
                            </option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <div class="relative">
                            <input type="date" name="tanggal" id="tanggalFilter" value="{{ request('tanggal') }}"
                                class="w-full px-3 py-2.5 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out appearance-none">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Kas -->
            <div class="flex flex-wrap -mx-3">
                <div class="flex-none w-full max-w-full px-3">
                    <div class="relative flex flex-col mb-6 bg-white shadow-soft-xl rounded-2xl">
                        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                            <div class="flex flex-wrap items-center justify-between">
                                <div>
                                    <h6 class="mb-0 font-bold">Daftar Transaksi Kas</h6>
                                    <p class="mb-0 text-sm text-slate-400 mt-1">
                                        <span class="font-medium text-blue-600">{{ $kas->count() }}</span> dari
                                        <span class="font-medium text-slate-600">{{ $kas->total() }}</span> transaksi
                                        ditemukan
                                    </p>
                                </div>
                                @if (request()->hasAny(['search', 'jenis', 'bulan', 'tahun', 'tanggal']))
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-slate-500">Filter aktif:</span>
                                        @if (request('search'))
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-search mr-1"></i>
                                                "{{ request('search') }}"
                                            </span>
                                        @endif
                                        @if (request('jenis'))
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-filter mr-1"></i>
                                                {{ ucfirst(request('jenis')) }}
                                            </span>
                                        @endif
                                        @if (request('bulan'))
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ date('F', mktime(0, 0, 0, request('bulan'), 1)) }}
                                            </span>
                                        @endif
                                        @if (request('tahun'))
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ request('tahun') }}
                                            </span>
                                        @endif
                                        @if (request('tanggal'))
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ date('d M Y', strtotime(request('tanggal'))) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex-auto px-0 pt-0 pb-2">
                            <div class="p-0 overflow-x-auto">
                                <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">
                                                Kode & Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">
                                                Keterangan</th>
                                            <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">
                                                Jenis</th>
                                            <th class="px-6 py-3 text-right text-xxs font-bold uppercase text-slate-400">
                                                Jumlah</th>
                                            <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($kas as $k)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="p-2">
                                                    <div class="flex px-2 py-1">
                                                        <div>
                                                            <div
                                                                class="inline-flex items-center justify-center mr-4 h-12 w-12 rounded-xl {{ $k->jenis == 'masuk' ? 'bg-gradient-to-tl from-green-600 to-green-400' : 'bg-gradient-to-tl from-red-600 to-red-400' }}">
                                                                <i
                                                                    class="fas {{ $k->jenis == 'masuk' ? 'fa-arrow-down' : 'fa-arrow-up' }} text-white text-lg"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col justify-center">
                                                            <h6 class="mb-0 text-sm font-semibold">
                                                                {{ $k->kode_transaksi }}</h6>
                                                            <p class="mb-0 text-xs text-slate-400">
                                                                {{ $k->tanggal->format('d M Y') }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="p-2">
                                                    <p class="mb-0 text-sm font-semibold">{{ $k->keterangan }}</p>
                                                    @if ($k->jenis == 'masuk' && $k->sumber)
                                                        <p class="mb-0 text-xs text-green-600">Sumber: {{ $k->sumber }}
                                                        </p>
                                                    @elseif ($k->jenis == 'keluar' && $k->tujuan)
                                                        <p class="mb-0 text-xs text-red-600">Tujuan: {{ $k->tujuan }}
                                                        </p>
                                                    @endif
                                                </td>
                                                <td class="p-2 text-center">
                                                    <span
                                                        class="{{ $k->jenis == 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2.5 py-1.4 text-xs rounded-1.8 font-bold uppercase">
                                                        {{ $k->jenis }}
                                                    </span>
                                                </td>
                                                <td class="p-2 text-right">
                                                    <p
                                                        class="mb-0 text-sm font-semibold {{ $k->jenis == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ $k->jenis == 'masuk' ? '+' : '-' }} Rp
                                                        {{ number_format($k->jumlah, 0, ',', '.') }}
                                                    </p>
                                                </td>
                                                <td class="p-2 text-center">
                                                    <div class="flex justify-center items-center space-x-2">
                                                        <a href="{{ route('bendahara.kas.show', $k->id) }}"
                                                            class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-all"
                                                            title="Detail Transaksi">
                                                            <i class="fas fa-eye text-sm"></i>
                                                        </a>
                                                        <a href="{{ route('bendahara.kas.edit', $k->id) }}"
                                                            class="text-orange-500 hover:text-orange-700 p-2 rounded-lg hover:bg-orange-50 transition-all"
                                                            title="Edit Transaksi">
                                                            <i class="fas fa-edit text-sm"></i>
                                                        </a>
                                                        <form id="deleteForm-{{ $k->id }}" method="POST"
                                                            action="{{ route('bendahara.kas.destroy', $k->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                onclick="confirmDelete({{ $k->id }}, '{{ addslashes($k->kode_transaksi) }}')"
                                                                class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all"
                                                                title="Hapus Transaksi">
                                                                <i class="fas fa-trash text-sm"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="p-8 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="mb-4 text-gray-400">
                                                            <i class="fas fa-money-bill-wave text-6xl"></i>
                                                        </div>
                                                        <h6 class="text-lg font-semibold text-gray-500 mb-2">
                                                            @if (request()->hasAny(['search', 'jenis', 'bulan', 'tahun', 'tanggal']))
                                                                Tidak ditemukan data yang sesuai
                                                            @else
                                                                Belum ada data transaksi kas
                                                            @endif
                                                        </h6>
                                                        <p class="text-sm text-gray-400 mb-4">
                                                            @if (request()->hasAny(['search', 'jenis', 'bulan', 'tahun', 'tanggal']))
                                                                Coba ubah filter atau kata kunci pencarian
                                                            @else
                                                                Belum ada transaksi kas yang tercatat dalam sistem
                                                            @endif
                                                        </p>
                                                        @if (!request()->hasAny(['search', 'jenis', 'bulan', 'tahun', 'tanggal']))
                                                            <a href="{{ route('bendahara.kas.create') }}"
                                                                class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                                                <i class="ni ni-fat-add mr-2"></i>
                                                                Tambah Transaksi Pertama
                                                            </a>
                                                        @else
                                                            <a href="{{ route('bendahara.kas.index') }}"
                                                                class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-gray-400 to-gray-600 rounded-lg shadow-md hover:scale-102 transition-all">
                                                                <i class="fas fa-list mr-2"></i>
                                                                Lihat Semua Transaksi
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Pagination -->
                        @if ($kas->hasPages())
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-700">
                                        Menampilkan <span class="font-medium">{{ $kas->firstItem() }}</span> hingga
                                        <span class="font-medium">{{ $kas->lastItem() }}</span> dari
                                        <span class="font-medium">{{ $kas->total() }}</span> hasil
                                    </div>
                                    <div class="flex space-x-2">
                                        {{ $kas->withQueryString()->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus -->
        <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="z-index: 99999;">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeDeleteModal()"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block p-6 my-8 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Hapus Transaksi Kas</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus transaksi "<span id="item-name"></span>"?
                                Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button type="button" onclick="closeDeleteModal()" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-1 sm:text-sm">
                            Batal
                        </button>
                        <button type="button" onclick="confirmDeleteAction()" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-1 sm:text-sm">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit filter form when date or status changes
            const jenis = document.querySelector('select[name="jenis"]');
            const bulan = document.querySelector('select[name="bulan"]');
            const tahun = document.querySelector('select[name="tahun"]');
            const tanggal = document.querySelector('input[name="tanggal"]');

            if (jenis) {
                jenis.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }
            if (bulan) {
                bulan.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }
            if (tahun) {
                tahun.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }
            if (tanggal) {
                tanggal.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }

            // Perbaikan search form
            const searchForm = document.getElementById('searchForm');
            const searchInput = document.getElementById('searchInput');

            if (searchForm && searchInput) {
                // Auto-submit saat menekan Enter
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        searchForm.submit();
                    }
                });

                // Auto-submit setelah 1 detik tidak mengetik
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        searchForm.submit();
                    }, 1000);
                });
            }
        });

        // Fungsi untuk konfirmasi hapus
        function confirmDelete(id, kode) {
            document.getElementById('item-name').textContent = kode;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').style.display = 'flex';
            window.currentDeleteId = id;
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').style.display = 'none';
            window.currentDeleteId = null;
        }

        function confirmDeleteAction() {
            if (window.currentDeleteId) {
                document.getElementById(`deleteForm-${window.currentDeleteId}`).submit();
            }
        }
    </script>
@endpush
