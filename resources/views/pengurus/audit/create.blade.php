@extends('pengurus.dashboard.layouts.app')

@section('title', 'Tambah Audit - Pengurus')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Tambah Audit Barang Baru</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Catat hasil audit internal kondisi barang inventori
                                </p>
                            </div>
                            <a href="{{ route('pengurus.audit.index') }}" class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Informasi Audit Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('pengurus.audit.store') }}" enctype="multipart/form-data">
                                @csrf
                                @include('pengurus.audit.form')
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Simpan Audit
                                        </button>
                                        <a href="{{ route('pengurus.audit.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Tips Audit Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-box mr-2"></i>Barang</h6>
                                <p class="text-sm text-slate-400">Pilih barang yang akan diaudit. Pastikan barang tersebut ada dalam daftar inventori.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-calendar-alt mr-2"></i>Tanggal Audit</h6>
                                <p class="text-sm text-slate-400">Isi tanggal saat audit dilakukan. Default adalah tanggal hari ini.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-clipboard-check mr-2"></i>Kondisi</h6>
                                <p class="text-sm text-slate-400">Pilih kondisi aktual barang setelah dilakukan audit. Pilih dengan jujur dan teliti.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-comment-alt mr-2"></i>Keterangan</h6>
                                <p class="text-sm text-slate-400">Berikan keterangan tambahan mengenai kondisi barang atau hasil audit.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-camera mr-2"></i>Foto</h6>
                                <p class="text-sm text-slate-400">Upload foto barang sebagai bukti audit. Foto akan membantu verifikasi kondisi barang.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Statistik Audit</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-wrap -mx-3">
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center border-r border-gray-200">
                                        <h4 class="font-bold text-blue-600">{{ \App\Models\Audit::where('user_id', auth()->id())->count() }}</h4>
                                        <small class="text-slate-400">Total Audit</small>
                                    </div>
                                </div>
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center">
                                        <h4 class="font-bold text-green-600">{{ \App\Models\Audit::where('user_id', auth()->id())->where('kondisi', 'baik')->count() }}</h4>
                                        <small class="text-slate-400">Kondisi Baik</small>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap -mx-3 mt-4">
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center border-r border-gray-200">
                                        <h4 class="font-bold text-orange-600">{{ \App\Models\Audit::where('user_id', auth()->id())->where('kondisi', 'rusak')->count() }}</h4>
                                        <small class="text-slate-400">Kondisi Rusak</small>
                                    </div>
                                </div>
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center">
                                        <h4 class="font-bold text-red-600">{{ \App\Models\Audit::where('user_id', auth()->id())->where('kondisi', 'hilang')->count() }}</h4>
                                        <small class="text-slate-400">Kondisi Hilang</small>
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
    const kategoriSelect = document.getElementById('kategoriSelect');
    const barangSelect = document.getElementById('barangSelect');

    // Simpan semua data barang untuk akses cepat
    const allBarangs = @json($barangs);

    // Handle perubahan kategori
    kategoriSelect.addEventListener('change', function() {
        const kategoriId = this.value;

        // Reset dropdown barang
        barangSelect.innerHTML = '<option value="">-- Pilih Barang --</option>';

        if (kategoriId) {
            // Filter barang berdasarkan kategori
            const filteredBarangs = allBarangs.filter(barang => barang.kategori_id == kategoriId);

            // Tambahkan opsi barang
            filteredBarangs.forEach(barang => {
                const option = document.createElement('option');
                option.value = barang.id;
                option.textContent = `${barang.nama} (${barang.kode_barang})`;
                barangSelect.appendChild(option);
            });

            // Aktifkan dropdown barang
            barangSelect.disabled = false;

            // Jika ada hasil, fokus ke dropdown barang
            if (filteredBarangs.length > 0) {
                setTimeout(() => barangSelect.focus(), 100);
            }
        } else {
            // Nonaktifkan dropdown barang jika tidak ada kategori yang dipilih
            barangSelect.disabled = true;
        }
    });

    // Jika kategori sudah terpilih saat halaman dimuat (misal saat edit)
    if (kategoriSelect.value) {
        // Trigger change event untuk mengisi dropdown barang
        kategoriSelect.dispatchEvent(new Event('change'));

        // Set selected value untuk barang jika ada
        const selectedBarangId = "{{ old('barang_id', $audit->barang_id ?? '') }}";
        if (selectedBarangId) {
            setTimeout(() => {
                barangSelect.value = selectedBarangId;
            }, 100);
        }
    }
});
</script>
@endsection
