@extends('pengurus.dashboard.layouts.app')
@section('title', 'Detail Peminjaman - Pengurus')
@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <!-- Header -->
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex justify-between items-center">
                    <div>
                        <h6 class="mb-0">Detail Peminjaman</h6>
                        <p class="text-sm leading-normal text-slate-500">
                            Informasi lengkap peminjaman: {{ $peminjaman->peminjam }}
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('pengurus.peminjaman.edit', $peminjaman->id) }}"
                           class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-orange-500 to-yellow-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <a href="{{ route('pengurus.peminjaman.index') }}"
                           class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-grey-600 to-grey-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
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
                    <h6 class="mb-0">Informasi Peminjaman</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <div class="flex flex-wrap -mx-3">
                            <div class="flex-none w-full max-w-full px-3 md:w-6/12">
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">ID Peminjaman</p>
                                    <p class="text-sm font-semibold">#{{ $peminjaman->id }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Tanggal Pinjam</p>
                                    <p class="text-sm font-semibold">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Tanggal Kembali</p>
                                    <p class="text-sm font-semibold">{{ $peminjaman->tanggal_kembali->format('d F Y') }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Jumlah</p>
                                    <p class="text-sm font-semibold">{{ $peminjaman->jumlah }} {{ $peminjaman->barang->satuan }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Status</p>
                                    @php
                                        $statusClass = [
                                            'dipinjam' => 'bg-gradient-to-tl from-blue-600 to-cyan-400',
                                            'dikembalikan' => 'bg-gradient-to-tl from-green-600 to-lime-400',
                                            'terlambat' => 'bg-gradient-to-tl from-red-600 to-rose-400',
                                        ][$peminjaman->status];
                                    @endphp
                                    <span class="{{ $statusClass }} px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-none w-full max-w-full px-3 md:w-6/12">
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Peminjam</p>
                                    <p class="text-sm font-semibold">{{ $peminjaman->peminjam }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Kontak</p>
                                    <p class="text-sm font-semibold">{{ $peminjaman->kontak }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Keperluan</p>
                                    <p class="text-sm font-semibold">{{ $peminjaman->keperluan }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Petugas</p>
                                    <p class="text-sm font-semibold">{{ $peminjaman->user->name }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Dibuat</p>
                                    <p class="text-sm font-semibold">{{ $peminjaman->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-1">Terakhir Diupdate</p>
                                    <p class="text-sm font-semibold">{{ $peminjaman->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex-none w-full max-w-full px-3">
                                <div class="mb-2">
                                    <p class="text-xs text-slate-500 mb-1">Keterangan</p>
                                    <p class="text-sm">{{ $peminjaman->keterangan ?: '-' }}</p>
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
                            @if ($peminjaman->barang->gambar)
                                @if (file_exists(public_path('storage/barang/' . $peminjaman->barang->gambar)))
                                    <img src="{{ asset('storage/barang/' . $peminjaman->barang->gambar) }}" class="mr-4 h-16 w-16 rounded-xl object-cover border border-gray-200">
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
                                <h5 class="text-lg font-semibold">{{ $peminjaman->barang->nama }}</h5>
                                <p class="text-sm text-slate-400">{{ $peminjaman->barang->kode_barang }}</p>
                                @if ($peminjaman->barang->deskripsi)
                                    <p class="text-sm mt-1">{{ $peminjaman->barang->deskripsi }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-slate-500 mb-1">Kategori</p>
                                <p class="text-sm font-medium">{{ $peminjaman->barang->kategori->nama }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-slate-500 mb-1">Satuan</p>
                                <p class="text-sm font-medium">{{ $peminjaman->barang->satuan }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-slate-500 mb-1">Stok Saat Ini</p>
                                <p class="text-sm font-medium">{{ $peminjaman->barang->stok }} {{ $peminjaman->barang->satuan }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs text-slate-500 mb-1">Harga</p>
                                <p class="text-sm font-medium">Rp {{ number_format($peminjaman->barang->harga, 0, ',', '.') }}</p>
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
                    <h6 class="mb-0">Status Peminjaman</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <div class="text-center mb-4">
                            @php
                                $statusIcon = [
                                    'dipinjam' => 'fa-handshake',
                                    'dikembalikan' => 'fa-check-circle',
                                    'terlambat' => 'fa-exclamation-triangle',
                                ][$peminjaman->status];

                                $statusColor = [
                                    'dipinjam' => 'from-blue-600 to-cyan-400',
                                    'dikembalikan' => 'from-green-600 to-lime-400',
                                    'terlambat' => 'from-red-600 to-rose-400',
                                ][$peminjaman->status];
                            @endphp
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-tl {{ $statusColor }} mb-3">
                                <i class="fas {{ $statusIcon }} text-white text-2xl"></i>
                            </div>
                            <h5 class="text-lg font-semibold {{ $peminjaman->status === 'dikembalikan' ? 'text-green-600' : ($peminjaman->status === 'terlambat' ? 'text-red-600' : 'text-blue-600') }}">
                                {{ ucfirst($peminjaman->status) }}
                            </h5>
                            <p class="text-sm text-slate-400">
                                @if($peminjaman->status === 'dipinjam')
                                    Barang sedang dipinjam
                                @elseif($peminjaman->status === 'dikembalikan')
                                    Barang sudah dikembalikan
                                @else
                                    Barang terlambat dikembalikan
                                @endif
                            </p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-xs text-slate-500">Durasi Peminjaman</span>
                                <span class="text-xs font-medium">{{ $peminjaman->tanggal_pinjam->diffInDays($peminjaman->tanggal_kembali) }} hari</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-xs text-slate-500">Tanggal Pinjam</span>
                                <span class="text-xs font-medium">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-slate-500">Batas Pengembalian</span>
                                <span class="text-xs font-medium {{ $peminjaman->tanggal_kembali < now() ? 'text-red-500' : 'text-slate-700' }}">
                                    {{ $peminjaman->tanggal_kembali->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>

                        @if($peminjaman->status === 'dipinjam')
                            @if($peminjaman->tanggal_kembali < now())
                                <div class="bg-red-50 border border-red-200 p-3 rounded-lg mb-4">
                                    <div class="flex">
                                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                        <p class="text-xs text-red-700">Peminjaman terlambat! Segera hubungi peminjam.</p>
                                    </div>
                                </div>
                            @endif
                        @endif
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
                            <a href="{{ route('pengurus.peminjaman.edit', $peminjaman->id) }}" class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-orange-500 to-yellow-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                <i class="fas fa-edit mr-2"></i>Edit Data
                            </a>

                            @if($peminjaman->status === 'dipinjam')
                                <form action="{{ route('pengurus.peminjaman.kembalikan', $peminjaman->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-green-600 to-lime-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                        <i class="fas fa-undo mr-2"></i>Kembalikan Barang
                                    </button>
                                </form>
                            @endif

                            <form id="deleteForm" method="POST" action="{{ route('pengurus.peminjaman.destroy', $peminjaman->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete()" class="w-full inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-600 to-rose-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-trash mr-2"></i>Hapus Data
                                </button>
                            </form>

                            <a href="{{ route('pengurus.peminjaman.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
            if (confirm(`Apakah Anda yakin ingin menghapus data peminjaman untuk "{{ $peminjaman->peminjam }}"?`)) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
@endsection
