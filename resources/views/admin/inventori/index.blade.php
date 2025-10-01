@extends('admin.dashboard.layouts.app')
@section('title', 'Master Barang - Admin')
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
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Master Barang</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Kelola data inventori barang gereja</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.kategori.index') }}"
                                    class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-red-600 to-red-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-tags mr-2"></i>
                                    Kategori
                                </a>
                                <a href="{{ route('admin.inventori.archived') }}"
                                    class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-green-600 to-green-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-archive mr-2"></i>
                                    Barang Diarsipkan
                                </a>
                                <a href="{{ route('admin.inventori.create') }}"
                                    class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="ni ni-fat-add mr-2"></i>
                                    Tambah Barang
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
            <form id="searchForm" method="GET" action="{{ route('admin.inventori.index') }}" class="flex items-center">
                <div class="relative flex flex-wrap items-stretch transition-all rounded-lg ease-soft">
                    <span class="absolute z-50 flex items-center h-full pl-2 text-slate-500">
                        <i class="fas fa-search text-sm"></i>
                    </span>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                        class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        placeholder="Cari barang atau kode..." />
                </div>
            </form>

            <!-- Filter di kanan -->
            <form id="filterForm" method="GET" action="{{ route('admin.inventori.index') }}"
                class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <select name="kategori" id="kategoriFilter"
                        class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <select name="stok_status" id="stokStatusFilter"
                        class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="habis" {{ request('stok_status') == 'habis' ? 'selected' : '' }}>Stok Habis (0)
                        </option>
                        <option value="rendah" {{ request('stok_status') == 'rendah' ? 'selected' : '' }}>Stok Rendah (1-5)
                        </option>
                        <option value="aman" {{ request('stok_status') == 'aman' ? 'selected' : '' }}>Stok Aman (>5)
                        </option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Table Barang -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col mb-6 bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Daftar Data Barang</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    <span class="font-medium text-blue-600">{{ $barangs->count() }}</span> dari
                                    <span class="font-medium text-slate-600">{{ $barangs->total() }}</span> barang
                                    ditemukan
                                </p>
                            </div>
                            @if (request()->hasAny(['search', 'kategori', 'stok_status']))
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-slate-500">Filter aktif:</span>
                                    @if (request('search'))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-search mr-1"></i>
                                            "{{ request('search') }}"
                                        </span>
                                    @endif
                                    @if (request('kategori'))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ $kategoris->find(request('kategori'))->nama ?? 'Kategori' }}
                                        </span>
                                    @endif
                                    @if (request('stok_status'))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-boxes mr-1"></i>
                                            {{ ucfirst(request('stok_status')) }}
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
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Kode &
                                            Nama Barang</th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Kategori
                                        </th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Stok
                                        </th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Status
                                        </th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Nilai
                                            Aset</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($barangs as $barang)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-2">
                                                <div class="flex px-2 py-1">
                                                    <div>
                                                        @if ($barang->gambar)
                                                            @if (file_exists(public_path('storage/barang/' . $barang->gambar)))
                                                                <img src="{{ asset('storage/barang/' . $barang->gambar) }}"
                                                                    class="inline-flex items-center justify-center mr-4 h-12 w-12 rounded-xl object-cover border border-gray-200">
                                                            @else
                                                                <div
                                                                    class="inline-flex items-center justify-center mr-4 h-12 w-12 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600">
                                                                    <i class="ni ni-box-2 text-lg text-white"></i>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div
                                                                class="inline-flex items-center justify-center mr-4 h-12 w-12 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600">
                                                                <i class="ni ni-box-2 text-lg text-white"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm font-semibold">{{ $barang->nama }}</h6>
                                                        <p class="mb-0 text-xs text-slate-400">{{ $barang->kode_barang }}
                                                        </p>
                                                        @if ($barang->deskripsi)
                                                            <p class="mb-0 text-xs text-slate-400">
                                                                {{ Str::limit($barang->deskripsi, 50) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                <p class="mb-0 text-xs font-semibold">{{ $barang->kategori->nama }}</p>
                                                <p class="mb-0 text-xs text-slate-400">{{ $barang->satuan }}</p>
                                            </td>
                                            <td class="p-2 text-center">
                                                <span
                                                    class="text-sm font-bold {{ $barang->stok <= 2 ? 'text-red-500' : ($barang->stok <= 5 ? 'text-orange-500' : 'text-green-600') }}">
                                                    {{ $barang->stok }}
                                                </span>
                                                @if ($barang->stok <= 2)
                                                    <div class="text-xs text-red-400">Stok Kritis!</div>
                                                @elseif ($barang->stok <= 5)
                                                    <div class="text-xs text-orange-400">Stok Rendah</div>
                                                @else
                                                    <div class="text-xs text-green-500">Stok Mencukupi</div>
                                                @endif
                                            </td>
                                            <td class="p-2 text-center">
                                                @php
                                                    $statusClass = [
                                                        'aktif' => 'bg-gradient-to-tl from-green-600 to-lime-400',
                                                        'rusak' => 'bg-gradient-to-tl from-red-600 to-rose-400',
                                                        'hilang' => 'bg-gradient-to-tl from-slate-600 to-slate-300',
                                                        'perawatan' =>
                                                            'bg-gradient-to-tl from-orange-600 to-yellow-400',
                                                    ][$barang->status];
                                                @endphp
                                                <span
                                                    class="{{ $statusClass }} px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                    {{ ucfirst($barang->status) }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center">
                                                <span class="text-sm font-semibold text-slate-700">Rp
                                                    {{ number_format($barang->harga, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="p-2 text-center">
                                                <div class="flex justify-center items-center space-x-2">
                                                    <a href="{{ route('admin.inventori.edit', $barang->id) }}"
                                                        class="text-orange-500 hover:text-orange-700 p-2 rounded-lg hover:bg-orange-50 transition-all"
                                                        title="Edit Barang">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form id="deleteForm-{{ $barang->id }}" method="POST"
                                                        action="{{ route('admin.inventori.destroy', $barang->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            onclick="confirmDelete({{ $barang->id }}, '{{ addslashes($barang->nama) }}')"
                                                            class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all"
                                                            title="Hapus Barang">
                                                            <i class="fas fa-trash"></i>
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
                                                        <i class="ni ni-box-2 text-6xl"></i>
                                                    </div>
                                                    <h6 class="text-lg font-semibold text-gray-500 mb-2">
                                                        @if (request()->hasAny(['search', 'kategori', 'stok_status']))
                                                            Tidak ditemukan data yang sesuai
                                                        @else
                                                            Belum ada data barang
                                                        @endif
                                                    </h6>
                                                    <p class="text-sm text-gray-400 mb-4">
                                                        @if (request()->hasAny(['search', 'kategori', 'stok_status']))
                                                            Coba ubah filter atau kata kunci pencarian
                                                        @else
                                                            Belum ada barang yang terdaftar dalam sistem
                                                        @endif
                                                    </p>
                                                    @if (!request()->hasAny(['search', 'kategori', 'stok_status']))
                                                        <a href="{{ route('admin.inventori.create') }}"
                                                            class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                                            <i class="ni ni-fat-add mr-2"></i>
                                                            Tambah Barang Pertama
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admin.inventori.index') }}"
                                                            class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-gray-400 to-gray-600 rounded-lg shadow-md hover:scale-102 transition-all">
                                                            <i class="fas fa-list mr-2"></i>
                                                            Lihat Semua Barang
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
                    @if ($barangs->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Menampilkan <span class="font-medium">{{ $barangs->firstItem() }}</span> hingga
                                    <span class="font-medium">{{ $barangs->lastItem() }}</span> dari
                                    <span class="font-medium">{{ $barangs->total() }}</span> hasil
                                </div>
                                <div class="flex space-x-2">
                                    {{ $barangs->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
