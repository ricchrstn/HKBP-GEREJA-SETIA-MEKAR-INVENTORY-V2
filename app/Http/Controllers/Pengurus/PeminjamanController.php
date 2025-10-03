<?php

namespace App\Http\Controllers\Pengurus; // Mendefinisikan namespace untuk controller ini, menempatkannya di bawah direktori Pengurus.

use App\Http\Controllers\Controller;    // Mengimpor kelas Controller dasar dari Laravel.
use App\Models\Barang;                  // Mengimpor model Barang untuk berinteraksi dengan tabel 'barang'.
use App\Models\Peminjaman;              // Mengimpor model Peminjaman untuk berinteraksi dengan tabel 'peminjaman'.
use App\Models\Kategori;                // Mengimpor model Kategori untuk berinteraksi dengan tabel 'kategori'.
use Illuminate\Http\Request;            // Mengimpor kelas Request untuk menangani input dari pengguna.
use Illuminate\Support\Facades\Auth;    // Mengimpor facade Auth untuk mendapatkan informasi pengguna yang sedang login.
use Illuminate\Support\Facades\DB;      // Mengimpor facade DB untuk transaksi database manual.
use Illuminate\Support\Str;             // Mengimpor kelas Str dari Illuminate\Support untuk fungsi-fungsi string (seperti Str::limit).

