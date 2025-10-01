@extends('pengurus.dashboard.layouts.app')

@section('title', 'Tambah Barang - Pengurus')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Tambah Barang Baru</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Tambahkan barang baru ke inventori gereja
                                </p>
                            </div>
                            <a href="{{ route('pengurus.barang') }}"
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
            <div class="flex-none w-full max-w-full px-3 lg:w-8/12">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Informasi Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('pengurus.barang.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @include('pengurus.barang.form')

                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit"
                                            class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Simpan Barang
                                        </button>
                                        <a href="{{ route('pengurus.barang') }}"
                                            class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                <!-- Tips -->
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Tips Menambah Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-lightbulb mr-2"></i>Kode Barang</h6>
                                <p class="text-sm text-slate-400">Kode barang akan dibuat otomatis oleh sistem dengan format
                                    BRG-001, BRG-002, dst.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-image mr-2"></i>Gambar</h6>
                                <p class="text-sm text-slate-400">Upload gambar barang untuk memudahkan identifikasi. Format
                                    yang didukung: JPG, PNG, JPEG. Maksimal 2MB.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-boxes mr-2"></i>Stok Awal</h6>
                                <p class="text-sm text-slate-400">Masukkan jumlah stok awal barang. Sistem akan mencatat ini
                                    sebagai transaksi barang masuk pertama.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-tags mr-2"></i>Kategori</h6>
                                <p class="text-sm text-slate-400">Pilih kategori yang sesuai. Jika kategori belum ada, Anda
                                    bisa menambahkannya dengan tombol + di samping dropdown.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Statistik Inventori</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-wrap -mx-3">
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center border-r border-gray-200">
                                        <h4 class="font-bold text-blue-600">{{ \App\Models\Barang::count() }}</h4>
                                        <small class="text-slate-400">Total Barang</small>
                                    </div>
                                </div>
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center">
                                        <h4 class="font-bold text-green-600">{{ \App\Models\Kategori::count() }}</h4>
                                        <small class="text-slate-400">Kategori</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pengurus.dashboard.partials.category')

@endsection

@section('scripts')
    <script>
        // Preview gambar
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.querySelector('input[name="gambar"]');
            if (imageInput) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            let preview = document.getElementById('imagePreview');
                            if (!preview) {
                                preview = document.createElement('img');
                                preview.id = 'imagePreview';
                                preview.className =
                                    'h-20 mt-3 rounded-lg border border-gray-200 shadow-sm object-cover';
                                imageInput.parentNode.appendChild(preview);
                            }
                            preview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endsection
