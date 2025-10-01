<div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Tingkat Urgensi Barang (K1)</label>
    <select name="urgensi" class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" required>
        <option value="">Pilih tingkat urgensi</option>
        <option value="10" {{ old('urgensi', $pengajuan->urgensi ?? '') == '10' ? 'selected' : '' }}>10 - Sangat Mendesak (Dibutuhkan segera untuk ibadah dalam 1-2 hari)</option>
        <option value="9" {{ old('urgensi', $pengajuan->urgensi ?? '') == '9' ? 'selected' : '' }}>9 - Mendesak (Digunakan rutin setiap minggu)</option>
        <option value="8" {{ old('urgensi', $pengajuan->urgensi ?? '') == '8' ? 'selected' : '' }}>8 - Mendesak (Digunakan rutin setiap minggu)</option>
        <option value="7" {{ old('urgensi', $pengajuan->urgensi ?? '') == '7' ? 'selected' : '' }}>7 - Cukup Mendesak (Digunakan dua mingguan atau bulanan)</option>
        <option value="6" {{ old('urgensi', $pengajuan->urgensi ?? '') == '6' ? 'selected' : '' }}>6 - Cukup Mendesak (Digunakan dua mingguan atau bulanan)</option>
        <option value="5" {{ old('urgensi', $pengajuan->urgensi ?? '') == '5' ? 'selected' : '' }}>5 - Kurang Mendesak (Jarang dipakai, tapi tetap diperlukan)</option>
        <option value="4" {{ old('urgensi', $pengajuan->urgensi ?? '') == '4' ? 'selected' : '' }}>4 - Kurang Mendesak (Jarang dipakai, tapi tetap diperlukan)</option>
        <option value="3" {{ old('urgensi', $pengajuan->urgensi ?? '') == '3' ? 'selected' : '' }}>3 - Tidak Mendesak</option>
        <option value="2" {{ old('urgensi', $pengajuan->urgensi ?? '') == '2' ? 'selected' : '' }}>2 - Tidak Mendesak</option>
        <option value="1" {{ old('urgensi', $pengajuan->urgensi ?? '') == '1' ? 'selected' : '' }}>1 - Tidak Mendesak</option>
    </select>
    @error('urgensi')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>

<div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-1">Ketersediaan Stok Barang (K2)</label>
    <select name="ketersediaan_stok" class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" required>
        <option value="">Pilih ketersediaan stok</option>
        <option value="10" {{ old('ketersediaan_stok', $pengajuan->ketersediaan_stok ?? '') == '10' ? 'selected' : '' }}>10 - Hampir habis (0-1 unit)</option>
        <option value="8" {{ old('ketersediaan_stok', $pengajuan->ketersediaan_stok ?? '') == '8' ? 'selected' : '' }}>8 - Stok sangat rendah (2-3 unit)</option>
        <option value="6" {{ old('ketersediaan_stok', $pengajuan->ketersediaan_stok ?? '') == '6' ? 'selected' : '' }}>6 - Stok rendah (4-5 unit)</option>
        <option value="4" {{ old('ketersediaan_stok', $pengajuan->ketersediaan_stok ?? '') == '4' ? 'selected' : '' }}>4 - Cukup tersedia (6-8 unit)</option>
        <option value="2" {{ old('ketersediaan_stok', $pengajuan->ketersediaan_stok ?? '') == '2' ? 'selected' : '' }}>2 - Stok masih sangat cukup (>8 unit)</option>
    </select>
    @error('ketersediaan_stok')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>

<div class="mb-5">
    <label class="block text-sm font-semibold text-slate-700 mb-1">
        Ketersediaan Dana Pengadaan (K3)
        <span class="text-xs font-normal text-blue-600">
            (Saldo Kas: <span id="saldo-kas" class="font-semibold">Memuat...</span>)
        </span>
    </label>
    <select name="ketersediaan_dana" class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" required>
        <option value="">Pilih ketersediaan dana</option>
        <option value="10" {{ old('ketersediaan_dana', $pengajuan->ketersediaan_dana ?? '') == '10' ? 'selected' : '' }}>10 - Sangat tinggi (> Rp 8.000.000)</option>
        <option value="8" {{ old('ketersediaan_dana', $pengajuan->ketersediaan_dana ?? '') == '8' ? 'selected' : '' }}>8 - Tinggi (Rp 6.000.000 - Rp 8.000.000)</option>
        <option value="6" {{ old('ketersediaan_dana', $pengajuan->ketersediaan_dana ?? '') == '6' ? 'selected' : '' }}>6 - Sedang (Rp 4.000.000 - Rp 5.999.999)</option>
        <option value="4" {{ old('ketersediaan_dana', $pengajuan->ketersediaan_dana ?? '') == '4' ? 'selected' : '' }}>4 - Rendah (Rp 2.000.000 - Rp 3.999.999)</option>
        <option value="2" {{ old('ketersediaan_dana', $pengajuan->ketersediaan_dana ?? '') == '2' ? 'selected' : '' }}>2 - Sangat rendah (< Rp 2.000.000)</option>
    </select>
    @error('ketersediaan_dana')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>
