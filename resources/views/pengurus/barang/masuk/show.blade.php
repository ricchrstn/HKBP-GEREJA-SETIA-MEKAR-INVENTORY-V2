@extends('pengurus.dashboard.layouts.app')
@section('title', 'Detail Barang Masuk - Pengurus')
@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex flex-wrap -mx-3">
                        <div class="flex-none w-full max-w-full px-3 md:w-8/12 md:flex-none">
                            <h6 class="mb-0">Detail Barang Masuk</h6>
                            <p class="text-sm leading-normal">Informasi lengkap transaksi barang masuk: {{ $barangMasuk->barang->nama }}</p>
                        </div>
                        <div class="flex-none w-full max-w-full px-3 md:w-4/12 md:flex-none">
                            <div class="md:flex md:justify-end space-x-2">
                                <a href="{{ route('pengurus.barang.masuk.edit', $barangMasuk->id) }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-orange-500 to-yellow-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-edit mr-2"></i>Edit
                                </a>
                                <a href="{{ route('pengurus.barang.masuk') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Content -->
    <div class="flex flex-wrap -mx-3">
        <!-- Main Detail -->
        <div class="flex-none w-full max-w-full px-3 lg:w-8/12 lg:flex-none">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="mb-0">Informasi Transaksi</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <div class="flex flex-wrap -mx-3">
                            <div class="flex-none w-full max-w-full px-3 md:w-6/12">
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">ID Transaksi</p>
                                    <p class="text-sm font-semibold">#{{ $barangMasuk->id }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Tanggal</p>
                                    <p class="text-sm font-semibold">{{ $barangMasuk->tanggal->format('d F Y') }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Jumlah</p>
                                    <p class="text-sm font-semibold">{{ $barangMasuk->jumlah }} {{ $barangMasuk->barang->satuan }}</p>
                                </div>
                            </div>
                            <div class="flex-none w-full max-w-full px-3 md:w-6/12">
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Petugas</p>
                                    <p class="text-sm font-semibold">{{ $barangMasuk->user->name }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Dibuat</p>
                                    <p class="text-sm font-semibold">{{ $barangMasuk->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Terakhir Diupdate</p>
                                    <p class="text-sm font-semibold">{{ $barangMasuk->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex-none w-full max-w-full px-3">
                                <div class="mb-2">
                                    <p class="text-xs text-slate-500 mb-1">Keterangan</p>
                                    <p class="text-sm">{{ $barangMasuk->keterangan ?: '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Barang -->
            <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="mb-0">Informasi Barang</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            @if ($barangMasuk->barang->gambar)
                                @if (file_exists(public_path('storage/barang/' . $barangMasuk->barang->gambar)))
                                    <img src="{{ asset('storage/barang/' . $barangMasuk->barang->gambar) }}" class="mr-4 h-16 w-16 rounded-xl object-cover border border-gray-200">
                                @else
                                    <div class="mr-4 h-16 w-16 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600 flex items-center justify-center">
                                        <i class="ni ni-box-2 text-xl text-white"></i>
                                    </div>
                                @endif
                            @else
                                <div class="mr-4 h-16 w-16 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600 flex items-center justify-center">
                                    <i class="ni ni-box-2 text-xl text-white"></i>
                                </div>
                            @endif
                            <div>
                                <h5 class="text-lg font-semibold">{{ $barangMasuk->barang->nama }}</h5>
                                <p class="text-sm text-slate-400">{{ $barangMasuk->barang->kode_barang }}</p>
                                @if ($barangMasuk->barang->deskripsi)
                                    <p class="text-sm mt-1">{{ $barangMasuk->barang->deskripsi }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-slate-500 mb-1">Kategori</p>
                                <p class="text-sm font-medium">{{ $barangMasuk->barang->kategori->nama }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-slate-500 mb-1">Satuan</p>
                                <p class="text-sm font-medium">{{ $barangMasuk->barang->satuan }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-slate-500 mb-1">Stok Saat Ini</p>
                                <p class="text-sm font-medium">{{ $barangMasuk->barang->stok }} {{ $barangMasuk->barang->satuan }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-slate-500 mb-1">Harga</p>
                                <p class="text-sm font-medium">Rp {{ number_format($barangMasuk->barang->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="flex-none w-full max-w-full px-3 lg:w-4/12 lg:flex-none">
            <!-- Status Card -->
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="mb-0">Status Transaksi</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-tl from-green-600 to-lime-400 mb-3">
                                <i class="fas fa-check text-white text-2xl"></i>
                            </div>
                            <h5 class="text-lg font-semibold text-green-600">Selesai</h5>
                            <p class="text-sm text-slate-400">Barang telah ditambahkan ke stok</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-xs text-slate-500">Stok Sebelum</span>
                                <span class="text-xs font-medium">{{ $barangMasuk->barang->stok - $barangMasuk->jumlah }} {{ $barangMasuk->barang->satuan }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-xs text-slate-500">Jumlah Masuk</span>
                                <span class="text-xs font-medium text-green-600">+{{ $barangMasuk->jumlah }} {{ $barangMasuk->barang->satuan }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-slate-500">Stok Sesudah</span>
                                <span class="text-xs font-medium">{{ $barangMasuk->barang->stok }} {{ $barangMasuk->barang->satuan }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="mb-0">Aksi Cepat</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('pengurus.barang.masuk.edit', $barangMasuk->id) }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-orange-500 to-yellow-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                <i class="fas fa-edit mr-2"></i>Edit Data
                            </a>
                            <form id="deleteForm" method="POST" action="{{ route('pengurus.barang.masuk.destroy', $barangMasuk->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete()" class="w-full inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-600 to-rose-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-trash mr-2"></i>Hapus Data
                                </button>
                            </form>
                            <a href="{{ route('pengurus.barang.masuk') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                <i class="fas fa-list mr-2"></i>Lihat Semua Data
                            </a>
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
        function confirmDelete() {
            if (confirm(`Apakah Anda yakin ingin menghapus data barang masuk untuk "${{{ $barangMasuk->barang->nama }}}"?`)) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
@endsection
