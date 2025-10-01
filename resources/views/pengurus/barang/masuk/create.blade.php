@extends('pengurus.dashboard.layouts.app')
@section('title', 'Tambah Barang Masuk - Pengurus')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Catat Barang Masuk</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Tambahkan catatan barang masuk dari hibah atau pembelian
                                </p>
                            </div>
                            <a href="{{ route('pengurus.barang.masuk') }}" class="inline-block px-6 py-3 font-bold text-center text-grey uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Informasi Barang Masuk</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('pengurus.barang.masuk.store') }}" id="barangMasukForm">
                                @csrf
                                @include('pengurus.barang.masuk.form')
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Simpan Data
                                        </button>
                                        <a href="{{ route('pengurus.barang.masuk') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                <!-- Info Barang -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Detail Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div id="barangDetails" class="hidden">
                                <div class="mb-4">
                                    <div class="flex items-center mb-3">
                                        <div id="barangImageContainer" class="mr-3">
                                            <!-- Placeholder for image -->
                                        </div>
                                        <div>
                                            <h6 id="barangNama" class="text-sm font-semibold"></h6>
                                            <p id="barangKode" class="text-xs text-slate-400"></p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <p class="text-xs text-slate-500">Kategori</p>
                                            <p id="barangKategori" class="text-sm font-medium"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Satuan</p>
                                            <p id="barangSatuan" class="text-sm font-medium"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Stok Saat Ini</p>
                                            <p id="barangStok" class="text-sm font-medium"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Harga</p>
                                            <p id="barangHarga" class="text-sm font-medium"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="barangPlaceholder" class="text-center py-4">
                                <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-400">Pilih barang untuk melihat detail</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Tips Pengisian</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-calendar-day mr-2"></i>Tanggal</h6>
                                <p class="text-sm text-slate-400">Isi tanggal saat barang diterima. Default adalah hari ini.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-sort-numeric-up mr-2"></i>Jumlah</h6>
                                <p class="text-sm text-slate-400">Masukkan jumlah barang yang masuk. Stok akan otomatis bertambah.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-file-alt mr-2"></i>Keterangan</h6>
                                <p class="text-sm text-slate-400">Isi keterangan seperti sumber barang (hibah, pembelian, dll.)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Statistik</h6>
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
                                        <h4 class="font-bold text-green-600">{{ \App\Models\BarangMasuk::count() }}</h4>
                                        <small class="text-slate-400">Transaksi Masuk</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
