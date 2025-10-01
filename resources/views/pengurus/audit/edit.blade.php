@extends('pengurus.dashboard.layouts.app')

@section('title', 'Edit Audit - Pengurus')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Edit Audit Barang</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Perbarui informasi audit barang inventori
                                </p>
                            </div>
                            <a href="{{ route('pengurus.audit.index') }}" class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
            <div class="flex-none w-full max-w-full px-3 lg:w-8/12">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Informasi Audit Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('pengurus.audit.update', $audit->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                @include('pengurus.audit.form', ['audit' => $audit])
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Update Audit
                                        </button>
                                        <a href="{{ route('pengurus.audit.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
            <div class="flex-none w-full max-w-full px-3 lg:w-4/12">
                <!-- Current Photo -->
                @if ($audit->foto)
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                            <h6 class="mb-0">Foto Saat Ini</h6>
                        </div>
                        <div class="flex-auto px-0 pt-0 pb-2">
                            <div class="p-6">
                                <div class="flex justify-center">
                                    <img src="{{ asset('storage/' . $audit->foto) }}" alt="Foto Barang" class="max-w-full h-auto rounded-lg shadow-md">
                                </div>
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-slate-500">Upload foto baru untuk mengganti foto saat ini</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Info Barang -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Informasi Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-tag mr-2"></i>Nama Barang</h6>
                                <p class="text-sm text-slate-700">{{ $audit->barang->nama }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-barcode mr-2"></i>Kode Barang</h6>
                                <p class="text-sm text-slate-700">{{ $audit->barang->kode_barang }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-cubes mr-2"></i>Kategori</h6>
                                <p class="text-sm text-slate-700">{{ $audit->barang->kategori->nama ?? 'Tidak ada kategori' }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-warehouse mr-2"></i>Lokasi</h6>
                                <p class="text-sm text-slate-700">{{ $audit->barang->lokasi ?: 'Tidak ada lokasi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
