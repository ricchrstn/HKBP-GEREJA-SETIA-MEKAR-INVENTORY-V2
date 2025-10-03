<?php

namespace App\Http\Controllers\Admin; // Mendefinisikan namespace untuk controller ini, biasanya digunakan untuk mengelompokkan controller berdasarkan fungsionalitas atau area (misal: Admin)

use App\Http\Controllers\Controller; // Mengimpor base Controller dari Laravel, yang menyediakan fungsionalitas dasar untuk semua controller
use App\Models\Barang; // Mengimpor model Barang, yang merepresentasikan tabel 'barangs' di database
use App\Models\BarangMasuk; // Mengimpor model BarangMasuk, untuk transaksi barang masuk
use App\Models\BarangKeluar; // Mengimpor model BarangKeluar, untuk transaksi barang keluar
use App\Models\Kategori; // Mengimpor model Kategori, untuk data kategori barang
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani HTTP request dari pengguna
use Illuminate\Support\Str; // Mengimpor helper Str untuk manipulasi string (misal: slugify)
use Illuminate\Support\Facades\Storage; // Mengimpor facade Storage untuk mengelola penyimpanan file (upload/hapus gambar)
use Illuminate\Support\Facades\DB; // Mengimpor facade DB untuk berinteraksi dengan database secara langsung (misal: transaksi database)
use Illuminate\Support\Facades\Log; // Mengimpor facade Log untuk mencatat pesan log (error, info, dll.)

