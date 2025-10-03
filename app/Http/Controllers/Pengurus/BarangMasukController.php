<?php

namespace App\Http\Controllers\Pengurus; // Mendefinisikan namespace untuk controller ini, menempatkannya di bawah direktori Pengurus.

use App\Http\Controllers\Controller;    // Mengimpor kelas Controller dasar dari Laravel.
use App\Models\Barang;                  // Mengimpor model Barang, yang merepresentasikan tabel 'barang' di database.
use App\Models\Kategori;                // Mengimpor model Kategori, yang merepresentasikan tabel 'kategori' di database.
use App\Models\BarangMasuk;             // Mengimpor model BarangMasuk, yang merepresentasikan tabel 'barang_masuk' di database.
use Illuminate\Http\Request;            // Mengimpor kelas Request untuk menangani input dari pengguna.
use Illuminate\Support\Facades\DB;      // Mengimpor facade DB untuk transaksi database manual.
use Illuminate\Support\Facades\Log;     // Mengimpor facade Log untuk mencatat pesan error atau informasi.
use Carbon\Carbon;                      // Mengimpor kelas Carbon untuk manipulasi tanggal dan waktu.

class BarangMasukController extends Controller // Mendefinisikan kelas BarangMasukController yang merupakan turunan dari Controller.
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Metode index untuk menampilkan daftar semua data barang masuk.
    {
        // Query dengan eager loading dan filter hanya yang memiliki barang valid
        $query = BarangMasuk::with(['barang.kategori', 'user']) // Memulai query untuk model BarangMasuk. 'with' digunakan untuk eager loading relasi 'barang' (yang juga meload 'kategori' dari barang) dan relasi 'user'. Ini menghindari masalah N+1 query.
            ->whereHas('barang'); // Memastikan hanya data BarangMasuk yang memiliki relasi 'barang' yang valid (barang terkait tidak null atau sudah dihapus).

        // Filter pencarian
        if ($request->filled('search')) { // Memeriksa apakah ada parameter 'search' dalam request dan tidak kosong.
            $search = $request->search;   // Menyimpan nilai pencarian dari request.
            $query->where(function ($q) use ($search) { // Menambahkan kondisi WHERE ke query. Menggunakan callback function untuk mengelompokkan kondisi OR.
                $q->whereHas('barang', function ($query) use ($search) { // Mencari di dalam relasi 'barang'.
                    $query->where('nama', 'like', "%{$search}%") // Mencari nama barang yang mengandung string $search.
                        ->orWhere('kode_barang', 'like', "%{$search}%"); // Atau mencari kode_barang yang mengandung string $search.
                })
                    ->orWhere('keterangan', 'like', "%{$search}%"); // Atau mencari keterangan barang masuk yang mengandung string $search.
            });
        }

        // Filter tanggal
        if ($request->filled('tanggal_mulai')) { // Memeriksa apakah ada parameter 'tanggal_mulai' dalam request dan tidak kosong.
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai); // Menambahkan kondisi WHERE untuk tanggal, mencari yang tanggalnya lebih besar atau sama dengan tanggal_mulai.
        }

        if ($request->filled('tanggal_selesai')) { // Memeriksa apakah ada parameter 'tanggal_selesai' dalam request dan tidak kosong.
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai); // Menambahkan kondisi WHERE untuk tanggal, mencari yang tanggalnya lebih kecil atau sama dengan tanggal_selesai.
        }

        $barangMasuks = $query->latest()->paginate(15)->withQueryString(); // Menjalankan query: mengurutkan berdasarkan tanggal terbaru ('latest'), memaginasi hasilnya menjadi 15 item per halaman, dan mempertahankan parameter query string saat navigasi paginasi.

        return view('pengurus.barang.masuk.index', compact('barangMasuks')); // Mengembalikan view 'pengurus.barang.masuk.index' dengan data $barangMasuks.
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() // Metode create untuk menampilkan form penambahan data barang masuk baru.
    {
        $kategoris = Kategori::orderBy('nama', 'asc')->get(); // Mengambil semua data kategori, diurutkan berdasarkan nama secara ascending.
        // Tidak perlu mengirim semua barang, karena akan diambil via AJAX
        return view('pengurus.barang.masuk.create', compact('kategoris')); // Mengembalikan view 'pengurus.barang.masuk.create' dengan data $kategoris.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Metode store untuk menyimpan data barang masuk baru ke database.
    {
        $validated = $request->validate([ // Memvalidasi data yang masuk dari request.
            'kategori_id' => 'required|exists:kategori,id', // Kategori_id wajib diisi dan harus ada di tabel 'kategori' kolom 'id'.
            'barang_id'  => 'required|exists:barang,id',  // Barang_id wajib diisi dan harus ada di tabel 'barang' kolom 'id'.
            'tanggal'    => 'required|date',             // Tanggal wajib diisi dan harus berformat tanggal.
            'jumlah'     => 'required|integer|min:1',    // Jumlah wajib diisi, harus berupa integer, dan minimal 1.
            'keterangan' => 'nullable|string|max:255'    // Keterangan bersifat opsional, harus berupa string, dan maksimal 255 karakter.
        ], [ // Pesan error kustom untuk validasi.
            'kategori_id.required' => 'Kategori harus dipilih', // Pesan jika kategori_id kosong.
            'barang_id.required' => 'Barang harus dipilih',     // Pesan jika barang_id kosong.
            'jumlah.min'        => 'Jumlah harus minimal 1'    // Pesan jika jumlah kurang dari 1.
        ]);

        try { // Blok try-catch untuk menangani potensi error saat menyimpan data dan melakukan transaksi database.
            DB::beginTransaction(); // Memulai transaksi database. Jika ada error di tengah jalan, semua perubahan akan dibatalkan (rollback).

            // Tambahkan user_id dari yang sedang login
            $validated['user_id'] = auth()->id(); // Menambahkan user_id dari pengguna yang sedang login ke data yang akan disimpan.

            // Format tanggal jika diperlukan
            if (is_string($validated['tanggal'])) { // Memeriksa apakah 'tanggal' masih berupa string.
                $validated['tanggal'] = Carbon::parse($validated['tanggal'])->format('Y-m-d'); // Mengubah format tanggal menjadi 'YYYY-MM-DD' menggunakan Carbon.
            }

            // Simpan data barang masuk
            $barangMasuk = BarangMasuk::create($validated); // Membuat record baru di tabel 'barang_masuk' dengan data yang sudah divalidasi.

            // Update stok barang
            $barang = Barang::find($validated['barang_id']); // Mencari data barang berdasarkan barang_id.
            $barang->stok += $validated['jumlah'];         // Menambahkan jumlah barang masuk ke stok barang.
            $barang->save();                               // Menyimpan perubahan stok barang ke database.

            DB::commit(); // Menyelesaikan transaksi database. Semua perubahan disimpan secara permanen.

            return redirect()->route('pengurus.barang.masuk') // Mengarahkan kembali ke halaman daftar barang masuk.
                ->with('success', 'Barang masuk berhasil dicatat'); // Mengirimkan pesan sukses ke view.
        } catch (\Exception $e) { // Menangkap setiap jenis exception (error).
            DB::rollBack(); // Membatalkan semua perubahan database yang dilakukan dalam transaksi.
            Log::error('Error creating barang masuk: ' . $e->getMessage()); // Mencatat detail error ke file log.
            return back()->withInput() // Mengarahkan kembali ke halaman sebelumnya dengan input yang sudah diisi.
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Mengirimkan pesan error ke view.
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangMasuk $barangMasuk) // Metode show untuk menampilkan detail satu data barang masuk. Menggunakan Route Model Binding untuk langsung mendapatkan instance BarangMasuk.
    {
        $barangMasuk->load(['barang.kategori', 'user']); // Melakukan eager loading untuk relasi 'barang' (dan kategori di dalamnya) serta 'user' pada instance $barangMasuk yang sudah ada.
        return view('pengurus.barang.masuk.show', compact('barangMasuk')); // Mengembalikan view 'pengurus.barang.masuk.show' dengan data $barangMasuk.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangMasuk $barangMasuk) // Metode edit untuk menampilkan form pengubahan data barang masuk. Menggunakan Route Model Binding.
    {
        $kategoris = Kategori::orderBy('nama', 'asc')->get(); // Mengambil semua data kategori untuk dropdown.
        // Ambil semua barang aktif untuk dropdown
        $barangs = Barang::where('status', 'aktif')->orderBy('nama', 'asc')->get(); // Mengambil semua data barang yang statusnya 'aktif' untuk dropdown.

        return view('pengurus.barang.masuk.edit', compact('barangMasuk', 'kategoris', 'barangs')); // Mengembalikan view 'pengurus.barang.masuk.edit' dengan data $barangMasuk, $kategoris, dan $barangs.
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangMasuk $barangMasuk) // Metode update untuk memperbarui data barang masuk di database. Menggunakan Route Model Binding.
    {
        $validated = $request->validate([ // Memvalidasi data yang masuk dari request, sama seperti pada metode store.
            'kategori_id' => 'required|exists:kategori,id',
            'barang_id'  => 'required|exists:barang,id',
            'tanggal'    => 'required|date',
            'jumlah'     => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction(); // Memulai transaksi database.

            // Hitung selisih jumlah untuk update stok
            $selisih = $validated['jumlah'] - $barangMasuk->jumlah; // Menghitung selisih antara jumlah baru yang dimasukkan dan jumlah lama yang tersimpan.

            // Format tanggal jika diperlukan
            if (is_string($validated['tanggal'])) { // Memeriksa format tanggal.
                $validated['tanggal'] = Carbon::parse($validated['tanggal'])->format('Y-m-d'); // Mengubah format tanggal.
            }

            // Update data barang masuk
            $barangMasuk->update($validated); // Memperbarui record BarangMasuk dengan data yang sudah divalidasi.

            // Update stok barang hanya jika barang_id tidak berubah
            if ($barangMasuk->barang_id == $validated['barang_id']) { // Jika barang_id (barang yang masuk) tidak berubah.
                $barang = Barang::find($validated['barang_id']); // Mencari data barang yang sama.
                $barang->stok += $selisih;                        // Menambahkan/mengurangi stok berdasarkan selisih.
                $barang->save();                                  // Menyimpan perubahan stok.
            } else {
                // Jika barang berubah, kembalikan stok lama dan tambahkan ke stok baru
                $barangLama = Barang::find($barangMasuk->barang_id); // Mencari barang lama.
                $barangLama->stok -= $barangMasuk->jumlah;           // Mengurangi stok barang lama sejumlah barang masuk sebelumnya.
                $barangLama->save();                                 // Menyimpan perubahan stok barang lama.

                $barangBaru = Barang::find($validated['barang_id']); // Mencari barang baru.
                $barangBaru->stok += $validated['jumlah'];           // Menambahkan stok barang baru sejumlah barang masuk yang baru.
                $barangBaru->save();                                 // Menyimpan perubahan stok barang baru.
            }

            DB::commit(); // Menyelesaikan transaksi database.

            return redirect()->route('pengurus.barang.masuk') // Mengarahkan kembali ke halaman daftar barang masuk.
                ->with('success', 'Data barang masuk berhasil diperbarui'); // Mengirimkan pesan sukses.
        } catch (\Exception $e) {
            DB::rollBack(); // Membatalkan transaksi.
            Log::error('Error updating barang masuk: ' . $e->getMessage()); // Mencatat error.
            return back()->withInput() // Kembali ke halaman sebelumnya dengan input yang sudah diisi.
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Mengirimkan pesan error.
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangMasuk $barangMasuk) // Metode destroy untuk menghapus data barang masuk. Menggunakan Route Model Binding.
    {
        try {
            DB::beginTransaction(); // Memulai transaksi database.

            // Kurangi stok barang
            $barang = Barang::find($barangMasuk->barang_id); // Mencari data barang yang terkait dengan barang masuk yang akan dihapus.

            // Pastikan stok tidak negatif
            if ($barang->stok < $barangMasuk->jumlah) { // Memeriksa apakah stok barang mencukupi untuk dikurangi.
                return response()->json([ // Mengembalikan respons JSON dengan status error jika stok tidak cukup.
                    'success' => false,
                    'message' => 'Tidak dapat menghapus karena stok barang tidak mencukupi'
                ], 400); // Kode status HTTP 400 Bad Request.
            }

            $barang->stok -= $barangMasuk->jumlah; // Mengurangi stok barang sejumlah barang masuk yang akan dihapus.
            $barang->save();                       // Menyimpan perubahan stok.

            // Hapus data barang masuk
            $barangMasuk->delete(); // Menghapus record barang masuk dari database.

            DB::commit(); // Menyelesaikan transaksi database.

            return response()->json([ // Mengembalikan respons JSON dengan status sukses.
                'success' => true,
                'message' => 'Data barang masuk berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Membatalkan transaksi.
            Log::error('Error deleting barang masuk: ' . $e->getMessage()); // Mencatat error.
            return response()->json([ // Mengembalikan respons JSON dengan status error.
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500); // Kode status HTTP 500 Internal Server Error.
        }
    }

    /**
     * Get barang details for AJAX request
     */
    public function getBarangDetails($id) // Metode untuk mendapatkan detail barang berdasarkan ID, biasanya dipanggil melalui AJAX.
    {
        try {
            $barang = Barang::with('kategori')->find($id); // Mencari barang berdasarkan ID dan melakukan eager loading relasi 'kategori'.

            if (!$barang) { // Jika barang tidak ditemukan.
                return response()->json([ // Mengembalikan respons JSON dengan status error.
                    'success' => false,
                    'message' => 'Barang tidak ditemukan'
                ], 404); // Kode status HTTP 404 Not Found.
            }

            return response()->json([ // Mengembalikan respons JSON dengan data barang.
                'success' => true,
                'data' => [
                    'id' => $barang->id,
                    'nama' => $barang->nama,
                    'kode_barang' => $barang->kode_barang,
                    'stok' => $barang->stok,
                    'satuan' => $barang->satuan,
                    'harga' => $barang->harga,
                    'kategori' => $barang->kategori->nama ?? '-' // Mengambil nama kategori, jika tidak ada, gunakan '-'.
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting barang details: ' . $e->getMessage()); // Mencatat error.
            return response()->json([ // Mengembalikan respons JSON dengan status error.
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500); // Kode status HTTP 500 Internal Server Error.
        }
    }

    /**
     * Get barang by kategori for AJAX request
     */
    public function getBarangByKategori($kategoriId) // Metode untuk mendapatkan daftar barang berdasarkan ID kategori, biasanya dipanggil melalui AJAX.
    {
        try {
            $barangs = Barang::with('kategori') // Memulai query untuk model Barang dengan eager loading relasi 'kategori'.
                ->where('kategori_id', $kategoriId) // Memfilter barang berdasarkan kategori_id yang diberikan.
                ->where('status', 'aktif')         // Memfilter hanya barang dengan status 'aktif'.
                ->orderBy('nama', 'asc')           // Mengurutkan hasil berdasarkan nama barang secara ascending.
                ->get()                            // Menjalankan query dan mendapatkan semua hasilnya.
                ->map(function ($barang) {         // Melakukan transformasi pada setiap item barang.
                    return [ // Mengembalikan array dengan data yang spesifik.
                        'id' => $barang->id,
                        'nama' => $barang->nama,
                        'kode_barang' => $barang->kode_barang,
                        'stok' => $barang->stok,
                        'satuan' => $barang->satuan,
                        'harga' => $barang->harga,
                        'gambar' => $barang->gambar,
                        'kategori_nama' => $barang->kategori->nama ?? '-' // Mengambil nama kategori, jika tidak ada, gunakan '-'.
                    ];
                });

            return response()->json([ // Mengembalikan respons JSON dengan daftar barang.
                'success' => true,
                'data' => $barangs
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting barang by kategori: ' . $e->getMessage()); // Mencatat error.
            return response()->json([ // Mengembalikan respons JSON dengan status error.
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500); // Kode status HTTP 500 Internal Server Error.
        }
    }
}