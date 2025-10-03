<?php

namespace App\Http\Controllers\Bendahara; // Mendefinisikan namespace untuk controller ini, menandakan lokasinya.

use App\Http\Controllers\Controller; // Mengimpor base Controller dari Laravel.
use App\Models\Kas; // Mengimpor model Kas yang merepresentasikan data transaksi kas.
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP.
use Illuminate\Support\Facades\Storage; // Mengimpor Facade Storage untuk mengelola penyimpanan file.
use Illuminate\Support\Facades\DB; // Mengimpor Facade DB untuk interaksi langsung dengan database (misalnya transaksi).

class KasController extends Controller // Mendefinisikan kelas KasController yang mewarisi dari base Controller.
{
    public function index(Request $request) // Metode untuk menampilkan daftar transaksi kas (halaman utama).
    {
        $query = Kas::with('user')->latest(); // Memulai query untuk mengambil data Kas, eager load relasi 'user', dan urutkan berdasarkan yang terbaru.

        // Filter berdasarkan pencarian
        if ($request->filled('search')) { // Memeriksa apakah ada parameter 'search' di request.
            $search = $request->search; // Mengambil nilai dari parameter 'search'.
            $query->where(function ($q) use ($search) { // Menambahkan kondisi WHERE dengan grup OR.
                $q->where('kode_transaksi', 'like', '%' . $search . '%') // Mencari berdasarkan kode_transaksi.
                    ->orWhere('keterangan', 'like', '%' . $search . '%') // Atau berdasarkan keterangan.
                    ->orWhere('sumber', 'like', '%' . $search . '%') // Atau berdasarkan sumber.
                    ->orWhere('tujuan', 'like', '%' . $search . '%'); // Atau berdasarkan tujuan.
            });
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) { // Memeriksa apakah ada parameter 'jenis' di request.
            $query->where('jenis', $request->jenis); // Menambahkan kondisi WHERE berdasarkan jenis (masuk/keluar).
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) { // Memeriksa apakah ada parameter 'tanggal' di request.
            $query->whereDate('tanggal', $request->tanggal); // Menambahkan kondisi WHERE berdasarkan tanggal spesifik.
        }

        // Filter berdasarkan bulan dan tahun
        if ($request->filled('bulan')) { // Memeriksa apakah ada parameter 'bulan' di request.
            $query->whereMonth('tanggal', $request->bulan); // Menambahkan kondisi WHERE berdasarkan bulan dari tanggal.
        }
        if ($request->filled('tahun')) { // Memeriksa apakah ada parameter 'tahun' di request.
            $query->whereYear('tanggal', $request->tahun); // Menambahkan kondisi WHERE berdasarkan tahun dari tanggal.
        }

        $kas = $query->paginate(10)->withQueryString(); // Menjalankan query, memaginasi hasilnya 10 per halaman, dan mempertahankan parameter query string untuk link paginasi.

        // Hitung total pemasukan dan pengeluaran
        $totalMasuk = Kas::masuk()->sum('jumlah'); // Menghitung total jumlah untuk transaksi 'masuk' menggunakan scope 'masuk' dari model Kas.
        $totalKeluar = Kas::keluar()->sum('jumlah'); // Menghitung total jumlah untuk transaksi 'keluar' menggunakan scope 'keluar' dari model Kas.
        $saldo = $totalMasuk - $totalKeluar; // Menghitung saldo kas.

        // Data untuk grafik
        $bulanIni = now()->month; // Mendapatkan bulan saat ini.
        $tahunIni = now()->year; // Mendapatkan tahun saat ini.

        $pemasukanBulanan = Kas::masuk() // Menghitung total pemasukan untuk bulan ini.
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('jumlah');

        $pengeluaranBulanan = Kas::keluar() // Menghitung total pengeluaran untuk bulan ini.
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('jumlah');

        return view('bendahara.kas.index', compact( // Mengembalikan view 'bendahara.kas.index' dengan data yang sudah dihitung.
            'kas', // Daftar transaksi kas yang sudah difilter dan dipaginasi.
            'totalMasuk', // Total pemasukan keseluruhan.
            'totalKeluar', // Total pengeluaran keseluruhan.
            'saldo', // Saldo kas keseluruhan.
            'pemasukanBulanan', // Pemasukan bulan ini.
            'pengeluaranBulanan' // Pengeluaran bulan ini.
        ));
    }

    public function create() // Metode untuk menampilkan form pembuatan transaksi kas baru.
    {

        return view('bendahara.kas.create'); // Mengembalikan view 'bendahara.kas.create'.
    }

