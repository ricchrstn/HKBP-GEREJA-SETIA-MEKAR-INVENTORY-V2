<?php
// app/Http/Controllers/Pengurus/AuditController.php
namespace App\Http\Controllers\Pengurus; // Mendefinisikan namespace untuk controller ini, menunjukkan lokasinya.

use App\Http\Controllers\Controller; // Mengimpor base Controller dari Laravel.
use App\Models\Audit; // Mengimpor model Audit (untuk audit yang dilakukan).
use App\Models\Barang; // Mengimpor model Barang (untuk data barang).
use App\Models\JadwalAudit; // Mengimpor model JadwalAudit (untuk jadwal audit yang diberikan admin).
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP.
use Illuminate\Support\Facades\Storage; // Mengimpor Facade Storage untuk mengelola penyimpanan file.

class AuditController extends Controller // Mendefinisikan kelas AuditController yang mewarisi dari base Controller.
{
    public function index(Request $request) // Metode untuk menampilkan daftar audit mandiri dan jadwal audit.
    {
        // Ambil audit mandiri yang dibuat oleh pengurus yang sedang login
        $queryMandiri = Audit::with(['barang', 'user'])->where('user_id', auth()->id()); // Memulai query audit mandiri, eager load relasi barang dan user, filter berdasarkan user_id yang sedang login.

        // Filter berdasarkan pencarian untuk audit mandiri
        if ($request->filled('search')) { // Jika ada parameter 'search'.
            $queryMandiri->where('keterangan', 'like', '%' . $request->search . '%') // Cari di kolom 'keterangan'.
                ->orWhereHas('barang', function ($q) use ($request) { // Atau cari di relasi 'barang'.
                    $q->where('nama', 'like', '%' . $request->search . '%'); // Cari nama barang.
                });
        }

        // Filter berdasarkan kondisi untuk audit mandiri
        if ($request->filled('kondisi')) { // Jika ada parameter 'kondisi'.
            $queryMandiri->where('kondisi', $request->kondisi); // Filter berdasarkan kondisi barang.
        }

        // Filter berdasarkan tanggal untuk audit mandiri
        if ($request->filled('tanggal')) { // Jika ada parameter 'tanggal'.
            $queryMandiri->whereDate('tanggal_audit', $request->tanggal); // Filter berdasarkan tanggal audit.
        }

        // Ambil hasil audit mandiri, urutkan terbaru, paginasi 10 item, dengan nama parameter 'mandiri_page'.
        $auditsMandiri = $queryMandiri->latest()->paginate(10, ['*'], 'mandiri_page');

        // Ambil jadwal audit dari admin untuk pengurus yang sedang login
        $queryJadwal = JadwalAudit::with(['barang', 'user']) // Memulai query jadwal audit, eager load relasi barang dan user.
            ->where('user_id', auth()->id()) // Filter hanya jadwal untuk user_id yang sedang login.
            ->whereIn('status', ['terjadwal', 'diproses']); // Hanya tampilkan jadwal dengan status 'terjadwal' atau 'diproses'.

        // Filter berdasarkan pencarian untuk jadwal audit
        if ($request->filled('search')) { // Jika ada parameter 'search'.
            $queryJadwal->where('judul', 'like', '%' . $request->search . '%') // Cari di kolom 'judul'.
                ->orWhereHas('barang', function ($q) use ($request) { // Atau cari di relasi 'barang'.
                    $q->where('nama', 'like', '%' . $request->search . '%'); // Cari nama barang.
                });
        }

        // Filter berdasarkan tanggal untuk jadwal audit
        if ($request->filled('tanggal')) { // Jika ada parameter 'tanggal'.
            $queryJadwal->whereDate('tanggal_audit', $request->tanggal); // Filter berdasarkan tanggal audit.
        }

        // Ambil hasil jadwal audit, urutkan terbaru, paginasi 10 item, dengan nama parameter 'jadwal_page'.
        $jadwalAudits = $queryJadwal->latest()->paginate(10, ['*'], 'jadwal_page');

        // Mengembalikan view 'pengurus.audit.index' dengan data audit mandiri dan jadwal audit.
        return view('pengurus.audit.index', compact('auditsMandiri', 'jadwalAudits'));
    }

