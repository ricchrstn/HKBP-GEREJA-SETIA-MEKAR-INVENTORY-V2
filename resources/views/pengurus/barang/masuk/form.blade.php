@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
    <!-- Dropdown Kategori -->
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
        <select id="kategori_id" name="kategori_id" required
                class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                       @error('kategori_id') border-red-500 @else border-gray-300 @enderror
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategoris as $kategori)
                <option value="{{ $kategori->id }}"
                    {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                    {{ $kategori->nama }}
                </option>
            @endforeach
        </select>
        @error('kategori_id')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>

    <!-- Dropdown Barang -->
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Barang</label>
        <select id="barang_id" name="barang_id" required
                class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                       @error('barang_id') border-red-500 @else border-gray-300 @enderror
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                disabled>
            <option value="">-- Pilih Barang --</option>
        </select>
        @error('barang_id')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal</label>
    <input type="date" name="tanggal"
           value="{{ old('tanggal', now()->format('Y-m-d')) }}"
           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                  @error('tanggal') border-red-500 @else border-gray-300 @enderror
                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
    @error('tanggal')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>

<div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah</label>
    <input type="number" name="jumlah" min="1"
           value="{{ old('jumlah') }}"
           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                  @error('jumlah') border-red-500 @else border-gray-300 @enderror
                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
    @error('jumlah')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>

<div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan</label>
    <textarea name="keterangan" rows="3"
              class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                     @error('keterangan') border-red-500 @else border-gray-300 @enderror
                     focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">{{ old('keterangan') }}</textarea>
    @error('keterangan')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>
