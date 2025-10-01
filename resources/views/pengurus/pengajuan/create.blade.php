@extends('pengurus.dashboard.layouts.app')

@section('title', 'Tambah Pengajuan - Pengurus')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Tambah Pengajuan Pengadaan Baru</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Ajukan barang baru yang dibutuhkan untuk kegiatan gereja
                                </p>
                            </div>
                            <a href="{{ route('pengurus.pengajuan.index') }}" class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Informasi Pengajuan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('pengurus.pengajuan.store') }}" enctype="multipart/form-data">
                                @csrf
                                @include('pengurus.pengajuan.form')
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Simpan Pengajuan
                                        </button>
                                        <a href="{{ route('pengurus.pengajuan.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Tips Pengajuan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-box mr-2"></i>Nama Barang</h6>
                                <p class="text-sm text-slate-400">Gunakan nama barang yang jelas dan spesifik untuk memudahkan identifikasi.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-list-ul mr-2"></i>Spesifikasi</h6>
                                <p class="text-sm text-slate-400">Berikan detail spesifikasi barang yang dibutuhkan (ukuran, merk, warna, dll).</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-calculator mr-2"></i>Jumlah</h6>
                                <p class="text-sm text-slate-400">Isi jumlah barang yang dibutuhkan sesuai dengan keperluan aktual.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-calendar-alt mr-2"></i>Tanggal Kebutuhan</h6>
                                <p class="text-sm text-slate-400">Tentukan tanggal kapan barang tersebut dibutuhkan untuk kegiatan.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-comment-alt mr-2"></i>Alasan</h6>
                                <p class="text-sm text-slate-400">Jelaskan alasan mengapa barang tersebut diajukan, semakin detail semakin baik.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-file-alt mr-2"></i>Dokumen Pendukung</h6>
                                <p class="text-sm text-slate-400">Upload dokumen pendukung seperti proposal, surat permintaan, atau penawaran harga.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Statistik Pengajuan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-wrap -mx-3">
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center border-r border-gray-200">
                                        <h4 class="font-bold text-blue-600">{{ \App\Models\Pengajuan::where('user_id', auth()->id())->count() }}</h4>
                                        <small class="text-slate-400">Total Pengajuan</small>
                                    </div>
                                </div>
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center">
                                        <h4 class="font-bold text-yellow-600">{{ \App\Models\Pengajuan::where('user_id', auth()->id())->where('status', 'pending')->count() }}</h4>
                                        <small class="text-slate-400">Pending</small>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap -mx-3 mt-4">
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center border-r border-gray-200">
                                        <h4 class="font-bold text-green-600">{{ \App\Models\Pengajuan::where('user_id', auth()->id())->where('status', 'disetujui')->count() }}</h4>
                                        <small class="text-slate-400">Disetujui</small>
                                    </div>
                                </div>
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center">
                                        <h4 class="font-bold text-red-600">{{ \App\Models\Pengajuan::where('user_id', auth()->id())->where('status', 'ditolak')->count() }}</h4>
                                        <small class="text-slate-400">Ditolak</small>
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

