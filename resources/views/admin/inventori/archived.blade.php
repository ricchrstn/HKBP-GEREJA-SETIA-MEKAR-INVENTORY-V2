@extends('admin.dashboard.layouts.app')
@section('title', 'Barang Diarsipkan - Admin')
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
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Barang Diarsipkan</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Kelola barang yang telah diarsipkan</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.inventori.index') }}"
                                    class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="ni ni-bold-left mr-2"></i>
                                    Kembali ke Daftar Barang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Barang Arsip -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col mb-6 bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Daftar Barang Diarsipkan</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    <span class="font-medium text-blue-600">{{ $barangs->count() }}</span> dari
                                    <span class="font-medium text-slate-600">{{ $barangs->total() }}</span> barang
                                    diarsipkan
                                </p>
                            </div>
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
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">
                                            Tanggal Dihapus</th>
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
                                                            <img src="{{ asset('storage/barang/' . $barang->gambar) }}"
                                                                class="inline-flex items-center justify-center mr-4 h-12 w-12 rounded-xl object-cover border border-gray-200">
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
                                                <span class="text-sm font-bold text-gray-600">{{ $barang->stok }}</span>
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
                                                <span
                                                    class="text-sm text-slate-600">{{ $barang->deleted_at->format('d/m/Y H:i') }}</span>
                                            </td>
                                            <td class="p-2 text-center">
                                                <div class="flex justify-center items-center space-x-2">
                                                    <form action="{{ route('admin.inventori.restore', $barang->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-green-500 hover:text-green-700 p-2 rounded-lg hover:bg-black-50 transition-all"
                                                            title="Pulihkan Barang">
                                                            <i class="fas fa-undo"></i> </button>
                                                    </form>
                                                    <form id="forceDeleteForm-{{ $barang->id }}" method="POST"
                                                        action="{{ route('admin.inventori.force-delete', $barang->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            onclick="confirmForceDelete({{ $barang->id }}, '{{ addslashes($barang->nama) }}')"
                                                            class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all"
                                                            title="Hapus Permanen">
                                                            <i class="fas fa-trash"></i> </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-8 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="mb-4 text-gray-400">
                                                        <i class="ni ni-archive-2 text-6xl"></i>
                                                    </div>
                                                    <h6 class="text-lg font-semibold text-gray-500 mb-2">Tidak ada barang
                                                        yang diarsipkan</h6>
                                                    <p class="text-sm text-gray-400 mb-4">Semua barang aktif ditampilkan di
                                                        daftar utama</p>
                                                    <a href="{{ route('admin.inventori.index') }}"
                                                        class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                                        <i class="ni ni-bold-left mr-2"></i>
                                                        Kembali ke Daftar Barang
                                                    </a>
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

@section('scripts')
    <script>
        function confirmForceDelete(id, name) {
            if (confirm('⚠️ PERINGATAN: Anda akan menghapus barang "' + name +
                    '" secara PERMANEN!\n\nSemua riwayat transaksi terkait juga akan dihapus.\n\nTindakan ini tidak dapat dibatalkan!'
                    )) {
                const form = document.getElementById('forceDeleteForm-' + id);
                const button = form.querySelector('button[type="button"]');

                // Disable button to prevent multiple clicks
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');

                // Send AJAX request
                fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the row from table with animation
                            const row = form.closest('tr');
                            row.style.transition = 'opacity 0.3s ease';
                            row.style.opacity = '0';
                            setTimeout(() => {
                                row.remove();
                                showNotification(data.message, 'success');

                                // Check if table is empty
                                const tbody = document.querySelector('tbody');
                                if (tbody && tbody.children.length === 0) {
                                    // Reload page to show empty state
                                    window.location.reload();
                                }
                            }, 300);
                        } else {
                            throw new Error(data.message || 'Gagal menghapus barang permanen');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification(error.message, 'error');

                        // Re-enable button
                        button.disabled = false;
                        button.innerHTML = '<i class="ni ni-trash-simple"></i>';
                    });
            }
            return false;
        }
    </script>
@endsection
