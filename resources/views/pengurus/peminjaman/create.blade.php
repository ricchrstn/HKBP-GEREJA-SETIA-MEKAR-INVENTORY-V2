@extends('pengurus.dashboard.layouts.app')
@section('title', 'Tambah Peminjaman - Pengurus')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Catat Peminjaman Barang</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Tambahkan catatan peminjaman barang gereja
                                </p>
                            </div>
                            <a href="{{ route('pengurus.peminjaman.index') }}" class="inline-block px-6 py-3 font-bold text-center text-grey uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Informasi Peminjaman</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('pengurus.peminjaman.store') }}" id="peminjamanForm">
                                @csrf
                                @include('pengurus.peminjaman.form')
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Simpan Data
                                        </button>
                                        <a href="{{ route('pengurus.peminjaman.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                                <h6 class="text-blue-600"><i class="fas fa-calendar-day mr-2"></i>Tanggal Pinjam</h6>
                                <p class="text-sm text-slate-400">Isi tanggal saat barang dipinjam. Default adalah hari ini.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-calendar-check mr-2"></i>Tanggal Kembali</h6>
                                <p class="text-sm text-slate-400">Isi tanggal perkiraan barang dikembalikan.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-sort-numeric-up mr-2"></i>Jumlah</h6>
                                <p class="text-sm text-slate-400">Masukkan jumlah barang yang dipinjam. Stok akan otomatis berkurang.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-user mr-2"></i>Peminjam</h6>
                                <p class="text-sm text-slate-400">Isi nama lengkap peminjam barang.</p>
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
                                        <h4 class="font-bold text-purple-600">{{ \App\Models\Peminjaman::count() }}</h4>
                                        <small class="text-slate-400">Peminjaman Aktif</small>
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

            // Event listener for kategori change
            if (kategoriSelect) {
                kategoriSelect.addEventListener('change', function() {
                    const kategoriId = this.value;
                    
                    // Reset barang select
                    barangSelect.innerHTML = '<option value="">-- Pilih Barang --</option>';
                    barangSelect.disabled = true;
                    
                    // Hide barang details
                    barangDetails.classList.add('hidden');
                    barangPlaceholder.classList.remove('hidden');
                    
                    if (kategoriId) {
                        // Fetch barang by kategori via AJAX
                        fetch(`/pengurus/peminjaman/get-barang-by-kategori/${kategoriId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Populate barang dropdown
                                    data.data.forEach(barang => {
                                        const option = document.createElement('option');
                                        option.value = barang.id;
                                        option.textContent = `${barang.nama} (${barang.kode_barang}) - Stok: ${barang.stok}`;
                                        barangSelect.appendChild(option);
                                    });
                                    
                                    // Enable barang select
                                    barangSelect.disabled = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }
                });
            }

            // Event listener for barang change
            if (barangSelect) {
                barangSelect.addEventListener('change', function() {
                    const barangId = this.value;

                    if (barangId) {
                        // Fetch barang details via AJAX
                        fetch(`/pengurus/peminjaman/get-barang-details/${barangId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const barang = data.data;

                                    // Update image
                                    const imageContainer = document.getElementById('barangImageContainer');
                                    if (barang.gambar) {
                                        imageContainer.innerHTML = `<img src="/storage/barang/${barang.gambar}" class="h-12 w-12 rounded-xl object-cover border border-gray-200">`;
                                    } else {
                                        imageContainer.innerHTML = `<div class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600"><i class="ni ni-box-2 text-lg text-white"></i></div>`;
                                    }

                                    // Update other details
                                    document.getElementById('barangNama').textContent = barang.nama;
                                    document.getElementById('barangKode').textContent = barang.kode_barang;
                                    document.getElementById('barangKategori').textContent = barang.kategori;
                                    document.getElementById('barangSatuan').textContent = barang.satuan;
                                    document.getElementById('barangStok').textContent = barang.stok + ' ' + barang.satuan;
                                    document.getElementById('barangHarga').textContent = 'Rp ' + parseInt(barang.harga).toLocaleString('id-ID');

                                    // Show details, hide placeholder
                                    barangDetails.classList.remove('hidden');
                                    barangPlaceholder.classList.add('hidden');

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
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    } else {
                        // Show placeholder, hide details
                        barangDetails.classList.add('hidden');
                        barangPlaceholder.classList.remove('hidden');
                        if (jumlahInput) {
                            jumlahInput.max = '';
                        }
                    }
                });
            }

            // Set minimum date for tanggal_pinjam to today
            const tanggalPinjamInput = document.getElementById('tanggal_pinjam');
            if (tanggalPinjamInput) {
                const today = new Date().toISOString().split('T')[0];
                tanggalPinjamInput.min = today;

                // Set default value to today if empty
                if (!tanggalPinjamInput.value) {
                    tanggalPinjamInput.value = today;
                }
            }

            // Set minimum date for tanggal_kembali to tanggal_pinjam
            const tanggalKembaliInput = document.getElementById('tanggal_kembali');
            if (tanggalPinjamInput && tanggalKembaliInput) {
                tanggalPinjamInput.addEventListener('change', function() {
                    tanggalKembaliInput.min = this.value;
                    if (tanggalKembaliInput.value && tanggalKembaliInput.value < this.value) {
                        tanggalKembaliInput.value = this.value;
                    }
                });
            }
        });
    </script>
@endsection