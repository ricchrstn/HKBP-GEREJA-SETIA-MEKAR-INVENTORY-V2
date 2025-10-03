<?php

namespace App\Http\Controllers\Bendahara; // Mendefinisikan namespace untuk controller ini, menandakan lokasinya.

use App\Http\Controllers\Controller; // Mengimpor base Controller dari Laravel.
use App\Models\Pengajuan; // Mengimpor model Pengajuan yang merepresentasikan data pengajuan pengadaan.
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP.

class VerifikasiPengadaanController extends Controller // Mendefinisikan kelas VerifikasiPengadaanController yang mewarisi dari base Controller.
{
    public function index(Request $request) // Metode untuk menampilkan daftar pengajuan pengadaan yang perlu diverifikasi.
    {
        // Memulai query untuk mengambil data Pengajuan.
        // Eager load relasi 'user' (agar bisa mengakses data user yang mengajukan).
        // Memfilter agar hanya menampilkan pengajuan dengan status 'pending', 'disetujui', 'ditolak', atau 'proses'.
        $query = Pengajuan::with('user')->whereIn('status', ['pending', 'disetujui', 'ditolak', 'proses']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) { // Memeriksa apakah ada parameter 'search' di request.
            $query->where('nama_barang', 'like', '%' . $request->search . '%') // Mencari berdasarkan nama barang.
                  ->orWhere('kode_pengajuan', 'like', '%' . $request->search . '%') // Atau berdasarkan kode pengajuan.
                  ->orWhere('alasan', 'like', '%' . $request->search . '%'); // Atau berdasarkan alasan pengajuan.
        }

        // Filter berdasarkan status
        if ($request->filled('status')) { // Memeriksa apakah ada parameter 'status' di request.
            $query->where('status', $request->status); // Menambahkan kondisi WHERE berdasarkan status tertentu.
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) { // Memeriksa apakah ada parameter 'tanggal' di request.
            $query->whereDate('created_at', $request->tanggal); // Menambahkan kondisi WHERE berdasarkan tanggal pembuatan pengajuan.
        }

        // Menjalankan query, mengurutkan hasil berdasarkan tanggal pembuatan terbaru (latest()), dan memaginasi 10 item per halaman.
        $pengajuans = $query->latest()->paginate(10);

        // Mengembalikan view 'bendahara.verifikasi.index' dengan data pengajuan yang sudah difilter dan dipaginasi.
        return view('bendahara.verifikasi.index', compact('pengajuans'));
    }

    // Metode untuk menampilkan detail satu pengajuan pengadaan.
    // Menggunakan Route Model Binding: Laravel akan secara otomatis mencari model Pengajuan berdasarkan ID dari URL.
    public function show(Pengajuan $pengajuan)
    {
        // Mengembalikan view 'bendahara.verifikasi.show' dengan data pengajuan yang ditemukan.
        return view('bendahara.verifikasi.show', compact('pengajuan'));
    }

    // Metode untuk memverifikasi (mengubah status) pengajuan pengadaan.
    // Menggunakan Route Model Binding untuk mengambil instance Pengajuan.
    public function verifikasi(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([ // Memvalidasi data yang masuk dari form verifikasi.
            'status' => 'required|in:disetujui,ditolak,proses', // 'status' wajib diisi dan harus salah satu dari 'disetujui', 'ditolak', 'proses'.
            'keterangan' => 'nullable|string|max:1000' // 'keterangan' bisa kosong (nullable), harus string, dan maksimal 1000 karakter.
        ]);

        $pengajuan->update([ // Memperbarui data pengajuan yang ditemukan.
            'status' => $request->status, // Mengubah status pengajuan sesuai input request.
            'keterangan' => $request->keterangan // Mengubah keterangan pengajuan sesuai input request.
        ]);

        // Mengarahkan kembali ke halaman index verifikasi pengadaan.
        return redirect()->route('bendahara.verifikasi.index')
                         ->with('success', 'Pengajuan berhasil diverifikasi'); // Menampilkan pesan sukses.
    }
}