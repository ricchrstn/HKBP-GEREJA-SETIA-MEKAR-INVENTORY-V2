@extends('pengurus.dashboard.layouts.app')
@section('title', 'Barang Keluar - Pengurus')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header Section -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-5 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Barang Keluar</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Catat barang keluar untuk pelayanan atau kegiatan</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('pengurus.barang.keluar.create') }}" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="ni ni-fat-add mr-2"></i>
                                    Tambah Barang Keluar
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
            <form id="searchForm" method="GET" action="{{ route('pengurus.barang.keluar') }}" class="flex items-center">
                <div class="relative flex flex-wrap items-stretch transition-all rounded-lg ease-soft">
                    <span class="absolute z-50 flex items-center h-full pl-2 text-slate-500">
                        <i class="fas fa-search text-sm"></i>
                    </span>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" placeholder="Cari barang atau keterangan..." />
                </div>
            </form>

            <!-- Filter di kanan -->
            <form id="filterForm" method="GET" action="{{ route('pengurus.barang.keluar') }}" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    @if(request()->hasAny(['search', 'tanggal_mulai', 'tanggal_selesai']))
                        <a href="{{ route('pengurus.barang.keluar') }}" class="ml-2 px-4 py-2 text-sm text-red-700 bg-red-200 rounded-lg hover:bg-gray-300 transition">
                            <i class="fas fa-times mr-1"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Barang Keluar -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col mb-6 bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Daftar Barang Keluar</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    <span class="font-medium text-blue-600">{{ $barangKeluars->count() }}</span> dari
                                    <span class="font-medium text-slate-600">{{ $barangKeluars->total() }}</span> data ditemukan
                                </p>
                            </div>
                            @if (request()->hasAny(['search', 'tanggal_mulai', 'tanggal_selesai']))
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-slate-500">Filter aktif:</span>
                                    @if (request('search'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-search mr-1"></i>
                                            "{{ request('search') }}"
                                        </span>
                                    @endif
                                    @if (request('tanggal_mulai'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ date('d/m/Y', strtotime(request('tanggal_mulai'))) }}
                                        </span>
                                    @endif
                                    @if (request('tanggal_selesai'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
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
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Barang</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Keterangan</th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Petugas</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($barangKeluars as $barangKeluar)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-4 align-middle">
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-semibold">{{ $barangKeluar->tanggal->format('d/m/Y') }}</span>
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                @if($barangKeluar->barang)
                                                    <div class="flex items-center">
                                                        @if ($barangKeluar->barang->gambar)
                                                            @if (file_exists(public_path('storage/barang/' . $barangKeluar->barang->gambar)))
                                                                <img src="{{ asset('storage/barang/' . $barangKeluar->barang->gambar) }}" class="inline-flex items-center justify-center mr-3 h-8 w-8 rounded-xl object-cover border border-gray-200">
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
                                                            <h6 class="mb-0 text-sm font-semibold">{{ $barangKeluar->barang->nama }}</h6>
                                                            <p class="mb-0 text-xs text-slate-400">{{ $barangKeluar->barang->kode_barang }}</p>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="flex items-center">
                                                        <div class="inline-flex items-center justify-center mr-3 h-8 w-8 rounded-xl bg-gradient-to-tl from-red-400 to-red-600">
                                                            <i class="ni ni-fat-remove text-xs text-white"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-sm font-semibold text-red-600">Barang tidak ditemukan</h6>
                                                            <p class="mb-0 text-xs text-slate-400">ID: {{ $barangKeluar->barang_id }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="p-2 text-center">
                                                @if($barangKeluar->barang)
                                                    <span class="text-sm font-bold text-red-600">{{ $barangKeluar->jumlah }}</span>
                                                    <div class="text-xs text-slate-400">{{ $barangKeluar->barang->satuan }}</div>
                                                @else
                                                    <span class="text-sm font-bold text-red-600">{{ $barangKeluar->jumlah }}</span>
                                                    <div class="text-xs text-slate-400">satuan</div>
                                                @endif
                                            </td>
                                            <td class="p-2">
                                                <p class="mb-0 text-sm">{{ $barangKeluar->keterangan ?: '-' }}</p>
                                            </td>
                                            <td class="p-2">
                                                @if($barangKeluar->user)
                                                    <p class="mb-0 text-xs font-semibold">{{ $barangKeluar->user->name }}</p>
                                                    <p class="mb-0 text-xs text-slate-400">{{ $barangKeluar->created_at->format('d/m/Y H:i') }}</p>
                                                @else
                                                    <p class="mb-0 text-xs font-semibold text-red-600">User tidak ditemukan</p>
                                                    <p class="mb-0 text-xs text-slate-400">ID: {{ $barangKeluar->user_id }}</p>
                                                @endif
                                            </td>
                                            <td class="p-2 text-center">
                                                <div class="flex justify-center items-center space-x-2">
                                                    <a href="{{ route('pengurus.barang.keluar.show', $barangKeluar->id) }}" class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-all" title="Detail">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                    @if($barangKeluar->barang)
                                                        <a href="{{ route('pengurus.barang.keluar.edit', $barangKeluar->id) }}" class="text-orange-500 hover:text-orange-700 p-2 rounded-lg hover:bg-orange-50 transition-all" title="Edit">
                                                            <i class="fas fa-edit text-sm"></i>
                                                        </a>
                                                    @endif
                                                    <form id="deleteForm-{{ $barangKeluar->id }}" method="POST" action="{{ route('pengurus.barang.keluar.destroy', $barangKeluar->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" onclick="confirmDelete({{ $barangKeluar->id }}, '{{ $barangKeluar->barang ? $barangKeluar->barang->nama : 'barang tidak ditemukan' }}')" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all" title="Hapus">
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
                                                        <i class="ni ni-basket text-6xl"></i>
                                                    </div>
                                                    <h6 class="text-lg font-semibold text-gray-500 mb-2">
                                                        @if (request()->hasAny(['search', 'tanggal_mulai', 'tanggal_selesai']))
                                                            Tidak ditemukan data yang sesuai
                                                        @else
                                                            Belum ada data barang keluar
                                                        @endif
                                                    </h6>
                                                    <p class="text-sm text-gray-400 mb-4">
                                                        @if (request()->hasAny(['search', 'tanggal_mulai', 'tanggal_selesai']))
                                                            Coba ubah filter atau kata kunci pencarian
                                                        @else
                                                            Belum ada catatan barang keluar dalam sistem
                                                        @endif
                                                    </p>
                                                    @if (!request()->hasAny(['search', 'tanggal_mulai', 'tanggal_selesai']))
                                                        <a href="{{ route('pengurus.barang.keluar.create') }}" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                                            <i class="ni ni-fat-add mr-2"></i>
                                                            Catat Barang Keluar Pertama
                                                        </a>
                                                    @else
                                                        <a href="{{ route('pengurus.barang.keluar') }}" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-gray-400 to-gray-600 rounded-lg shadow-md hover:scale-102 transition-all">
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
                    @if ($barangKeluars->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Menampilkan <span class="font-medium">{{ $barangKeluars->firstItem() }}</span> hingga
                                    <span class="font-medium">{{ $barangKeluars->lastItem() }}</span> dari
                                    <span class="font-medium">{{ $barangKeluars->total() }}</span> hasil
                                </div>
                                <div class="flex space-x-2">
                                    {{ $barangKeluars->withQueryString()->links() }}
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
            if (confirm(`Apakah Anda yakin ingin menghapus data barang keluar untuk "${nama}"?`)) {
                document.getElementById(`deleteForm-${id}`).submit();
            }
        }

        // Auto-submit filter form when date changes
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]');
            const tanggalSelesai = document.querySelector('input[name="tanggal_selesai"]');

            if (tanggalMulai && tanggalSelesai) {
                tanggalMulai.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });

                tanggalSelesai.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }
        });
    </script>
@endsection
