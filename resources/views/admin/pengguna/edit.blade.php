@extends('admin.dashboard.layouts.app')
@section('title', 'Edit User - Admin')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-3 mb-0 bg-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0">Edit User</h6>
                                <p class="text-sm leading-normal text-slate-500">Edit informasi user: {{ $user->name }}</p>
                            </div>
                            <a href="{{ route('admin.users.index') }}"
                                class="inline-block px-6 py-3 font-bold text-center text-grey uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-red-400 to-red-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
            <div class="flex-none w-full max-w-full px-3 lg:w-8/12 lg:flex-none">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Informasi User</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                @method('PUT')
                                @csrf
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
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
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
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
                                    <select name="role"
                                        class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                               @error('role') border-red-500 @else border-gray-300 @enderror
                                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                        <option value="">-- Pilih Role --</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                            Admin</option>
                                        <option value="pengurus"
                                            {{ old('role', $user->role) == 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                                        <option value="bendahara"
                                            {{ old('role', $user->role) == 'bendahara' ? 'selected' : '' }}>Bendahara
                                        </option>
                                    </select>
                                    @error('role')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password <span
                                            class="text-xs text-slate-400">(Kosongkan jika tidak ingin
                                            diubah)</span></label>
                                    <input type="password" name="password"
                                        class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                              @error('password') border-red-500 @else border-gray-300 @enderror
                                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                        placeholder="Masukkan password baru" />
                                    @error('password')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi
                                        Password</label>
                                    <input type="password" name="password_confirmation"
                                        class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                              @error('password_confirmation') border-red-500 @else border-gray-300 @enderror
                                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                        placeholder="Konfirmasi password baru" />
                                    @error('password_confirmation')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="flex flex-wrap mt-6 -mx-3">
                                    <div class="flex-none w-full max-w-full px-3">
                                        <button type="submit"
                                            class="inline-block px-6 py-3 mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>Update User
                                        </button>
                                        <a href="{{ route('admin.users.index') }}"
                                            class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
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
            <div class="flex-none w-full max-w-full px-3 lg:w-4/12 lg:flex-none">
                <!-- Info User -->
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Informasi User</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="mb-4">
                                <strong class="text-slate-700">Email:</strong><br>
                                <span class="text-sm">{{ $user->email }}</span>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Role:</strong><br>
                                @php
                                    $roleClass = [
                                        'admin' => 'bg-gradient-to-tl from-purple-700 to-pink-500',
                                        'pengurus' => 'bg-gradient-to-tl from-green-600 to-lime-400',
                                        'bendahara' => 'bg-gradient-to-tl from-orange-600 to-yellow-400',
                                    ][$user->role];
                                @endphp
                                <span
                                    class="{{ $roleClass }} px-2.5 py-1.4 text-xs font-bold text-white uppercase rounded-lg">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Status:</strong><br>
                                @if ($user->id == auth()->user()->id)
                                    <span
                                        class="inline-block px-2.5 py-1.4 text-xs font-bold uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 text-white rounded-lg">
                                        Sedang Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-block px-2.5 py-1.4 text-xs font-bold uppercase bg-gradient-to-tl from-gray-400 to-gray-600 text-white rounded-lg">
                                        Normal
                                    </span>
                                @endif
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Dibuat:</strong><br>
                                <small class="text-slate-400">{{ $user->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <div class="mb-4">
                                <strong class="text-slate-700">Terakhir Diupdate:</strong><br>
                                <small class="text-slate-400">{{ $user->updated_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reset Password -->
                @if ($user->id != auth()->user()->id)
                    <div
                        class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                            <h6 class="mb-0">Reset Password</h6>
                        </div>
                        <div class="flex-auto px-0 pt-0 pb-2">
                            <div class="p-6">
                                <p class="text-sm text-slate-400 mb-4">Reset password user ini ke password default atau
                                    password baru.</p>
                                <button type="button"
                                    onclick="showResetPasswordModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                    class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-orange-600 to-yellow-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs w-full">
                                    <i class="fas fa-key mr-2"></i>Reset Password
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0">Aksi Cepat</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="flex flex-col space-y-2">
                                <a href="{{ route('admin.users.index') }}"
                                    class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-list mr-2"></i>Lihat Semua User
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div id="resetPasswordModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Reset Password</h3>
                <p class="text-sm text-gray-500 mb-4">Reset password untuk user: <span id="resetUserName"
                        class="font-semibold"></span></p>

                <form id="resetPasswordForm" method="POST">
                    @csrf
                    <input type="hidden" id="resetUserId" name="user_id">

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Password Baru</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                            placeholder="Masukkan password baru" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                            placeholder="Konfirmasi password baru" />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeResetPasswordModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 focus:outline-none">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
