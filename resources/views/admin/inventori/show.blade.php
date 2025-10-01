@extends('admin.dashboard.layouts.app')
@section('title', 'Detail Barang - Admin')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Detail Barang</h6>
                                <p class="text-sm leading-normal text-slate-500">Informasi lengkap barang: {{ $barang->nama }}</p>
                            </div>
                            <a href="{{ route('admin.inventori.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Content -->
        <div class="flex flex-wrap -mx-3">
            <!-- Main Content -->
            <div class="flex-none w-full max-w-full px-3 lg:w-8/12 lg:flex-none">
                <!-- Info Barang -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Informasi Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row">
                                <div class="md:w-1/3 flex justify-center mb-4 md:mb-0">
                                    @if ($barang->gambar)
                                        <img src="{{ asset('storage/barang/' . $barang->gambar) }}" class="h-48 w-48 rounded-xl object-cover border border-gray-200 shadow-sm">
                                    @else
                                        <div class="h-48 w-48 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600 flex items-center justify-center">
                                            <i class="ni ni-box-2 text-4xl text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="md:w-2/3 md:pl-6">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-slate-500">Kode Barang</p>
                                            <p class="font-semibold">{{ $barang->kode_barang }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-slate-500">Nama Barang</p>
                                            <p class="font-semibold">{{ $barang->nama }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-slate-500">Kategori</p>
                                            <p class="font-semibold">{{ $barang->kategori->nama }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-slate-500">Satuan</p>
                                            <p class="font-semibold">{{ $barang->satuan }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-slate-500">Stok</p>
                                            <p class="font-semibold {{ $barang->stok <= 2 ? 'text-red-500' : ($barang->stok <= 5 ? 'text-orange-500' : 'text-green-600') }}">
                                                {{ $barang->stok }} {{ $barang->satuan }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-slate-500">Harga</p>
                                            <p class="font-semibold">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    @if ($barang->deskripsi)
                                        <div class="mt-4">
                                            <p class="text-sm text-slate-500">Deskripsi</p>
                                            <p class="text-sm">{{ $barang->deskripsi }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Transaksi -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Riwayat Transaksi</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <!-- Tabs -->
                            <div class="mb-4 border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8">
                                    <button onclick="showTab('barangMasuk')" id="barangMasukTab" class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                                        Barang Masuk
                                    </button>
                                    <button onclick="showTab('barangKeluar')" id="barangKeluarTab" class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                        Barang Keluar
                                    </button>
                                </nav>
                            </div>

                            <!-- Tab Content -->
                            <div id="barangMasukContent" class="tab-content">
                                @if ($barangMasuk->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach ($barangMasuk as $masuk)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $masuk->tanggal->format('d M Y') }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $masuk->jumlah }} {{ $barang->satuan }}</td>
                                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $masuk->keterangan }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-gray-500 text-center py-4">Tidak ada data barang masuk</p>
                                @endif
                            </div>

                            <div id="barangKeluarContent" class="tab-content hidden">
                                @if ($barangKeluar->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach ($barangKeluar as $keluar)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $keluar->tanggal->format('d M Y') }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $keluar->jumlah }} {{ $barang->satuan }}</td>
                                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $keluar->keterangan }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-gray-500 text-center py-4">Tidak ada data barang keluar</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="flex-none w-full max-w-full px-3 lg:w-4/12 lg:flex-none">
                <!-- Status & Aksi -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Status & Aksi</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <p class="text-sm text-slate-500 mb-1">Status</p>
                                @php
                                    $statusClass = [
                                        'aktif' => 'bg-gradient-to-tl from-green-600 to-lime-400',
                                        'rusak' => 'bg-gradient-to-tl from-red-600 to-rose-400',
                                        'hilang' => 'bg-gradient-to-tl from-slate-600 to-slate-300',
                                        'perawatan' => 'bg-gradient-to-tl from-orange-600 to-yellow-400',
                                    ][$barang->status];
                                @endphp
                                <span class="{{ $statusClass }} px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                    {{ ucfirst($barang->status) }}
                                </span>
                            </div>

                            <div class="flex flex-col space-y-2 mt-6">
                                <a href="{{ route('admin.inventori.edit', $barang->id) }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-edit mr-2"></i>Edit Barang
                                </a>
                                <form id="deleteForm-{{ $barang->id }}" method="POST" action="{{ route('admin.inventori.destroy', $barang->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $barang->id }}, '{{ addslashes($barang->nama) }}')" class="w-full inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-600 to-rose-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                        <i class="fas fa-archive mr-2"></i>Arsipkan Barang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Informasi Tambahan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <p class="text-sm text-slate-500">Dibuat</p>
                                <p class="text-sm">{{ $barang->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-sm text-slate-500">Terakhir Diupdate</p>
                                <p class="text-sm">{{ $barang->updated_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Total Nilai Aset</p>
                                <p class="text-lg font-semibold">Rp {{ number_format($barang->harga * $barang->stok, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Remove active state from all tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('border-blue-500', 'text-blue-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected tab content
        document.getElementById(tabName + 'Content').classList.remove('hidden');

        // Add active state to selected tab
        const selectedTab = document.getElementById(tabName + 'Tab');
        selectedTab.classList.remove('border-transparent', 'text-gray-500');
        selectedTab.classList.add('border-blue-500', 'text-blue-600');
    }
</script>
@endsection
