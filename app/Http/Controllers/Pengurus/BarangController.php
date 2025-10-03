<?php
namespace App\Http\Controllers\Pengurus; // Mendefinisikan namespace untuk controller ini, menunjukkan lokasinya.

use App\Http\Controllers\Controller; // Mengimpor base Controller dari Laravel.
use App\Models\Barang; // Mengimpor model Barang yang merepresentasikan data barang inventaris.
use App\Models\BarangMasuk; // Mengimpor model BarangMasuk untuk mencatat transaksi barang masuk.
use App\Models\BarangKeluar; // Mengimpor model BarangKeluar untuk mencatat transaksi barang keluar.
use App\Models\Kategori; // Mengimpor model Kategori untuk data kategori barang.
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP.
use Illuminate\Support\Str; // Mengimpor helper Str untuk manipulasi string (tidak digunakan di sini, bisa dihapus jika tidak dipakai di tempat lain).
use Illuminate\Support\Facades\Storage; // Mengimpor Facade Storage untuk mengelola penyimpanan file (tidak digunakan di sini, bisa dihapus).
use Illuminate\Support\Facades\DB; // Mengimpor Facade DB untuk interaksi langsung dengan database (transaksi).
use Illuminate\Support\Facades\Log; // Mengimpor Facade Log untuk mencatat pesan log.

