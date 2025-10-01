@extends('admin.dashboard.layouts.app')
@section('title', 'Tambah Jadwal Audit - Admin')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Tambah Jadwal Audit Baru</h6>
                                <p class="text-sm leading-normal text-slate-500">Jadwalkan audit untuk inventori barang gereja</p>
                            </div>
                            <a href="{{ route('admin.jadwal-audit.index') }}" class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Informasi Jadwal Audit</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('admin.jadwal-audit.store') }}">
                                @csrf
                                @include('admin.jadwal-audit.form')
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Simpan Jadwal
                                        </button>
                                        <a href="{{ route('admin.jadwal-audit.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Tips Jadwal Audit</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-lightbulb mr-2"></i>Judul Audit</h6>
                                <p class="text-sm text-slate-400">Buat judul yang deskriptif untuk memudahkan identifikasi jadwal audit.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-calendar-alt mr-2"></i>Tanggal Audit</h6>
                                <p class="text-sm text-slate-400">Pilih tanggal yang sesuai untuk pelaksanaan audit. Pastikan tidak bertabrakan dengan jadwal lain.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-user mr-2"></i>PIC (Person In Charge)</h6>
                                <p class="text-sm text-slate-400">Tentukan penanggung jawab yang akan melaksanakan audit. PIC akan menerima notifikasi terkait jadwal audit.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-boxes mr-2"></i>Barang</h6>
                                <p class="text-sm text-slate-400">Pilih barang yang akan diaudit. Anda dapat memilih beberapa barang dengan membuat beberapa jadwal audit.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Quick Stats -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Statistik Audit</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-wrap -mx-3">
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center border-r border-gray-200">
                                        <h4 class="font-bold text-blue-600">{{ \App\Models\JadwalAudit::count() }}</h4>
                                        <small class="text-slate-400">Total Jadwal</small>
                                    </div>
                                </div>
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center">
                                        <h4 class="font-bold text-green-600">{{ \App\Models\JadwalAudit::where('status', 'selesai')->count() }}</h4>
                                        <small class="text-slate-400">Selesai</small>
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
