@csrf
<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Audit</label>
  <input type="text" name="judul" value="{{ old('judul') }}"
         class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                @error('judul') border-red-500 @else border-gray-300 @enderror
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
         placeholder="Contoh: Audit Perlengkapan Ibadah Triwulan 1">
  @error('judul')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>
<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
  <textarea name="deskripsi" rows="3"
            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                   @error('deskripsi') border-red-500 @else border-gray-300 @enderror
                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
            placeholder="Deskripsi detail tentang audit yang akan dilakukan...">{{ old('deskripsi') }}</textarea>
  @error('deskripsi')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>
<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Audit</label>
  <input type="date" name="tanggal_audit" value="{{ old('tanggal_audit') }}"
         class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                @error('tanggal_audit') border-red-500 @else border-gray-300 @enderror
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
  @error('tanggal_audit')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>
<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Barang</label>
  <select name="barang_id" required
          class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                 @error('barang_id') border-red-500 @else border-gray-300 @enderror
                 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
    <option value="">-- Pilih Barang --</option>
    @foreach($barangs as $barang)
      <option value="{{ $barang->id }}"
        {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
        {{ $barang->nama }} ({{ $barang->kode_barang }})
      </option>
    @endforeach
  </select>
  @error('barang_id')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>
<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Yang Bertanggung Jawab</label>
  <select name="user_id" required
          class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                 @error('user_id') border-red-500 @else border-gray-300 @enderror
                 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
    <option value="">-- Pilih Penanggung Jawab --</option>
    @foreach($users as $user)
      <option value="{{ $user->id }}"
        {{ old('user_id') == $user->id ? 'selected' : '' }}>
        {{ $user->name }} ({{ $user->email }})
      </option>
    @endforeach
  </select>
  @error('user_id')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>
<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Status</label>
  <select name="status"
          class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                 @error('status') border-red-500 @else border-gray-300 @enderror
                 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
    <option value="terjadwal" {{ old('status', 'terjadwal') == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
    <option value="diproses" {{ old('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
    <option value="ditunda" {{ old('status') == 'ditunda' ? 'selected' : '' }}>Ditunda</option>
  </select>
  @error('status')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>