class PeminjamanController extends Controller // Mendefinisikan kelas PeminjamanController yang merupakan turunan dari Controller.
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Metode index untuk menampilkan daftar semua data peminjaman.
    {
        // Cek dan update status terlambat setiap kali halaman dimuat
        $this->checkAndUpdateOverdue(); // Memanggil metode internal untuk memeriksa dan memperbarui status peminjaman yang terlambat.

        // Mulai dengan query builder agar bisa ditambahkan filter
        $query = Peminjaman::with(['barang', 'user', 'kategori']); // Memulai query untuk model Peminjaman. 'with' digunakan untuk eager loading relasi 'barang', 'user', dan 'kategori' untuk menghindari N+1 query.

        // Filter pencarian berdasarkan nama barang, kode barang, peminjam, atau keperluan
        if ($request->filled('search')) { // Memeriksa apakah ada parameter 'search' dalam request dan tidak kosong.
            $search = $request->search;   // Menyimpan nilai pencarian dari request.
            $query->where(function ($q) use ($search) { // Menambahkan kondisi WHERE ke query. Menggunakan callback function untuk mengelompokkan kondisi OR.
                $q->whereHas('barang', function ($query) use ($search) { // Mencari di dalam relasi 'barang'.
                    $query->where('nama', 'like', "%{$search}%") // Mencari nama barang yang mengandung string $search.
                          ->orWhere('kode_barang', 'like', "%{$search}%"); // Atau mencari kode_barang yang mengandung string $search.
                })
                ->orWhere('peminjam', 'like', "%{$search}%") // Atau mencari peminjam yang mengandung string $search.
                ->orWhere('keperluan', 'like', "%{$search}%"); // Atau mencari keperluan yang mengandung string $search.
            });
        }

        // Filter berdasarkan status (dipinjam, dikembalikan, terlambat)
        if ($request->filled('status')) { // Memeriksa apakah ada parameter 'status' dalam request dan tidak kosong.
            $query->where('status', $request->status); // Menambahkan kondisi WHERE untuk memfilter berdasarkan status peminjaman.
        }

        // Filter berdasarkan rentang tanggal pinjam
        if ($request->filled('tanggal_mulai')) { // Memeriksa apakah ada parameter 'tanggal_mulai' dalam request dan tidak kosong.
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai); // Menambahkan kondisi WHERE untuk tanggal pinjam, mencari yang tanggalnya lebih besar atau sama dengan tanggal_mulai.
        }

        if ($request->filled('tanggal_selesai')) { // Memeriksa apakah ada parameter 'tanggal_selesai' dalam request dan tidak kosong.
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_selesai); // Menambahkan kondisi WHERE untuk tanggal pinjam, mencari yang tanggalnya lebih kecil atau sama dengan tanggal_selesai.
        }

        // Gunakan paginate() dan simpan dalam variabel $peminjamans (plural)
        $peminjamans = $query->orderBy('tanggal_pinjam', 'desc')->paginate(15)->withQueryString(); // Menjalankan query: mengurutkan berdasarkan tanggal pinjam terbaru, memaginasi hasilnya menjadi 15 item per halaman, dan mempertahankan parameter query string saat navigasi paginasi.

        // Kirim variabel $peminjamans ke view
        return view('pengurus.peminjaman.index', compact('peminjamans')); // Mengembalikan view 'pengurus.peminjaman.index' dengan data $peminjamans.
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() // Metode create untuk menampilkan form penambahan data peminjaman baru.
    {
        $kategoris = Kategori::all(); // Mengambil semua data kategori untuk dropdown.
        return view('pengurus.peminjaman.create', compact('kategoris')); // Mengembalikan view 'pengurus.peminjaman.create' dengan data $kategoris.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Metode store untuk menyimpan data peminjaman baru ke database.
    {
        $request->validate([ // Memvalidasi data yang masuk dari request.
            'kategori_id' => 'required|exists:kategori,id',                 // Kategori_id wajib diisi dan harus ada di tabel 'kategori' kolom 'id'.
            'barang_id' => 'required|exists:barang,id',                     // Barang_id wajib diisi dan harus ada di tabel 'barang' kolom 'id'.
            'tanggal_pinjam' => 'required|date',                            // Tanggal pinjam wajib diisi dan harus berformat tanggal.
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam', // Tanggal kembali wajib diisi, berformat tanggal, dan harus setelah atau sama dengan tanggal pinjam.
            'jumlah' => 'required|integer|min:1',                           // Jumlah wajib diisi, harus berupa integer, dan minimal 1.
            'nama_peminjam' => 'required|string|max:255',                   // Nama peminjam wajib diisi, string, maksimal 255 karakter.
            'kontak' => 'nullable|string|max:255',                          // Kontak opsional, string, maksimal 255 karakter.
            'keperluan' => 'nullable|string|max:255',                       // Keperluan opsional, string, maksimal 255 karakter.
            'keterangan' => 'nullable|string|max:500',                      // Keterangan opsional, string, maksimal 500 karakter.
        ]);

        try { // Blok try-catch untuk menangani potensi error saat menyimpan data dan melakukan transaksi database.
            DB::beginTransaction(); // Memulai transaksi database. Jika ada error di tengah jalan, semua perubahan akan dibatalkan (rollback).

            // Get barang
            $barang = Barang::findOrFail($request->barang_id); // Mencari data barang berdasarkan barang_id. Jika tidak ditemukan, akan melempar exception.

            // Check if stok is sufficient
            if ($barang->stok < $request->jumlah) { // Memeriksa apakah stok barang mencukupi.
                return redirect()->back() // Jika stok tidak cukup, kembalikan ke halaman sebelumnya.
                    ->withInput()      // Dengan input yang sudah diisi.
                    ->withErrors(['jumlah' => 'Stok tidak mencukupi! Stok tersedia: ' . $barang->stok]); // Dan pesan error.
            }

            // Create peminjaman
            $peminjaman = Peminjaman::create([ // Membuat record baru di tabel 'peminjaman' dengan data dari request.
                'barang_id' => $request->barang_id,
                'kategori_id' => $request->kategori_id,
                'user_id' => Auth::id(), // Mengambil ID pengguna yang sedang login.
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'jumlah' => $request->jumlah,
                'peminjam' => $request->nama_peminjam,
                'kontak' => $request->kontak,
                'keperluan' => $request->keperluan,
                'keterangan' => $request->keterangan,
                'status' => 'dipinjam', // Menetapkan status awal peminjaman sebagai 'dipinjam'.
            ]);

            // Update barang stok
            $barang->stok -= $request->jumlah; // Mengurangi stok barang sejumlah yang dipinjam.
            $barang->save();                   // Menyimpan perubahan stok barang ke database.

            DB::commit(); // Menyelesaikan transaksi database. Semua perubahan disimpan secara permanen.

            return redirect()->route('pengurus.peminjaman.index') // Mengarahkan kembali ke halaman daftar peminjaman.
                ->with('success', 'Data peminjaman berhasil ditambahkan!'); // Mengirimkan pesan sukses ke view.
        } catch (\Exception $e) { // Menangkap setiap jenis exception (error).
            DB::rollBack(); // Membatalkan semua perubahan database yang dilakukan dalam transaksi.
            return redirect()->back() // Mengarahkan kembali ke halaman sebelumnya.
                ->withInput()       // Dengan input yang sudah diisi.
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.']); // Dan pesan error.
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman) // Metode show untuk menampilkan detail satu data peminjaman. Menggunakan Route Model Binding untuk langsung mendapatkan instance Peminjaman.
    {
        $peminjaman->load(['barang', 'user', 'kategori']); // Melakukan eager loading untuk relasi 'barang', 'user', dan 'kategori' pada instance $peminjaman yang sudah ada.
        return view('pengurus.peminjaman.show', compact('peminjaman')); // Mengembalikan view 'pengurus.peminjaman.show' dengan data $peminjaman.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman) // Metode edit untuk menampilkan form pengubahan data peminjaman. Menggunakan Route Model Binding.
    {
        $kategoris = Kategori::all(); // Mengambil semua data kategori untuk dropdown.
        $peminjaman->load(['barang']); // Melakukan eager loading relasi 'barang' pada instance $peminjaman.
        return view('pengurus.peminjaman.edit', compact('peminjaman', 'kategoris')); // Mengembalikan view 'pengurus.peminjaman.edit' dengan data $peminjaman dan $kategoris.
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peminjaman $peminjaman) // Metode update untuk memperbarui data peminjaman di database. Menggunakan Route Model Binding.
    {
        $request->validate([ // Memvalidasi data yang masuk dari request, sama seperti pada metode store.
            'kategori_id' => 'required|exists:kategori,id',
            'barang_id' => 'required|exists:barang,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jumlah' => 'required|integer|min:1',
            'nama_peminjam' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'keperluan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction(); // Memulai transaksi database.

            // Get old and new barang
            $oldBarang = Barang::findOrFail($peminjaman->barang_id); // Mengambil data barang lama yang terkait dengan peminjaman ini.
            $newBarang = Barang::findOrFail($request->barang_id);     // Mengambil data barang baru yang dipilih dari form (bisa sama dengan oldBarang).

            // If barang changed, restore old barang stok and check new barang stok
            if ($oldBarang->id != $newBarang->id) { // Memeriksa apakah barang yang dipinjam diubah.
                // Restore old barang stok
                $oldBarang->stok += $peminjaman->jumlah; // Mengembalikan stok barang lama sejumlah yang pernah dipinjam.
                $oldBarang->save();                       // Menyimpan perubahan stok barang lama.

                // Check if new barang stok is sufficient
                if ($newBarang->stok < $request->jumlah) { // Memeriksa apakah stok barang baru mencukupi.
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['jumlah' => 'Stok tidak mencukupi! Stok tersedia: ' . $newBarang->stok]);
                }

                // Update new barang stok
                $newBarang->stok -= $request->jumlah; // Mengurangi stok barang baru sejumlah yang akan dipinjam.
                $newBarang->save();                   // Menyimpan perubahan stok barang baru.
            } else {
                // If same barang, adjust stok based on quantity change
                $stokDifference = $peminjaman->jumlah - $request->jumlah; // Menghitung selisih antara jumlah lama dan jumlah baru.

                if ($stokDifference > 0) { // Jika jumlah baru lebih kecil dari jumlah lama (artinya sebagian dikembalikan).
                    // Tambahkan stok yang 'dikembalikan' ke barang saat ini.
                    $newBarang->stok += $stokDifference;
                } else if ($stokDifference < 0) { // Jika jumlah baru lebih besar dari jumlah lama (artinya pinjam lebih banyak).
                    // Kurangi stok yang 'dipinjam lebih' dari barang saat ini.
                    // $stokDifference akan negatif, jadi += akan berfungsi sebagai pengurangan.
                    // Pastikan stok mencukupi sebelum mengurangi.
                    if ($newBarang->stok < abs($stokDifference)) { // Periksa apakah stok cukup untuk mengurangi sebanyak selisih.
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['jumlah' => 'Stok tidak mencukupi! Stok tersedia: ' . $newBarang->stok]);
                    }
                    $newBarang->stok += $stokDifference;
                }
                // Jika stokDifference == 0, tidak ada perubahan jumlah, jadi tidak perlu update stok.
                $newBarang->save(); // Menyimpan perubahan stok barang.
            }

            // Update peminjaman
            $peminjaman->update([ // Memperbarui record Peminjaman dengan data yang sudah divalidasi.
                'barang_id' => $request->barang_id,
                'kategori_id' => $request->kategori_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'jumlah' => $request->jumlah,
                'peminjam' => $request->nama_peminjam,
                'kontak' => $request->kontak,
                'keperluan' => $request->keperluan,
                'keterangan' => $request->keterangan,
                // Status tidak diupdate di sini, karena status 'dipinjam' atau 'terlambat' akan dihandle di checkAndUpdateOverdue/kembalikan.
            ]);

            DB::commit(); // Menyelesaikan transaksi database.

            return redirect()->route('pengurus.peminjaman.index') // Mengarahkan kembali ke halaman daftar peminjaman.
                ->with('success', 'Data peminjaman berhasil diperbarui!'); // Mengirimkan pesan sukses.
        } catch (\Exception $e) {
            DB::rollBack(); // Membatalkan transaksi.
            return redirect()->back() // Kembali ke halaman sebelumnya.
                ->withInput()       // Dengan input yang sudah diisi.
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data.']); // Mengirimkan pesan error.
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman) // Metode destroy untuk menghapus data peminjaman. Menggunakan Route Model Binding.
    {
        try {
            DB::beginTransaction(); // Memulai transaksi database.

            // Get barang
            $barang = Barang::findOrFail($peminjaman->barang_id); // Mengambil data barang yang terkait dengan peminjaman yang akan dihapus.

            // Restore barang stok (penting: hanya jika status bukan 'dikembalikan', tapi di sini diasumsikan dihapus berarti belum dikembalikan sepenuhnya)
            // Jika peminjaman ini dihapus saat status 'dipinjam' atau 'terlambat', maka stok perlu dikembalikan.
            // Jika dihapus saat 'dikembalikan', stok sudah dikembalikan sebelumnya. Logika ini perlu disesuaikan jika kasus tersebut mungkin.
            if ($peminjaman->status != 'dikembalikan') { // Hanya kembalikan stok jika belum dikembalikan
                $barang->stok += $peminjaman->jumlah; // Mengembalikan stok barang sejumlah barang yang dipinjam.
                $barang->save();                       // Menyimpan perubahan stok.
            }


            // Delete peminjaman
            $peminjaman->delete(); // Menghapus record peminjaman dari database.

            DB::commit(); // Menyelesaikan transaksi database.

            return redirect()->route('pengurus.peminjaman.index') // Mengarahkan kembali ke halaman daftar peminjaman.
                ->with('success', 'Data peminjaman berhasil dihapus!'); // Mengirimkan pesan sukses.
        } catch (\Exception $e) {
            DB::rollBack(); // Membatalkan transaksi.
            return redirect()->back()->with('error', 'Gagal menghapus data.'); // Mengirimkan pesan error.
        }
    }

    /**
     * Return the borrowed item.
     */
    public function kembalikan(Peminjaman $peminjaman) // Metode kustom untuk menandai barang sebagai dikembalikan.
    {
        // Cek apakah status masih 'dipinjam' atau 'terlambat' untuk mencegah pengembalian ganda
        if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'])) { // Memastikan peminjaman belum dikembalikan.
            return redirect()->back()->with('error', 'Barang ini sudah dikembalikan atau statusnya tidak valid.'); // Mengembalikan error jika sudah dikembalikan.
        }

        try {
            DB::beginTransaction(); // Memulai transaksi database.

            // Get barang
            $barang = Barang::findOrFail($peminjaman->barang_id); // Mengambil data barang yang dipinjam.

            // Restore barang stok
            $barang->stok += $peminjaman->jumlah; // Mengembalikan stok barang sejumlah yang dipinjam.
            $barang->save();                       // Menyimpan perubahan stok.

            // Update peminjaman status dan tanggal dikembalikan
            $peminjaman->status = 'dikembalikan';     // Mengubah status peminjaman menjadi 'dikembalikan'.
            $peminjaman->tanggal_dikembalikan = now(); // Menetapkan tanggal dikembalikan ke waktu saat ini.
            $peminjaman->save();                       // Menyimpan perubahan pada record peminjaman.

            DB::commit(); // Menyelesaikan transaksi database.

            return redirect()->route('pengurus.peminjaman.index') // Mengarahkan kembali ke halaman daftar peminjaman.
                ->with('success', 'Barang berhasil dikembalikan!'); // Mengirimkan pesan sukses.
        } catch (\Exception $e) {
            DB::rollBack(); // Membatalkan transaksi.
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengembalikan barang.'); // Mengirimkan pesan error.
        }
    }

    /**
     * Check and update overdue items.
     * Method ini bisa dipanggil di method index() atau via Artisan Command.
     */
    public function checkAndUpdateOverdue() // Metode internal untuk memeriksa dan memperbarui status peminjaman yang terlambat.
    {
        // Cari semua peminjaman yang statusnya 'dipinjam' dan sudah melewati tanggal kembali
        $overduePeminjaman = Peminjaman::where('status', 'dipinjam') // Mencari peminjaman dengan status 'dipinjam'.
            ->where('tanggal_kembali', '<', now()->startOfDay()) // Dan tanggal kembali sudah melewati hari ini (startOfDay untuk membandingkan hanya tanggal).
            ->get(); // Mengambil semua hasil.

        // Update statusnya menjadi 'terlambat'
        $count = $overduePeminjaman->count(); // Menghitung jumlah peminjaman yang terlambat.
        if ($count > 0) { // Jika ada peminjaman yang terlambat.
            Peminjaman::whereIn('id', $overduePeminjaman->pluck('id')) // Mengupdate semua peminjaman dengan ID tersebut.
                ->update(['status' => 'terlambat']); // Mengubah statusnya menjadi 'terlambat'.
        }

        return $count; // Mengembalikan jumlah item yang terlambat (bisa untuk logging atau informasi).
    }

    /**
     * Get barang details by ID (AJAX).
     */
    public function getBarangDetails($id) // Metode untuk mendapatkan detail barang berdasarkan ID, biasanya dipanggil melalui AJAX.
    {
        $barang = Barang::with('kategori')->findOrFail($id); // Mencari barang berdasarkan ID dan melakukan eager loading relasi 'kategori'. Jika tidak ditemukan, akan melempar exception (404).

        return response()->json([ // Mengembalikan respons JSON dengan data barang.
            'success' => true,
            'data' => [
                'id' => $barang->id,
                'nama' => $barang->nama,
                'kode_barang' => $barang->kode_barang,
                'kategori' => $barang->kategori->nama,
                'satuan' => $barang->satuan,
                'stok' => $barang->stok,
                'harga' => $barang->harga,
                'gambar' => $barang->gambar,
            ]
        ]);
    }

    /**
     * Get barang by kategori ID (AJAX).
     */
    public function getBarangByKategori($kategoriId) // Metode untuk mendapatkan daftar barang berdasarkan ID kategori, biasanya dipanggil melalui AJAX.
    {
        $barangs = Barang::where('kategori_id', $kategoriId) // Memfilter barang berdasarkan kategori_id yang diberikan.
            ->where('status', 'Aktif')                       // Memfilter hanya barang dengan status 'Aktif'.
            ->get();                                         // Mengambil semua hasil.

        return response()->json([ // Mengembalikan respons JSON dengan daftar barang.
            'success' => true,
            'data' => $barangs
        ]);
    }
}