    public function store(Request $request) // Metode untuk menyimpan data transaksi kas baru ke database.
    {
        $request->validate([ // Memvalidasi data yang masuk dari form.
            'jenis' => 'required|in:masuk,keluar', // 'jenis' wajib diisi dan harus 'masuk' atau 'keluar'.
            'jumlah' => 'required|numeric|min:0', // 'jumlah' wajib diisi, harus angka, dan minimal 0.
            'tanggal' => 'required|date', // 'tanggal' wajib diisi dan harus format tanggal yang valid.
            'keterangan' => 'required|string|max:255', // 'keterangan' wajib diisi, harus string, dan maksimal 255 karakter.
            'sumber' => 'required_if:jenis,masuk|nullable|string|max:255', // 'sumber' wajib jika jenisnya 'masuk', bisa null, string, maks 255 karakter.
            'tujuan' => 'required_if:jenis,keluar|nullable|string|max:255', // 'tujuan' wajib jika jenisnya 'keluar', bisa null, string, maks 255 karakter.
            'bukti_transaksi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // 'bukti_transaksi' bisa null, harus file, format pdf/jpg/jpeg/png, maks 2MB.
        ], [ // Pesan error kustom untuk validasi.
            'jenis.required' => 'Jenis transaksi harus dipilih',
            'jumlah.required' => 'Jumlah transaksi harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah tidak boleh negatif',
            'tanggal.required' => 'Tanggal transaksi harus diisi',
            'keterangan.required' => 'Keterangan transaksi harus diisi',
            'sumber.required_if' => 'Sumber pemasukan harus diisi',
            'tujuan.required_if' => 'Tujuan pengeluaran harus diisi',
            'bukti_transaksi.mimes' => 'Format file bukti transaksi tidak valid',
            'bukti_transaksi.max' => 'Ukuran file maksimal 2MB',
        ]);

        try { // Blok try-catch untuk menangani potensi error selama penyimpanan.
            DB::beginTransaction(); // Memulai transaksi database, memastikan semua operasi berhasil atau tidak sama sekali.

            $data = $request->only(['jenis', 'jumlah', 'tanggal', 'keterangan', 'sumber', 'tujuan']); // Mengambil hanya field yang dibutuhkan dari request.
            $data['user_id'] = auth()->id(); // Menyimpan ID user yang sedang login sebagai pembuat transaksi.
            $data['kode_transaksi'] = Kas::generateKode($request->jenis); // Membuat kode transaksi unik menggunakan metode statis dari model Kas.

            if ($request->hasFile('bukti_transaksi')) { // Memeriksa apakah ada file bukti transaksi yang diunggah.
                $path = $request->file('bukti_transaksi')->store('bukti_transaksi', 'public'); // Menyimpan file ke direktori 'bukti_transaksi' di storage disk 'public'.
                $data['bukti_transaksi'] = $path; // Menyimpan path file yang diunggah ke array data.
            }

            Kas::create($data); // Membuat record baru di tabel 'kas' dengan data yang sudah disiapkan.

            DB::commit(); // Mengkonfirmasi dan menyimpan semua perubahan transaksi ke database.

            return redirect()->route('bendahara.kas.index') // Mengarahkan kembali ke halaman index kas.
                ->with('success', 'Transaksi kas berhasil ditambahkan'); // Menampilkan pesan sukses.
        } catch (\Exception $e) { // Menangkap exception jika terjadi error.
            DB::rollback(); // Membatalkan semua perubahan transaksi jika ada error.
            return redirect()->back() // Mengarahkan kembali ke halaman sebelumnya.
                ->withInput() // Mengisi kembali input form dengan data sebelumnya.
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Menampilkan pesan error.
        }
    }

    public function show($id) // Metode untuk menampilkan detail satu transaksi kas.
    {
        $kas = Kas::with('user')->findOrFail($id); // Mencari transaksi kas berdasarkan ID, eager load user, dan jika tidak ditemukan akan melempar 404.
        return view('bendahara.kas.show', compact('kas')); // Mengembalikan view 'bendahara.kas.show' dengan data transaksi kas.
    }

    public function edit($id) // Metode untuk menampilkan form edit transaksi kas.
    {
        $kas = Kas::findOrFail($id); // Mencari transaksi kas berdasarkan ID, jika tidak ditemukan akan melempar 404.
        return view('bendahara.kas.edit', compact('kas')); // Mengembalikan view 'bendahara.kas.edit' dengan data transaksi kas.
    }

