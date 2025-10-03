<?php

namespace App\Http\Controllers\Pengurus; // Mendefinisikan namespace untuk controller ini, menempatkannya di bawah direktori Pengurus.

use App\Http\Controllers\Controller;    // Mengimpor kelas Controller dasar dari Laravel.
use App\Models\Pengajuan;               // Mengimpor model Pengajuan untuk berinteraksi dengan tabel 'pengajuan'.
use Illuminate\Http\Request;            // Mengimpor kelas Request untuk menangani input dari pengguna.
use Illuminate\Support\Facades\Storage; // Mengimpor facade Storage untuk mengelola penyimpanan file.

class PengajuanController extends Controller // Mendefinisikan kelas PengajuanController yang merupakan turunan dari Controller.
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Metode index untuk menampilkan daftar semua pengajuan pengadaan.
    {
        // Memulai query untuk model Pengajuan. Menggunakan eager loading untuk relasi 'user'.
        // Filter awal: hanya tampilkan pengajuan yang dibuat oleh user yang sedang login (auth()->id()).
        $query = Pengajuan::with('user')->where('user_id', auth()->id());

        // Filter berdasarkan pencarian
        if ($request->filled('search')) { // Memeriksa apakah ada parameter 'search' dalam request dan tidak kosong.
            $query->where('nama_barang', 'like', '%' . $request->search . '%') // Mencari nama_barang yang mengandung string $search.
                ->orWhere('kode_pengajuan', 'like', '%' . $request->search . '%') // Atau kode_pengajuan yang mengandung string $search.
                ->orWhere('alasan', 'like', '%' . $request->search . '%');     // Atau alasan yang mengandung string $search.
        }

        // Filter berdasarkan status
        if ($request->filled('status')) { // Memeriksa apakah ada parameter 'status' dalam request dan tidak kosong.
            $query->where('status', $request->status); // Menambahkan kondisi WHERE untuk memfilter berdasarkan status pengajuan.
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) { // Memeriksa apakah ada parameter 'tanggal' dalam request dan tidak kosong.
            $query->whereDate('created_at', $request->tanggal); // Menambahkan kondisi WHERE untuk memfilter berdasarkan tanggal dibuat (created_at).
        }

        $pengajuans = $query->latest()->paginate(10); // Menjalankan query: mengurutkan berdasarkan tanggal terbaru ('latest'), memaginasi hasilnya menjadi 10 item per halaman.

        return view('pengurus.pengajuan.index', compact('pengajuans')); // Mengembalikan view 'pengurus.pengajuan.index' dengan data $pengajuans.
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() // Metode create untuk menampilkan form penambahan pengajuan baru.
    {
        return view('pengurus.pengajuan.create'); // Mengembalikan view 'pengurus.pengajuan.create'.
    }

    // app/Http/Controllers/Pengurus/PengajuanController.php
    public function store(Request $request) // Metode store untuk menyimpan pengajuan baru ke database.
    {
        $request->validate([ // Memvalidasi data yang masuk dari request.
            'nama_barang' => 'required|string|max:255',                  // Nama barang wajib diisi, string, maksimal 255 karakter.
            'spesifikasi' => 'nullable|string',                          // Spesifikasi opsional, string.
            'jumlah' => 'required|integer|min:1',                        // Jumlah wajib diisi, integer, minimal 1.
            'satuan' => 'required|string|max:50',                        // Satuan wajib diisi, string, maksimal 50 karakter.
            'alasan' => 'required|string',                               // Alasan wajib diisi, string.
            'kebutuhan' => 'required|date|after_or_equal:today',         // Tanggal kebutuhan wajib diisi, format tanggal, dan harus setelah atau sama dengan hari ini.
            'file_pengajuan' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // File pengajuan opsional, berupa file, dengan tipe PDF/DOC/DOCX, dan ukuran maksimal 2MB.
            // Tambahkan validasi untuk kriteria TOPSIS (ini adalah bagian dari metode pengambilan keputusan)
            'urgensi' => 'required|integer|min:1|max:10',                // Urgensi wajib diisi, integer, antara 1 sampai 10.
            'ketersediaan_stok' => 'required|integer|in:2,4,6,8,10',     // Ketersediaan stok wajib diisi, integer, hanya nilai 2, 4, 6, 8, atau 10.
            'ketersediaan_dana' => 'required|integer|in:2,4,6,8,10',     // Ketersediaan dana wajib diisi, integer, hanya nilai 2, 4, 6, 8, atau 10.
        ]);

        $data = $request->all();        // Mengambil semua data dari request.
        $data['user_id'] = auth()->id(); // Menambahkan ID pengguna yang sedang login ke data.
        $data['kode_pengajuan'] = Pengajuan::generateKode(); // Menghasilkan kode pengajuan unik menggunakan metode statis dari model Pengajuan.
        $data['status'] = 'pending';    // Menetapkan status awal pengajuan sebagai 'pending'.

        if ($request->hasFile('file_pengajuan')) { // Memeriksa apakah ada file yang diunggah.
            $path = $request->file('file_pengajuan')->store('pengajuan_files', 'public'); // Menyimpan file ke direktori 'storage/app/public/pengajuan_files'.
            $data['file_pengajuan'] = $path; // Menyimpan path file di database.
        }

        Pengajuan::create($data); // Membuat record baru di tabel 'pengajuan' dengan data yang sudah disiapkan.

        return redirect()->route('pengurus.pengajuan.index') // Mengarahkan kembali ke halaman daftar pengajuan.
            ->with('success', 'Pengajuan pengadaan berhasil ditambahkan'); // Mengirimkan pesan sukses ke view.
    }

    public function update(Request $request, Pengajuan $pengajuan) // Metode update untuk memperbarui pengajuan di database. Menggunakan Route Model Binding.
    {
        // Pastikan hanya pemilik pengajuan yang bisa mengupdate dan status masih pending
        if ($pengajuan->user_id !== auth()->id() || $pengajuan->status !== 'pending') { // Melakukan otorisasi: hanya pemilik pengajuan dan statusnya harus 'pending'.
            abort(403); // Jika tidak memenuhi syarat, tampilkan error 403 Forbidden.
        }

        $request->validate([ // Memvalidasi data yang masuk dari request, sama seperti pada metode store.
            'nama_barang' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'alasan' => 'required|string',
            'kebutuhan' => 'required|date|after_or_equal:today',
            'file_pengajuan' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            // Validasi kriteria TOPSIS
            'urgensi' => 'required|integer|min:1|max:10',
            'ketersediaan_stok' => 'required|integer|in:2,4,6,8,10',
            'ketersediaan_dana' => 'required|integer|in:2,4,6,8,10',
        ]);

        $data = $request->except('file_pengajuan'); // Mengambil semua data dari request kecuali 'file_pengajuan'.

        if ($request->hasFile('file_pengajuan')) { // Memeriksa apakah ada file baru yang diunggah.
            // Hapus file lama jika ada
            if ($pengajuan->file_pengajuan) { // Jika sudah ada file pengajuan lama.
                Storage::disk('public')->delete($pengajuan->file_pengajuan); // Hapus file lama dari penyimpanan.
            }
            $path = $request->file('file_pengajuan')->store('pengajuan_files', 'public'); // Menyimpan file baru.
            $data['file_pengajuan'] = $path; // Menyimpan path file baru di database.
        }

        $pengajuan->update($data); // Memperbarui record Pengajuan dengan data yang sudah disiapkan.

        return redirect()->route('pengurus.pengajuan.index') // Mengarahkan kembali ke halaman daftar pengajuan.
            ->with('success', 'Pengajuan pengadaan berhasil diperbarui'); // Mengirimkan pesan sukses.
    }

    public function show(Pengajuan $pengajuan) // Metode show untuk menampilkan detail satu pengajuan. Menggunakan Route Model Binding.
    {
        // Pastikan hanya pemilik pengajuan yang bisa melihat
        if ($pengajuan->user_id !== auth()->id()) { // Melakukan otorisasi: hanya pemilik pengajuan yang bisa melihat.
            abort(403); // Jika tidak, tampilkan error 403 Forbidden.
        }

        return view('pengurus.pengajuan.show', compact('pengajuan')); // Mengembalikan view 'pengurus.pengajuan.show' dengan data $pengajuan.
    }

    public function edit(Pengajuan $pengajuan) // Metode edit untuk menampilkan form pengubahan pengajuan. Menggunakan Route Model Binding.
    {
        // Pastikan hanya pemilik pengajuan yang bisa mengedit dan status masih pending
        if ($pengajuan->user_id !== auth()->id() || $pengajuan->status !== 'pending') { // Melakukan otorisasi: hanya pemilik pengajuan dan statusnya harus 'pending'.
            abort(403); // Jika tidak, tampilkan error 403 Forbidden.
        }

        return view('pengurus.pengajuan.edit', compact('pengajuan')); // Mengembalikan view 'pengurus.pengajuan.edit' dengan data $pengajuan.
    }

    public function destroy(Pengajuan $pengajuan) // Metode destroy untuk menghapus pengajuan. Menggunakan Route Model Binding.
    {
        // Pastikan hanya pemilik pengajuan yang bisa menghapus dan status masih pending
        if ($pengajuan->user_id !== auth()->id() || $pengajuan->status !== 'pending') { // Melakukan otorisasi: hanya pemilik pengajuan dan statusnya harus 'pending'.
            abort(403); // Jika tidak, tampilkan error 403 Forbidden.
        }

        // Hapus file jika ada
        if ($pengajuan->file_pengajuan) { // Jika ada file pengajuan yang terkait.
            Storage::disk('public')->delete($pengajuan->file_pengajuan); // Hapus file dari penyimpanan.
        }

        $pengajuan->delete(); // Menghapus record pengajuan dari database.

        return redirect()->route('pengurus.pengajuan.index') // Mengarahkan kembali ke halaman daftar pengajuan.
            ->with('success', 'Pengajuan pengadaan berhasil dihapus'); // Mengirimkan pesan sukses.
    }
}