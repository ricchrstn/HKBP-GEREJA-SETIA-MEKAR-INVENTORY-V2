@extends('pengurus.dashboard.layouts.app')
@section('title', 'Tambah Perawatan - Pengurus')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Catat Perawatan Barang</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Tambahkan catatan perawatan barang gereja
                                </p>
                            </div>
                            <a href="{{ route('pengurus.perawatan.index') }}" class="inline-block px-6 py-3 font-bold text-center text-grey uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Informasi Perawatan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('pengurus.perawatan.store') }}" id="perawatanForm">
                                @csrf
                                @include('pengurus.perawatan.form')
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Simpan Data
                                        </button>
                                        <a href="{{ route('pengurus.perawatan.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                                            <p class="text-xs text-slate-500">Status</p>
                                            <p id="barangStatus" class="text-sm font-medium"></p>
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
                                <h6 class="text-blue-600"><i class="fas fa-calendar-day mr-2"></i>Tanggal Perawatan</h6>
                                <p class="text-sm text-slate-400">Isi tanggal saat perawatan dilakukan. Default adalah hari ini.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-tools mr-2"></i>Jenis Perawatan</h6>
                                <p class="text-sm text-slate-400">Isi jenis perawatan yang dilakukan, misalnya: "Perbaikan", "Pengecatan", dll.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-money-bill-wave mr-2"></i>Biaya</h6>
                                <p class="text-sm text-slate-400">Isi biaya perawatan jika ada. Default adalah 0.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-info-circle mr-2"></i>Status Barang</h6>
                                <p class="text-sm text-slate-400">Status barang akan otomatis berubah menjadi "Perawatan" saat dicatat.</p>
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
                                        <h4 class="font-bold text-orange-600">{{ \App\Models\Perawatan::where('status', 'proses')->count() }}</h4>
                                        <small class="text-slate-400">Perawatan Proses</small>
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

            // Event listener untuk kategori
            if (kategoriSelect) {
                kategoriSelect.addEventListener('change', function() {
                    const kategoriId = this.value;

                    // Reset dropdown barang
                    barangSelect.innerHTML = '<option value="">-- Pilih Barang --</option>';
                    barangSelect.disabled = true;

                    // Hide barang details
                    barangDetails.classList.add('hidden');
                    barangPlaceholder.classList.remove('hidden');

                    if (kategoriId) {
                        // Fetch barang by kategori via AJAX
                        fetch(`/pengurus/perawatan/get-barang-by-kategori/${kategoriId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Populate barang dropdown
                                    data.data.forEach(barang => {
                                        const option = document.createElement('option');
                                        option.value = barang.id;
                                        option.textContent = `${barang.nama} (Stok: ${barang.stok})`;
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

            // Event listener untuk barang
            if (barangSelect) {
                barangSelect.addEventListener('change', function() {
                    const barangId = this.value;

                    if (barangId) {
                        // Fetch barang details via AJAX
                        fetch(`/pengurus/perawatan/get-barang-details/${barangId}`)
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

                                    // Update status with color
                                    const statusElement = document.getElementById('barangStatus');
                                    statusElement.textContent = ucfirst(barang.status);

                                    // Remove all status classes
                                    statusElement.classList.remove('text-green-600', 'text-orange-600', 'text-red-600');

                                    // Add appropriate color class based on status
                                    if (barang.status === 'aktif') {
                                        statusElement.classList.add('text-green-600');
                                    } else if (barang.status === 'perawatan') {
                                        statusElement.classList.add('text-orange-600');
                                    } else {
                                        statusElement.classList.add('text-red-600');
                                    }

                                    // Show details, hide placeholder
                                    barangDetails.classList.remove('hidden');
                                    barangPlaceholder.classList.add('hidden');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    } else {
                        // Show placeholder, hide details
                        barangDetails.classList.add('hidden');
                        barangPlaceholder.classList.remove('hidden');
                    }
                });
            }

            // Set minimum date for tanggal_perawatan to today
            const tanggalPerawatanInput = document.getElementById('tanggal_perawatan');
            if (tanggalPerawatanInput) {
                const today = new Date().toISOString().split('T')[0];
                tanggalPerawatanInput.min = today;

                // Set default value to today if empty
                if (!tanggalPerawatanInput.value) {
                    tanggalPerawatanInput.value = today;
                }
            }
        });
    </script>
@endsection
