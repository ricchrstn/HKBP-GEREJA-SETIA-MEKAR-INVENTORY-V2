@extends('admin.dashboard.layouts.app')

@section('title', 'Laporan - Admin')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header Section -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-5 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Laporan Sistem</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Generate laporan inventaris dan
                                    keuangan gereja</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button onclick="window.print()"
                                    class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-print mr-2"></i>
                                    Print
                                </button>
                                <button onclick="exportData('pdf')"
                                    class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-red-600 to-red-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-file-pdf mr-2"></i>
                                    PDF
                                </button>
                                <button onclick="exportData('excel')"
                                    class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-green-600 to-green-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="fas fa-file-excel mr-2"></i>
                                    Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="flex flex-wrap items-center justify-between px-6 pt-4 pb-2 bg-gray-50 rounded-t-xl">
            <!-- Jenis Laporan di kiri -->
            <form id="filterForm" method="GET" action="{{ route('admin.laporan.index') }}" class="flex items-center">
                <div class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Laporan</label>
                        <select name="jenis_laporan" id="jenisLaporan"
                            class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="barang_masuk_keluar"
                                {{ request('jenis_laporan') == 'barang_masuk_keluar' ? 'selected' : '' }}>
                                Barang Masuk & Keluar
                            </option>
                            <option value="peminjaman" {{ request('jenis_laporan') == 'peminjaman' ? 'selected' : '' }}>
                                Peminjaman
                            </option>
                            <option value="perawatan" {{ request('jenis_laporan') == 'perawatan' ? 'selected' : '' }}>
                                Perawatan
                            </option>
                            <option value="audit" {{ request('jenis_laporan') == 'audit' ? 'selected' : '' }}">Audit
                            </option>
                        </select>
                    </div>
                </div>
            </form>

            <!-- Filter Tanggal dan Status di kanan -->
            <div class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                        class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                        class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Table Laporan -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col mb-6 bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Data Laporan</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    <span class="font-medium text-blue-600">{{ $laporanData->count() }}</span> dari
                                    <span class="font-medium text-slate-600">{{ $laporanData->total() }}</span> data
                                    ditemukan
                                </p>
                            </div>
                            @if (request()->hasAny(['jenis_laporan', 'tanggal_mulai', 'tanggal_selesai', 'status']))
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-slate-500">Filter aktif:</span>
                                    @if (request('jenis_laporan'))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-file-alt mr-1"></i>
                                            {{ request('jenis_laporan') == 'barang_masuk_keluar' ? 'Barang Masuk & Keluar' : ucfirst(str_replace('_', ' ', request('jenis_laporan'))) }}
                                        </span>
                                    @endif
                                    @if (request('tanggal_mulai') && request('tanggal_selesai'))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ date('d/m/Y', strtotime(request('tanggal_mulai'))) }} -
                                            {{ date('d/m/Y', strtotime(request('tanggal_selesai'))) }}
                                        </span>
                                    @endif
                                    @if (request('status'))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ request('status') }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">ID</th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Jenis
                                        </th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Nama
                                            Barang</th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Tanggal
                                        </th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Jumlah
                                        </th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Petugas
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($laporanData as $data)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-2 align-middle">
                                                <span class="text-xs font-semibold">{{ $data->id }}</span>
                                            </td>
                                            <td class="p-2 align-middle">
                                                @if (isset($data->jenis_laporan))
                                                    @if ($data->jenis_laporan == 'barang_masuk')
                                                        <span
                                                            class="bg-gradient-to-tl from-green-600 to-green-400 px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                            Barang Masuk
                                                        </span>
                                                    @elseif($data->jenis_laporan == 'barang_keluar')
                                                        <span
                                                            class="bg-gradient-to-tl from-red-600 to-red-400 px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                            Barang Keluar
                                                        </span>
                                                    @endif
                                                @elseif (isset($data->jenis))
                                                    @if ($data->jenis == 'peminjaman')
                                                        <span
                                                            class="bg-gradient-to-tl from-blue-600 to-blue-400 px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                            Peminjaman
                                                        </span>
                                                    @elseif($data->jenis == 'perawatan')
                                                        <span
                                                            class="bg-gradient-to-tl from-yellow-600 to-yellow-400 px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                            Perawatan
                                                        </span>
                                                    @elseif($data->jenis == 'audit')
                                                        <span
                                                            class="bg-gradient-to-tl from-purple-600 to-purple-400 px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                            Audit
                                                        </span>
                                                    @endif
                                                @else
                                                    <span
                                                        class="bg-gradient-to-tl from-gray-600 to-gray-400 px-2.5 py-1.4 text-xs rounded-1.8 text-black font-bold uppercase">
                                                        N/A
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-2 align-middle">
                                                <h6 class="mb-0 text-sm font-semibold">{{ $data->barang->nama ?? 'N/A' }}
                                                </h6>
                                                <p class="mb-0 text-xs text-slate-400">
                                                    {{ $data->barang->kode_barang ?? 'N/A' }}</p>
                                            </td>
                                            <td class="p-2 align-middle">
                                                @if (isset($data->tanggal))
                                                    <span
                                                        class="text-sm">{{ date('d/m/Y', strtotime($data->tanggal)) }}</span>
                                                @elseif(isset($data->tanggal_pinjam))
                                                    <span
                                                        class="text-sm">{{ $data->tanggal_pinjam->format('d/m/Y') }}</span>
                                                @elseif(isset($data->tanggal_perawatan))
                                                    <span
                                                        class="text-sm">{{ $data->tanggal_perawatan->format('d/m/Y') }}</span>
                                                @elseif(isset($data->tanggal_audit))
                                                    <span
                                                        class="text-sm">{{ $data->tanggal_audit->format('d/m/Y') }}</span>
                                                @else
                                                    <span class="text-sm">N/A</span>
                                                @endif
                                            </td>
                                            <td class="p-2 align-middle">
                                                <span class="text-sm font-semibold">{{ $data->jumlah ?? 1 }}
                                                    {{ $data->barang->satuan ?? '' }}</span>
                                            </td>
                                            <td class="p-2 align-middle">
                                                @if (isset($data->status))
                                                    @php
                                                        $statusClass =
                                                            [
                                                                'Aktif' => 'from-green-600 to-lime-400',
                                                                'Diproses' => 'from-blue-600 to-blue-400',
                                                                'Selesai' => 'from-green-600 to-green-400',
                                                                'Dipinjam' => 'from-red-600 to-red-400',
                                                                'Dikembalikan' => 'from-blue-600 to-blue-400',
                                                                'pending' => 'from-blue-600 to-blue-400',
                                                                'disetujui' => 'from-green-600 to-green-400',
                                                                'ditolak' => 'from-red-600 to-red-400',
                                                                'proses' => 'from-blue-600 to-blue-400',
                                                            ][$data->status] ?? 'from-green-600 to-green-400';
                                                    @endphp
                                                    <span
                                                        class="bg-gradient-to-tl {{ $statusClass }} px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                        {{ $data->status }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="bg-gradient-to-tl from-gray-600 to-gray-400 px-2.5 py-1.4 text-xs rounded-1.8 text-black font-bold uppercase">
                                                        N/A
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-2 align-middle">
                                                <div class="flex items-center">
                                                    @if ($data->user && $data->user->gambar)
                                                        <img src="{{ asset('storage/users/' . $data->user->gambar) }}"
                                                            class="w-6 h-6 rounded-full mr-2 object-cover">
                                                    @else
                                                        <div
                                                            class="w-6 h-6 rounded-full bg-gradient-to-tl from-gray-400 to-gray-600 mr-2 flex items-center justify-center">
                                                            <i class="fas fa-user text-xs text-white"></i>
                                                        </div>
                                                    @endif
                                                    <span
                                                        class="text-sm font-semibold">{{ $data->user->name ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="p-8 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="mb-4 text-gray-400">
                                                        <i class="fas fa-file-alt text-6xl"></i>
                                                    </div>
                                                    <h6 class="text-lg font-semibold text-gray-500 mb-2">
                                                        @if (request()->hasAny(['jenis_laporan', 'tanggal_mulai', 'tanggal_selesai', 'status']))
                                                            Tidak ditemukan data laporan yang sesuai
                                                        @else
                                                            Belum ada data laporan
                                                        @endif
                                                    </h6>
                                                    <p class="text-sm text-gray-400 mb-4">
                                                        @if (request()->hasAny(['jenis_laporan', 'tanggal_mulai', 'tanggal_selesai', 'status']))
                                                            Coba ubah filter atau rentang tanggal
                                                        @else
                                                            Belum ada aktivitas yang tercatat dalam sistem
                                                        @endif
                                                    </p>
                                                    <button onclick="resetFilter()"
                                                        class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-gray-400 to-gray-600 rounded-lg shadow-md hover:scale-102 transition-all">
                                                        <i class="fas fa-redo mr-2"></i>
                                                        Reset Filter
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if ($laporanData->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Menampilkan <span class="font-medium">{{ $laporanData->firstItem() }}</span> hingga
                                    <span class="font-medium">{{ $laporanData->lastItem() }}</span> dari
                                    <span class="font-medium">{{ $laporanData->total() }}</span> hasil
                                </div>
                                <div class="flex space-x-2">
                                    {{ $laporanData->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function resetFilter() {
            document.getElementById('filterForm').reset();
            document.getElementById('filterForm').submit();
        }

        function exportData(format) {
            const form = document.getElementById('filterForm');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'export';
            input.value = format;
            form.appendChild(input);
            form.submit();
            form.removeChild(input);
        }

        // Auto submit form when jenis laporan changes
        document.getElementById('jenisLaporan').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    </script>
@endsection
