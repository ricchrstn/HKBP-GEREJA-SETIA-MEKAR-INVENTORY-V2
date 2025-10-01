@extends('bendahara.dashboard.layouts.app')
@section('title', 'Analisis TOPSIS - Bendahara')
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
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Analisis TOPSIS</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Analisis prioritas pengadaan barang
                                    menggunakan metode TOPSIS</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('bendahara.analisis.hasil') }}"
                                    class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-calculator mr-2"></i>
                                    Hitung Analisis TOPSIS
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Pengajuan -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Daftar Pengajuan Pending</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    <span class="font-medium text-blue-600">{{ $pengajuans->count() }}</span> pengajuan
                                    pending
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Kode &
                                            Barang</th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Pengaju
                                        </th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Kriteria
                                        </th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pengajuans as $pengajuan)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-2">
                                                <div class="flex px-2 py-1">
                                                    <div>
                                                        <div
                                                            class="inline-flex items-center justify-center mr-4 h-12 w-12 rounded-xl bg-gradient-to-tl from-purple-700 to-pink-500">
                                                            <i class="fas fa-file-alt text-white text-lg"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm font-semibold">
                                                            {{ $pengajuan->kode_pengajuan }}</h6>
                                                        <p class="mb-0 text-xs text-slate-400">{{ $pengajuan->nama_barang }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                <p class="mb-0 text-xs font-semibold">{{ $pengajuan->user->name }}</p>
                                                <p class="mb-0 text-xs text-slate-400">
                                                    {{ $pengajuan->created_at->format('d M Y') }}</p>
                                            </td>
                                            <td class="p-2">
                                                <div class="text-xs space-y-0.5">
                                                    <div>
                                                        <span class="text-slate-500">Urgensi = </span><span
                                                            class="font-semibold">{{ $pengajuan->urgensi }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-500">Stok = </span><span
                                                            class="font-semibold">{{ $pengajuan->ketersediaan_stok }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-500">Dana = </span><span
                                                            class="font-semibold">{{ $pengajuan->ketersediaan_dana }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2 text-center">
                                                <div class="flex justify-center items-center space-x-2">
                                                    <a href="{{ route('bendahara.verifikasi.show', $pengajuan->id) }}"
                                                        class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-all"
                                                        title="Detail Pengajuan">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="p-8 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="mb-4 text-gray-400">
                                                        <i class="fas fa-file-invoice-dollar text-6xl"></i>
                                                    </div>
                                                    <h6 class="text-lg font-semibold text-gray-500 mb-2">
                                                        Belum ada data pengajuan pengadaan
                                                    </h6>
                                                    <p class="text-sm text-gray-400 mb-4">
                                                        Belum ada pengajuan pengadaan yang diajukan oleh pengurus
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kriteria Info -->
        <div class="flex flex-wrap -mx-3 mt-6">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Kriteria yang Digunakan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <h6 class="text-sm font-semibold text-slate-700">Tingkat Urgensi Barang</h6>
                                        <span
                                            class="px-2 py-1 text-xs rounded-full font-medium bg-green-100 text-green-800">
                                            Benefit
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500">Bobot: 0.3</p>
                                    <p class="text-xs text-slate-500 mt-1">Semakin tinggi nilai, semakin prioritas</p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <h6 class="text-sm font-semibold text-slate-700">Ketersediaan Stok Barang</h6>
                                        <span class="px-2 py-1 text-xs rounded-full font-medium bg-red-100 text-red-800">
                                            Cost
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500">Bobot: 0.25</p>
                                    <p class="text-xs text-slate-500 mt-1">Semakin rendah stok, semakin prioritas</p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <h6 class="text-sm font-semibold text-slate-700">Ketersediaan Dana</h6>
                                        <span
                                            class="px-2 py-1 text-xs rounded-full font-medium bg-green-100 text-green-800">
                                            Benefit
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500">Bobot: 0.45</p>
                                    <p class="text-xs text-slate-500 mt-1">Semakin tinggi dana, semakin prioritas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
