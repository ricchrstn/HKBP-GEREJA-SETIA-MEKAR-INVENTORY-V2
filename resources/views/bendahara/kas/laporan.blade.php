@extends('bendahara.dashboard.layouts.app')
@section('title', 'Laporan Kas - Bendahara')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header Section -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-5 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Laporan Kas</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Cetak laporan transaksi kas gereja</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('bendahara.kas.index') }}" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-gray-600 to-gray-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Kembali
                                </a>
                                <button onclick="window.print()" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-purple-600 to-purple-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-print mr-2"></i>
                                    Cetak
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Filter Laporan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="GET" action="{{ route('bendahara.kas.laporan') }}">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                        <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi</label>
                                        <select name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Semua</option>
                                            <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Pemasukan</option>
                                            <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Pengeluaran</option>
                                        </select>
                                    </div>
                                    <div class="flex items-end">
                                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <i class="fas fa-filter mr-2"></i>Filter
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full max-w-full px-3 sm:w-1/3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="text-sm font-bold text-slate-700">Total Pemasukan</h6>
                    </div>
                    <div class="flex-auto p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="text-2xl font-bold text-green-600">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h5>
                                <p class="text-xs text-slate-400 mt-1">Periode laporan</p>
                            </div>
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100">
                                <i class="fas fa-arrow-up text-green-600 text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full max-w-full px-3 sm:w-1/3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="text-sm font-bold text-slate-700">Total Pengeluaran</h6>
                    </div>
                    <div class="flex-auto p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="text-2xl font-bold text-red-600">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h5>
                                <p class="text-xs text-slate-400 mt-1">Periode laporan</p>
                            </div>
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100">
                                <i class="fas fa-arrow-down text-red-600 text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full max-w-full px-3 sm:w-1/3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="text-sm font-bold text-slate-700">Saldo Akhir</h6>
                    </div>
                    <div class="flex-auto p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="text-2xl font-bold {{ $saldo >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                    Rp {{ number_format($saldo, 0, ',', '.') }}
                                </h5>
                                <p class="text-xs text-slate-400 mt-1">Periode laporan</p>
                            </div>
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full {{ $saldo >= 0 ? 'bg-blue-100' : 'bg-red-100' }}">
                                <i class="fas fa-wallet {{ $saldo >= 0 ? 'text-blue-600' : 'text-red-600' }} text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Transaksi -->
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Detail Transaksi</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    <span class="font-medium text-blue-600">{{ $transaksi->count() }}</span> transaksi ditemukan
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($transaksi as $t)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $t->tanggal->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $t->kode_transaksi }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $t->keterangan }}
                                                @if ($t->jenis == 'masuk' && $t->sumber)
                                                    <br><span class="text-xs text-green-600">Sumber: {{ $t->sumber }}</span>
                                                @elseif ($t->jenis == 'keluar' && $t->tujuan)
                                                    <br><span class="text-xs text-red-600">Tujuan: {{ $t->tujuan }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="{{ $t->jenis == 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                    {{ $t->jenis }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $t->jenis == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $t->jenis == 'masuk' ? '+' : '-' }} Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="4" class="px-6 py-3 text-sm font-medium text-gray-900 text-right">TOTAL</td>
                                        <td class="px-6 py-3 text-sm font-bold text-gray-900 text-right">
                                            Rp {{ number_format($saldo, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
