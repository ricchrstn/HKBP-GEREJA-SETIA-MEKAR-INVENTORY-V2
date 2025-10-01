@extends('bendahara.dashboard.layouts.app')

@section('title', 'Verifikasi Pengadaan - Bendahara')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header Section -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-5 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Verifikasi Pengadaan</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Verifikasi pengajuan barang dari pengurus</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="flex flex-wrap items-center justify-between px-6 pt-4 pb-2 bg-gray-50 rounded-t-xl">
            <!-- Search di kiri -->
            <form id="searchForm" method="GET" action="{{ route('bendahara.verifikasi.index') }}" class="flex items-center">
                <div class="relative flex flex-wrap items-stretch transition-all rounded-lg ease-soft">
                    <span class="absolute z-50 flex items-center h-full pl-2 text-slate-500">
                        <i class="fas fa-search text-sm"></i>
                    </span>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" placeholder="Cari pengajuan atau barang..." />
                </div>
            </form>
            <!-- Filter di kanan -->
            <form id="filterForm" method="GET" action="{{ route('bendahara.verifikasi.index') }}" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <select name="status" id="statusFilter"
                        class="w-full px-3 py-2.5 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out appearance-none bg-white bg-no-repeat bg-right bg-[length:16px] pr-10"
                        style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e');">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
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

        <!-- Table Pengajuan -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col mb-6 bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Daftar Pengajuan Pengadaan</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    <span class="font-medium text-blue-600">{{ $pengajuans->count() }}</span> dari
                                    <span class="font-medium text-slate-600">{{ $pengajuans->total() }}</span> pengajuan ditemukan
                                </p>
                            </div>
                            @if (request()->hasAny(['search', 'status', 'tanggal']))
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-slate-500">Filter aktif:</span>
                                    @if (request('search'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-search mr-1"></i>
                                            "{{ request('search') }}"
                                        </span>
                                    @endif
                                    @if (request('status'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-filter mr-1"></i>
                                            {{ ucfirst(request('status')) }}
                                        </span>
                                    @endif
                                    @if (request('tanggal'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
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
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Kode & Barang</th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Pengaju & Tanggal</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Status</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pengajuans as $pengajuan)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-2">
                                                <div class="flex px-2 py-1">
                                                    <div>
                                                        <div class="inline-flex items-center justify-center mr-4 h-12 w-12 rounded-xl bg-gradient-to-tl from-purple-700 to-pink-500">
                                                            <i class="fas fa-file-alt text-white text-lg"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm font-semibold">{{ $pengajuan->kode_pengajuan }}</h6>
                                                        <p class="mb-0 text-xs text-slate-400">{{ $pengajuan->nama_barang }}</p>
                                                        @if ($pengajuan->spesifikasi)
                                                            <p class="mb-0 text-xs text-slate-400">{{ Str::limit($pengajuan->spesifikasi, 30) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                <p class="mb-0 text-xs font-semibold">{{ $pengajuan->user->name }}</p>
                                                <p class="mb-0 text-xs text-slate-400">{{ $pengajuan->created_at->format('d M Y') }}</p>
                                            </td>
                                            <td class="p-2 text-center">
                                                @php
                                                    $statusClass = [
                                                        'pending' => 'bg-gradient-to-tl from-blue-600 to-blue-400',
                                                        'disetujui' => 'bg-gradient-to-tl from-green-600 to-lime-400',
                                                        'ditolak' => 'bg-gradient-to-tl from-red-600 to-rose-400',
                                                        'proses' => 'bg-gradient-to-tl from-blue-600 to-cyan-400',
                                                    ][$pengajuan->status];
                                                @endphp
                                                <span class="{{ $statusClass }} px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                    {{ ucfirst($pengajuan->status) }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center">
                                                <div class="flex justify-center items-center space-x-2">
                                                    <a href="{{ route('bendahara.verifikasi.show', $pengajuan->id) }}" class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-all" title="Detail Pengajuan">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                    <a href="{{ route('bendahara.verifikasi.show', $pengajuan->id) }}#verifikasi" class="text-green-500 hover:text-green-700 p-2 rounded-lg hover:bg-green-50 transition-all" title="Verifikasi">
                                                        <i class="fas fa-check-circle text-sm"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="p-8 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="mb-4 text-gray-400">
                                                        <i class="fas fa-file-invoice-dollar text-6xl"></i>
                                                    </div>
                                                    <h6 class="text-lg font-semibold text-gray-500 mb-2">
                                                        @if (request()->hasAny(['search', 'status', 'tanggal']))
                                                            Tidak ditemukan data yang sesuai
                                                        @else
                                                            Belum ada data pengajuan pengadaan
                                                        @endif
                                                    </h6>
                                                    <p class="text-sm text-gray-400 mb-4">
                                                        @if (request()->hasAny(['search', 'status', 'tanggal']))
                                                            Coba ubah filter atau kata kunci pencarian
                                                        @else
                                                            Belum ada pengajuan pengadaan yang terdaftar dalam sistem
                                                        @endif
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Pagination -->
                    @if ($pengajuans->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Menampilkan <span class="font-medium">{{ $pengajuans->firstItem() }}</span> hingga
                                    <span class="font-medium">{{ $pengajuans->lastItem() }}</span> dari
                                    <span class="font-medium">{{ $pengajuans->total() }}</span> hasil
                                </div>
                                <div class="flex space-x-2">
                                    {{ $pengajuans->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit filter form when date or status changes
        const tanggal = document.querySelector('input[name="tanggal"]');
        const statusFilter = document.querySelector('select[name="status"]');
        if (tanggal) {
            tanggal.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        }
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        }
    });
</script>
@endpush
