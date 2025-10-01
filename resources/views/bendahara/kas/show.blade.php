@extends('bendahara.dashboard.layouts.app')
@section('title', 'Detail Transaksi Kas - Bendahara')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Detail Transaksi Kas</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Informasi lengkap transaksi kas
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('bendahara.kas.index') }}" class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-400 to-blue-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Detail Content -->
        <div class="flex flex-wrap -mx-3">
            <!-- Main Detail -->
            <div class="flex-none w-full max-w-full px-3 lg:w-8/12">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Informasi Transaksi</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-wrap -mx-3">
                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="flex items-center">
                                        <div class="inline-flex items-center justify-center mr-4 h-16 w-16 rounded-xl {{ $kas->jenis == 'masuk' ? 'bg-gradient-to-tl from-green-600 to-green-400' : 'bg-gradient-to-tl from-red-600 to-red-400' }}">
                                            <i class="fas {{ $kas->jenis == 'masuk' ? 'fa-arrow-down' : 'fa-arrow-up' }} text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-slate-700">{{ $kas->kode_transaksi }}</h4>
                                            <p class="text-sm text-slate-400">Dibuat pada: {{ $kas->created_at->format('d F Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500">Jenis Transaksi</p>
                                            <span class="{{ $kas->jenis == 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2.5 py-1.4 text-xs rounded-1.8 font-bold uppercase">
                                                {{ $kas->jenis }}
                                            </span>
                                        </div>
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500">Tanggal Transaksi</p>
                                            <p class="text-sm font-semibold text-slate-700">{{ $kas->tanggal->format('d F Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <p class="text-xs text-slate-500 mb-2">Jumlah</p>
                                        <p class="text-2xl font-bold {{ $kas->jenis == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $kas->jenis == 'masuk' ? '+' : '-' }} Rp {{ number_format($kas->jumlah, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <p class="text-xs text-slate-500 mb-2">Keterangan</p>
                                        <p class="text-sm text-slate-700">{{ $kas->keterangan }}</p>
                                    </div>
                                </div>
                                @if ($kas->jenis == 'masuk' && $kas->sumber)
                                    <div class="flex-none w-full max-w-full px-3 mb-4">
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500 mb-2">Sumber Pemasukan</p>
                                            <p class="text-sm text-slate-700">{{ $kas->sumber }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if ($kas->jenis == 'keluar' && $kas->tujuan)
                                    <div class="flex-none w-full max-w-full px-3 mb-4">
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500 mb-2">Tujuan Pengeluaran</p>
                                            <p class="text-sm text-slate-700">{{ $kas->tujuan }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if ($kas->bukti_transaksi)
                                    <div class="flex-none w-full max-w-full px-3">
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500 mb-2">Bukti Transaksi</p>
                                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                                <div class="flex items-center">
                                                    <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                                                    <span class="text-sm text-slate-700">{{ basename($kas->bukti_transaksi) }}</span>
                                                </div>
                                                <a href="{{ asset('storage/' . $kas->bukti_transaksi) }}" target="_blank" class="text-blue-500 hover:text-blue-700">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sidebar Info -->
            <div class="flex-none w-full max-w-full px-3 lg:w-4/12">
                <!-- Action Buttons -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Aksi</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6 space-y-3">
                            <a href="{{ route('bendahara.kas.edit', $kas->id) }}" class="w-full inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-blue-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                <i class="fas fa-edit mr-2"></i>Edit Transaksi
                            </a>
                            <form method="POST" action="{{ route('bendahara.kas.destroy', $kas->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-600 to-red-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-trash mr-2"></i>Hapus Transaksi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Info Pembuat -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Informasi Pembuat</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 rounded-full" src="{{ $kas->user->profile_photo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($kas->user->name) . '&color=6366f1&background=f3f4f6' }}" alt="{{ $kas->user->name }}">
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">{{ $kas->user->name }}</h6>
                                    <p class="text-sm text-slate-500">{{ $kas->user->email }}</p>
                                    <p class="text-xs text-slate-400">{{ $kas->user->role }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
