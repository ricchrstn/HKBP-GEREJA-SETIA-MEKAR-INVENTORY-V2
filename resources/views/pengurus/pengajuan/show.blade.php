@extends('pengurus.dashboard.layouts.app')

@section('title', 'Detail Pengajuan - Pengurus')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Detail Pengajuan Pengadaan</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Informasi lengkap pengajuan barang
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                @if ($pengajuan->status == 'pending')
                                    <a href="{{ route('pengurus.pengajuan.edit', $pengajuan->id) }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-orange-400 to-orange-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                        <i class="fas fa-edit mr-2"></i>Edit
                                    </a>
                                @endif
                                <a href="{{ route('pengurus.pengajuan.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-400 to-blue-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Informasi Pengajuan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-wrap -mx-3">
                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="flex items-center">
                                        <div class="inline-flex items-center justify-center mr-4 h-16 w-16 rounded-xl bg-gradient-to-tl from-purple-700 to-pink-500">
                                            <i class="fas fa-file-alt text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-slate-700">{{ $pengajuan->kode_pengajuan }}</h4>
                                            <p class="text-sm text-slate-400">Diajukan pada: {{ $pengajuan->created_at->format('d F Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500">Status Pengajuan</p>
                                            @php
                                                $statusClass = [
                                                    'pending' => 'bg-gradient-to-tl from-yellow-600 to-amber-400',
                                                    'disetujui' => 'bg-gradient-to-tl from-green-600 to-lime-400',
                                                    'ditolak' => 'bg-gradient-to-tl from-red-600 to-rose-400',
                                                    'proses' => 'bg-gradient-to-tl from-blue-600 to-cyan-400',
                                                ][$pengajuan->status];
                                            @endphp
                                            <span class="{{ $statusClass }} px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                {{ ucfirst($pengajuan->status) }}
                                            </span>
                                        </div>
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500">Tanggal Kebutuhan</p>
                                            <p class="text-sm font-semibold text-slate-700">{{ $pengajuan->kebutuhan->format('d F Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <p class="text-xs text-slate-500 mb-2">Nama Barang</p>
                                        <p class="text-sm text-slate-700 font-semibold">{{ $pengajuan->nama_barang }}</p>
                                    </div>
                                </div>

                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <p class="text-xs text-slate-500 mb-2">Spesifikasi</p>
                                        <p class="text-sm text-slate-700">{{ $pengajuan->spesifikasi ?: 'Tidak ada spesifikasi' }}</p>
                                    </div>
                                </div>

                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500 mb-2">Jumlah</p>
                                            <p class="text-sm text-slate-700 font-semibold">{{ $pengajuan->jumlah }} {{ $pengajuan->satuan }}</p>
                                        </div>
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500 mb-2">Pengaju</p>
                                            <p class="text-sm text-slate-700">{{ $pengajuan->user->name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <p class="text-xs text-slate-500 mb-2">Alasan Pengajuan</p>
                                        <p class="text-sm text-slate-700">{{ $pengajuan->alasan }}</p>
                                    </div>
                                </div>

                                @if ($pengajuan->keterangan)
                                    <div class="flex-none w-full max-w-full px-3 mb-4">
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500 mb-2">Keterangan</p>
                                            <p class="text-sm text-slate-700">{{ $pengajuan->keterangan }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($pengajuan->file_pengajuan)
                                    <div class="flex-none w-full max-w-full px-3">
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500 mb-2">Dokumen Pendukung</p>
                                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                                <div class="flex items-center">
                                                    <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                                                    <span class="text-sm text-slate-700">{{ basename($pengajuan->file_pengajuan) }}</span>
                                                </div>
                                                <a href="{{ asset('storage/' . $pengajuan->file_pengajuan) }}" target="_blank" class="text-blue-500 hover:text-blue-700">
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
                <!-- Status Timeline -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Status Timeline</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-file text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-900 font-medium">Pengajuan Dibuat</p>
                                                        <p class="text-sm text-gray-500">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    @if ($pengajuan->status !== 'pending')
                                        <li>
                                            <div class="relative pb-8">
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                                            <i class="fas fa-eye text-white text-xs"></i>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-900 font-medium">Ditinjau oleh Bendahara</p>
                                                            <p class="text-sm text-gray-500">Dalam proses verifikasi</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif

                                    @if ($pengajuan->status === 'disetujui')
                                        <li>
                                            <div class="relative pb-8">
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                            <i class="fas fa-check text-white text-xs"></i>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-900 font-medium">Disetujui</p>
                                                            <p class="text-sm text-gray-500">Pengajuan disetujui oleh Bendahara</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif

                                    @if ($pengajuan->status === 'ditolak')
                                        <li>
                                            <div class="relative pb-8">
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                            <i class="fas fa-times text-white text-xs"></i>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-900 font-medium">Ditolak</p>
                                                            <p class="text-sm text-gray-500">Pengajuan ditolak oleh Bendahara</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif

                                    @if ($pengajuan->status === 'proses')
                                        <li>
                                            <div class="relative pb-8">
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                            <i class="fas fa-shopping-cart text-white text-xs"></i>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-900 font-medium">Dalam Proses Pengadaan</p>
                                                            <p class="text-sm text-gray-500">Barang sedang dalam proses pembelian</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pengajuan -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Riwayat Pengajuan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            @php
                                $riwayatPengajuan = \App\Models\Pengajuan::where('user_id', auth()->id())
                                    ->where('id', '!=', $pengajuan->id)
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();
                            @endphp

                            @if ($riwayatPengajuan->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($riwayatPengajuan as $riwayat)
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="text-xs text-slate-500">{{ $riwayat->kode_pengajuan }}</p>
                                                    <p class="text-sm font-semibold text-slate-700">{{ $riwayat->nama_barang }}</p>
                                                </div>
                                                @php
                                                    $statusClass = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'disetujui' => 'bg-green-100 text-green-800',
                                                        'ditolak' => 'bg-red-100 text-red-800',
                                                        'proses' => 'bg-blue-100 text-blue-800',
                                                    ][$riwayat->status];
                                                @endphp
                                                <span class="{{ $statusClass }} px-2 py-1 text-xs rounded-full font-medium">
                                                    {{ ucfirst($riwayat->status) }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-1">{{ $riwayat->created_at->format('d M Y') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-500 text-center">Belum ada riwayat pengajuan lain</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
