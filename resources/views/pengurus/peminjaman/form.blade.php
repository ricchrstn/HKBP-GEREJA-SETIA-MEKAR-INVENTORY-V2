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

<div class="grid grid-cols-2 gap-4 mb-5">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Pinjam</label>
        <input type="date" id="tanggal_pinjam" name="tanggal_pinjam"
               value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam ?? now()->format('Y-m-d')) }}"
               class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                      @error('tanggal_pinjam') border-red-500 @else border-gray-300 @enderror
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
        @error('tanggal_pinjam')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Kembali</label>
        <input type="date" id="tanggal_kembali" name="tanggal_kembali"
               value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali ?? now()->addDays(7)->format('Y-m-d')) }}"
               class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                      @error('tanggal_kembali') border-red-500 @else border-gray-300 @enderror
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
        @error('tanggal_kembali')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah</label>
    <input type="number" id="jumlah" name="jumlah" min="1"
           value="{{ old('jumlah', $peminjaman->jumlah ?? '') }}"
           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                  @error('jumlah') border-red-500 @else border-gray-300 @enderror
                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
    <div id="stokWarning" class="hidden mt-1 text-xs text-red-500"></div>
    @error('jumlah')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>

<div class="grid grid-cols-2 gap-4 mb-5">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Peminjam</label>
        <input type="text" name="nama_peminjam"
               value="{{ old('nama_peminjam', $peminjaman->nama_peminjam ?? '') }}"
               class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                      @error('nama_peminjam') border-red-500 @else border-gray-300 @enderror
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
               placeholder="Nama lengkap peminjam">
        @error('nama_peminjam')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Kontak</label>
        <input type="text" name="kontak"
               value="{{ old('kontak', $peminjaman->kontak ?? '') }}"
               class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                      @error('kontak') border-red-500 @else border-gray-300 @enderror
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
               placeholder="No. HP/WA/Email">
        @error('kontak')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Keperluan</label>
    <input type="text" name="keperluan"
           value="{{ old('keperluan', $peminjaman->keperluan ?? '') }}"
           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                  @error('keperluan') border-red-500 @else border-gray-300 @enderror
                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
           placeholder="Contoh: Kebaktian, Acara Natal, etc.">
    @error('keperluan')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>

<div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan</label>
    <textarea name="keterangan" rows="3"
              class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                     @error('keterangan') border-red-500 @else border-gray-300 @enderror
                     focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">{{ old('keterangan', $peminjaman->keterangan ?? '') }}</textarea>
    @error('keterangan')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>
