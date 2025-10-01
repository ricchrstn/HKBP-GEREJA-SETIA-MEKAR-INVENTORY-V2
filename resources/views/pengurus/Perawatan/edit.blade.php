@extends('pengurus.dashboard.layouts.app')
@section('title', 'Edit Perawatan - Pengurus')
@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex flex-wrap -mx-3">
                        <div class="flex-none w-full max-w-full px-3 md:w-6/12 md:flex-none">
                            <h6 class="mb-0">Edit Perawatan Barang</h6>
                            <p class="text-sm leading-normal">Edit data perawatan: {{ $perawatan->jenis_perawatan }}</p>
                        </div>
                        <div class="flex-none w-full max-w-full px-3 md:w-6/12 md:flex-none">
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
    <!-- Form Content -->
    <div class="flex flex-wrap -mx-3">
        <!-- Main Form -->
        <div class="flex-none w-full max-w-full px-3 lg:w-8/12 lg:flex-none">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="mb-0">Informasi Perawatan</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <form method="POST" action="{{ route('pengurus.perawatan.update', $perawatan->id) }}" id="perawatanForm">
                            @method('PUT')
                            @csrf
                            @include('pengurus.perawatan.form')
                            <div class="flex flex-wrap mt-6 -mx-3">
                                <div class="flex-none w-full max-w-full px-3">
                                    <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                        <i class="fas fa-save mr-2"></i>Update Data
                                    </button>
                                    <a href="{{ route('pengurus.perawatan.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                        <i class="fas fa-times mr-2"></i>Batal
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar Info -->
        <div class="flex-none w-full max-w-full px-3 lg:w-4/12 lg:flex-none">
            <!-- Info Perawatan -->
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="mb-0">Informasi Perawatan</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <div class="mb-4">
                            <strong class="text-slate-700">ID Perawatan:</strong><br>
                            <code class="px-2 py-1 text-sm bg-gray-100 rounded">#{{ $perawatan->id }}</code>
                        </div>
                        <div class="mb-4">
                            <strong class="text-slate-700">Tanggal Perawatan:</strong><br>
                            <span class="text-sm">{{ $perawatan->tanggal_perawatan->format('d F Y') }}</span>
                        </div>
                        <div class="mb-4">
                            <strong class="text-slate-700">Status:</strong><br>
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
                            <strong class="text-slate-700">Petugas:</strong><br>
                            <span class="text-sm">{{ $perawatan->user->name }}</span>
                        </div>
                        <div class="mb-4">
                            <strong class="text-slate-700">Dibuat:</strong><br>
                            <small class="text-slate-400">{{ $perawatan->created_at->format('d M Y H:i') }}</small>
                        </div>
                        <div class="mb-4">
                            <strong class="text-slate-700">Terakhir Diupdate:</strong><br>
                            <small class="text-slate-400">{{ $perawatan->updated_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Info Barang -->
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="mb-0">Detail Barang</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            @if ($perawatan->barang->gambar)
                                @if (file_exists(public_path('storage/barang/' . $perawatan->barang->gambar)))
                                    <img src="{{ asset('storage/barang/' . $perawatan->barang->gambar) }}" class="mr-3 h-12 w-12 rounded-xl object-cover border border-gray-200">
                                @else
                                    <div class="mr-3 h-12 w-12 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600 flex items-center justify-center">
                                        <i class="ni ni-box-2 text-lg text-white"></i>
                                    </div>
                                @endif
                            @else
                                <div class="mr-3 h-12 w-12 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600 flex items-center justify-center">
                                    <i class="ni ni-box-2 text-lg text-white"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="text-sm font-semibold">{{ $perawatan->barang->nama }}</h6>
                                <p class="text-xs text-slate-400">{{ $perawatan->barang->kode_barang }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-slate-500">Kategori</p>
                                <p class="text-sm font-medium">{{ $perawatan->barang->kategori->nama }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Satuan</p>
                                <p class="text-sm font-medium">{{ $perawatan->barang->satuan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Stok</p>
                                <p class="text-sm font-medium">{{ $perawatan->barang->stok }} {{ $perawatan->barang->satuan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Status Barang</p>
                                <p class="text-sm font-medium">{{ ucfirst($perawatan->barang->status) }}</p>
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
                            <a href="{{ route('pengurus.perawatan.show', $perawatan->id) }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                <i class="fas fa-eye mr-2"></i>Lihat Detail
                            </a>
                            @if($perawatan->status === 'proses')
                                <form action="{{ route('pengurus.perawatan.selesaikan', $perawatan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-green-600 to-lime-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                        <i class="fas fa-check mr-2"></i>Selesaikan Perawatan
                                    </button>
                                </form>
                            @endif
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
        document.addEventListener('DOMContentLoaded', function() {
            const barangSelect = document.getElementById('barang_id');
            if (barangSelect) {
                barangSelect.addEventListener('change', function() {
                    const barangId = this.value;
                    if (barangId) {
                        // Fetch barang details via AJAX
                        fetch(`/pengurus/perawatan/get-barang-details/${barangId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const barang = data.data;
                                    // Update other details if needed
                                    console.log('Barang details loaded:', barang);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }
                });
            }
        });
    </script>
@endsection
