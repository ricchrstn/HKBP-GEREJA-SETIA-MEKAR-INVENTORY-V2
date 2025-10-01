@csrf
<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Barang</label>
  <input type="text" name="nama" value="{{ old('nama', $barang->nama ?? '') }}"
         class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                @error('nama') border-red-500 @else border-gray-300 @enderror
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
  @error('nama')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>

<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
  <div class="flex items-center space-x-2">
    <select id="kategori_select" name="kategori_id" required
            class="flex-grow px-4 py-2 text-sm border rounded-lg shadow-sm
                   @error('kategori_id') border-red-500 @else border-gray-300 @enderror
                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
      <option value="">-- Pilih Kategori --</option>
      @foreach($kategoris as $kategori)
        <option value="{{ $kategori->id }}"
          {{ old('kategori_id', $barang->kategori_id ?? '') == $kategori->id ? 'selected' : '' }}>
          {{ $kategori->nama }}
        </option>
      @endforeach
    </select>
    <button type="button" onclick="toggleCategoryModal()"
            class="px-3 py-2 text-sm bg-blue-600 text-blue rounded-lg shadow hover:bg-blue-700 transition">
      <i class="fas fa-plus"></i>
    </button>
  </div>
  @error('kategori_id')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>

<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
  <textarea name="deskripsi" rows="3"
            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                   @error('deskripsi') border-red-500 @else border-gray-300 @enderror
                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">{{ old('deskripsi', $barang->deskripsi ?? '') }}</textarea>
  @error('deskripsi')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>

<div class="mb-5 flex space-x-4">
  <div class="w-1/2">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Satuan</label>
    <input type="text" name="satuan" value="{{ old('satuan', $barang->satuan ?? '') }}"
           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                  @error('satuan') border-red-500 @else border-gray-300 @enderror
                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
    @error('satuan')
      <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
  </div>
  <div class="w-1/2">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Harga</label>
    <input type="number" name="harga" value="{{ old('harga', $barang->harga ?? '') }}"
           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                  @error('harga') border-red-500 @else border-gray-300 @enderror
                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
    @error('harga')
      <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
  </div>
</div>

<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Stok Awal</label>
  <input type="number" name="stok" value="{{ old('stok', $barang->stok ?? 0) }}"
         class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                @error('stok') border-red-500 @else border-gray-300 @enderror
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
  @error('stok')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>

<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Gambar</label>
  <input type="file" name="gambar"
         class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
                file:rounded-lg file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100">
  @error('gambar')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
  @if(!empty($barang->gambar))
    <img src="{{ asset('storage/barang/' . $barang->gambar) }}"
         class="h-20 mt-3 rounded-lg border border-gray-200 shadow-sm object-cover">
  @endif
</div>
