@extends('pengurus.dashboard.layouts.app')

@section('title', 'Edit Pengajuan - Pengurus')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Edit Pengajuan Pengadaan</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Perbarui informasi pengajuan barang
                                </p>
                            </div>
                            <a href="{{ route('pengurus.pengajuan.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Informasi Pengajuan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('pengurus.pengajuan.update', $pengajuan->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                @include('pengurus.pengajuan.form', ['pengajuan' => $pengajuan])
                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Update Pengajuan
                                        </button>
                                        <a href="{{ route('pengurus.pengajuan.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                <!-- Current File -->
                @if ($pengajuan->file_pengajuan)
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                            <h6 class="mb-0">Dokumen Saat Ini</h6>
                        </div>
                        <div class="flex-auto px-0 pt-0 pb-2">
                            <div class="p-6">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                                        <span class="text-sm text-slate-700">{{ basename($pengajuan->file_pengajuan) }}</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $pengajuan->file_pengajuan) }}" target="_blank" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-slate-500">Upload dokumen baru untuk mengganti dokumen saat ini</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Info Pengajuan -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Informasi Pengajuan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-hashtag mr-2"></i>Kode Pengajuan</h6>
                                <p class="text-sm text-slate-700">{{ $pengajuan->kode_pengajuan }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-calendar-alt mr-2"></i>Tanggal Diajukan</h6>
                                <p class="text-sm text-slate-700">{{ $pengajuan->created_at->format('d F Y') }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-info-circle mr-2"></i>Status</h6>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'disetujui' => 'bg-green-100 text-green-800',
                                        'ditolak' => 'bg-red-100 text-red-800',
                                        'proses' => 'bg-blue-100 text-blue-800',
                                    ][$pengajuan->status];
                                @endphp
                                <span class="{{ $statusClass }} px-2 py-1 text-xs rounded-full font-medium">
                                    {{ ucfirst($pengajuan->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil data saldo kas
        fetch('{{ route("bendahara.kas.get-saldo") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const saldoElement = document.getElementById('saldo-info');
                    if (saldoElement) {
                        // Format saldo dengan pemisah ribuan
                        const formattedSaldo = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(data.saldo);

                        saldoElement.textContent = formattedSaldo;

                        // Tambahkan tooltip untuk memberikan informasi lebih detail
                        saldoElement.title = 'Saldo kas saat ini: ' + formattedSaldo;
                        saldoElement.classList.add('font-semibold', 'text-green-600');
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching saldo data:', error);
                const saldoElement = document.getElementById('saldo-info');
                if (saldoElement) {
                    saldoElement.textContent = 'Tidak tersedia';
                    saldoElement.classList.add('text-red-500');
                }
            });
    });
</script>
@endpush
