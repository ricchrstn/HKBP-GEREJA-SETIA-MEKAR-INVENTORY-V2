@extends('admin.dashboard.layouts.app')
@section('title', 'Manajemen Pengguna - Admin')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header Section -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-5 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 text-lg font-bold text-slate-700">Manajemen Pengguna</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Kelola akun pengguna sistem</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.users.create') }}" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                    <i class="ni ni-fat-add mr-2"></i>
                                    Tambah User
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
            <form id="searchForm" method="GET" action="{{ route('admin.users.index') }}" class="flex items-center">
                <div class="relative flex flex-wrap items-stretch transition-all rounded-lg ease-soft">
                    <span class="absolute z-50 flex items-center h-full pl-2 text-slate-500">
                        <i class="fas fa-search text-sm"></i>
                    </span>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" placeholder="Cari nama atau email..." />
                </div>
            </form>
            <!-- Filter di kanan -->
            <form id="filterForm" method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <select name="role" id="roleFilter" class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pengurus" {{ request('role') == 'pengurus' ? 'selected' : '' }}">Pengurus</option>
                        <option value="bendahara" {{ request('role') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                    </select>
                </div>
            </form>
        </div>
        <!-- Table Users -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col mb-6 bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <div class="flex flex-wrap items-center justify-between">
                            <div>
                                <h6 class="mb-0 font-bold">Daftar Pengguna</h6>
                                <p class="mb-0 text-sm text-slate-400 mt-1">
                                    <span class="font-medium text-blue-600">{{ $users->count() }}</span> dari
                                    <span class="font-medium text-slate-600">{{ $users->total() }}</span> pengguna ditemukan
                                </p>
                            </div>
                            @if (request()->hasAny(['search', 'role']))
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-slate-500">Filter aktif:</span>
                                    @if (request('search'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-search mr-1"></i>
                                            "{{ request('search') }}"
                                        </span>
                                    @endif
                                    @if (request('role'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-user-tag mr-1"></i>
                                            {{ ucfirst(request('role')) }}
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
                                        <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400">Nama & Email</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Role</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Status</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Dibuat</th>
                                        <th class="px-6 py-3 text-center text-xxs font-bold uppercase text-slate-400">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-2">
                                                <div class="flex px-2 py-1">
                                                    <div>
                                                        <div class="inline-flex items-center justify-center mr-4 h-12 w-12 rounded-xl
                                                            @if($user->role == 'admin') bg-gradient-to-tl from-purple-700 to-pink-500
                                                            @elseif($user->role == 'pengurus') bg-gradient-to-tl from-green-600 to-lime-400
                                                            @else bg-gradient-to-tl from-red-600 to-yellow-400 @endif">
                                                            <i class="fas fa-user text-white text-lg"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm font-semibold">{{ $user->name }}</h6>
                                                        <p class="mb-0 text-xs text-slate-400">{{ $user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2 text-center">
                                                @php
                                                    $roleClass = [
                                                        'admin' => 'bg-gradient-to-tl from-purple-700 to-pink-500',
                                                        'pengurus' => 'bg-gradient-to-tl from-green-600 to-lime-400',
                                                        'bendahara' => 'bg-gradient-to-tl from-red-600 to-yellow-400',
                                                    ][$user->role];
                                                @endphp
                                                <span class="{{ $roleClass }} px-2.5 py-1.4 text-xs rounded-1.8 text-white font-bold uppercase">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center">
                                                @if($user->id == auth()->user()->id)
                                                    <span class="inline-block px-2.5 py-1.4 text-xs rounded-1.8 font-bold uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 text-white">
                                                        Sedang Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-block px-2.5 py-1.4 text-xs rounded-1.8 font-bold uppercase bg-gradient-to-tl from-gray-400 to-gray-600 text-white">
                                                        Normal
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-2 text-center">
                                                <span class="text-sm text-slate-600">{{ $user->created_at->format('d M Y') }}</span>
                                            </td>
                                            <td class="p-2 text-center">
                                                <div class="flex justify-center items-center space-x-2">
                                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-orange-500 hover:text-orange-700 p-2 rounded-lg hover:bg-orange-50 transition-all" title="Edit User">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($user->id != auth()->user()->id)
                                                        <!-- PERBAIKAN: Tambahkan form di sekitar tombol delete -->
                                                        <form id="deleteForm-{{ $user->id }}" method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all" title="Hapus User">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-8 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="mb-4 text-gray-400">
                                                        <i class="fas fa-users text-6xl"></i>
                                                    </div>
                                                    <h6 class="text-lg font-semibold text-gray-500 mb-2">
                                                        @if (request()->hasAny(['search', 'role']))
                                                            Tidak ditemukan data yang sesuai
                                                        @else
                                                            Belum ada data pengguna
                                                        @endif
                                                    </h6>
                                                    <p class="text-sm text-gray-400 mb-4">
                                                        @if (request()->hasAny(['search', 'role']))
                                                            Coba ubah filter atau kata kunci pencarian
                                                        @else
                                                            Belum ada pengguna yang terdaftar dalam sistem
                                                        @endif
                                                    </p>
                                                    @if (!request()->hasAny(['search', 'role']))
                                                        <a href="{{ route('admin.users.create') }}" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:scale-102 transition-all">
                                                            <i class="ni ni-fat-add mr-2"></i>
                                                            Tambah User Pertama
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admin.users.index') }}" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-gray-400 to-gray-600 rounded-lg shadow-md hover:scale-102 transition-all">
                                                            <i class="fas fa-list mr-2"></i>
                                                            Lihat Semua User
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
                    <!-- Pagination -->
                    @if ($users->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Menampilkan <span class="font-medium">{{ $users->firstItem() }}</span> hingga
                                    <span class="font-medium">{{ $users->lastItem() }}</span> dari
                                    <span class="font-medium">{{ $users->total() }}</span> hasil
                                </div>
                                <div class="flex space-x-2">
                                    {{ $users->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

