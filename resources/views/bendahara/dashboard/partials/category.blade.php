<!-- Modal Overlay -->
<div id="categoryModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50" style="display: none; z-index: 99999;">
  <div class="relative w-full max-w-md mx-4">
    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl shadow-soft-xl">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b border-gray-200 rounded-t-2xl">
        <h3 class="text-lg font-semibold text-slate-700">
          Tambah Kategori
        </h3>
        <button type="button" onclick="closeCategoryModal()"
                class="text-gray-400 hover:text-gray-600 transition-colors">
          <i class="fas fa-times text-lg"></i>
        </button>
      </div>

      <!-- Body -->
      <div class="p-6">
        <form id="quickAddCategory">
          @csrf
          <!-- Nama Kategori -->
          <div class="mb-4">
            <label class="block text-sm font-semibold text-slate-700 mb-2">
              Nama Kategori
            </label>
            <input type="text"
                   name="nama"
                   id="kategori_nama"
                   required
                   placeholder="Masukkan nama kategori..."
                   class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
            <div class="error-message text-red-500 text-xs mt-1" id="nama-error" style="display: none;"></div>
          </div>

          <!-- Deskripsi (opsional) -->
          <div class="mb-4">
            <label class="block text-sm font-semibold text-slate-700 mb-2">
              Deskripsi (opsional)
            </label>
            <textarea name="deskripsi"
                      rows="3"
                      placeholder="Masukkan deskripsi kategori..."
                      class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"></textarea>
          </div>
        </form>
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-end p-6 space-x-3 border-t border-gray-200 rounded-b-2xl">
        <button type="button"
                onclick="closeCategoryModal()"
                class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-gray-400 to-gray-600 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
          Batal
        </button>
        <button type="button"
                onclick="submitNewCategory()"
                class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-600 to-cyan-400 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs"
                id="submitCategoryBtn">
          <span class="btn-text">Simpan</span>
          <span class="btn-loading" style="display: none;">
            <i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...
          </span>
        </button>
      </div>
    </div>
  </div>
</div>


