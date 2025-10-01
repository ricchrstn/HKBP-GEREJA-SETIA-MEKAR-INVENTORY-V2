@extends('admin.dashboard.layouts.app')
@section('title', 'Tambah User - Admin')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Tambah User Baru</h6>
                                <p class="text-sm leading-normal text-slate-500">
                                    Tambahkan akun pengguna baru ke sistem
                                </p>
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="inline-block px-6 py-3 font-bold text-center text-grey uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Informasi User</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('admin.users.store') }}">
                                @csrf
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                                  @error('name') border-red-500 @else border-gray-300 @enderror
                                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                           placeholder="Masukkan nama lengkap" />
                                    @error('name')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                                  @error('email') border-red-500 @else border-gray-300 @enderror
                                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                           placeholder="Masukkan alamat email" />
                                    @error('email')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Role</label>
                                    <select name="role" class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                                   @error('role') border-red-500 @else border-gray-300 @enderror
                                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                        <option value="">-- Pilih Role --</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="pengurus" {{ old('role') == 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                                        <option value="bendahara" {{ old('role') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                    </select>
                                    @error('role')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                                    <input type="password" name="password"
                                           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                                  @error('password') border-red-500 @else border-gray-300 @enderror
                                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                           placeholder="Masukkan password" />
                                    @error('password')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation"
                                           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                                  @error('password_confirmation') border-red-500 @else border-gray-300 @enderror
                                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                           placeholder="Konfirmasi password" />
                                    @error('password_confirmation')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit" class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Simpan User
                                        </button>
                                        <a href="{{ route('admin.users.index') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
                        <h6 class="mb-0">Tips Menambah User</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <h6 class="text-blue-600"><i class="fas fa-user-shield mr-2"></i>Role Admin</h6>
                                <p class="text-sm text-slate-400">Memiliki akses penuh ke sistem, termasuk manajemen pengguna dan master data.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-green-600"><i class="fas fa-users mr-2"></i>Role Pengurus</h6>
                                <p class="text-sm text-slate-400">Mengelola operasional inventori, termasuk barang masuk/keluar dan peminjaman.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-orange-600"><i class="fas fa-money-bill-wave mr-2"></i>Role Bendahara</h6>
                                <p class="text-sm text-slate-400">Mengelola keuangan dan verifikasi pengadaan barang.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-purple-600"><i class="fas fa-lock mr-2"></i>Password</h6>
                                <p class="text-sm text-slate-400">Password minimal 8 karakter dan harus dikonfirmasi dengan benar.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0">Statistik Pengguna</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-wrap -mx-3">
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center border-r border-gray-200">
                                        <h4 class="font-bold text-blue-600">{{ \App\Models\User::count() }}</h4>
                                        <small class="text-slate-400">Total User</small>
                                    </div>
                                </div>
                                <div class="flex-none w-1/2 max-w-full px-3">
                                    <div class="text-center">
                                        <h4 class="font-bold text-green-600">{{ \App\Models\User::where('role', 'pengurus')->count() }}</h4>
                                        <small class="text-slate-400">Pengurus Aktif</small>
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