    public function update(Request $request, $id) // Metode untuk memperbarui data transaksi kas yang sudah ada.
    {
        $kas = Kas::findOrFail($id); // Mencari transaksi kas berdasarkan ID yang akan diperbarui.

        $request->validate([ // Memvalidasi data yang masuk dari form, sama seperti metode 'store'.
            'jenis' => 'required|in:masuk,keluar',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'sumber' => 'required_if:jenis,masuk|nullable|string|max:255',
            'tujuan' => 'required_if:jenis,keluar|nullable|string|max:255',
            'bukti_transaksi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'jenis.required' => 'Jenis transaksi harus dipilih',
            'jumlah.required' => 'Jumlah transaksi harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah tidak boleh negatif',
            'tanggal.required' => 'Tanggal transaksi harus diisi',
            'keterangan.required' => 'Keterangan transaksi harus diisi',
            'sumber.required_if' => 'Sumber pemasukan harus diisi',
            'tujuan.required_if' => 'Tujuan pengeluaran harus diisi',
            'bukti_transaksi.mimes' => 'Format file bukti transaksi tidak valid',
            'bukti_transaksi.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            DB::beginTransaction(); // Memulai transaksi database.

            $data = $request->only(['jenis', 'jumlah', 'tanggal', 'keterangan', 'sumber', 'tujuan']); // Mengambil hanya field yang dibutuhkan dari request.

            // Jika jenis berubah, generate kode baru
            if ($kas->jenis != $request->jenis) { // Memeriksa apakah jenis transaksi berubah.
                $data['kode_transaksi'] = Kas::generateKode($request->jenis); // Jika berubah, buat kode transaksi baru.
            }

            if ($request->hasFile('bukti_transaksi')) { // Memeriksa apakah ada file bukti transaksi baru yang diunggah.
                // Hapus file lama jika ada
                if ($kas->bukti_transaksi) { // Jika sudah ada bukti transaksi lama.
                    Storage::disk('public')->delete($kas->bukti_transaksi); // Hapus file bukti transaksi lama dari storage.
                }
                $path = $request->file('bukti_transaksi')->store('bukti_transaksi', 'public'); // Menyimpan file baru.
                $data['bukti_transaksi'] = $path; // Menyimpan path file baru.
            }

            $kas->update($data); // Memperbarui record transaksi kas dengan data yang sudah disiapkan.

            DB::commit(); // Mengkonfirmasi dan menyimpan perubahan ke database.

            return redirect()->route('bendahara.kas.index') // Mengarahkan kembali ke halaman index kas.
                ->with('success', 'Transaksi kas berhasil diperbarui'); // Menampilkan pesan sukses.
        } catch (\Exception $e) { // Menangkap exception jika terjadi error.
            DB::rollback(); // Membatalkan semua perubahan transaksi.
            return redirect()->back() // Mengarahkan kembali ke halaman sebelumnya.
                ->withInput() // Mengisi kembali input form dengan data sebelumnya.
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Menampilkan pesan error.
        }
    }

    public function destroy($id) // Metode untuk menghapus transaksi kas.
    {
        try {
            $kas = Kas::findOrFail($id); // Mencari transaksi kas berdasarkan ID, jika tidak ditemukan akan melempar 404.

            // Hapus file bukti transaksi jika ada
            if ($kas->bukti_transaksi) { // Memeriksa apakah ada file bukti transaksi.
                Storage::disk('public')->delete($kas->bukti_transaksi); // Hapus file dari storage.
            }

            $kas->forceDelete(); // Menghapus transaksi kas secara permanen dari database (jika menggunakan soft deletes).

            return redirect()->route('bendahara.kas.index') // Mengarahkan kembali ke halaman index kas.
                ->with('success', 'Transaksi kas berhasil dihapus'); // Menampilkan pesan sukses.
        } catch (\Exception $e) { // Menangkap exception jika terjadi error.
            // Log error untuk debugging
            \Log::error('Error deleting kas: ' . $e->getMessage()); // Mencatat error ke log aplikasi.

            return redirect()->back() // Mengarahkan kembali ke halaman sebelumnya.
                ->with('error', 'Terjadi kesalahan saat menghapus transaksi: ' . $e->getMessage()); // Menampilkan pesan error.
        }
    }

    public function laporan(Request $request) // Metode untuk menampilkan laporan transaksi kas.
    {
        $query = Kas::query(); // Memulai query untuk mengambil semua data Kas.

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) { // Memeriksa apakah ada parameter tanggal_mulai dan tanggal_selesai.
            $query->whereBetween('tanggal', [ // Menambahkan kondisi WHERE untuk rentang tanggal.
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) { // Memeriksa apakah ada parameter 'jenis'.
            $query->where('jenis', $request->jenis); // Menambahkan kondisi WHERE berdasarkan jenis.
        }

        $transaksi = $query->orderBy('tanggal', 'asc')->get(); // Menjalankan query, mengurutkan berdasarkan tanggal secara ascending, dan mengambil semua hasilnya.

        $totalMasuk = $transaksi->where('jenis', 'masuk')->sum('jumlah'); // Menghitung total pemasukan dari hasil query.
        $totalKeluar = $transaksi->where('jenis', 'keluar')->sum('jumlah'); // Menghitung total pengeluaran dari hasil query.
        $saldo = $totalMasuk - $totalKeluar; // Menghitung saldo.

        return view('bendahara.kas.laporan', compact( // Mengembalikan view 'bendahara.kas.laporan' dengan data laporan.
            'transaksi', // Daftar transaksi untuk laporan.
            'totalMasuk', // Total pemasukan dalam laporan.
            'totalKeluar', // Total pengeluaran dalam laporan.
            'saldo' // Saldo dalam laporan.
        ));
    }
}