class BarangController extends Controller // Mendefinisikan kelas BarangController yang mewarisi dari base Controller.
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Metode untuk menampilkan daftar barang.
    {
        // Memulai query untuk mengambil data Barang, eager load relasi 'kategori'.
        // Memastikan hanya mengambil barang yang belum dihapus secara soft delete (deleted_at IS NULL).
        $query = Barang::with('kategori')->whereNull('deleted_at');

        // Filter pencarian
        if ($request->filled('search')) { // Memeriksa apakah ada parameter 'search' di request.
            $search = $request->search; // Mengambil nilai dari parameter 'search'.
            $query->where(function ($q) use ($search) { // Menambahkan kondisi WHERE dengan grup OR.
                $q->where('nama', 'like', "%{$search}%") // Mencari berdasarkan nama barang.
                    ->orWhere('kode_barang', 'like', "%{$search}%") // Atau berdasarkan kode barang.
                    ->orWhere('deskripsi', 'like', "%{$search}%"); // Atau berdasarkan deskripsi.
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) { // Memeriksa apakah ada parameter 'kategori' di request.
            $query->where('kategori_id', $request->kategori); // Menambahkan kondisi WHERE berdasarkan ID kategori.
        }

        // Filter status stok
        if ($request->filled('stok_status')) { // Memeriksa apakah ada parameter 'stok_status' di request.
            switch ($request->stok_status) { // Melakukan pengecekan nilai dari 'stok_status'.
                case 'habis': // Jika status 'habis'.
                    $query->where('stok', 0); // Filter barang dengan stok 0.
                    break;
                case 'rendah': // Jika status 'rendah'.
                    $query->where('stok', '>', 0)->where('stok', '<=', 5); // Filter barang dengan stok antara 1 sampai 5.
                    break;
                case 'aman': // Jika status 'aman'.
                    $query->where('stok', '>', 5); // Filter barang dengan stok di atas 5.
                    break;
            }
        }

        // Menjalankan query, mengurutkan hasil berdasarkan yang terbaru (latest()),
        // memaginasi 15 item per halaman, dan mempertahankan parameter query string untuk link paginasi.
        $barangs = $query->latest()->paginate(15)->withQueryString();

        // Statistik
        // Menghitung jumlah barang dengan stok habis, rendah, dan aman (tidak termasuk yang dihapus).
        $stokHabis = Barang::whereNull('deleted_at')->where('stok', 0)->count();
        $stokRendah = Barang::whereNull('deleted_at')->where('stok', '>', 0)->where('stok', '<=', 5)->count();
        $stokAman = Barang::whereNull('deleted_at')->where('stok', '>', 5)->count();

        // Data kategori untuk filter di view
        $kategoris = Kategori::orderBy('nama')->get();

        // Mengembalikan view 'pengurus.barang.index' dengan data barang, kategori, dan statistik stok.
        return view('pengurus.barang.index', compact(
            'barangs',
            'kategoris',
            'stokHabis',
            'stokRendah',
            'stokAman'
        ));
    }

    /**
     * Show form for barang masuk
     */
    public function createMasuk() // Metode untuk menampilkan form penambahan barang masuk.
    {
        // Hanya tampilkan barang yang belum dihapus untuk dipilih di form.
        $barangs = Barang::whereNull('deleted_at')->get();
        return view('pengurus.barang.masuk.create', compact('barangs')); // Mengembalikan view form barang masuk.
    }

    /**
     * Show form for barang keluar
     */
    public function createKeluar() // Metode untuk menampilkan form pengurangan barang keluar.
    {
        // Hanya tampilkan barang yang belum dihapus dan memiliki stok > 0 untuk dipilih di form.
        $barangs = Barang::whereNull('deleted_at')->where('stok', '>', 0)->get();
        return view('pengurus.barang.keluar.create', compact('barangs')); // Mengembalikan view form barang keluar.
    }

    /**
     * Process barang masuk
     */
    public function barangMasuk(Request $request, Barang $barang) // Metode untuk memproses pencatatan barang masuk.
    {
        // Cek apakah barang sudah dihapus (diarsipkan)
        if ($barang->deleted_at) { // Memeriksa apakah kolom 'deleted_at' memiliki nilai.
            return back()->with('error', 'Barang tidak ditemukan atau sudah diarsipkan'); // Jika ya, kembalikan error.
        }

        $validated = $request->validate([ // Memvalidasi input dari request.
            'jumlah'     => 'required|integer|min:1', // 'jumlah' wajib, harus integer, minimal 1.
            'keterangan' => 'nullable|string|max:255', // 'keterangan' bisa null, string, maks 255 karakter.
        ]);

        try { // Blok try-catch untuk menangani potensi error selama transaksi database.
            DB::beginTransaction(); // Memulai transaksi database.

            // Update stok
            $barang->increment('stok', $validated['jumlah']); // Menambahkan jumlah barang ke kolom 'stok' di tabel 'barang'.

            // Catat transaksi
            BarangMasuk::create([ // Membuat record baru di tabel 'barang_masuk'.
                'barang_id'  => $barang->id, // ID barang.
                'tanggal'    => now(), // Tanggal saat ini.
                'jumlah'     => $validated['jumlah'], // Jumlah barang masuk.
                'keterangan' => $validated['keterangan'] ?? 'Barang masuk', // Keterangan atau default 'Barang masuk'.
                'user_id'    => auth()->id() // ID user yang sedang login.
            ]);

            DB::commit(); // Mengkonfirmasi dan menyimpan semua perubahan transaksi ke database.

            return back()->with('success', 'Barang masuk berhasil dicatat'); // Mengarahkan kembali dengan pesan sukses.
        } catch (\Exception $e) { // Menangkap exception jika terjadi error.
            DB::rollBack(); // Membatalkan semua perubahan transaksi jika ada error.
            Log::error('Error recording barang masuk: ' . $e->getMessage()); // Mencatat error ke log aplikasi.
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Mengarahkan kembali dengan pesan error.
        }
    }

    /**
     * Process barang keluar
     */
    public function barangKeluar(Request $request, Barang $barang) // Metode untuk memproses pencatatan barang keluar.
    {
        // Cek apakah barang sudah dihapus (diarsipkan)
        if ($barang->deleted_at) { // Memeriksa apakah kolom 'deleted_at' memiliki nilai.
            return back()->with('error', 'Barang tidak ditemukan atau sudah diarsipkan'); // Jika ya, kembalikan error.
        }

        $validated = $request->validate([ // Memvalidasi input dari request.
            'jumlah'     => 'required|integer|min:1', // 'jumlah' wajib, harus integer, minimal 1.
            'keterangan' => 'nullable|string|max:255', // 'keterangan' bisa null, string, maks 255 karakter.
        ]);

        // Cek stok
        if ($validated['jumlah'] > $barang->stok) { // Memeriksa apakah jumlah yang akan dikeluarkan lebih besar dari stok yang tersedia.
            return back()->with('error', 'Stok barang tidak mencukupi untuk dikeluarkan'); // Jika ya, kembalikan error.
        }

        try { // Blok try-catch untuk menangani potensi error selama transaksi database.
            DB::beginTransaction(); // Memulai transaksi database.

            // Update stok
            $barang->decrement('stok', $validated['jumlah']); // Mengurangi jumlah barang dari kolom 'stok' di tabel 'barang'.

            // Catat transaksi
            BarangKeluar::create([ // Membuat record baru di tabel 'barang_keluar'.
                'barang_id'  => $barang->id, // ID barang.
                'tanggal'    => now(), // Tanggal saat ini.
                'jumlah'     => $validated['jumlah'], // Jumlah barang keluar.
                'keterangan' => $validated['keterangan'] ?? 'Barang keluar', // Keterangan atau default 'Barang keluar'.
                'user_id'    => auth()->id() // ID user yang sedang login.
            ]);

            DB::commit(); // Mengkonfirmasi dan menyimpan semua perubahan transaksi ke database.

            return back()->with('success', 'Barang keluar berhasil dicatat'); // Mengarahkan kembali dengan pesan sukses.
        } catch (\Exception $e) { // Menangkap exception jika terjadi error.
            DB::rollBack(); // Membatalkan semua perubahan transaksi jika ada error.
            Log::error('Error recording barang keluar: ' . $e->getMessage()); // Mencatat error ke log aplikasi.
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Mengarahkan kembali dengan pesan error.
        }
    }
}