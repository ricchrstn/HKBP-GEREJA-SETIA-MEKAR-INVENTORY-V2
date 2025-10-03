<?php
namespace App\Http\Controllers\Pengurus; // Mendefinisikan namespace untuk controller ini, menempatkannya di bawah direktori Pengurus.

use App\Http\Controllers\Controller;    // Mengimpor kelas Controller dasar dari Laravel.
use App\Models\Barang;                  // Mengimpor model Barang untuk berinteraksi dengan tabel 'barang'.
use App\Models\Perawatan;               // Mengimpor model Perawatan untuk berinteraksi dengan tabel 'perawatan'.
use App\Models\Kategori;                // Mengimpor model Kategori untuk berinteraksi dengan tabel 'kategori'.
use Illuminate\Http\Request;            // Mengimpor kelas Request untuk menangani input dari pengguna.
use Illuminate\Support\Facades\DB;      // Mengimpor facade DB untuk transaksi database manual.
use Illuminate\Support\Facades\Log;     // Mengimpor facade Log untuk mencatat pesan error atau informasi.
use Carbon\Carbon;                      // Mengimpor kelas Carbon untuk manipulasi tanggal dan waktu.

class PerawatanController extends Controller // Mendefinisikan kelas PerawatanController yang merupakan turunan dari Controller.
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Metode index untuk menampilkan daftar semua data perawatan.
    {
        // Memulai query untuk model Perawatan. Menggunakan eager loading untuk relasi 'barang' (dan 'kategori' di dalamnya) serta 'user'.
        // Filter awal: hanya tampilkan data perawatan yang memiliki relasi barang yang valid.
        $query = Perawatan::with(['barang.kategori', 'user'])
            ->whereHas('barang');

        // Filter pencarian
        if ($request->filled('search')) { // Memeriksa apakah ada parameter 'search' dalam request dan tidak kosong.
            $search = $request->search;   // Menyimpan nilai pencarian dari request.
            $query->where(function ($q) use ($search) { // Menambahkan kondisi WHERE ke query dengan pengelompokan OR.
                $q->whereHas('barang', function ($query) use ($search) { // Mencari di dalam relasi 'barang'.
                    $query->where('nama', 'like', "%{$search}%") // Mencari nama barang yang mengandung string $search.
                          ->orWhere('kode_barang', 'like', "%{$search}%"); // Atau kode_barang yang mengandung string $search.
                })
                ->orWhere('jenis_perawatan', 'like', "%{$search}%") // Atau mencari jenis_perawatan yang mengandung string $search.
                ->orWhere('keterangan', 'like', "%{$search}%");     // Atau mencari keterangan yang mengandung string $search.
            });
        }

        // Filter status
        if ($request->filled('status')) { // Memeriksa apakah ada parameter 'status' dalam request dan tidak kosong.
            $query->where('status', $request->status); // Menambahkan kondisi WHERE untuk memfilter berdasarkan status perawatan.
        }

        // Filter tanggal
        if ($request->filled('tanggal_mulai')) { // Memeriksa apakah ada parameter 'tanggal_mulai' dalam request dan tidak kosong.
            $query->whereDate('tanggal_perawatan', '>=', $request->tanggal_mulai); // Menambahkan kondisi WHERE untuk tanggal perawatan, mencari yang tanggalnya lebih besar atau sama dengan tanggal_mulai.
        }

        if ($request->filled('tanggal_selesai')) { // Memeriksa apakah ada parameter 'tanggal_selesai' dalam request dan tidak kosong.
            $query->whereDate('tanggal_perawatan', '<=', $request->tanggal_selesai); // Menambahkan kondisi WHERE untuk tanggal perawatan, mencari yang tanggalnya lebih kecil atau sama dengan tanggal_selesai.
        }

        $perawatans = $query->latest()->paginate(15)->withQueryString(); // Menjalankan query: mengurutkan berdasarkan tanggal terbaru ('latest'), memaginasi hasilnya menjadi 15 item per halaman, dan mempertahankan parameter query string saat navigasi paginasi.

        return view('pengurus.perawatan.index', compact('perawatans')); // Mengembalikan view 'pengurus.perawatan.index' dengan data $perawatans.
    }