    public function create() // Metode untuk menampilkan form pembuatan audit mandiri baru.
    {
        $barangs = Barang::all(); // Mengambil semua data barang.
        $categories = \App\Models\Kategori::all(); // Mengambil semua data kategori.
        return view('pengurus.audit.create', compact('barangs', 'categories')); // Mengembalikan view 'pengurus.audit.create' dengan data barang dan kategori.
    }

    public function store(Request $request) // Metode untuk menyimpan data audit mandiri baru ke database.
    {
        $request->validate([ // Memvalidasi data yang masuk dari form.
            'barang_id' => 'required|exists:barang,id', // 'barang_id' wajib dan harus ada di tabel 'barang'.
            'tanggal_audit' => 'required|date', // 'tanggal_audit' wajib dan harus format tanggal.
            'kondisi' => 'required|in:baik,rusak,hilang,tidak_terpakai', // 'kondisi' wajib dan harus salah satu nilai yang ditentukan.
            'keterangan' => 'nullable|string', // 'keterangan' bisa null, harus string.
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 'foto' bisa null, harus gambar, format tertentu, maks 2MB.
        ]);

        $data = $request->except('foto'); // Mengambil semua data dari request kecuali 'foto'.
        $data['user_id'] = auth()->id(); // Menetapkan user_id dari user yang sedang login.
        $data['status'] = 'selesai'; // Menetapkan status audit sebagai 'selesai' (karena ini audit mandiri).

        if ($request->hasFile('foto')) { // Jika ada file foto yang diunggah.
            $path = $request->file('foto')->store('audit_foto', 'public'); // Simpan foto ke direktori 'audit_foto' di public storage.
            $data['foto'] = $path; // Simpan path foto ke array data.
        }

        Audit::create($data); // Membuat record audit baru di database.

        return redirect()->route('pengurus.audit.index') // Mengarahkan kembali ke halaman index audit.
            ->with('success', 'Audit barang berhasil ditambahkan'); // Menampilkan pesan sukses.
    }

    public function selesaikanJadwal(Request $request, JadwalAudit $jadwalAudit) // Metode untuk menyelesaikan jadwal audit yang diberikan admin.
    {
        // Pastikan hanya pengurus yang ditugaskan yang bisa menyelesaikan
        if ($jadwalAudit->user_id !== auth()->id()) { // Memeriksa apakah user yang login adalah user yang ditugaskan pada jadwal audit ini.
            return response()->json([ // Jika tidak, kembalikan respon JSON error.
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menyelesaikan jadwal audit ini'
            ], 403); // Status HTTP 403 (Forbidden).
        }

        // Validasi input
        $validated = $request->validate([ // Memvalidasi input dari form penyelesaian jadwal.
            'kondisi' => 'required|in:baik,rusak,hilang,tidak_terpakai', // 'kondisi' wajib dan harus salah satu nilai yang ditentukan.
            'keterangan' => 'required|string', // 'keterangan' wajib dan harus string.
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 'foto' bisa null, harus gambar, format tertentu, maks 2MB.
        ]);

        // Update status jadwal audit
        $jadwalAudit->status = 'selesai'; // Mengubah status jadwal audit menjadi 'selesai'.
        $jadwalAudit->save(); // Menyimpan perubahan status jadwal audit.

        // Buat audit mandiri berdasarkan jadwal audit
        $data = [ // Menyiapkan data untuk membuat record Audit baru.
            'barang_id' => $jadwalAudit->barang_id, // Ambil barang_id dari jadwal audit.
            'user_id' => auth()->id(), // user_id adalah user yang sedang login.
            'tanggal_audit' => $jadwalAudit->tanggal_audit, // Tanggal audit diambil dari jadwal.
            'kondisi' => $validated['kondisi'], // Kondisi diambil dari input yang divalidasi.
            'keterangan' => $validated['keterangan'], // Keterangan diambil dari input yang divalidasi.
            'status' => 'selesai', // Status audit ini adalah 'selesai'.
        ];

        if ($request->hasFile('foto')) { // Jika ada file foto yang diunggah.
            $path = $request->file('foto')->store('audit_foto', 'public'); // Simpan foto.
            $data['foto'] = $path; // Simpan path foto.
        }

        Audit::create($data); // Membuat record audit baru di database.

        return response()->json([ // Mengembalikan respon JSON sukses.
            'success' => true,
            'message' => 'Jadwal audit berhasil diselesaikan'
        ]);
    }

