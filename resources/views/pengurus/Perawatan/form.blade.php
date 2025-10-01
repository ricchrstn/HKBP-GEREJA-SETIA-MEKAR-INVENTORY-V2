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
                    {{ old('kategori_id', (isset($perawatan) ? $perawatan->barang->kategori_id : '')) == $kategori->id ? 'selected' : '' }}>
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
            <span class="text text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Perawatan</label>
    <input type="date" id="tanggal_perawatan" name="tanggal_perawatan"
           value="{{ old('tanggal_perawatan', $perawatan->tanggal_perawatan ?? now()->format('Y-m-d')) }}"
           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                  @error('tanggal_perawatan') border-red-500 @else border-gray-300 @enderror
                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
    @error('tanggal_perawatan')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>

<div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Perawatan</label>
    <input type="text" name="jenis_perawatan"
           value="{{ old('jenis_perawatan', $perawatan->jenis_perawatan ?? '') }}"
           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                  @error('jenis_perawatan') border-red-500 @else border-gray-300 @enderror
                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
           placeholder="Contoh: Perbaikan, Pengecatan, Service, dll.">
    @error('jenis_perawatan')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>

<div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Biaya <span class="text-red-500">*</span></label>
    <div class="flex">
        <div class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
            Rp
        </div>
        <input type="number" name="biaya" value="{{ old('biaya', $perawatan->biaya ?? '0') }}" min="0"
            class="w-full px-4 py-2 text-sm border rounded-r-lg shadow-sm @error('biaya') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
            placeholder="1.000" required>
    </div>
    @error('biaya')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>

<div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan</label>
    <textarea name="keterangan" rows="3"
              class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                     @error('.blade-akhir('keterangan') border-red-500 @else border-gray-300 @enderror
                     focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">{{ old('keterangan', $perawatan->keterangan ?? '') }}</textarea>
    @error('keterangan')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>
