<?php

namespace App\Http\Controllers\Admin; // Mendefinisikan namespace untuk controller ini, mengindikasikan bahwa ini adalah bagian dari fungsionalitas admin

use App\Http\Controllers\Controller; // Mengimpor base Controller dari Laravel
use App\Models\JadwalAudit; // Mengimpor model JadwalAudit untuk berinteraksi dengan tabel jadwal_audits
use App\Models\Barang; // Mengimpor model Barang untuk mendapatkan daftar barang
use App\Models\User; // Mengimpor model User untuk mendapatkan daftar user (petugas audit)
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani HTTP request dari pengguna

class JadwalAuditController extends Controller // Mendefinisikan kelas JadwalAuditController yang mewarisi dari base Controller
{
    public function index(Request $request) // Metode untuk menampilkan daftar semua jadwal audit
    {
        $query = JadwalAudit::with(['barang', 'user']); // Menginisialisasi query untuk mengambil semua jadwal audit, dengan memuat relasi 'barang' dan 'user' (agar bisa menampilkan nama barang dan nama user)

        // Filter berdasarkan pencarian
        if ($request->filled('search')) { // Memeriksa apakah ada parameter 'search' di request
            $query->where('judul', 'like', '%' . $request->search . '%') // Mencari berdasarkan 'judul' jadwal audit
                ->orWhereHas('barang', function ($q) use ($request) { // Atau mencari di relasi 'barang'
                    $q->where('nama', 'like', '%' . $request->search . '%'); // Mencari berdasarkan 'nama' barang
                });
        }
        // Filter berdasarkan status
        if ($request->filled('status')) { // Memeriksa apakah ada parameter 'status' di request
            $query->where('status', $request->status); // Menambahkan kondisi filter berdasarkan 'status' jadwal audit
        }
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) { // Memeriksa apakah ada parameter 'tanggal' di request
            $query->whereDate('tanggal_audit', $request->tanggal); // Menambahkan kondisi filter berdasarkan tanggal audit (hanya tanggal, mengabaikan waktu)
        }
        $jadwalAudits = $query->latest()->paginate(10); // Menjalankan query, mengurutkan berdasarkan yang terbaru, dan memaginasi 10 item per halaman

        // Ganti path view
        return view('admin.jadwal-audit.index', compact('jadwalAudits')); // Mengembalikan view 'admin.jadwal-audit.index' dengan data jadwal audit
    }

    public function create() // Metode untuk menampilkan form pembuatan jadwal audit baru
    {
        $barangs = Barang::all(); // Mengambil semua data barang untuk dropdown pilihan barang
        $users = User::all(); // Mengambil semua data user untuk dropdown pilihan petugas audit
        // Ganti path view
        return view('admin.jadwal-audit.create', compact('barangs', 'users')); // Mengembalikan view 'admin.jadwal-audit.create' dengan data barang dan user
    }

    public function store(Request $request) // Metode untuk menyimpan data jadwal audit baru ke database
    {
        $request->validate([ // Melakukan validasi data yang diterima dari form
            'judul' => 'required|string|max:255', // Judul wajib, string, max 255 karakter
            'deskripsi' => 'nullable|string', // Deskripsi opsional, string
            'tanggal_audit' => 'required|date', // Tanggal audit wajib, harus format tanggal
            'barang_id' => 'required|exists:barang,id', // ID barang wajib, harus ada di tabel 'barang' kolom 'id'
            'user_id' => 'required|exists:users,id', // ID user wajib, harus ada di tabel 'users' kolom 'id'
        ]);

        JadwalAudit::create($request->all()); // Membuat record jadwal audit baru di database dengan semua data yang divalidasi

        return redirect()->route('admin.jadwal-audit.index') // Redirect ke halaman daftar jadwal audit
            ->with('success', 'Jadwal audit berhasil ditambahkan'); // Dengan pesan sukses
    }

    public function edit(JadwalAudit $jadwalAudit) // Metode untuk menampilkan form edit jadwal audit berdasarkan model binding
    {
        $barangs = Barang::all(); // Mengambil semua data barang
        $users = User::all(); // Mengambil semua data user
        return view('admin.jadwal-audit.edit', compact('jadwalAudit', 'barangs', 'users')); // Mengembalikan view 'admin.jadwal-audit.edit' dengan data jadwal audit yang akan diedit, barang, dan user
    }

    public function update(Request $request, JadwalAudit $jadwalAudit) // Metode untuk memperbarui data jadwal audit di database
    {
        $request->validate([ // Melakukan validasi data yang diterima dari form
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_audit' => 'required|date',
            'barang_id' => 'required|exists:barang,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:terjadwal,diproses,selesai,ditunda', // Status wajib, harus salah satu dari nilai yang ditentukan
        ]);

        $jadwalAudit->update($request->all()); // Memperbarui record jadwal audit di database dengan semua data yang divalidasi

        return redirect()->route('admin.jadwal-audit.index') // Redirect ke halaman daftar jadwal audit
            ->with('success', 'Jadwal audit berhasil diperbarui'); // Dengan pesan sukses
    }

    public function destroy(JadwalAudit $jadwalAudit) // Metode untuk menghapus jadwal audit
    {
        $jadwalAudit->delete(); // Melakukan penghapusan record jadwal audit dari database (jika model JadwalAudit menggunakan SoftDeletes, maka akan di-soft delete)

        return redirect()->route('admin.jadwal-audit.index') // Redirect ke halaman daftar jadwal audit
            ->with('success', 'Jadwal audit berhasil dihapus'); // Dengan pesan sukses
    }
}