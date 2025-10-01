@extends('pengurus.dashboard.layouts.app')
@section('title', 'Edit Barang Keluar - Pengurus')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Edit Barang Keluar</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Edit data barang keluar: {{ $barangKeluar->barang->nama }}
                                </p>
                            </div>
                            <a href="{{ route('pengurus.barang.keluar') }}" class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Informasi Barang Keluar</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('pengurus.barang.keluar.update', $barangKeluar->id) }}" id="barangKeluarForm">
                                @method('PUT')
                                @csrf
                                @include('pengurus.barang.keluar.form')
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Update Data
                                        </button>
                                        <a href="{{ route('pengurus.barang.keluar') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                <!-- Info Barang Keluar -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Informasi Transaksi</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <strong class="text-slate-700">ID Transaksi:</strong><br>
                                <code class="px-2 py-1 text-sm bg-gray-100 rounded">#{{ $barangKeluar->id }}</code>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Tanggal:</strong><br>
                                <span class="text-sm">{{ $barangKeluar->tanggal->format('d F Y') }}</span>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Tujuan:</strong><br>
                                <span class="text-sm">{{ $barangKeluar->tujuan }}</span>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Petugas:</strong><br>
                                <span class="text-sm">{{ $barangKeluar->user->name }}</span>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Dibuat:</strong><br>
                                <small class="text-slate-400">{{ $barangKeluar->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Terakhir Diupdate:</strong><br>
                                <small class="text-slate-400">{{ $barangKeluar->updated_at->format('d M Y H:i') }}</small>
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
                                @if ($barangKeluar->barang->gambar)
                                    @if (file_exists(public_path('storage/barang/' . $barangKeluar->barang->gambar)))
                                        <img src="{{ asset('storage/barang/' . $barangKeluar->barang->gambar) }}"
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
                                    <h6 class="text-sm font-semibold">{{ $barangKeluar->barang->nama }}</h6>
                                    <p class="text-xs text-slate-400">{{ $barangKeluar->barang->kode_barang }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-slate-500">Kategori</p>
                                    <p class="text-sm font-medium">{{ $barangKeluar->barang->kategori->nama }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Satuan</p>
                                    <p class="text-sm font-medium">{{ $barangKeluar->barang->satuan }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Stok Saat Ini</p>
                                    <p class="text-sm font-medium">{{ $barangKeluar->barang->stok }}
                                        {{ $barangKeluar->barang->satuan }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Harga</p>
                                    <p class="text-sm font-medium">Rp
                                        {{ number_format($barangKeluar->barang->harga, 0, ',', '.') }}</p>
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
                                <a href="{{ route('pengurus.barang.keluar.show', $barangKeluar->id) }}"
                                    class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                                </a>
                                <a href="{{ route('pengurus.barang.keluar') }}"
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
            const barangDetails = document.getElementById('barangDetails');
            const barangPlaceholder = document.getElementById('barangPlaceholder');
            const jumlahInput = document.getElementById('jumlah');
            const stokWarning = document.getElementById('stokWarning');

            let allBarangs = [];

            // Pre-select kategori and barang if editing
            const kategoriId = "{{ $barangKeluar->barang->kategori_id }}";
            const barangId = "{{ $barangKeluar->barang_id }}";

            if (kategoriSelect && kategoriId) {
                kategoriSelect.value = kategoriId;
                kategoriSelect.dispatchEvent(new Event('change'));

                // Wait for barang options to load, then select the barang
                setTimeout(() => {
                    if (barangSelect && barangId) {
                        barangSelect.value = barangId;
                        barangSelect.dispatchEvent(new Event('change'));
                    }
                }, 500);
            }

            if (kategoriSelect) {
                kategoriSelect.addEventListener('change', function() {
                    const kategoriId = this.value;

                    // Reset dropdown barang
                    barangSelect.innerHTML = '<option value="">-- Pilih Barang --</option>';
                    barangSelect.disabled = true;

                    // Hide barang details
                    if (barangDetails) barangDetails.classList.add('hidden');
                    if (barangPlaceholder) barangPlaceholder.classList.remove('hidden');

                    if (kategoriId) {
                        // Fetch barang by kategori via AJAX
                        fetch(`/pengurus/barang/keluar/get-barang-by-kategori/${kategoriId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    allBarangs = data.data;

                                    // Populate dropdown barang
                                    data.data.forEach(barang => {
                                        const option = document.createElement('option');
                                        option.value = barang.id;
                                        option.textContent = `${barang.nama} (${barang.kode_barang}) - Stok: ${barang.stok}`;
                                        option.dataset.nama = barang.nama.toLowerCase();
                                        option.dataset.kode = barang.kode_barang.toLowerCase();
                                        barangSelect.appendChild(option);
                                    });

                                    // Enable dropdown
                                    barangSelect.disabled = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }
                });
            }

            if (barangSelect) {
                barangSelect.addEventListener('change', function() {
                    const barangId = this.value;

                    if (barangId) {
                        // Find selected barang from allBarangs array
                        const barang = allBarangs.find(b => b.id == barangId);

                        if (barang) {
                            // Update image
                            const imageContainer = document.getElementById('barangImageContainer');
                            if (imageContainer) {
                                if (barang.gambar) {
                                    imageContainer.innerHTML = `<img src="/storage/barang/${barang.gambar}" class="h-12 w-12 rounded-xl object-cover border border-gray-200">`;
                                } else {
                                    imageContainer.innerHTML = `<div class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600"><i class="ni ni-box-2 text-lg text-white"></i></div>`;
                                }
                            }

                            // Update other details
                            const barangNama = document.getElementById('barangNama');
                            const barangKode = document.getElementById('barangKode');
                            const barangKategori = document.getElementById('barangKategori');
                            const barangSatuan = document.getElementById('barangSatuan');
                            const barangStok = document.getElementById('barangStok');
                            const barangHarga = document.getElementById('barangHarga');

                            if (barangNama) barangNama.textContent = barang.nama;
                            if (barangKode) barangKode.textContent = barang.kode_barang;
                            if (barangKategori) barangKategori.textContent = barang.kategori_nama;
                            if (barangSatuan) barangSatuan.textContent = barang.satuan;
                            if (barangStok) barangStok.textContent = barang.stok + ' ' + barang.satuan;
                            if (barangHarga) barangHarga.textContent = 'Rp ' + parseInt(barang.harga).toLocaleString('id-ID');

                            // Show details, hide placeholder
                            if (barangDetails) barangDetails.classList.remove('hidden');
                            if (barangPlaceholder) barangPlaceholder.classList.add('hidden');

                            // Set max value for jumlah input
                            if (jumlahInput) {
                                jumlahInput.max = barang.stok;
                                // Add event listener to check stok
                                jumlahInput.addEventListener('input', function() {
                                    if (parseInt(this.value) > parseInt(barang.stok)) {
                                        stokWarning.textContent = `Stok tidak mencukupi! Maksimal: ${barang.stok} ${barang.satuan}`;
                                        stokWarning.classList.remove('hidden');
                                        this.setCustomValidity(`Stok tidak mencukupi! Maksimal: ${barang.stok} ${barang.satuan}`);
                                    } else {
                                        stokWarning.classList.add('hidden');
                                        this.setCustomValidity('');
                                    }
                                });
                            }
                        }
                    } else {
                        // Show placeholder, hide details
                        if (barangDetails) barangDetails.classList.add('hidden');
                        if (barangPlaceholder) barangPlaceholder.classList.remove('hidden');
                        if (jumlahInput) {
                            jumlahInput.max = '';
                        }
                    }
                });
            }
        });
    </script>
@endsection
