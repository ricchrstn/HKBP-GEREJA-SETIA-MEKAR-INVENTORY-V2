@extends('pengurus.dashboard.layouts.app')
@section('title', 'Edit Peminjaman - Pengurus')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Edit Peminjaman</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Ubah data peminjaman untuk: <strong>{{ $peminjaman->peminjam }}</strong>
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('pengurus.peminjaman.show', $peminjaman->id) }}" class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-500 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                                </a>
                                <a href="{{ route('pengurus.peminjaman.index') }}" class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-rose-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="flex flex-wrap -mx-3">
            <!-- Main Form -->
            <div class="flex-none w-full max-w-full px-3 lg:w-8/12">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Informasi Peminjaman</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('pengurus.peminjaman.update', $peminjaman->id) }}">
                                @include('pengurus.peminjaman.form')
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Update Data
                                        </button>
                                        <a href="{{ route('pengurus.peminjaman.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                <!-- Informasi Peminjaman -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Informasi Peminjaman</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <strong class="text-slate-700">ID Peminjaman:</strong><br>
                                <code class="px-2 py-1 text-sm bg-gray-100 rounded">#{{ $peminjaman->id }}</code>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Status:</strong><br>
                                @php
                                    $statusClass = [
                                        'dipinjam' => 'bg-gradient-to-tl from-blue-600 to-cyan-400',
                                        'dikembalikan' => 'bg-gradient-to-tl from-green-600 to-lime-400',
                                        'terlambat' => 'bg-gradient-to-tl from-red-600 to-rose-400',
                                    ][$peminjaman->status];
                                @endphp
                                <span class="{{ $statusClass }} px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Petugas:</strong><br>
                                <span class="text-sm">{{ $peminjaman->user->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Barang -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Detail Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                @if ($peminjaman->barang->gambar)
                                    @if (file_exists(public_path('storage/barang/' . $peminjaman->barang->gambar)))
                                        <img src="{{ asset('storage/barang/' . $peminjaman->barang->gambar) }}" class="mr-4 h-16 w-16 rounded-xl object-cover border border-gray-200">
                                    @else
                                        <div class="mr-4 h-16 w-16 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600 flex items-center justify-center">
                                            <i class="ni ni-box-2 text-xl text-white"></i>
                                        </div>
                                    @endif
                                @else
                                    <div class="mr-4 h-16 w-16 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600 flex items-center justify-center">
                                        <i class="ni ni-box-2 text-xl text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="text-lg font-semibold">{{ $peminjaman->barang->nama }}</h5>
                                    <p class="text-sm text-slate-400">{{ $peminjaman->barang->kode_barang }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-slate-500 mb-1">Kategori</p>
                                    <p class="text-sm font-medium">{{ $peminjaman->barang->kategori->nama }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-slate-500 mb-1">Satuan</p>
                                    <p class="text-sm font-medium">{{ $peminjaman->barang->satuan }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-slate-500 mb-1">Stok Saat Ini</p>
                                    <p class="text-sm font-medium">{{ $peminjaman->barang->stok }} {{ $peminjaman->barang->satuan }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-slate-500 mb-1">Harga</p>
                                    <p class="text-sm font-medium">Rp {{ number_format($peminjaman->barang->harga, 0, ',', '.') }}</p>
                                </div>
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
            const barangDetails = document.getElementById('barangDetails');
            const barangPlaceholder = document.getElementById('barangPlaceholder');
            const jumlahInput = document.getElementById('jumlah');
            const stokWarning = document.getElementById('stokWarning');

            // Fungsi untuk memuat barang berdasarkan kategori
            function loadBarangs(kategoriId, selectedBarangId = null) {
                // Reset barang select
                barangSelect.innerHTML = '<option value="">-- Pilih Barang --</option>';
                barangSelect.disabled = true;

                if (kategoriId) {
                    fetch(`/pengurus/peminjaman/get-barang-by-kategori/${kategoriId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                data.data.forEach(barang => {
                                    const option = document.createElement('option');
                                    option.value = barang.id;
                                    option.textContent = `${barang.nama} (Stok: ${barang.stok})`;
                                    if (selectedBarangId == barang.id) {
                                        option.selected = true;
                                    }
                                    barangSelect.appendChild(option);
                                });
                                barangSelect.disabled = false;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            }

            // Event listener untuk kategori
            if (kategoriSelect) {
                kategoriSelect.addEventListener('change', function() {
                    loadBarangs(this.value);
                });
            }

            // Event listener untuk barang
            if (barangSelect) {
                barangSelect.addEventListener('change', function() {
                    const barangId = this.value;
                    if (barangId) {
                        fetch(`/pengurus/peminjaman/get-barang-details/${barangId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const barang = data.data;
                                    // Update stok warning
                                    if (jumlahInput) {
                                        jumlahInput.max = barang.stok;
                                        jumlahInput.addEventListener('input', function() {
                                            if (parseInt(this.value) > parseInt(barang.stok)) {
                                                stokWarning.textContent = `Stok tidak mencukupi! Maksimal: ${barang.stok}`;
                                                stokWarning.classList.remove('hidden');
                                            } else {
                                                stokWarning.classList.add('hidden');
                                            }
                                        });
                                    }
                                }
                            });
                    }
                });
            }

            // Inisialisasi saat halaman dimuat
            const initialKategoriId = kategoriSelect.value;
            const initialBarangId = barangSelect.value;
            if (initialKategoriId) {
                loadBarangs(initialKategoriId, initialBarangId);
            }
        });
    </script>
@endsection
