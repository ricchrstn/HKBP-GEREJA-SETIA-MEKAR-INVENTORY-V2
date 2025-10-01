@extends('admin.dashboard.layouts.app')
@section('title', 'Edit Jadwal Audit - Admin')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Edit Jadwal Audit</h6>
                                <p class="text-sm leading-normal text-slate-500">Edit informasi jadwal audit: {{ $jadwalAudit->judul }}</p>
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
                            <form method="POST" action="{{ route('admin.jadwal-audit.update', $jadwalAudit->id) }}">
                                @method('PUT')
                                @csrf
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Audit</label>
                                    <input type="text" name="judul" value="{{ old('judul', $jadwalAudit->judul) }}" class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm @error('judul') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" placeholder="Contoh: Audit Perlengkapan Ibadah Triwulan 1">
                                    @error('judul')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                                    <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm @error('deskripsi') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" placeholder="Deskripsi detail tentang audit yang akan dilakukan...">{{ old('deskripsi', $jadwalAudit->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Audit</label>
                                    <input type="date" name="tanggal_audit" value="{{ old('tanggal_audit', optional($jadwalAudit->tanggal_audit)->format('Y-m-d')) }}" class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm @error('tanggal_audit') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    @error('tanggal_audit')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Barang</label>
                                    <select name="barang_id" required class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm @error('barang_id') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($barangs as $barang)
                                            <option value="{{ $barang->id }}" {{ old('barang_id', $jadwalAudit->barang_id) == $barang->id ? 'selected' : '' }}>
                                                {{ $barang->nama }} ({{ $barang->kode_barang }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('barang_id')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">PIC (Person In Charge)</label>
                                    <select name="user_id" required class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm @error('user_id') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        <option value="">-- Pilih PIC --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $jadwalAudit->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Status</label>
                                    <select name="status" class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm @error('status') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        <option value="terjadwal" {{ old('status', $jadwalAudit->status) == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                                        <option value="diproses" {{ old('status', $jadwalAudit->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="selesai" {{ old('status', $jadwalAudit->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="ditunda" {{ old('status', $jadwalAudit->status) == 'ditunda' ? 'selected' : '' }}>Ditunda</option>
                                    </select>
                                    @error('status')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Update Jadwal
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
                <!-- Info Jadwal Audit -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Informasi Jadwal Audit</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <strong class="text-slate-700">ID Jadwal:</strong><br>
                                <code class="px-2 py-1 text-sm bg-gray-100 rounded">#{{ $jadwalAudit->id }}</code>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Tanggal Audit:</strong><br>
                                @php
                                    $badgeClass = 'bg-gradient-to-tl from-blue-600 to-cyan-400';
                                    if ($jadwalAudit->tanggal_audit < now()) {
                                        $badgeClass = 'bg-gradient-to-tl from-orange-500 to-yellow-400';
                                    }
                                @endphp
                                <span class="inline-block px-2 py-1 text-xs font-bold text-white uppercase rounded-lg {{ $badgeClass }}">
                                    {{ optional($jadwalAudit->tanggal_audit)->format('d M Y') }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Status:</strong><br>
                                @php
                                    $statusClass = [
                                        'terjadwal' => 'bg-gradient-to-tl from-blue-600 to-cyan-400',
                                        'diproses' => 'bg-gradient-to-tl from-orange-600 to-yellow-400',
                                        'selesai' => 'bg-gradient-to-tl from-green-600 to-lime-400',
                                        'ditunda' => 'bg-gradient-to-tl from-red-600 to-rose-400',
                                    ][$jadwalAudit->status];
                                @endphp
                                <span class="{{ $statusClass }} px-2.5 py-1.4 text-xs font-bold text-white uppercase rounded-lg">
                                    {{ ucfirst($jadwalAudit->status) }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Dibuat:</strong><br>
                                <small class="text-slate-400">{{ $jadwalAudit->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Terakhir Diupdate:</strong><br>
                                <small class="text-slate-400">{{ $jadwalAudit->updated_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Detail Barang & PIC -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Detail Barang & PIC</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-box mr-2"></i>Barang</h6>
                                <p class="text-sm font-semibold mb-1">{{ $jadwalAudit->barang->nama }}</p>
                                <p class="text-xs text-slate-400">{{ $jadwalAudit->barang->kode_barang }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-green-600"><i class="fas fa-user mr-2"></i>PIC</h6>
                                <p class="text-sm font-semibold mb-1">{{ $jadwalAudit->user->name }}</p>
                                <p class="text-xs text-slate-400">{{ $jadwalAudit->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Quick Actions -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Aksi Cepat</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-col space-y-2">
                                <a href="{{ route('admin.jadwal-audit.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-list mr-2"></i>Lihat Semua Jadwal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
