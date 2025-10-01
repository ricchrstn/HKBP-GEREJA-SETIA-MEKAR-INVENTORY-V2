@extends('admin.dashboard.layouts.app')
@section('title', 'Edit Barang - Admin')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Edit Barang</h6>
                                <p class="text-sm leading-normal text-slate-500">Edit informasi barang: {{ $barang->nama }}
                                </p>
                            </div>
                            <a href="{{ route('admin.inventori.index') }}"
                                class="inline-block px-6 py-3 font-bold text-center text-grey uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="flex flex-wrap -mx-3">
            <!-- Main Form -->
            <div class="flex-none w-full max-w-full px-3 lg:w-8/12 lg:flex-none">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Informasi Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('admin.inventori.update', $barang) }}"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @include('admin.inventori.form')
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit"
                                            class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Update Barang
                                        </button>
                                        <a href="{{ route('admin.inventori.index') }}"
                                            class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                <!-- Info Barang -->
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Informasi Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <strong class="text-slate-700">Kode Barang:</strong><br>
                                <code class="px-2 py-1 text-sm bg-gray-100 rounded">{{ $barang->kode_barang }}</code>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Stok Saat Ini:</strong><br>
                                @php
                                    $badgeClass = 'bg-gradient-to-tl from-green-600 to-lime-400';
                                    if ($barang->stok == 0) {
                                        $badgeClass = 'bg-gradient-to-tl from-red-600 to-rose-400';
                                    } elseif ($barang->stok <= 5) {
                                        $badgeClass = 'bg-gradient-to-tl from-orange-500 to-yellow-400';
                                    }
                                @endphp
                                <span
                                    class="inline-block px-2 py-1 text-xs font-bold text-white uppercase rounded-lg {{ $badgeClass }}">
                                    {{ $barang->stok }} {{ $barang->satuan }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Status:</strong><br>
                                <span
                                    class="inline-block px-2 py-1 text-xs font-bold text-white uppercase rounded-lg {{ $barang->status == 'aktif' ? 'bg-gradient-to-tl from-green-600 to-lime-400' : 'bg-gradient-to-tl from-gray-400 to-gray-600' }}">
                                    {{ ucfirst($barang->status) }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Dibuat:</strong><br>
                                <small class="text-slate-400">{{ $barang->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Terakhir Diupdate:</strong><br>
                                <small class="text-slate-400">{{ $barang->updated_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaksi Terakhir -->
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Transaksi Terakhir</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            @php
                                $transaksiMasuk = $barang->barangMasuk()->latest()->first();
                                $transaksiKeluar = $barang->barangKeluar()->latest()->first();
                            @endphp
                            @if ($transaksiMasuk)
                                <div class="mb-4">
                                    <h6 class="text-green-600"><i class="fas fa-arrow-up mr-2"></i>Barang Masuk</h6>
                                    <p class="text-sm mb-1">{{ $transaksiMasuk->jumlah }} {{ $barang->satuan }}</p>
                                    <small
                                        class="text-slate-400">{{ $transaksiMasuk->created_at->format('d M Y') }}</small>
                                </div>
                            @endif
                            @if ($transaksiKeluar)
                                <div class="mb-4">
                                    <h6 class="text-red-600"><i class="fas fa-arrow-down mr-2"></i>Barang Keluar</h6>
                                    <p class="text-sm mb-1">{{ $transaksiKeluar->jumlah }} {{ $barang->satuan }}</p>
                                    <small
                                        class="text-slate-400">{{ $transaksiKeluar->created_at->format('d M Y') }}</small>
                                </div>
                            @endif
                            @if (!$transaksiMasuk && !$transaksiKeluar)
                                <p class="text-center text-slate-400">Belum ada transaksi</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Aksi Cepat</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-col space-y-2">
                                <a href="{{ route('admin.inventori.index') }}"
                                    class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-list mr-2"></i>Lihat Semua Barang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.dashboard.partials.category')
@endsection

