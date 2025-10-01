@extends('bendahara.dashboard.layouts.app')
@section('title', 'Tambah Transaksi Kas - Bendahara')
<!-- Show error messages -->
@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
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
                                <h6 class="mb-0">Tambah Transaksi Kas Baru</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Catat pemasukan atau pengeluaran kas gereja
                                </p>
                            </div>
                            <a href="{{ route('bendahara.kas.index') }}"
                                class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Informasi Transaksi</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form id="kasForm" method="POST" action="{{ route('bendahara.kas.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Transaksi <span
                                            class="text-red-500">*</span></label>
                                    <select name="jenis" id="jenisTransaksi"
                                        class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                        required>
                                        <option value="">-- Pilih Jenis Transaksi --</option>
                                        <option value="masuk" {{ old('jenis') == 'masuk' ? 'selected' : '' }}>Pemasukan
                                        </option>
                                        <option value="keluar" {{ old('jenis') == 'keluar' ? 'selected' : '' }}>Pengeluaran
                                        </option>
                                    </select>
                                    @error('jenis')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah <span
                                            class="text-red-500">*</span></label>
                                    <div class="flex">
                                        <div
                                            class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                            Rp
                                        </div>
                                        <input type="number" name="jumlah" value="{{ old('jumlah') }}" min="0"
                                            class="w-full px-4 py-2 text-sm border rounded-r-lg shadow-sm @error('jumlah') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                            placeholder="1.000" required>
                                    </div>
                                    @error('jumlah')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Transaksi <span
                                            class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal"
                                        value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                                        max="{{ now()->format('Y-m-d') }}"
                                        class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm @error('tanggal') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                        required>
                                    @error('tanggal')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Field Sumber Pemasukan -->
                                <div class="mb-5" id="sumberField" style="display: none;">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                                        Sumber Pemasukan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="sumber" id="sumberInput"
                                        class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                        placeholder="Contoh: Persembahan Ibadah Minggu, Donasi dari Bapak Ahmad, Sumbangan Natal..."
                                        value="{{ old('sumber') }}">
                                    @error('sumber')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Field Tujuan Pengeluaran -->
                                <div class="mb-5" id="tujuanField" style="display: none;">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                                        Tujuan Pengeluaran <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="tujuan" id="tujuanInput"
                                        class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                        placeholder="Contoh: Pengadaan Sound System, Operasional Gereja, Perawatan Gedung..."
                                        value="{{ old('tujuan') }}">
                                    @error('tujuan')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan <span
                                            class="text-red-500">*</span></label>
                                    <textarea name="keterangan" rows="3"
                                        class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm @error('keterangan') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                        placeholder="Jelaskan detail transaksi secara lengkap..." required>{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Bukti Transaksi</label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="bukti_transaksi"
                                            class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik
                                                        untuk upload</span> atau drag and drop</p>
                                                <p class="text-xs text-gray-500">PDF, JPG, atau PNG (MAX. 2MB)</p>
                                            </div>
                                            <input id="bukti_transaksi" name="bukti_transaksi" type="file"
                                                class="hidden" accept=".pdf,.jpg,.jpeg,.png" />
                                        </label>
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <strong>Tip:</strong> Upload bukti transaksi seperti struk, kwitansi, atau nota
                                        untuk dokumentasi
                                    </div>
                                    @error('bukti_transaksi')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" id="submitBtn"
                                            class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Simpan Transaksi
                                        </button>
                                        <a href="{{ route('bendahara.kas.index') }}"
                                            class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Tips Input Transaksi</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-info-circle mr-2"></i>Jenis Transaksi</h6>
                                <p class="text-sm text-slate-400">Pilih jenis transaksi apakah pemasukan atau pengeluaran
                                    kas.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-money-bill-wave mr-2"></i>Jumlah</h6>
                                <p class="text-sm text-slate-400">Masukkan jumlah transaksi dengan angka yang valid.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-calendar-alt mr-2"></i>Tanggal</h6>
                                <p class="text-sm text-slate-400">Pilih tanggal ketika transaksi terjadi.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-comment-alt mr-2"></i>Keterangan</h6>
                                <p class="text-sm text-slate-400">Berikan keterangan detail untuk dokumentasi transaksi.
                                </p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-file-alt mr-2"></i>Bukti Transaksi</h6>
                                <p class="text-sm text-slate-400">Upload bukti transaksi seperti struk, kwitansi, atau
                                    nota.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Quick Stats -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Statistik Kas</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-wrap -mx-3">
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center border-r border-gray-200">
                                        <h4 class="font-bold text-green-600">Rp
                                            {{ number_format(\App\Models\Kas::masuk()->sum('jumlah'), 0, ',', '.') }}</h4>
                                        <small class="text-slate-400">Total Pemasukan</small>
                                    </div>
                                </div>
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center">
                                        <h4 class="font-bold text-red-600">Rp
                                            {{ number_format(\App\Models\Kas::keluar()->sum('jumlah'), 0, ',', '.') }}</h4>
                                        <small class="text-slate-400">Total Pengeluaran</small>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap -mx-3 mt-4">
                                <div class="flex-none w-full max-w-full px-3">
                                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                                        <h4 class="font-bold text-blue-600">Rp
                                            {{ number_format(\App\Models\Kas::masuk()->sum('jumlah') - \App\Models\Kas::keluar()->sum('jumlah'), 0, ',', '.') }}
                                        </h4>
                                        <small class="text-slate-400">Saldo Saat Ini</small>
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