    public function show(Audit $audit) // Metode untuk menampilkan detail satu audit mandiri.
    {
        // Pastikan hanya pemilik audit yang bisa melihat
        if ($audit->user_id !== auth()->id()) { // Memeriksa apakah user yang login adalah pemilik audit.
            abort(403); // Jika tidak, hentikan eksekusi dengan error 403 (Forbidden).
        }
        return view('pengurus.audit.show', compact('audit')); // Mengembalikan view 'pengurus.audit.show' dengan data audit.
    }

    public function showJadwal(JadwalAudit $jadwalAudit) // Metode untuk menampilkan detail satu jadwal audit.
    {
        // Pastikan hanya pengurus yang ditugaskan yang bisa melihat
        if ($jadwalAudit->user_id !== auth()->id()) { // Memeriksa apakah user yang login adalah user yang ditugaskan pada jadwal audit ini.
            abort(403); // Jika tidak, hentikan eksekusi dengan error 403 (Forbidden).
        }

        return view('pengurus.audit.show-jadwal', compact('jadwalAudit')); // Mengembalikan view 'pengurus.audit.show-jadwal' dengan data jadwal audit.
    }

    public function edit(Audit $audit) // Metode untuk menampilkan form edit audit mandiri.
    {
        // Pastikan hanya pemilik audit yang bisa mengedit
        if ($audit->user_id !== auth()->id()) { // Memeriksa apakah user yang login adalah pemilik audit.
            abort(403); // Jika tidak, hentikan eksekusi dengan error 403 (Forbidden).
        }
        $barangs = Barang::all(); // Mengambil semua data barang.
        $categories = \App\Models\Kategori::all(); // Mengambil semua data kategori.
        return view('pengurus.audit.edit', compact('audit', 'barangs', 'categories')); // Mengembalikan view 'pengurus.audit.edit' dengan data audit, barang, dan kategori.
    }

    public function update(Request $request, Audit $audit) // Metode untuk memperbarui data audit mandiri yang sudah ada.
    {
        // Pastikan hanya pemilik audit yang bisa mengupdate
        if ($audit->user_id !== auth()->id()) { // Memeriksa apakah user yang login adalah pemilik audit.
            abort(403); // Jika tidak, hentikan eksekusi dengan error 403 (Forbidden).
        }
        $request->validate([ // Memvalidasi data yang masuk dari form.
            'barang_id' => 'required|exists:barang,id',
            'tanggal_audit' => 'required|date',
            'kondisi' => 'required|in:baik,rusak,hilang,tidak_terpakai',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('foto'); // Mengambil semua data dari request kecuali 'foto'.

        if ($request->hasFile('foto')) { // Jika ada file foto baru yang diunggah.
            // Hapus foto lama jika ada
            if ($audit->foto) { // Jika ada foto lama.
                Storage::disk('public')->delete($audit->foto); // Hapus foto lama dari storage.
            }
            $path = $request->file('foto')->store('audit_foto', 'public'); // Simpan foto baru.
            $data['foto'] = $path; // Simpan path foto baru.
        }

        $audit->update($data); // Memperbarui record audit di database.

        return redirect()->route('pengurus.audit.index') // Mengarahkan kembali ke halaman index audit.
            ->with('success', 'Audit barang berhasil diperbarui'); // Menampilkan pesan sukses.
    }

    public function destroy(Audit $audit) // Metode untuk menghapus audit mandiri.
    {
        // Pastikan hanya pemilik audit yang bisa menghapus
        if ($audit->user_id !== auth()->id()) { // Memeriksa apakah user yang login adalah pemilik audit.
            abort(403); // Jika tidak, hentikan eksekusi dengan error 403 (Forbidden).
        }
        // Hapus foto jika ada
        if ($audit->foto) { // Jika ada foto yang terkait.
            Storage::disk('public')->delete($audit->foto); // Hapus foto dari storage.
        }
        $audit->delete(); // Menghapus record audit dari database.
        return redirect()->route('pengurus.audit.index') // Mengarahkan kembali ke halaman index audit.
            ->with('success', 'Audit barang berhasil dihapus'); // Menampilkan pesan sukses.
    }
}