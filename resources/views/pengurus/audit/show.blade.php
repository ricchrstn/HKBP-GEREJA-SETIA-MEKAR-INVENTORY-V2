@extends('pengurus.dashboard.layouts.app')

@section('title', 'Detail Audit - Pengurus')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Detail Audit Barang</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Informasi lengkap hasil audit barang
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('pengurus.audit.edit', $audit->id) }}" class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-orange-400 to-orange-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-edit mr-2"></i>Edit
                                </a>
                                <a href="{{ route('pengurus.audit.index') }}" class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-400 to-blue-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Informasi Audit</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-wrap -mx-3">
                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="flex items-center">
                                        <div class="inline-flex items-center justify-center mr-4 h-16 w-16 rounded-xl bg-gradient-to-tl from-purple-700 to-pink-500">
                                            <i class="fas fa-box text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-slate-700">{{ $audit->barang->nama }}</h4>
                                            <p class="text-sm text-slate-400">Kode Barang: {{ $audit->barang->kode_barang }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="text-xs text-slate-500">Tanggal Audit</p>
                                            <p class="text-sm font-semibold text-slate-700">{{ $audit->tanggal_audit->format('d F Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Auditor</p>
                                            <p class="text-sm font-semibold text-slate-700">{{ $audit->user->name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500">Kondisi Barang</p>
                                            @php
                                                $kondisiClass = [
                                                    'baik' => 'bg-gradient-to-tl from-green-600 to-lime-400',
                                                    'rusak' => 'bg-gradient-to-tl from-red-600 to-red-400',
                                                    'hilang' => 'bg-gradient-to-tl from-red-600 to-rose-400',
                                                    'tidak_terpakai' => 'bg-gradient-to-tl from-gray-600 to-slate-400',
                                                ][$audit->kondisi];
                                            @endphp
                                            <span class="{{ $kondisiClass }} px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                {{ ucfirst(str_replace('_', ' ', $audit->kondisi)) }}
                                            </span>
                                        </div>
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500">Status Audit</p>
                                            <span class="bg-gradient-to-tl from-blue-600 to-cyan-400 px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                {{ ucfirst($audit->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-none w-full max-w-full px-3 mb-4">
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <p class="text-xs text-slate-500 mb-2">Keterangan</p>
                                        <p class="text-sm text-slate-700">{{ $audit->keterangan ?: 'Tidak ada keterangan' }}</p>
                                    </div>
                                </div>

                                @if ($audit->foto)
                                    <div class="flex-none w-full max-w-full px-3">
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-slate-500 mb-2">Foto Barang</p>
                                            <div class="flex justify-center">
                                                <img src="{{ asset('storage/' . $audit->foto) }}" alt="Foto Barang" class="max-w-full h-auto rounded-lg shadow-md">
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
                <!-- Info Barang -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Informasi Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-tag mr-2"></i>Kategori</h6>
                                <p class="text-sm text-slate-700">{{ $audit->barang->kategori->nama ?? 'Tidak ada kategori' }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-cubes mr-2"></i>Satuan</h6>
                                <p class="text-sm text-slate-700">{{ $audit->barang->satuan->nama ?? 'Tidak ada satuan' }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-warehouse mr-2"></i>Lokasi</h6>
                                <p class="text-sm text-slate-700">{{ $audit->barang->lokasi ?: 'Tidak ada lokasi' }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-info-circle mr-2"></i>Deskripsi</h6>
                                <p class="text-sm text-slate-700">{{ $audit->barang->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Audit -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Riwayat Audit</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            @php
                                $riwayatAudit = \App\Models\Audit::where('barang_id', $audit->barang_id)
                                    ->where('id', '!=', $audit->id)
                                    ->orderBy('tanggal_audit', 'desc')
                                    ->take(5)
                                    ->get();
                            @endphp

                            @if ($riwayatAudit->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($riwayatAudit as $riwayat)
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="text-xs text-slate-500">{{ $riwayat->tanggal_audit->format('d M Y') }}</p>
                                                    <p class="text-sm font-semibold text-slate-700">
                                                        {{ ucfirst(str_replace('_', ' ', $riwayat->kondisi)) }}
                                                    </p>
                                                </div>
                                                @php
                                                    $kondisiClass = [
                                                        'baik' => 'bg-green-100 text-green-800',
                                                        'rusak' => 'bg-orange-100 text-orange-800',
                                                        'hilang' => 'bg-red-100 text-red-800',
                                                        'tidak_terpakai' => 'bg-gray-100 text-gray-800',
                                                    ][$riwayat->kondisi];
                                                @endphp
                                                <span class="{{ $kondisiClass }} px-2 py-1 text-xs rounded-full font-medium">
                                                    {{ ucfirst($riwayat->kondisi) }}
                                                </span>
                                            </div>
                                            @if ($riwayat->keterangan)
                                                <p class="text-xs text-slate-500 mt-1">{{ Str::limit($riwayat->keterangan, 50) }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-500 text-center">Belum ada riwayat audit untuk barang ini</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
