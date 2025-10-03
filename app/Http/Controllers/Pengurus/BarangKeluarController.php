<?php

namespace App\Http\Controllers\Pengurus; // Mendefinisikan namespace untuk controller ini, menunjukkan lokasinya.

use App\Http\Controllers\Controller; // Mengimpor base Controller dari Laravel.
use App\Models\Barang; // Mengimpor model Barang yang merepresentasikan data barang inventaris.
use App\Models\Kategori; // Mengimpor model Kategori untuk data kategori barang.
use App\Models\BarangKeluar; // Mengimpor model BarangKeluar untuk mencatat transaksi barang keluar.
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP.
use Illuminate\Support\Facades\DB; // Mengimpor Facade DB untuk interaksi langsung dengan database (transaksi).
use Illuminate\Support\Facades\Log; // Mengimpor Facade Log untuk mencatat pesan log.
use Carbon\Carbon; // Mengimpor kelas Carbon untuk manipulasi tanggal dan waktu.

class BarangKeluarController extends Controller // Mendefinisikan kelas BarangKeluarController yang mewarisi dari base Controller.
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Metode untuk menampilkan daftar transaksi barang keluar.
    {
        // Memulai query untuk mengambil data BarangKeluar.
        // Eager load relasi 'barang' (dan di dalamnya 'kategori') serta 'user'.
        // Pastikan hanya mengambil data BarangKeluar yang terkait dengan Barang yang masih valid (belum dihapus).
        $query = BarangKeluar::with(['barang.kategori', 'user'])
            ->whereHas('barang'); // Memfilter agar hanya mencakup barang keluar yang barangnya masih ada.

        // Filter pencarian
        if ($request->filled('search')) { // Memeriksa apakah ada parameter 'search' di request.
            $search = $request->search; // Mengambil nilai dari parameter 'search'.
            $query->where(function ($q) use ($search) { // Menambahkan kondisi WHERE dengan grup OR.
                $q->whereHas('barang', function ($query) use ($search) { // Mencari di relasi 'barang'.
                    $query->where('nama', 'like', "%{$search}%") // Berdasarkan nama barang.
                        ->orWhere('kode_barang', 'like', "%{$search}%"); // Atau berdasarkan kode barang.
                })
                    ->orWhere('keterangan', 'like', "%{$search}%"); // Atau mencari di kolom 'keterangan' transaksi barang keluar.
            });
        }

        // Filter tanggal
        if ($request->filled('tanggal_mulai')) { // Memeriksa apakah ada parameter 'tanggal_mulai'.
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai); // Filter transaksi dari tanggal mulai.
        }

        if ($request->filled('tanggal_selesai')) { // Memeriksa apakah ada parameter 'tanggal_selesai'.
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai); // Filter transaksi sampai tanggal selesai.
        }

        // Menjalankan query, mengurutkan hasil berdasarkan yang terbaru (latest()),
        // memaginasi 15 item per halaman, dan mempertahankan parameter query string untuk link paginasi.
        $barangKeluars = $query->latest()->paginate(15)->withQueryString();

        return view('pengurus.barang.keluar.index', compact('barangKeluars')); // Mengembalikan view 'pengurus.barang.keluar.index' dengan data transaksi barang keluar.
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() // Metode untuk menampilkan form pembuatan transaksi barang keluar baru.
    {
        $kategoris = Kategori::orderBy('nama', 'asc')->get(); // Mengambil semua kategori, diurutkan berdasarkan nama.
        $barangs = collect([]); // Membuat koleksi kosong untuk barang, akan diisi via AJAX.
        return view('pengurus.barang.keluar.create', compact('kategoris', 'barangs')); // Mengembalikan view form barang keluar.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Metode untuk menyimpan transaksi barang keluar baru ke database.
    {
        $validated = $request->validate([ // Memvalidasi input dari request.
            'kategori_id' => 'required|exists:kategori,id', // 'kategori_id' wajib dan harus ada di tabel 'kategori'.
            'barang_id'  => 'required|exists:barang,id', // 'barang_id' wajib dan harus ada di tabel 'barang'.
            'tanggal'    => 'required|date', // 'tanggal' wajib dan harus format tanggal.
            'jumlah'     => 'required|integer|min:1', // 'jumlah' wajib, harus integer, minimal 1.
            'keterangan' => 'nullable|string|max:255' // 'keterangan' bisa null, string, maks 255 karakter.
        ], [ // Pesan error kustom untuk validasi.
            'kategori_id.required' => 'Kategori harus dipilih',
            'barang_id.required' => 'Barang harus dipilih',
            'jumlah.min'        => 'Jumlah harus minimal 1',
        ]);

        try { // Blok try-catch untuk menangani potensi error selama transaksi database.
            DB::beginTransaction(); // Memulai transaksi database.

            // Cek stok barang
            $barang = Barang::find($validated['barang_id']); // Mencari data barang yang dipilih.
            if ($barang->stok < $validated['jumlah']) { // Memeriksa apakah stok barang tidak mencukupi.
                return back()->withInput() // Jika tidak cukup, kembalikan ke form dengan input sebelumnya.
                    ->with('error', 'Stok barang tidak mencukupi. Stok tersedia: ' . $barang->stok . ' ' . $barang->satuan); // Serta pesan error.
            }

            // Tambahkan user_id dari yang sedang login
            $validated['user_id'] = auth()->id(); // Menambahkan ID user yang sedang login ke data yang divalidasi.

            // Format tanggal jika diperlukan (misal dari string ke Y-m-d)
            if (is_string($validated['tanggal'])) {
                $validated['tanggal'] = Carbon::parse($validated['tanggal'])->format('Y-m-d');
            }

            // Pastikan keterangan tidak null (jika kosong, set string kosong)
            $validated['keterangan'] = $validated['keterangan'] ?? '';

            // Simpan data barang keluar
            $barangKeluar = BarangKeluar::create($validated); // Membuat record baru di tabel 'barang_keluar'.

            // Update stok barang
            $barang->stok -= $validated['jumlah']; // Mengurangi stok barang.
            $barang->save(); // Menyimpan perubahan stok.

            DB::commit(); // Mengkonfirmasi dan menyimpan semua perubahan transaksi ke database.

            return redirect()->route('pengurus.barang.keluar') // Mengarahkan kembali ke halaman index barang keluar.
                ->with('success', 'Barang keluar berhasil dicatat'); // Menampilkan pesan sukses.
        } catch (\Exception $e) { // Menangkap exception jika terjadi error.
            DB::rollBack(); // Membatalkan semua perubahan transaksi.
            Log::error('Error creating barang keluar: ' . $e->getMessage()); // Mencatat error ke log.
            return back()->withInput() // Mengarahkan kembali dengan input sebelumnya.
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Menampilkan pesan error.
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangKeluar $barangKeluar) // Metode untuk menampilkan detail satu transaksi barang keluar.
    {
        $barangKeluar->load(['barang.kategori', 'user']); // Eager load relasi untuk 'barang' (dengan 'kategori') dan 'user'.
        return view('pengurus.barang.keluar.show', compact('barangKeluar')); // Mengembalikan view 'pengurus.barang.keluar.show' dengan data transaksi.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangKeluar $barangKeluar) // Metode untuk menampilkan form edit transaksi barang keluar.
    {
        $kategoris = Kategori::orderBy('nama', 'asc')->get(); // Mengambil semua kategori.
        $barangs = Barang::where('status', 'aktif')->orderBy('nama', 'asc')->get(); // Mengambil semua barang aktif.

        return view('pengurus.barang.keluar.edit', compact('barangKeluar', 'kategoris', 'barangs')); // Mengembalikan view form edit.
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangKeluar $barangKeluar) // Metode untuk memperbarui transaksi barang keluar.
    {
        $validated = $request->validate([ // Memvalidasi input dari request.
            'kategori_id' => 'required|exists:kategori,id',
            'barang_id'  => 'required|exists:barang,id',
            'tanggal'    => 'required|date',
            'jumlah'     => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction(); // Memulai transaksi database.

            // Hitung selisih jumlah untuk update stok
            $selisih = $validated['jumlah'] - $barangKeluar->jumlah; // Menghitung perbedaan jumlah baru dan jumlah lama.

            // Format tanggal jika diperlukan
            if (is_string($validated['tanggal'])) {
                $validated['tanggal'] = Carbon::parse($validated['tanggal'])->format('Y-m-d');
            }

            // Pastikan keterangan tidak null
            $validated['keterangan'] = $validated['keterangan'] ?? '';

            // Cek stok jika jumlah bertambah (selisih > 0)
            if ($selisih > 0) { // Jika jumlah baru lebih besar dari jumlah lama.
                $barang = Barang::find($validated['barang_id']); // Ambil data barang.
                if ($barang->stok < $selisih) { // Cek apakah stok cukup untuk menampung penambahan ini.
                    return back()->withInput()
                        ->with('error', 'Stok barang tidak mencukupi. Stok tersedia: ' . $barang->stok . ' ' . $barang->satuan);
                }
            }

            // Update data barang keluar
            $barangKeluar->update($validated); // Memperbarui record transaksi barang keluar.

            // Update stok barang hanya jika barang_id tidak berubah
            if ($barangKeluar->barang_id == $validated['barang_id']) { // Jika barang yang dipilih tetap sama.
                $barang = Barang::find($validated['barang_id']); // Ambil data barang.
                $barang->stok -= $selisih; // Sesuaikan stok berdasarkan selisih.
                $barang->save(); // Simpan perubahan stok.
            } else {
                // Jika barang berubah, kembalikan stok lama dan kurangi dari stok baru
                $barangLama = Barang::find($barangKeluar->barang_id); // Ambil barang lama.
                $barangLama->stok += $barangKeluar->jumlah; // Kembalikan stok yang sebelumnya dikeluarkan.
                $barangLama->save(); // Simpan perubahan.

                $barangBaru = Barang::find($validated['barang_id']); // Ambil barang baru.
                if ($barangBaru->stok < $validated['jumlah']) { // Cek stok barang baru.
                    return back()->withInput()
                        ->with('error', 'Stok barang baru tidak mencukupi. Stok tersedia: ' . $barangBaru->stok . ' ' . $barangBaru->satuan);
                }
                $barangBaru->stok -= $validated['jumlah']; // Kurangi stok barang baru.
                $barangBaru->save(); // Simpan perubahan.
            }

            DB::commit(); // Mengkonfirmasi dan menyimpan semua perubahan transaksi.

            return redirect()->route('pengurus.barang.keluar') // Mengarahkan kembali ke halaman index.
                ->with('success', 'Data barang keluar berhasil diperbarui'); // Menampilkan pesan sukses.
        } catch (\Exception $e) { // Menangkap exception jika terjadi error.
            DB::rollBack(); // Membatalkan semua perubahan transaksi.
            Log::error('Error updating barang keluar: ' . $e->getMessage()); // Mencatat error ke log.
            return back()->withInput() // Mengarahkan kembali dengan input sebelumnya.
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Menampilkan pesan error.
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangKeluar $barangKeluar) // Metode untuk menghapus transaksi barang keluar.
    {
        try {
            DB::beginTransaction(); // Memulai transaksi database.

            // Tambahkan stok barang (karena transaksi barang keluar dihapus, stok harus dikembalikan)
            $barang = Barang::find($barangKeluar->barang_id); // Ambil data barang yang terkait.
            $barang->stok += $barangKeluar->jumlah; // Tambahkan kembali jumlah stok.
            $barang->save(); // Simpan perubahan stok.

            // Hapus data barang keluar
            $barangKeluar->delete(); // Menghapus record transaksi barang keluar.

            DB::commit(); // Mengkonfirmasi dan menyimpan semua perubahan transaksi.

            return response()->json([ // Mengembalikan respon JSON sukses (biasanya untuk request AJAX).
                'success' => true,
                'message' => 'Data barang keluar berhasil dihapus'
            ]);
        } catch (\Exception $e) { // Menangkap exception jika terjadi error.
            DB::rollBack(); // Membatalkan semua perubahan transaksi.
            Log::error('Error deleting barang keluar: ' . $e->getMessage()); // Mencatat error ke log.
            return response()->json([ // Mengembalikan respon JSON error.
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500); // Status HTTP 500 (Internal Server Error).
        }
    }

    /**
     * Get barang details for AJAX request
     */
    public function getBarangDetails($id) // Metode untuk mengambil detail barang via AJAX.
    {
        try {
            $barang = Barang::with('kategori')->find($id); // Mencari barang berdasarkan ID, eager load kategori.

            if (!$barang) { // Jika barang tidak ditemukan.
                return response()->json([ // Mengembalikan respon JSON error.
                    'success' => false,
                    'message' => 'Barang tidak ditemukan'
                ], 404); // Status HTTP 404 (Not Found).
            }

            return response()->json([ // Mengembalikan respon JSON sukses dengan detail barang.
                'success' => true,
                'data' => [
                    'id' => $barang->id,
                    'nama' => $barang->nama,
                    'kode_barang' => $barang->kode_barang,
                    'stok' => $barang->stok,
                    'satuan' => $barang->satuan,
                    'harga' => $barang->harga,
                    'kategori' => $barang->kategori->nama ?? '-' // Nama kategori, atau '-' jika tidak ada.
                ]
            ]);
        } catch (\Exception $e) { // Menangkap exception jika terjadi error.
            Log::error('Error getting barang details: ' . $e->getMessage()); // Mencatat error ke log.
            return response()->json([ // Mengembalikan respon JSON error.
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500); // Status HTTP 500 (Internal Server Error).
        }
    }

    /**
     * Get barang by kategori for AJAX request
     */
    public function getBarangByKategori($kategoriId) // Metode untuk mengambil daftar barang berdasarkan kategori via AJAX.
    {
        try {
            $barangs = Barang::with('kategori') // Eager load kategori.
                ->where('kategori_id', $kategoriId) // Filter berdasarkan ID kategori.
                ->where('status', 'aktif') // Hanya barang dengan status 'aktif'.
                ->where('stok', '>', 0) // Hanya barang dengan stok lebih dari 0.
                ->orderBy('nama', 'asc') // Urutkan berdasarkan nama.
                ->get() // Ambil semua hasil.
                ->map(function ($barang) { // Memetakan koleksi untuk format output yang spesifik.
                    return [
                        'id' => $barang->id,
                        'nama' => $barang->nama,
                        'kode_barang' => $barang->kode_barang,
                        'stok' => $barang->stok,
                        'satuan' => $barang->satuan,
                        'harga' => $barang->harga,
                        'gambar' => $barang->gambar,
                        'kategori_nama' => $barang->kategori->nama ?? '-'
                    ];
                });

            return response()->json([ // Mengembalikan respon JSON sukses dengan daftar barang.
                'success' => true,
                'data' => $barangs
            ]);
        } catch (\Exception $e) { // Menangkap exception jika terjadi error.
            Log::error('Error getting barang by kategori: ' . $e->getMessage()); // Mencatat error ke log.
            return response()->json([ // Mengembalikan respon JSON error.
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500); // Status HTTP 500 (Internal Server Error).
        }
    }
}