@csrf
<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Barang</label>
  <input type="text" name="nama_barang" value="{{ old('nama_barang', $pengajuan->nama_barang ?? '') }}"
         class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                @error('nama_barang') border-red-500 @else border-gray-300 @enderror
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
         placeholder="Contoh: Proyektor Epson">
  @error('nama_barang')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>

<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Spesifikasi</label>
  <textarea name="spesifikasi" rows="3"
            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                   @error('spesifikasi') border-red-500 @else border-gray-300 @enderror
                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
            placeholder="Jelaskan spesifikasi detail barang yang dibutuhkan...">{{ old('spesifikasi', $pengajuan->spesifikasi ?? '') }}</textarea>
  @error('spesifikasi')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>

<div class="grid grid-cols-2 gap-4 mb-5">
  <div>
    <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah</label>
    <input type="number" name="jumlah" value="{{ old('jumlah', $pengajuan->jumlah ?? '') }}" min="1"
           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                  @error('jumlah') border-red-500 @else border-gray-300 @enderror
                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
           placeholder="1">
    @error('jumlah')
      <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
  </div>
  <div>
    <label class="block text-sm font-semibold text-slate-700 mb-1">Satuan</label>
    <input type="text" name="satuan" value="{{ old('satuan', $pengajuan->satuan ?? '') }}"
           class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                  @error('satuan') border-red-500 @else border-gray-300 @enderror
                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
           placeholder="Unit, Buah, etc">
    @error('satuan')
      <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
  </div>
</div>

<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Kebutuhan</label>
  <input type="date" name="kebutuhan" value="{{ old('kebutuhan') }}" min="{{ now()->format('Y-m-d') }}"
         class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                @error('kebutuhan') border-red-500 @else border-gray-300 @enderror
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
  @error('kebutuhan')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>

<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Alasan Pengajuan</label>
  <textarea name="alasan" rows="4" required
            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm
                   @error('alasan') border-red-500 @else border-gray-300 @enderror
                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
            placeholder="Jelaskan alasan mengapa barang ini dibutuhkan...">{{ old('alasan', $pengajuan->alasan ?? '') }}</textarea>
  @error('alasan')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>

<!-- Tambahkan form kriteria TOPSIS -->
@include('pengurus.pengajuan.form_kriteria')

<div class="mb-5">
  <label class="block text-sm font-semibold text-slate-700 mb-1">Dokumen Pendukung</label>
  <div class="flex items-center justify-center w-full">
    <label for="file_pengajuan" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
      <div class="flex flex-col items-center justify-center pt-5 pb-6">
        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
        </svg>
        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
        <p class="text-xs text-gray-500">PDF, DOC, atau DOCX (MAX. 2MB)</p>
      </div>
      <input id="file_pengajuan" name="file_pengajuan" type="file" class="hidden" accept=".pdf,.doc,.docx" />
    </label>
  </div>
  @error('file_pengajuan')
    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
  @enderror
</div>
