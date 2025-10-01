@extends('pengurus.dashboard.layouts.app')
@section('title', 'Edit Barang Masuk - Pengurus')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Edit Barang Masuk</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Edit data barang masuk: {{ $barangMasuk->barang->nama }}
                                </p>
                            </div>
                            <a href="{{ route('pengurus.barang.masuk') }}"
                                class="inline-block px-6 py-3 font-bold text-center text-grey uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                            <form method="POST" action="{{ route('pengurus.barang.masuk.update', $barangMasuk->id) }}"
                                id="barangMasukForm">
                                @method('PUT')
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                                    <!-- Dropdown Kategori -->
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                                        <select id="kategori_id" name="kategori_id" required
                                                class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                                       @error('kategori_id') border-red-500 @else border-gray-300 @enderror
                                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}"
                                                    {{ old('kategori_id', $barangMasuk->barang->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kategori_id')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Dropdown Barang -->
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Barang</label>
                                        <select id="barang_id" name="barang_id" required
                                                class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                                       @error('barang_id') border-red-500 @else border-gray-300 @enderror
                                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach($barangs as $barang)
                                                <option value="{{ $barang->id }}"
                                                    {{ old('barang_id', $barangMasuk->barang_id) == $barang->id ? 'selected' : '' }}
                                                    data-kategori-id="{{ $barang->kategori_id }}">
                                                    {{ $barang->nama }} ({{ $barang->kode_barang }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('barang_id')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal</label>
                                    <input type="date" name="tanggal"
                                           value="{{ old('tanggal', $barangMasuk->tanggal->format('Y-m-d')) }}"
                                           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                                  @error('tanggal') border-red-500 @else border-gray-300 @enderror
                                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    @error('tanggal')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah</label>
                                    <input type="number" name="jumlah" min="1"
                                           value="{{ old('jumlah', $barangMasuk->jumlah) }}"
                                           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                                  @error('jumlah') border-red-500 @else border-gray-300 @enderror
                                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    @error('jumlah')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan</label>
                                    <textarea name="keterangan" rows="3"
                                              class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                                     @error('keterangan') border-red-500 @else border-gray-300 @enderror
                                                     focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">{{ old('keterangan', $barangMasuk->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit"
                                            class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Update Data
                                        </button>
                                        <a href="{{ route('pengurus.barang.masuk') }}"
                                            class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                <!-- Info Barang Masuk -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Informasi Transaksi</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <strong class="text-slate-700">ID Transaksi:</strong><br>
                                <code class="px-2 py-1 text-sm bg-gray-100 rounded">#{{ $barangMasuk->id }}</code>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Tanggal:</strong><br>
                                <span class="text-sm">{{ $barangMasuk->tanggal->format('d F Y') }}</span>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Petugas:</strong><br>
                                <span class="text-sm">{{ $barangMasuk->user->name }}</span>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Dibuat:</strong><br>
                                <small class="text-slate-400">{{ $barangMasuk->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Terakhir Diupdate:</strong><br>
                                <small class="text-slate-400">{{ $barangMasuk->updated_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Barang -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Detail Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                @if ($barangMasuk->barang->gambar)
                                    @if (file_exists(public_path('storage/barang/' . $barangMasuk->barang->gambar)))
                                        <img src="{{ asset('storage/barang/' . $barangMasuk->barang->gambar) }}"
                                            class="mr-3 h-12 w-12 rounded-xl object-cover border border-gray-200">
                                    @else
                                        <div
                                            class="mr-3 h-12 w-12 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600 flex items-center justify-center">
                                            <i class="ni ni-box-2 text-lg text-white"></i>
                                        </div>
                                    @endif
                                @else
                                    <div
                                        class="mr-3 h-12 w-12 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600 flex items-center justify-center">
                                        <i class="ni ni-box-2 text-lg text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="text-sm font-semibold">{{ $barangMasuk->barang->nama }}</h6>
                                    <p class="text-xs text-slate-400">{{ $barangMasuk->barang->kode_barang }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-slate-500">Kategori</p>
                                    <p class="text-sm font-medium">{{ $barangMasuk->barang->kategori->nama }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Satuan</p>
                                    <p class="text-sm font-medium">{{ $barangMasuk->barang->satuan }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Stok Saat Ini</p>
                                    <p class="text-sm font-medium">{{ $barangMasuk->barang->stok }}
                                        {{ $barangMasuk->barang->satuan }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Harga</p>
                                    <p class="text-sm font-medium">Rp
                                        {{ number_format($barangMasuk->barang->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Aksi Cepat</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-col space-y-2">
                                <a href="{{ route('pengurus.barang.masuk.show', $barangMasuk->id) }}"
                                    class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                                </a>
                                <a href="{{ route('pengurus.barang.masuk') }}"
                                    class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-list mr-2"></i>Lihat Semua Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kategoriSelect = document.getElementById('kategori_id');
            const barangSelect = document.getElementById('barang_id');

            // Filter barang berdasarkan kategori yang dipilih
            if (kategoriSelect) {
                kategoriSelect.addEventListener('change', function() {
                    const selectedKategoriId = this.value;

                    // Sembunyikan semua opsi barang terlebih dahulu
                    Array.from(barangSelect.options).forEach(option => {
                        if (option.value !== '') {
                            const optionKategoriId = option.getAttribute('data-kategori-id');
                            option.style.display = optionKategoriId === selectedKategoriId ? '' : 'none';
                        }
                    });

                    // Jika tidak ada opsi yang visible, reset pilihan
                    const visibleOptions = Array.from(barangSelect.options).filter(option =>
                        option.value !== '' && option.style.display !== 'none'
                    );

                    if (visibleOptions.length === 0) {
                        barangSelect.value = '';
                    } else if (!visibleOptions.some(option => option.value === barangSelect.value)) {
                        // Jika pilihan saat ini tidak visible, pilih opsi pertama yang visible
                        barangSelect.value = visibleOptions[0].value;
                    }
                });

                // Trigger change event saat halaman dimuat untuk memfilter barang
                kategoriSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