/**
 * Show the form for creating a new resource.
 */
public function create() // Metode create untuk menampilkan form penambahan data perawatan baru.
{
    $kategoris = Kategori::orderBy('nama')->get(); // Mengambil semua data kategori, diurutkan berdasarkan nama.
    $barangs = Barang::where('status', 'aktif')->get(); // Mengambil semua data barang yang berstatus 'aktif'.
    return view('pengurus.perawatan.create', compact('kategoris', 'barangs')); // Mengembalikan view 'pengurus.perawatan.create' dengan data $kategoris dan $barangs.
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Metode store untuk menyimpan data perawatan baru ke database.
    {
        $validated = $request->validate([ // Memvalidasi data yang masuk dari request.
            'barang_id'         => 'required|exists:barang,id',         // Barang_id wajib diisi dan harus ada di tabel 'barang' kolom 'id'.
            'tanggal_perawatan' => 'required|date',                     // Tanggal perawatan wajib diisi dan harus berformat tanggal.
            'jenis_perawatan'   => 'required|string|max:255',           // Jenis perawatan wajib diisi, string, maksimal 255 karakter.
            'biaya'             => 'nullable|numeric|min:0',            // Biaya opsional, harus angka, minimal 0.
            'keterangan'        => 'nullable|string|max:255'            // Keterangan opsional, string, maksimal 255 karakter.
        ], [ // Pesan error kustom untuk validasi.
            'barang_id.required'         => 'Barang harus dipilih',
            'tanggal_perawatan.required' => 'Tanggal perawatan harus diisi',
            'jenis_perawatan.required'   => 'Jenis perawatan harus diisi',
            'biaya.numeric'                => 'Biaya harus berupa angka',
            'biaya.min'                   => 'Biaya tidak boleh negatif'
        ]);

        try { // Blok try-catch untuk menangani potensi error saat menyimpan data dan melakukan transaksi database.
            DB::beginTransaction(); // Memulai transaksi database.

            // Tambahkan user_id dari yang sedang login
            $validated['user_id'] = auth()->id(); // Menambahkan user_id dari pengguna yang sedang login.
            $validated['status'] = 'proses';     // Menetapkan status awal perawatan sebagai 'proses'.

            // Format tanggal jika diperlukan
            if (is_string($validated['tanggal_perawatan'])) { // Memeriksa format tanggal.
                $validated['tanggal_perawatan'] = Carbon::parse($validated['tanggal_perawatan'])->format('Y-m-d'); // Mengubah format tanggal menjadi 'YYYY-MM-DD'.
            }

            // Pastikan biaya dan keterangan tidak null (memberikan nilai default jika kosong)
            $validated['biaya'] = $validated['biaya'] ?? 0;
            $validated['keterangan'] = $validated['keterangan'] ?? '';

            // Simpan data perawatan
            $perawatan = Perawatan::create($validated); // Membuat record baru di tabel 'perawatan' dengan data yang sudah divalidasi.

            // Update status barang menjadi perawatan jika masih aktif
            $barang = Barang::find($validated['barang_id']); // Mencari data barang yang terkait.
            if ($barang->status === 'aktif') { // Jika status barang masih 'aktif'.
                $barang->status = 'perawatan'; // Mengubah status barang menjadi 'perawatan'.
                $barang->save();               // Menyimpan perubahan status barang.
            }

            DB::commit(); // Menyelesaikan transaksi database.

            return redirect()->route('pengurus.perawatan.index') // Mengarahkan kembali ke halaman daftar perawatan.
                ->with('success', 'Data perawatan berhasil dicatat'); // Mengirimkan pesan sukses.
        } catch (\Exception $e) { // Menangkap setiap jenis exception.
            DB::rollBack(); // Membatalkan semua perubahan database yang dilakukan dalam transaksi.
            Log::error('Error creating perawatan: ' . $e->getMessage()); // Mencatat detail error ke file log.
            return back()->withInput() // Mengarahkan kembali ke halaman sebelumnya dengan input yang sudah diisi.
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Mengirimkan pesan error.
        }
    }

        /**
     * Get barang by kategori ID (AJAX).
     */
    public function getBarangByKategori($kategoriId) // Metode untuk mendapatkan daftar barang berdasarkan ID kategori, biasanya dipanggil melalui AJAX.
    {
        try {
            $barangs = Barang::where('kategori_id', $kategoriId) // Memfilter barang berdasarkan kategori_id yang diberikan.
                ->where('status', 'aktif') // Hanya barang yang aktif yang bisa dipilih untuk perawatan.
                ->orderBy('nama')
                ->get();

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

    /**
     * Display the specified resource.
     */
    public function show(Perawatan $perawatan) // Metode show untuk menampilkan detail satu data perawatan. Menggunakan Route Model Binding.
    {
        $perawatan->load(['barang.kategori', 'user']); // Melakukan eager loading untuk relasi 'barang' (dan kategori di dalamnya) serta 'user'.
        return view('pengurus.perawatan.show', compact('perawatan')); // Mengembalikan view 'pengurus.perawatan.show' dengan data $perawatan.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perawatan $perawatan) // Metode edit untuk menampilkan form pengubahan data perawatan. Menggunakan Route Model Binding.
    {
        $barangs = Barang::where('status', 'aktif')->get(); // Mengambil semua data barang yang berstatus 'aktif' untuk dropdown.
        return view('pengurus.perawatan.edit', compact('perawatan', 'barangs')); // Mengembalikan view 'pengurus.perawatan.edit' dengan data $perawatan dan $barangs.
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perawatan $perawatan) // Metode update untuk memperbarui data perawatan di database. Menggunakan Route Model Binding.
    {
        $validated = $request->validate([ // Memvalidasi data yang masuk dari request.
            'barang_id'         => 'required|exists:barang,id',
            'tanggal_perawatan' => 'required|date',
            'jenis_perawatan'   => 'required|string|max:255',
            'biaya'             => 'nullable|numeric|min:0',
            'keterangan'        => 'nullable|string|max:255',
            'status'            => 'required|in:proses,selesai,dibatalkan' // Status perawatan harus salah satu dari ini.
        ], [ // Pesan error kustom untuk validasi.
            'barang_id.required'         => 'Barang harus dipilih',
            'tanggal_perawatan.required' => 'Tanggal perawatan harus diisi',
            'jenis_perawatan.required'   => 'Jenis perawatan harus diisi',
            'biaya.numeric'                => 'Biaya harus berupa angka',
            'biaya.min'                   => 'Biaya tidak boleh negatif'
        ]);

        try {
            DB::beginTransaction(); // Memulai transaksi database.

            // Format tanggal jika diperlukan
            if (is_string($validated['tanggal_perawatan'])) { // Memeriksa format tanggal.
                $validated['tanggal_perawatan'] = Carbon::parse($validated['tanggal_perawatan'])->format('Y-m-d'); // Mengubah format tanggal.
            }

            // Pastikan biaya dan keterangan tidak null
            $validated['biaya'] = $validated['biaya'] ?? 0;
            $validated['keterangan'] = $validated['keterangan'] ?? '';

            // Cek perubahan status
            $statusChanged = $perawatan->status !== $validated['status']; // Memeriksa apakah ada perubahan status perawatan.

            // Update data perawatan
            $perawatan->update($validated); // Memperbarui record Perawatan dengan data yang sudah divalidasi.

            // Update status barang jika status perawatan berubah
            if ($statusChanged) { // Jika status perawatan memang berubah.
                $barang = Barang::find($validated['barang_id']); // Mencari data barang yang terkait.

                if ($validated['status'] === 'selesai') { // Jika status perawatan diubah menjadi 'selesai'.
                    $barang->status = 'aktif'; // Kembalikan status barang ke 'aktif'.
                } elseif ($validated['status'] === 'dibatalkan') { // Jika status perawatan diubah menjadi 'dibatalkan'.
                    $barang->status = 'aktif'; // Kembalikan status barang ke 'aktif'.
                } else { // Jika statusnya tetap 'proses' atau diubah ke 'proses'.
                    $barang->status = 'perawatan'; // Pastikan status barang adalah 'perawatan'.
                }

                $barang->save(); // Menyimpan perubahan status barang.
            }

            DB::commit(); // Menyelesaikan transaksi database.

            return redirect()->route('pengurus.perawatan.index') // Mengarahkan kembali ke halaman daftar perawatan.
                ->with('success', 'Data perawatan berhasil diperbarui'); // Mengirimkan pesan sukses.
        } catch (\Exception $e) {
            DB::rollBack(); // Membatalkan transaksi.
            Log::error('Error updating perawatan: ' . $e->getMessage()); // Mencatat error.
            return back()->withInput() // Kembali ke halaman sebelumnya dengan input yang sudah diisi.
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Mengirimkan pesan error.
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perawatan $perawatan) // Metode destroy untuk menghapus data perawatan. Menggunakan Route Model Binding.
    {
        try {
            DB::beginTransaction(); // Memulai transaksi database.

            // Cek status perawatan
            if ($perawatan->status === 'proses') { // Jika perawatan yang akan dihapus masih berstatus 'proses'.
                // Jika masih dalam proses, kembalikan status barang ke aktif
                $barang = Barang::find($perawatan->barang_id); // Mencari data barang yang terkait.
                if ($barang->status === 'perawatan') { // Jika status barang juga 'perawatan'.
                    $barang->status = 'aktif'; // Mengubah status barang kembali menjadi 'aktif'.
                    $barang->save();           // Menyimpan perubahan status barang.
                }
            }

            // Hapus data perawatan
            $perawatan->delete(); // Menghapus record perawatan dari database.

            DB::commit(); // Menyelesaikan transaksi database.

            return response()->json([ // Mengembalikan respons JSON dengan status sukses.
                'success' => true,
                'message' => 'Data perawatan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Membatalkan transaksi.
            Log::error('Error deleting perawatan: ' . $e->getMessage()); // Mencatat error.
            return response()->json([ // Mengembalikan respons JSON dengan status error.
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500); // Kode status HTTP 500 Internal Server Error.
        }
    }

    /**
     * Update status perawatan menjadi selesai
     */
    public function selesaikan(Request $request, Perawatan $perawatan) // Metode kustom untuk menandai perawatan sebagai 'selesai'.
    {
        try {
            DB::beginTransaction(); // Memulai transaksi database.

            // Update status perawatan
            $perawatan->status = 'selesai'; // Mengubah status perawatan menjadi 'selesai'.
            $perawatan->save();             // Menyimpan perubahan pada record perawatan.

            // Update status barang menjadi aktif
            $barang = Barang::find($perawatan->barang_id); // Mencari data barang yang terkait.
            if ($barang->status === 'perawatan') { // Jika status barang adalah 'perawatan'.
                $barang->status = 'aktif'; // Mengubah status barang menjadi 'aktif'.
                $barang->save();           // Menyimpan perubahan status barang.
            }

            DB::commit(); // Menyelesaikan transaksi database.

            return redirect()->route('pengurus.perawatan.index') // Mengarahkan kembali ke halaman daftar perawatan.
                ->with('success', 'Perawatan barang telah selesai'); // Mengirimkan pesan sukses.
        } catch (\Exception | \Throwable $e) { // Menangkap setiap jenis exception atau Throwable.
            DB::rollBack(); // Membatalkan transaksi.
            Log::error('Error completing perawatan: ' . $e->getMessage()); // Mencatat error.
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Mengirimkan pesan error.
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
                    'kategori' => $barang->kategori->nama ?? '-', // Mengambil nama kategori, jika tidak ada, gunakan '-'.
                    'status' => $barang->status
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
}