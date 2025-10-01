@extends('pengurus.dashboard.layouts.app')
@section('title', 'Audit Mandiri - Pengurus')
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
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Audit Mandiri</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Kelola audit internal kondisi barang
                                    inventori</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('pengurus.audit.create') }}"
                                    class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="ni ni-fat-add mr-2"></i>
                                    Tambah Audit Mandiri
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="flex flex-wrap items-center justify-between px-6 pt-4 pb-2 bg-gray-50 rounded-t-xl">
            <!-- Search di kiri -->
            <form id="searchForm" method="GET" action="{{ route('pengurus.audit.index') }}" class="flex items-center">
                <div class="relative flex flex-wrap items-stretch transition-all rounded-lg ease-soft">
                    <span class="absolute z-50 flex items-center h-full pl-2 text-slate-500">
                        <i class="fas fa-search text-sm"></i>
                    </span>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                        class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        placeholder="Cari audit atau barang..." />
                </div>
            </form>
            <!-- Filter di kanan -->
            <form id="filterForm" method="GET" action="{{ route('pengurus.audit.index') }}"
                class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <select name="kondisi" id="kondisiFilter"
                        class="w-full px-3 py-2.5 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out appearance-none bg-white bg-no-repeat bg-right bg-[length:16px] pr-10"
                        style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e');">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak" {{ request('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="hilang" {{ request('kondisi') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                        <option value="tidak_terpakai" {{ request('kondisi') == 'tidak_terpakai' ? 'selected' : '' }}>Tidak
                            Terpakai</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="date" name="tanggal" id="tanggalFilter" value="{{ request('tanggal') }}"
                            class="w-full px-3 py-2.5 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out appearance-none">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Jadwal Audit dari Admin -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col mb-6 bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Jadwal Audit dari Admin</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    <span class="font-medium text-blue-600">{{ $jadwalAudits->count() }}</span> dari
                                    <span class="font-medium text-slate-600">{{ $jadwalAudits->total() }}</span> jadwal
                                    audit ditemukan
                                </p>
                            </div>
                            @if (request()->hasAny(['search', 'tanggal']))
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-slate-500">Filter aktif:</span>
                                    @if (request('search'))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-search mr-1"></i>
                                            "{{ request('search') }}"
                                        </span>
                                    @endif
                                    @if (request('tanggal'))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ date('d M Y', strtotime(request('tanggal'))) }}
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
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Judul &
                                            Barang</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">
                                            Tanggal Audit</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Status
                                        </th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($jadwalAudits as $jadwalAudit)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-2">
                                                <div class="flex px-2 py-1">
                                                    <div>
                                                        <div
                                                            class="inline-flex items-center justify-center mr-4 h-12 w-12 rounded-xl bg-gradient-to-tl from-purple-700 to-pink-500">
                                                            <i class="fas fa-calendar-check text-white text-lg"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm font-semibold">{{ $jadwalAudit->judul }}
                                                        </h6>
                                                        <p class="mb-0 text-xs text-slate-400">
                                                            {{ $jadwalAudit->barang->nama }}</p>
                                                        @if ($jadwalAudit->deskripsi)
                                                            <p class="mb-0 text-xs text-slate-400">
                                                                {{ Str::limit($jadwalAudit->deskripsi, 50) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2 text-center">
                                                <span
                                                    class="text-sm font-semibold text-slate-700">{{ $jadwalAudit->tanggal_audit->format('d M Y') }}</span>
                                            </td>
                                            <td class="p-2 text-center">
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
                                            </td>
                                            <td class="p-2 text-center">
                                                <div class="flex justify-center items-center space-x-2">
                                                    @if ($jadwalAudit->status === 'terjadwal' || $jadwalAudit->status === 'diproses')
                                                        <button
                                                            onclick="console.log('Button clicked for jadwal audit ID:', {{ $jadwalAudit->id }}); openSelesaikanModal({{ $jadwalAudit->id }}, '{{ addslashes($jadwalAudit->judul) }}')"
                                                            class="text-green-500 hover:text-green-700 p-2 rounded-lg hover:bg-green-50 transition-all"
                                                            title="Selesaikan Audit">
                                                            <i class="fas fa-check text-sm"></i>
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('pengurus.audit.show-jadwal', $jadwalAudit->id) }}"
                                                        class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-all"
                                                        title="Detail Jadwal">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="p-8 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="mb-4 text-gray-400">
                                                        <i class="fas fa-calendar-times text-6xl"></i>
                                                    </div>
                                                    <h6 class="text-lg font-semibold text-gray-500 mb-2">
                                                        Tidak ada jadwal audit
                                                    </h6>
                                                    <p class="text-sm text-gray-400 mb-4">
                                                        Anda tidak memiliki jadwal audit dari admin saat ini
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Pagination untuk Jadwal Audit -->
                    @if ($jadwalAudits->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Menampilkan <span class="font-medium">{{ $jadwalAudits->firstItem() }}</span> hingga
                                    <span class="font-medium">{{ $jadwalAudits->lastItem() }}</span> dari
                                    <span class="font-medium">{{ $jadwalAudits->total() }}</span> hasil
                                </div>
                                <div class="flex space-x-2">
                                    {{ $jadwalAudits->withQueryString()->links('vendor.pagination.tailwind', ['pageName' => 'jadwal_page']) }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Table Audit Mandiri -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col mb-6 bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Daftar Audit Mandiri</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    <span class="font-medium text-blue-600">{{ $auditsMandiri->count() }}</span> dari
                                    <span class="font-medium text-slate-600">{{ $auditsMandiri->total() }}</span> audit
                                    ditemukan
                                </p>
                            </div>
                            @if (request()->hasAny(['search', 'kondisi', 'tanggal']))
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-slate-500">Filter aktif:</span>
                                    @if (request('search'))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-search mr-1"></i>
                                            "{{ request('search') }}"
                                        </span>
                                    @endif
                                    @if (request('kondisi'))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-filter mr-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', request('kondisi'))) }}
                                        </span>
                                    @endif
                                    @if (request('tanggal'))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ date('d M Y', strtotime(request('tanggal'))) }}
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
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Barang
                                        </th>
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Tanggal
                                            Audit</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">
                                            Kondisi</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">
                                            Status</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($auditsMandiri as $audit)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-2">
                                                <div class="flex px-2 py-1">
                                                    <div>
                                                        <div
                                                            class="inline-flex items-center justify-center mr-4 h-12 w-12 rounded-xl bg-gradient-to-tl from-purple-700 to-pink-500">
                                                            <i class="fas fa-box text-white text-lg"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm font-semibold">{{ $audit->barang->nama }}
                                                        </h6>
                                                        <p class="mb-0 text-xs text-slate-400">
                                                            {{ $audit->barang->kode_barang }}</p>
                                                        @if ($audit->keterangan)
                                                            <p class="mb-0 text-xs text-slate-400">
                                                                {{ Str::limit($audit->keterangan, 50) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                <span
                                                    class="text-sm font-semibold text-slate-700">{{ $audit->tanggal_audit->format('d M Y') }}</span>
                                            </td>
                                            <td class="p-2 text-center">
                                                @php
                                                    $kondisiClass = [
                                                        'baik' => 'bg-gradient-to-tl from-green-600 to-lime-400',
                                                        'rusak' => 'bg-gradient-to-tl from-red-600 to-yellow-400',
                                                        'hilang' => 'bg-gradient-to-tl from-red-600 to-rose-400',
                                                        'tidak_terpakai' =>
                                                            'bg-gradient-to-tl from-gray-600 to-slate-400',
                                                    ][$audit->kondisi];
                                                @endphp
                                                <span
                                                    class="{{ $kondisiClass }} px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                    {{ ucfirst(str_replace('_', ' ', $audit->kondisi)) }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center">
                                                <span
                                                    class="bg-gradient-to-tl from-blue-600 to-cyan-400 px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                    {{ ucfirst($audit->status) }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center">
                                                <div class="flex justify-center items-center space-x-2">
                                                    <a href="{{ route('pengurus.audit.show', $audit->id) }}"
                                                        class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-all"
                                                        title="Detail Audit">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                    <a href="{{ route('pengurus.audit.edit', $audit->id) }}"
                                                        class="text-orange-500 hover:text-orange-700 p-2 rounded-lg hover:bg-orange-50 transition-all"
                                                        title="Edit Audit">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </a>
                                                    <form id="deleteForm-{{ $audit->id }}" method="POST"
                                                        action="{{ route('pengurus.audit.destroy', $audit->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            onclick="confirmDelete({{ $audit->id }}, '{{ addslashes($audit->barang->nama) }}')"
                                                            class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all"
                                                            title="Hapus Audit">
                                                            <i class="fas fa-trash text-sm"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-8 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="mb-4 text-gray-400">
                                                        <i class="fas fa-clipboard-check text-6xl"></i>
                                                    </div>
                                                    <h6 class="text-lg font-semibold text-gray-500 mb-2">
                                                        @if (request()->hasAny(['search', 'kondisi', 'tanggal']))
                                                            Tidak ditemukan data yang sesuai
                                                        @else
                                                            Belum ada data audit barang
                                                        @endif
                                                    </h6>
                                                    <p class="text-sm text-gray-400 mb-4">
                                                        @if (request()->hasAny(['search', 'kondisi', 'tanggal']))
                                                            Coba ubah filter atau kata kunci pencarian
                                                        @else
                                                            Belum ada audit barang yang terdaftar dalam sistem
                                                        @endif
                                                    </p>
                                                    @if (!request()->hasAny(['search', 'kondisi', 'tanggal']))
                                                        <a href="{{ route('pengurus.audit.create') }}"
                                                            class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                                            <i class="ni ni-fat-add mr-2"></i>
                                                            Tambah Audit Pertama
                                                        </a>
                                                    @else
                                                        <a href="{{ route('pengurus.audit.index') }}"
                                                            class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-gray-400 to-gray-600 rounded-lg shadow-md hover:scale-102 transition-all">
                                                            <i class="fas fa-list mr-2"></i>
                                                            Lihat Semua Audit
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Pagination untuk Audit Mandiri -->
                    @if ($auditsMandiri->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Menampilkan <span class="font-medium">{{ $auditsMandiri->firstItem() }}</span> hingga
                                    <span class="font-medium">{{ $auditsMandiri->lastItem() }}</span> dari
                                    <span class="font-medium">{{ $auditsMandiri->total() }}</span> hasil
                                </div>
                                <div class="flex space-x-2">
                                    {{ $auditsMandiri->withQueryString()->links('vendor.pagination.tailwind', ['pageName' => 'mandiri_page']) }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="fixed inset-0 z-50 hidden overflow-y-auto" id="deleteModal">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeDeleteModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block p-6 my-8 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Hapus Audit Barang</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus audit barang <span
                                id="item-name" class="font-semibold"></span>? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="button" onclick="confirmDeleteAction()"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm">
                        Hapus
                    </button>
                    <button type="button" onclick="closeDeleteModal()"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Selesaikan Jadwal Audit -->
    <div class="fixed inset-0 z-50 hidden overflow-y-auto" id="selesaikanModal" style="z-index: 9999;">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeSelesaikanModal()"
                style="z-index: 9999;"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block p-6 my-8 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                style="z-index: 10000;">
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
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:col-start-2 sm:text-sm">
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
@endsection
