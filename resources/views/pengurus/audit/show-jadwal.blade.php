@extends('pengurus.dashboard.layouts.app')
@section('title', 'Detail Jadwal Audit - Pengurus')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Detail Jadwal Audit</h6>
                                <p class="text-sm leading-normal">Informasi lengkap jadwal audit: {{ $jadwalAudit->judul }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('pengurus.audit.index') }}"
                                    class="inline-block px-6 py-3 font-bold text-center text-black uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-rose-600 to-rose-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Detail Content -->
        <div class="flex flex-wrap -mx-3">
            <!-- Main Detail -->
            <div class="flex-none w-full max-w-full px-3 lg:w-8/12 lg:flex-none">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Informasi Jadwal Audit</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-wrap -mx-3">
                                <div class="flex-none w-full max-w-full px-3 md:w-6/12">
                                    <div class="mb-4">
                                        <p class="text-xs text-slate-500 mb-1">ID Jadwal</p>
                                        <p class="text-sm font-semibold">#{{ $jadwalAudit->id }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-xs text-slate-500 mb-1">Judul</p>
                                        <p class="text-sm font-semibold">{{ $jadwalAudit->judul }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-xs text-slate-500 mb-1">Tanggal Audit</p>
                                        <p class="text-sm font-semibold">{{ $jadwalAudit->tanggal_audit->format('d F Y') }}
                                        </p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-xs text-slate-500 mb-1">Status</p>
                                        @php
                                            $statusClass = [
                                                'terjadwal' => 'bg-gradient-to-tl from-blue-600 to-cyan-400',
                                                'diproses' => 'bg-gradient-to-tl from-orange-600 to-yellow-400',
                                                'selesai' => 'bg-gradient-to-tl from-green-600 to-lime-400',
                                                'ditunda' => 'bg-gradient-to-tl from-red-600 to-rose-400',
                                            ][$jadwalAudit->status];
                                        @endphp
                                        <span
                                            class="{{ $statusClass }} px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                            {{ ucfirst($jadwalAudit->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-none w-full max-w-full px-3 md:w-6/12">
                                    <div class="mb-4">
                                        <p class="text-xs text-slate-500 mb-1">Petugas</p>
                                        <p class="text-sm font-semibold">{{ $jadwalAudit->user->name }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-xs text-slate-500 mb-1">Dibuat</p>
                                        <p class="text-sm font-semibold">{{ $jadwalAudit->created_at->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-xs text-slate-500 mb-1">Terakhir Diupdate</p>
                                        <p class="text-sm font-semibold">{{ $jadwalAudit->updated_at->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex-none w-full max-w-full px-3">
                                    <div class="mb-2">
                                        <p class="text-xs text-slate-500 mb-1">Deskripsi</p>
                                        <p class="text-sm">{{ $jadwalAudit->deskripsi ?: '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Barang -->
                <div
                    class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Informasi Barang</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                @if ($jadwalAudit->barang->gambar)
                                    @if (file_exists(public_path('storage/barang/' . $jadwalAudit->barang->gambar)))
                                        <img src="{{ asset('storage/barang/' . $jadwalAudit->barang->gambar) }}"
                                            class="mr-4 h-16 w-16 rounded-xl object-cover border border-gray-200">
                                    @else
                                        <div
                                            class="mr-4 h-16 w-16 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600 flex items-center justify-center">
                                            <i class="ni ni-box-2 text-xl text-white"></i>
                                        </div>
                                    @endif
                                @else
                                    <div
                                        class="mr-4 h-16 w-16 rounded-xl bg-gradient-to-tl from-gray-400 to-gray-600 flex items-center justify-center">
                                        <i class="ni ni-box-2 text-xl text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="text-lg font-semibold">{{ $jadwalAudit->barang->nama }}</h5>
                                    <p class="text-sm text-slate-400">{{ $jadwalAudit->barang->kode_barang }}</p>
                                    @if ($jadwalAudit->barang->deskripsi)
                                        <p class="text-sm mt-1">{{ $jadwalAudit->barang->deskripsi }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-slate-500 mb-1">Kategori</p>
                                    <p class="text-sm font-medium">{{ $jadwalAudit->barang->kategori->nama }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-slate-500 mb-1">Satuan</p>
                                    <p class="text-sm font-medium">{{ $jadwalAudit->barang->satuan }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-slate-500 mb-1">Stok</p>
                                    <p class="text-sm font-medium">{{ $jadwalAudit->barang->stok }}
                                        {{ $jadwalAudit->barang->satuan }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-xs text-slate-500 mb-1">Status Barang</p>
                                    <p class="text-sm font-medium">{{ ucfirst($jadwalAudit->barang->status) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="flex-none w-full max-w-full px-3 lg:w-4/12 lg:flex-none">
                <!-- Status Card -->
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Status Jadwal</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="text-center mb-4">
                                @php
                                    $statusIcon = [
                                        'terjadwal' => 'fa-calendar-check',
                                        'diproses' => 'fa-wrench',
                                        'selesai' => 'fa-check-circle',
                                        'ditunda' => 'fa-times-circle',
                                    ][$jadwalAudit->status];

                                    $statusColor = [
                                        'terjadwal' => 'from-blue-600 to-cyan-400',
                                        'diproses' => 'from-orange-600 to-yellow-400',
                                        'selesai' => 'from-green-600 to-lime-400',
                                        'ditunda' => 'from-red-600 to-rose-400',
                                    ][$jadwalAudit->status];

                                    $statusText = [
                                        'terjadwal' => 'Terjadwal',
                                        'diproses' => 'Sedang Diproses',
                                        'selesai' => 'Selesai',
                                        'ditunda' => 'Ditunda',
                                    ][$jadwalAudit->status];
                                @endphp
                                <div
                                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-tl {{ $statusColor }} mb-3">
                                    <i class="fas {{ $statusIcon }} text-white text-2xl"></i>
                                </div>
                                <h5 class="text-lg font-semibold">{{ ucfirst($jadwalAudit->status) }}</h5>
                                <p class="text-sm text-slate-400">{{ $statusText }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between mb-2">
                                    <span class="text-xs text-slate-500">Tanggal Audit</span>
                                    <span
                                        class="text-xs font-medium">{{ $jadwalAudit->tanggal_audit->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-slate-500">Petugas</span>
                                    <span class="text-xs font-medium">{{ $jadwalAudit->user->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Aksi Cepat</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-col space-y-2">
                                @if ($jadwalAudit->status === 'terjadwal' || $jadwalAudit->status === 'diproses')
                                    <button
                                        onclick="openSelesaikanModal({{ $jadwalAudit->id }}, '{{ $jadwalAudit->judul }}')"
                                        class="w-full inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-green-600 to-lime-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                        <i class="fas fa-check mr-2"></i>Selesaikan Audit
                                    </button>
                                @endif
                                <a href="{{ route('pengurus.audit.index') }}"
                                    class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-list mr-2"></i>Lihat Semua Jadwal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Selesaikan Jadwal Audit -->
    <div class="fixed inset-0 z-50 hidden overflow-y-auto" id="selesaikanModal">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeSelesaikanModal()">
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block p-6 my-8 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="selesaikanForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Selesaikan Jadwal Audit
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Lengkapi informasi untuk menyelesaikan jadwal audit <span
                                    id="jadwal-title" class="font-semibold"></span></p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Barang</label>
                            <select name="kondisi" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                                <option value="hilang">Hilang</option>
                                <option value="tidak_terpakai">Tidak Terpakai</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <textarea name="keterangan" rows="3" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Jelaskan kondisi barang secara detail..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Barang (Opsional)</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="foto_selesaikan"
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk
                                                upload</span></p>
                                        <p class="text-xs text-gray-500">PNG, JPG atau JPEG (MAX. 2MB)</p>
                                    </div>
                                    <input id="foto_selesaikan" name="foto" type="file" class="hidden"
                                        accept="image/*" />
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button type="submit"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-black bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:col-start-2 sm:text-sm">
                            Selesaikan
                        </button>
                        <button type="button" onclick="closeSelesaikanModal()"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openSelesaikanModal(id, title) {
            document.getElementById('jadwal-title').textContent = title;
            document.getElementById('selesaikanForm').action =
                "{{ route('pengurus.audit.selesaikan-jadwal', ['jadwalAudit' => ':id']) }}".replace(':id', id);
            document.getElementById('selesaikanModal').classList.remove('hidden');
        }

        function closeSelesaikanModal() {
            document.getElementById('selesaikanModal').classList.add('hidden');
            document.getElementById('selesaikanForm').reset();
        }

        // Handle form submission
        document.addEventListener('DOMContentLoaded', function() {
            const selesaikanForm = document.getElementById('selesaikanForm');
            if (selesaikanForm) {
                selesaikanForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const action = this.action;

                    fetch(action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                closeSelesaikanModal();
                                window.location.reload();
                            } else {
                                alert(data.message ||
                                    'Terjadi kesalahan saat menyelesaikan jadwal audit');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menyelesaikan jadwal audit');
                        });
                });
            }
        });
    </script>
@endsection
