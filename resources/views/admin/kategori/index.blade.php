@extends('admin.dashboard.layouts.app')
@section('title', 'Manajemen Kategori - Admin')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header Section -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-5 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Manajemen Kategori</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Kelola data kategori barang inventori</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.inventori.index') }}" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Inventori
                                </a>
                                <button type="button" onclick="toggleCategoryModal()" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-blue-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-plus mr-2"></i> Tambah Kategori
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="flex flex-wrap items-center justify-between px-6 pt-4 pb-2 bg-gray-50 rounded-t-xl mb-6">
            <form id="searchForm" method="GET" action="{{ route('admin.kategori.index') }}" class="flex items-center w-full">
                <div class="relative flex flex-wrap items-stretch transition-all rounded-lg ease-soft w-full max-w-md">
                    <span class="absolute z-50 flex items-center h-full pl-2 text-slate-500">
                        <i class="fas fa-search text-sm"></i>
                    </span>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-full leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" placeholder="Cari kategori..." />
                </div>
            </form>
        </div>

        <!-- Table Kategori -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col mb-6 bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Daftar Kategori</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    <span class="font-medium text-blue-600">{{ $kategoris->count() }}</span> dari
                                    <span class="font-medium text-slate-600">{{ $kategoris->total() }}</span> kategori ditemukan
                                </p>
                            </div>
                            @if (request()->has('search'))
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-slate-500">Filter aktif:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-search mr-1"></i> "{{ request('search') }}"
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Nama Kategori</th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Deskripsi</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Jumlah Barang</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Tanggal Dibuat</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kategoris as $kategori)
                                        <tr class="hover:bg-gray-50 transition-colors" id="kategori-row-{{ $kategori->id }}">
                                            <td class="p-2">
                                                <div class="flex px-2 py-1">
                                                    <div>
                                                        <div class="inline-flex items-center justify-center mr-4 h-12 w-12 rounded-xl bg-gradient-to-tl from-purple-700 to-pink-500">
                                                            <i class="fas fa-tag text-white text-lg"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm font-semibold">{{ $kategori->nama }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                <p class="mb-0 text-sm text-slate-700">
                                                    {{ $kategori->deskripsi ?: '-' }}
                                                </p>
                                            </td>
                                            <td class="p-2 text-center">
                                                <span class="text-sm font-semibold text-slate-700">
                                                    {{ $kategori->barangs()->count() }}
                                                </span>
                                            </td>
                                            <td class="p-2">
                                                <span class="text-sm font-semibold text-slate-700">
                                                    {{ $kategori->created_at->format('d M Y') }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center">
                                                <div class="flex justify-center items-center space-x-2">
                                                    <a href="{{ route('admin.kategori.edit', $kategori->id) }}" class="text-orange-500 hover:text-orange-700 p-2 rounded-lg hover:bg-orange-50 transition-all" title="Edit Kategori">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form id="deleteForm-{{ $kategori->id }}" method="POST" action="{{ route('admin.kategori.destroy', $kategori->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" onclick="confirmDelete({{ $kategori->id }}, '{{ addslashes($kategori->nama) }}')" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all" title="Hapus Kategori">
                                                            <i class="fas fa-trash"></i>
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
                                                        <i class="fas fa-tags text-6xl"></i>
                                                    </div>
                                                    <h6 class="text-lg font-semibold text-gray-500 mb-2">
                                                        @if (request()->has('search'))
                                                            Tidak ditemukan data yang sesuai
                                                        @else
                                                            Belum ada data kategori
                                                        @endif
                                                    </h6>
                                                    <p class="text-sm text-gray-400 mb-4">
                                                        @if (request()->has('search'))
                                                            Coba ubah kata kunci pencarian
                                                        @else
                                                            Belum ada kategori yang terdaftar dalam sistem
                                                        @endif
                                                    </p>
                                                    @if (!request()->has('search'))
                                                        <button type="button" onclick="toggleCategoryModal()" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-purple-600 to-purple-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                                            <i class="fas fa-plus mr-2"></i> Tambah Kategori Pertama
                                                        </button>
                                                    @else
                                                        <a href="{{ route('admin.kategori.index') }}" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-gray-400 to-gray-600 rounded-lg shadow-md hover:scale-102 transition-all">
                                                            <i class="fas fa-list mr-2"></i> Lihat Semua Kategori
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
                    @if ($kategoris->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Menampilkan <span class="font-medium">{{ $kategoris->firstItem() }}</span> hingga
                                    <span class="font-medium">{{ $kategoris->lastItem() }}</span> dari
                                    <span class="font-medium">{{ $kategoris->total() }}</span> hasil
                                </div>
                                <div class="flex space-x-2">
                                    {{ $kategoris->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('admin.dashboard.partials.category')

    @push('scripts')
    <script>
        // Fungsi untuk modal kategori
        function toggleCategoryModal() {
            const modal = document.getElementById('categoryModal');
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
            } else {
                closeCategoryModal();
            }
        }

        function closeCategoryModal() {
            const modal = document.getElementById('categoryModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.getElementById('quickAddCategory').reset();
        }

        // Fungsi untuk modal hapus
        function confirmDelete(id, name) {
            document.getElementById('item-name').textContent = name;
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
@endsection
