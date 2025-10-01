@csrf
<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
  <select name="kategori_id" id="kategoriSelect" required
          class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                 @error('kategori_id') border-red-500 @else border-gray-300 @enderror
                 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
    <option value="">-- Pilih Kategori --</option>
    @foreach($categories as $category)
      <option value="{{ $category->id }}"
        {{ old('kategori_id') == $category->id ? 'selected' : '' }}>
        {{ $category->nama }}
      </option>
    @endforeach
  </select>
  @error('kategori_id')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>

<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Barang</label>
  <select name="barang_id" id="barangSelect" required
          class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                 @error('barang_id') border-red-500 @else border-gray-300 @enderror
                 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          disabled>
    <option value="">-- Pilih Kategori Terlebih Dahulu --</option>
  </select>
  @error('barang_id')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>

<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Audit</label>
  <input type="date" name="tanggal_audit" value="{{ old('tanggal_audit', now()->format('Y-m-d')) }}"
         class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                @error('tanggal_audit') border-red-500 @else border-gray-300 @enderror
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
  @error('tanggal_audit')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>

<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Kondisi Barang</label>
  <select name="kondisi" required
          class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                 @error('kondisi') border-red-500 @else border-gray-300 @enderror
                 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
    <option value="">-- Pilih Kondisi --</option>
    <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
    <option value="rusak" {{ old('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
    <option value="hilang" {{ old('kondisi') == 'hilang' ? 'selected' : '' }}>Hilang</option>
    <option value="tidak_terpakai" {{ old('kondisi') == 'tidak_terpakai' ? 'selected' : '' }}>Tidak Terpakai</option>
  </select>
  @error('kondisi')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>

<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan</label>
  <textarea name="keterangan" rows="3"
            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                   @error('keterangan') border-red-500 @else border-gray-300 @enderror
                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
            placeholder="Jelaskan kondisi barang secara detail...">{{ old('keterangan') }}</textarea>
  @error('keterangan')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>

<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Foto Barang</label>
  <div class="flex items-center justify-center w-full">
    <label for="foto" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
      <div class="flex flex-col items-center justify-center pt-5 pb-6">
        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
        </svg>
        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
        <p class="text-xs text-gray-500">PNG, JPG atau JPEG (MAX. 2MB)</p>
      </div>
      <input id="foto" name="foto" type="file" class="hidden" accept="image/*" />
    </label>
  </div>
  @error('foto')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>
