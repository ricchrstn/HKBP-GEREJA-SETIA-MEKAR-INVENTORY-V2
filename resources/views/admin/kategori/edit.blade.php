@extends('admin.dashboard.layouts.app')
@section('title', 'Edit Kategori - Admin')
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
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Edit Kategori</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Ubah data kategori barang</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.kategori.index') }}"
                                    class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-purple-600 to-purple-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Kembali ke Daftar Kategori
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit -->
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 lg:w-8/12">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Informasi Kategori</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('admin.kategori.update', $kategori->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Kategori <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="nama" value="{{ old('nama', $kategori->nama) }}" required
                                        class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                               @error('nama') border-red-500 @else border-gray-300 @enderror
                                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    @error('nama')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                                    <textarea name="deskripsi" rows="3"
                                        class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                               @error('deskripsi') border-red-500 @else border-gray-300 @enderror
                                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit"
                                            class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-purple-600 to-purple-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                        </button>
                                        <a href="{{ route('admin.kategori.index') }}"
                                            class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-times mr-2"></i>Batal
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full max-w-full px-3 lg:w-4/12">
                <!-- Info Card -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Informasi Kategori</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <h6 class="text-purple-600"><i class="fas fa-info-circle mr-2"></i>ID Kategori</h6>
                                <p class="text-sm text-slate-400">{{ $kategori->id }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-purple-600"><i class="fas fa-calendar mr-2"></i>Dibuat Pada</h6>
                                <p class="text-sm text-slate-400">{{ $kategori->created_at->format('d F Y H:i') }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-purple-600"><i class="fas fa-edit mr-2"></i>Terakhir Diupdate</h6>
                                <p class="text-sm text-slate-400">{{ $kategori->updated_at->format('d F Y H:i') }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-purple-600"><i class="fas fa-box mr-2"></i>Jumlah Barang</h6>
                                <p class="text-sm text-slate-400">{{ $kategori->barangs()->count() }} barang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
