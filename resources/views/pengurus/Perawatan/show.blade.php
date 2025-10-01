@extends('pengurus.dashboard.layouts.app')
@section('title', 'Detail Perawatan - Pengurus')
@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex flex-wrap -mx-3">
                        <div class="flex-none w-full max-w-full px-3 md:w-8/12">
                            <h6 class="mb-0">Detail Perawatan Barang</h6>
                            <p class="text-sm leading-normal">Informasi lengkap perawatan: {{ $perawatan->jenis_perawatan }}</p>
                        </div>
                        <div class="flex-none w-full max-w-full px-3 md:w-4/12">
                            <div class="md:flex md:justify-end">
                                <a href="{{ route('pengurus.perawatan.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                    <h6 class="mb-0">Informasi Perawatan</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <div class="flex flex-wrap -mx-3">
                            <div class="flex-none w-full max-w-full px-3 md:w-6/12">
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">ID Perawatan</p>
                                    <p class="text-sm font-semibold">#{{ $perawatan->id }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Tanggal Perawatan</p>
                                    <p class="text-sm font-semibold">{{ $perawatan->tanggal_perawatan->format('d F Y') }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Jenis Perawatan</p>
                                    <p class="text-sm font-semibold">{{ $perawatan->jenis_perawatan }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Biaya</p>
                                    <p class="text-sm font-semibold">Rp {{ number_format($perawatan->biaya, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="flex-none w-full max-w-full px-3 md:w-6/12">
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Petugas</p>
                                    <p class="text-sm font-semibold">{{ $perawatan->user->name }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Status</p>
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
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Dibuat</p>
                                    <p class="text-sm font-semibold">{{ $perawatan->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Terakhir Diupdate</p>
                                    <p class="text-sm font-semibold">{{ $perawatan->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex-none w-full max-w-full px-3">
                                <div class="mb-2">
                                    <p class="text-xs text-slate-500 mb-1">Keterangan</p>
                                    <p class="text-sm">{{ $perawatan->keterangan ?: '-' }}</p>
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
                            @if ($perawatan->barang->gambar)
                                @if (file_exists(public_path('storage/barang/' . $perawatan->barang->gambar)))
                                    <img src="{{ asset('storage/barang/' . $perawatan->barang->gambar) }}" class="mr-4 h-16 w-16 rounded-xl object-cover border border-gray-200">
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
                                <h5 class="text-lg font-semibold">{{ $perawatan->barang->nama }}</h5>
                                <p class="text-sm text-slate-400">{{ $perawatan->barang->kode_barang }}</p>
                                @if ($perawatan->barang->deskripsi)
                                    <p class="text-sm mt-1">{{ $perawatan->barang->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-slate-500 mb-1">Kategori</p>
                                <p class="text-sm font-medium">{{ $perawatan->barang->kategori->nama }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-slate-500 mb-1">Satuan</p>
                                <p class="text-sm font-medium">{{ $perawatan->barang->satuan }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-slate-500 mb-1">Stok</p>
                                <p class="text-sm font-medium">{{ $perawatan->barang->stok }} {{ $perawatan->barang->satuan }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-slate-500 mb-1">Status Barang</p>
                                <p class="text-sm font-medium">{{ ucfirst($perawatan->barang->status) }}</p>
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
                    <h6 class="mb-0">Status Perawatan</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <div class="text-center mb-4">
                            @php
                                $statusIcon = [
                                    'proses' => 'fa-wrench',
                                    'selesai' => 'fa-check-circle',
                                    'dibatalkan' => 'fa-times-circle',
                                ][$perawatan->status];

                                $statusColor = [
                                    'proses' => 'from-blue-600 to-cyan-400',
                                    'selesai' => 'from-green-600 to-lime-400',
                                    'dibatalkan' => 'from-red-600 to-rose-400',
                                ][$perawatan->status];

                                $statusText = [
                                    'proses' => 'Sedang Diproses',
                                    'selesai' => 'Selesai',
                                    'dibatalkan' => 'Dibatalkan',
                                ][$perawatan->status];
                            @endphp
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-tl {{ $statusColor }} mb-3">
                                <i class="fas {{ $statusIcon }} text-white text-2xl"></i>
                            </div>
                            <h5 class="text-lg font-semibold">{{ ucfirst($perawatan->status) }}</h5>
                            <p class="text-sm text-slate-400">{{ $statusText }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between mb-2">
                                <span class="text-xs text-slate-500">Tanggal Perawatan</span>
                                <span class="text-xs font-medium">{{ $perawatan->tanggal_perawatan->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-xs text-slate-500">Biaya Perawatan</span>
                                <span class="text-xs font-medium">Rp {{ number_format($perawatan->biaya, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-slate-500">Petugas</span>
                                <span class="text-xs font-medium">{{ $perawatan->user->name }}</span>
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
                            <a href="{{ route('pengurus.perawatan.edit', $perawatan->id) }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-orange-500 to-yellow-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                <i class="fas fa-edit mr-2"></i>Edit Data
                            </a>
                            @if($perawatan->status === 'proses')
                                <form action="{{ route('pengurus.perawatan.selesaikan', $perawatan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-green-600 to-lime-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                        <i class="fas fa-check mr-2"></i>Selesaikan Perawatan
                                    </button>
                                </form>
                            @endif
                            <form id="deleteForm" method="POST" action="{{ route('pengurus.perawatan.destroy', $perawatan->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete()" class="w-full inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-600 to-rose-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-trash mr-2"></i>Hapus Data
                                </button>
                            </form>
                            <a href="{{ route('pengurus.perawatan.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
            if (confirm(`Apakah Anda yakin ingin menghapus data perawatan "{{ $perawatan->jenis_perawatan }}"?`)) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
@endsection
