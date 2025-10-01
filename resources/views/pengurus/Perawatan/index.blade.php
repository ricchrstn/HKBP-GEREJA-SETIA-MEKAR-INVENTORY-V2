@extends('pengurus.dashboard.layouts.app')
@section('title', 'Perawatan Barang - Pengurus')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header Section -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-5 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Perawatan Barang</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Kelola data perawatan barang gereja</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('pengurus.perawatan.create') }}" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="ni ni-fat-add mr-2"></i>
                                    Tambah Perawatan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Search & Filter Section -->
        <div class="flex flex-wrap items-center justify-between px-6 pt-4 pb-2 bg-gray-50 rounded-t-xl">
            <!-- Search di kiri -->
            <form id="searchForm" method="GET" action="{{ route('pengurus.perawatan.index') }}" class="flex items-center">
                <div class="relative flex flex-wrap items-stretch transition-all rounded-lg ease-soft">
                    <span class="absolute z-50 flex items-center h-full pl-2 text-slate-500">
                        <i class="fas fa-search text-sm"></i>
                    </span>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" placeholder="Cari barang atau jenis perawatan..." />
                </div>
            </form>
            <!-- Filter di kanan -->
            <form id="filterForm" method="GET" action="{{ route('pengurus.perawatan.index') }}" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <select name="status" id="statusFilter"
                        class="w-full px-3 py-2.5 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out appearance-none bg-white bg-no-repeat bg-right bg-[length:16px] pr-10"
                        style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e');">
                        <option value="">Semua Status</option>
                        <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
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
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
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
                <div class="flex items-end">
                    @if(request()->hasAny(['search', 'status', 'tanggal_mulai', 'tanggal_selesai']))
                        <a href="{{ route('pengurus.perawatan.index') }}" class="ml-2 px-4 py-2.5 text-sm text-red-700 bg-red-200 rounded-lg hover:bg-red-300 transition">
                            <i class="fas fa-times mr-1"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
        <!-- Table Perawatan -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col mb-6 bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Daftar Perawatan</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    <span class="font-medium text-blue-600">{{ $perawatans->count() }}</span> dari
                                    <span class="font-medium text-slate-600">{{ $perawatans->total() }}</span> data ditemukan
                                </p>
                            </div>
                            @if (request()->hasAny(['search', 'status', 'tanggal_mulai', 'tanggal_selesai']))
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
                                    @if (request('tanggal_mulai'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ date('d/m/Y', strtotime(request('tanggal_mulai'))) }}
                                        </span>
                                    @endif
                                    @if (request('tanggal_selesai'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ date('d/m/Y', strtotime(request('tanggal_selesai'))) }}
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
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Tanggal Perawatan</th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Barang</th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Jenis Perawatan</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Biaya</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Status</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($perawatans as $perawatan)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-4 align-middle">
                                                <span class="text-xs font-semibold">{{ $perawatan->tanggal_perawatan->format('d/m/Y') }}</span>
                                            </td>
                                            <td class="p-2">
                                                @if($perawatan->barang)
                                                    <div class="flex items-center">
                                                        @if ($perawatan->barang->gambar)
                                                            @if (file_exists(public_path('storage/barang/' . $perawatan->barang->gambar)))
                                                                <img src="{{ asset('storage/barang/' . $perawatan->barang->gambar) }}" class="inline-flex items-center justify-center mr-3 h-8 w-8 rounded-xl object-cover border border-gray-200">
                                                            @else
                                                                <div class="inline-flex items-center justify-center mr-4 h-8 w-8 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600">
                                                                    <i class="ni ni-box-2 text-xs text-white"></i>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div class="inline-flex items-center justify-center mr-4 h-8 w-8 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600">
                                                                <i class="ni ni-box-2 text-xs text-white"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0 text-sm font-semibold">{{ $perawatan->barang->nama }}</h6>
                                                            <p class="mb-0 text-xs text-slate-400">{{ $perawatan->barang->kode_barang }}</p>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="flex items-center">
                                                        <div class="inline-flex items-center justify-center mr-3 h-8 w-8 rounded-xl bg-gradient-to-tl from-red-400 to-red-600">
                                                            <i class="ni ni-fat-remove text-xs text-white"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-sm font-semibold text-red-600">Barang tidak ditemukan</h6>
                                                            <p class="mb-0 text-xs text-slate-400">ID: {{ $perawatan->barang_id }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="p-4">
                                                <p class="mb-0 text-sm font-semibold">{{ $perawatan->jenis_perawatan }}</p>
                                                @if($perawatan->keterangan)
                                                    <p class="mb-0 text-xs text-slate-400">{{ Str::limit($perawatan->keterangan, 30) }}</p>
                                                @endif
                                            </td>
                                            <td class="p-2 text-center">
                                                <span class="text-sm font-semibold">Rp {{ number_format($perawatan->biaya, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="p-2 text-center">
                                                @php
                                                    $statusClass = [
                                                        'proses' => 'bg-gradient-to-tl from-blue-600 to-cyan-400',
                                                        'selesai' => 'bg-gradient-to-tl from-green-600 to-lime-400',
                                                        'dibatalkan' => 'bg-gradient-to-tl from-red-600 to-rose-400',
                                                    ][$perawatan->status];
                                                @endphp
                                                <span class="{{ $statusClass }} px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                    {{ ucfirst($perawatan->status) }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center">
                                                <div class="flex justify-center items-center space-x-2">
                                                    <a href="{{ route('pengurus.perawatan.show', $perawatan->id) }}" class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-all" title="Detail">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                    <a href="{{ route('pengurus.perawatan.edit', $perawatan->id) }}" class="text-orange-500 hover:text-orange-700 p-2 rounded-lg hover:bg-orange-50 transition-all" title="Edit">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </a>
                                                    @if($perawatan->status === 'proses')
                                                        <form action="{{ route('pengurus.perawatan.selesaikan', $perawatan->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="text-green-500 hover:text-green-700 p-2 rounded-lg hover:bg-green-50 transition-all" title="Selesaikan">
                                                                <i class="fas fa-check text-sm"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form id="deleteForm-{{ $perawatan->id }}" method="POST" action="{{ route('pengurus.perawatan.destroy', $perawatan->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" onclick="confirmDelete({{ $perawatan->id }}, '{{ $perawatan->jenis_perawatan }}')" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all" title="Hapus">
                                                            <i class="fas fa-trash text-sm"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-8 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="mb-4 text-gray-400">
                                                        <i class="ni ni-settings text-6xl"></i>
                                                    </div>
                                                    <h6 class="text-lg font-semibold text-gray-500 mb-2">
                                                        @if (request()->hasAny(['search', 'status', 'tanggal_mulai', 'tanggal_selesai']))
                                                            Tidak ditemukan data yang sesuai
                                                        @else
                                                            Belum ada data perawatan
                                                        @endif
                                                    </h6>
                                                    <p class="text-sm text-gray-400 mb-4">
                                                        @if (request()->hasAny(['search', 'status', 'tanggal_mulai', 'tanggal_selesai']))
                                                            Coba ubah filter atau kata kunci pencarian
                                                        @else
                                                            Belum ada catatan perawatan dalam sistem
                                                        @endif
                                                    </p>
                                                    @if (!request()->hasAny(['search', 'status', 'tanggal_mulai', 'tanggal_selesai']))
                                                        <a href="{{ route('pengurus.perawatan.create') }}" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                                            <i class="ni ni-fat-add mr-2"></i>
                                                            Catat Perawatan Pertama
                                                        </a>
                                                    @else
                                                        <a href="{{ route('pengurus.perawatan.index') }}" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-gray-400 to-gray-600 rounded-lg shadow-md hover:scale-102 transition-all">
                                                            <i class="fas fa-list mr-2"></i>
                                                            Lihat Semua Data
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
                    @if ($perawatans->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Menampilkan <span class="font-medium">{{ $perawatans->firstItem() }}</span> hingga
                                    <span class="font-medium">{{ $perawatans->lastItem() }}</span> dari
                                    <span class="font-medium">{{ $perawatans->total() }}</span> hasil
                                </div>
                                <div class="flex space-x-2">
                                    {{ $perawatans->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function confirmDelete(id, nama) {
            if (confirm(`Apakah Anda yakin ingin menghapus data perawatan "${nama}"?`)) {
                document.getElementById(`deleteForm-${id}`).submit();
            }
        }
        // Auto-submit filter form when date or status changes
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]');
            const tanggalSelesai = document.querySelector('input[name="tanggal_selesai"]');
            const statusFilter = document.querySelector('select[name="status"]');

            if (tanggalMulai) {
                tanggalMulai.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }
            if (tanggalSelesai) {
                tanggalSelesai.addEventListener('change', function() {
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
@endsection
