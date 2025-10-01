@extends('pengurus.dashboard.layouts.app')
@section('title', 'Tambah Barang Keluar - Pengurus')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Catat Barang Keluar</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Tambahkan catatan barang keluar untuk pelayanan atau kegiatan
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
                            <form method="POST" action="{{ route('pengurus.barang.keluar.store') }}" id="barangKeluarForm">
                                @csrf
                                @include('pengurus.barang.keluar.form')
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Simpan Data
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
                <!-- Info Barang -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Detail Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div id="barangDetails" class="hidden">
                                <div class="mb-4">
                                    <div class="flex items-center mb-3">
                                        <div id="barangImageContainer" class="mr-3">
                                            <!-- Placeholder for image -->
                                        </div>
                                        <div>
                                            <h6 id="barangNama" class="text-sm font-semibold"></h6>
                                            <p id="barangKode" class="text-xs text-slate-400"></p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <p class="text-xs text-slate-500">Kategori</p>
                                            <p id="barangKategori" class="text-sm font-medium"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Satuan</p>
                                            <p id="barangSatuan" class="text-sm font-medium"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Stok Saat Ini</p>
                                            <p id="barangStok" class="text-sm font-medium"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Harga</p>
                                            <p id="barangHarga" class="text-sm font-medium"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="barangPlaceholder" class="text-center py-4">
                                <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-400">Pilih barang untuk melihat detail</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tips -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Tips Pengisian</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-calendar-day mr-2"></i>Tanggal</h6>
                                <p class="text-sm text-slate-400">Isi tanggal saat barang dikeluarkan. Default adalah hari ini.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-sort-numeric-up mr-2"></i>Jumlah</h6>
                                <p class="text-sm text-slate-400">Masukkan jumlah barang yang keluar. Stok akan otomatis berkurang.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-bullseye mr-2"></i>Tujuan</h6>
                                <p class="text-sm text-slate-400">Isi tujuan pengeluaran barang, misalnya: "Pelayanan Minggu", "Kegiatan Natal", dll.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-file-alt mr-2"></i>Keterangan</h6>
                                <p class="text-sm text-slate-400">Isi keterangan tambahan jika diperlukan.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Quick Stats -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Statistik</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-wrap -mx-3">
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center border-r border-gray-200">
                                        <h4 class="font-bold text-blue-600">{{ \App\Models\Barang::count() }}</h4>
                                        <small class="text-slate-400">Total Barang</small>
                                    </div>
                                </div>
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center">
                                        <h4 class="font-bold text-red-600">{{ \App\Models\BarangKeluar::count() }}</h4>
                                        <small class="text-slate-400">Transaksi Keluar</small>
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