class BarangController extends Controller // Mendefinisikan kelas BarangController yang mewarisi dari base Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Metode untuk menampilkan daftar semua barang
    {
        $query = Barang::with('kategori')->whereNull('deleted_at'); // Menginisialisasi query untuk mengambil semua barang yang belum di-soft delete, dengan memuat relasi 'kategori'

        // Filter pencarian
        if ($request->filled('search')) { // Memeriksa apakah ada parameter 'search' di request
            $search = $request->search; // Mengambil nilai dari parameter 'search'
            $query->where(function ($q) use ($search) { // Menambahkan kondisi pencarian ke query
                $q->where('nama', 'like', "%{$search}%") // Mencari berdasarkan nama barang
                    ->orWhere('kode_barang', 'like', "%{$search}%") // Atau berdasarkan kode barang
                    ->orWhere('deskripsi', 'like', "%{$search}%"); // Atau berdasarkan deskripsi barang
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) { // Memeriksa apakah ada parameter 'kategori' di request
            $query->where('kategori_id', $request->kategori); // Menambahkan kondisi filter berdasarkan kategori_id
        }

        // Filter status stok
        if ($request->filled('stok_status')) { // Memeriksa apakah ada parameter 'stok_status' di request
            switch ($request->stok_status) { // Melakukan switch case berdasarkan nilai 'stok_status'
                case 'habis': // Jika status 'habis'
                    $query->where('stok', 0); // Barang dengan stok 0
                    break;
                case 'rendah': // Jika status 'rendah'
                    $query->where('stok', '>', 0)->where('stok', '<=', 5); // Barang dengan stok > 0 dan <= 5
                    break;
                case 'aman': // Jika status 'aman'
                    $query->where('stok', '>', 5); // Barang dengan stok > 5
                    break;
            }
        }

        $barangs = $query->latest()->paginate(15)->withQueryString(); // Menjalankan query, mengurutkan berdasarkan terbaru, memaginasi 15 item per halaman, dan mempertahankan parameter query pada link paginasi

        // Statistik
        $stokHabis = Barang::whereNull('deleted_at')->where('stok', 0)->count(); // Menghitung jumlah barang dengan stok habis (0)
        $stokRendah = Barang::whereNull('deleted_at')->where('stok', '>', 0)->where('stok', '<=', 5)->count(); // Menghitung jumlah barang dengan stok rendah (1-5)
        $stokAman = Barang::whereNull('deleted_at')->where('stok', '>', 5)->count(); // Menghitung jumlah barang dengan stok aman (>5)

        // Data kategori untuk filter
        $kategoris = Kategori::orderBy('nama')->get(); // Mengambil semua kategori, diurutkan berdasarkan nama

        return view('admin.inventori.index', compact( // Mengembalikan view 'admin.inventori.index' dengan data yang dibutuhkan
            'barangs', // Data barang yang sudah difilter dan dipaginasi
            'kategoris', // Data kategori untuk filter
            'stokHabis', // Statistik stok habis
            'stokRendah', // Statistik stok rendah
            'stokAman' // Statistik stok aman
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() // Metode untuk menampilkan form pembuatan barang baru
    {
        $kategoris = Kategori::all(); // Mengambil semua kategori untuk dropdown di form
        return view('admin.inventori.create', compact('kategoris')); // Mengembalikan view 'admin.inventori.create' dengan data kategori
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Metode untuk menyimpan data barang baru ke database
    {
        $validated = $request->validate([ // Melakukan validasi data yang diterima dari form
            'nama'        => 'required|string|max:255', // Nama wajib, string, max 255 karakter
            'kategori_id' => 'required|exists:kategori,id', // Kategori wajib, harus ada di tabel 'kategori' kolom 'id'
            'deskripsi'   => 'nullable|string', // Deskripsi opsional, string
            'satuan'      => 'required|string|max:50', // Satuan wajib, string, max 50 karakter
            'stok'        => 'required|integer|min:0', // Stok wajib, integer, minimal 0
            'harga'       => 'required|numeric|min:0', // Harga wajib, numeric, minimal 0
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Gambar opsional, harus gambar, format jpeg/png/jpg, max 2MB
        ], [ // Pesan error kustom untuk validasi
            'kategori_id.required' => 'Kategori harus dipilih',
            'kategori_id.exists'   => 'Kategori tidak valid',
            'harga.min'            => 'Harga tidak boleh negatif'
        ]);

        try {
            DB::beginTransaction(); // Memulai transaksi database. Jika ada error di tengah proses, semua perubahan akan di-rollback.

            // Generate kode barang unik
            $latestBarang = Barang::withTrashed()->latest('id')->first(); // Mengambil barang terakhir (termasuk yang di-soft delete) berdasarkan ID
            $nextId = $latestBarang ? $latestBarang->id + 1 : 1; // Menentukan ID berikutnya untuk kode barang
            $kodeBarang = 'BRG-' . str_pad($nextId, 3, '0', STR_PAD_LEFT); // Membuat kode barang (misal: BRG-001)

            // Handle upload gambar
            if ($request->hasFile('gambar')) { // Memeriksa apakah ada file gambar yang diupload
                $gambar = $request->file('gambar'); // Mengambil objek file gambar
                $filename = time() . '_' . Str::slug($request->nama) . '.' . $gambar->getClientOriginalExtension(); // Membuat nama file unik untuk gambar (timestamp_namabarangslug.ext)
                // Simpan ke storage/app/public/barang
                $path = $gambar->storeAs('barang', $filename, 'public'); // Menyimpan gambar ke folder 'storage/app/public/barang'
                $validated['gambar'] = $filename; // Menyimpan nama file gambar ke data yang divalidasi
            }

            $validated['kode_barang'] = $kodeBarang; // Menambahkan kode barang ke data yang divalidasi
            $validated['status'] = 'aktif'; // Menetapkan status barang awal sebagai 'aktif'

            $barang = Barang::create($validated); // Membuat record barang baru di database dengan data yang divalidasi

            // Catat stok awal jika ada
            if ($validated['stok'] > 0) { // Jika stok awal lebih dari 0
                BarangMasuk::create([ // Mencatat transaksi barang masuk untuk stok awal
                    'barang_id'  => $barang->id,
                    'tanggal'    => now(),
                    'jumlah'     => $validated['stok'],
                    'keterangan' => 'Stok awal',
                    'user_id'    => auth()->id() // Mencatat ID user yang sedang login
                ]);
            }

            DB::commit(); // Mengakhiri transaksi database dan menyimpan semua perubahan

            return redirect()->route('admin.inventori.index')->with('success', 'Barang berhasil ditambahkan'); // Redirect ke halaman daftar barang dengan pesan sukses
        } catch (\Exception $e) { // Menangkap jika terjadi error selama proses
            DB::rollBack(); // Mengembalikan semua perubahan database yang telah dilakukan
            Log::error('Error creating barang: ' . $e->getMessage()); // Mencatat error ke log Laravel
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Kembali ke form dengan input sebelumnya dan pesan error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang) // Metode untuk menampilkan detail satu barang berdasarkan model binding
    {
        // Cek apakah barang sudah dihapus
        if ($barang->deleted_at) { // Memeriksa apakah barang sudah di-soft delete
            return redirect()->route('admin.inventori.index')->with('error', 'Barang tidak ditemukan atau sudah diarsipkan'); // Redirect dengan pesan error
        }

        // Ambil data transaksi terkait barang
        $barangMasuk = BarangMasuk::where('barang_id', $barang->id)->latest()->take(5)->get(); // Mengambil 5 transaksi barang masuk terbaru untuk barang ini
        $barangKeluar = BarangKeluar::where('barang_id', $barang->id)->latest()->take(5)->get(); // Mengambil 5 transaksi barang keluar terbaru untuk barang ini

        return view('admin.inventori.show', compact('barang', 'barangMasuk', 'barangKeluar')); // Mengembalikan view 'admin.inventori.show' dengan data barang dan transaksinya
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang) // Metode untuk menampilkan form edit barang
    {
        // Cek apakah barang sudah dihapus
        if ($barang->deleted_at) { // Memeriksa apakah barang sudah di-soft delete
            return redirect()->route('admin.inventori.index')->with('error', 'Barang tidak ditemukan atau sudah diarsipkan'); // Redirect dengan pesan error
        }

        $kategoris = Kategori::all(); // Mengambil semua kategori untuk dropdown di form
        return view('admin.inventori.edit', compact('barang', 'kategoris')); // Mengembalikan view 'admin.inventori.edit' dengan data barang yang akan diedit dan kategori
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang) // Metode untuk memperbarui data barang di database
    {
        // Cek apakah barang sudah dihapus
        if ($barang->deleted_at) { // Memeriksa apakah barang sudah di-soft delete
            return redirect()->route('admin.inventori.index')->with('error', 'Barang tidak ditemukan atau sudah diarsipkan'); // Redirect dengan pesan error
        }

        $validated = $request->validate([ // Melakukan validasi data yang diterima dari form
            'nama'        => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi'   => 'nullable|string',
            'satuan'      => 'required|string|max:50',
            'harga'       => 'required|numeric|min:0.01', // Harga minimal 0.01 (tidak boleh 0 jika ini adalah harga jual)
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            if ($request->hasFile('gambar')) { // Memeriksa apakah ada file gambar baru yang diupload
                // Hapus gambar lama jika ada
                if ($barang->gambar && Storage::exists('public/barang/' . $barang->gambar)) { // Jika barang punya gambar lama dan file-nya ada
                    Storage::delete('public/barang/' . $barang->gambar); // Hapus gambar lama dari storage
                }

                // Upload gambar baru
                $gambar = $request->file('gambar');
                $filename = time() . '_' . Str::slug($request->nama) . '.' . $gambar->getClientOriginalExtension();
                $gambar->storeAs('public/barang', $filename); // Menyimpan gambar baru
                $validated['gambar'] = $filename; // Update nama file gambar di data yang divalidasi
            }

            $barang->update($validated); // Memperbarui record barang di database

            return redirect()->route('admin.inventori.index')->with('success', 'Barang berhasil diperbarui'); // Redirect dengan pesan sukses
        } catch (\Exception $e) {
            Log::error('Error updating barang: ' . $e->getMessage()); // Mencatat error ke log
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Kembali ke form dengan pesan error
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang) // Metode untuk menghapus (soft delete) barang
    {
        try {
            DB::beginTransaction(); // Memulai transaksi database

            // Hapus gambar jika ada
            if ($barang->gambar && Storage::exists('public/barang/' . $barang->gambar)) { // Jika barang punya gambar
                Storage::delete('public/barang/' . $barang->gambar); // Hapus gambar dari storage
            }

            // Gunakan soft delete
            $barang->delete(); // Melakukan soft delete (mengisi kolom 'deleted_at')

            DB::commit(); // Menyimpan perubahan

            return response()->json([ // Mengembalikan respons JSON untuk request AJAX
                'success' => true,
                'message' => 'Barang berhasil diarsipkan' // Pesan sukses
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Mengembalikan perubahan jika terjadi error
            Log::error('Error deleting barang: ' . $e->getMessage()); // Mencatat error
            return response()->json([ // Mengembalikan respons JSON dengan error
                'success' => false,
                'message' => 'Gagal mengarsipkan barang: ' . $e->getMessage()
            ], 500); // Status kode 500 (Internal Server Error)
        }
    }

    /**
     * Display archived items.
     */
    public function archived() // Metode untuk menampilkan daftar barang yang sudah diarsipkan (di-soft delete)
    {
        $barangs = Barang::onlyTrashed()->with('kategori')->latest()->paginate(15); // Mengambil hanya barang yang di-soft delete, dengan relasi kategori, diurutkan terbaru, dan dipaginasi
        return view('admin.inventori.archived', compact('barangs')); // Mengembalikan view 'admin.inventori.archived' dengan data barang terarsip
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id) // Metode untuk mengembalikan (restore) barang yang sudah di-soft delete
    {
        try {
            $barang = Barang::onlyTrashed()->findOrFail($id); // Mencari barang yang di-soft delete berdasarkan ID
            $barang->restore(); // Mengembalikan barang (mengosongkan kolom 'deleted_at')
            return redirect()->route('admin.inventori.archived')->with('success', 'Barang berhasil dipulihkan'); // Redirect dengan pesan sukses
        } catch (\Exception $e) {
            Log::error('Error restoring barang: ' . $e->getMessage()); // Mencatat error
            return back()->with('error', 'Gagal memulihkan barang: ' . $e->getMessage()); // Kembali dengan pesan error
        }
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDelete($id) // Metode untuk menghapus permanen barang
    {
        try {
            DB::beginTransaction(); // Memulai transaksi database
            $barang = Barang::onlyTrashed()->findOrFail($id); // Mencari barang yang di-soft delete berdasarkan ID
            // Hapus semua riwayat transaksi
            BarangMasuk::where('barang_id', $barang->id)->delete(); // Menghapus semua transaksi barang masuk terkait barang ini
            BarangKeluar::where('barang_id', $barang->id)->delete(); // Menghapus semua transaksi barang keluar terkait barang ini
            // Hapus gambar jika ada
            if ($barang->gambar && Storage::exists('public/barang/' . $barang->gambar)) { // Jika barang punya gambar
                Storage::delete('public/barang/' . $barang->gambar); // Hapus gambar dari storage
            }
            // Hapus permanen
            $barang->forceDelete(); // Melakukan penghapusan permanen dari database
            DB::commit(); // Menyimpan perubahan
            return redirect()->route('admin.inventori.archived')->with('success', 'Barang berhasil dihapus permanen'); // Redirect dengan pesan sukses
        } catch (\Exception $e) {
            DB::rollBack(); // Mengembalikan perubahan jika terjadi error
            Log::error('Error force deleting barang: ' . $e->getMessage()); // Mencatat error
            return back()->with('error', 'Gagal menghapus barang: ' . $e->getMessage()); // Kembali dengan pesan error
        }
    }
}