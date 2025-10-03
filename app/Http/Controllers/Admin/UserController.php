<?php
namespace App\Http\Controllers\Admin; // Mendefinisikan namespace untuk controller ini, biasanya digunakan untuk mengelompokkan controller berdasarkan fungsionalitas atau area (misal: Admin)

use App\Http\Controllers\Controller; // Mengimpor base Controller dari Laravel, yang menyediakan fungsionalitas dasar untuk semua controller
use App\Models\User; // Mengimpor model User, yang merepresentasikan tabel 'users' di database
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani HTTP request dari pengguna
use Illuminate\Support\Facades\Hash; // Mengimpor facade Hash untuk mengenkripsi password
use Illuminate\Support\Facades\DB; // Mengimpor facade DB untuk berinteraksi dengan database secara langsung (misal: transaksi database)
use Illuminate\Support\Facades\Log; // Mengimpor facade Log untuk mencatat pesan log (error, info, dll.)

class UserController extends Controller // Mendefinisikan kelas UserController yang mewarisi dari base Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Metode untuk menampilkan daftar semua user
    {
        $query = User::query(); // Menginisialisasi query untuk mengambil semua user

        // Filter pencarian
        if ($request->filled('search')) { // Memeriksa apakah ada parameter 'search' di request
            $search = $request->search; // Mengambil nilai dari parameter 'search'
            $query->where(function ($q) use ($search) { // Menambahkan kondisi pencarian ke query
                $q->where('name', 'like', "%{$search}%") // Mencari berdasarkan nama user
                    ->orWhere('email', 'like', "%{$search}%"); // Atau berdasarkan email user
            });
        }

        // Filter role
        if ($request->filled('role')) { // Memeriksa apakah ada parameter 'role' di request
            $query->where('role', $request->role); // Menambahkan kondisi filter berdasarkan role user
        }

        $users = $query->latest()->paginate(15)->withQueryString(); // Menjalankan query, mengurutkan berdasarkan terbaru, memaginasi 15 item per halaman, dan mempertahankan parameter query pada link paginasi

        // Statistik
        $totalAdmin = User::where('role', 'admin')->count(); // Menghitung jumlah user dengan role 'admin'
        $totalPengurus = User::where('role', 'pengurus')->count(); // Menghitung jumlah user dengan role 'pengurus'
        $totalBendahara = User::where('role', 'bendahara')->count(); // Menghitung jumlah user dengan role 'bendahara'

        return view('admin.pengguna.index', compact( // Mengembalikan view 'admin.pengguna.index' dengan data yang dibutuhkan
            'users', // Data user yang sudah difilter dan dipaginasi
            'totalAdmin', // Statistik total admin
            'totalPengurus', // Statistik total pengurus
            'totalBendahara' // Statistik total bendahara
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() // Metode untuk menampilkan form pembuatan user baru
    {
        return view('admin.pengguna.create'); // Mengembalikan view 'admin.pengguna.create'
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Metode untuk menyimpan data user baru ke database
    {
        $validated = $request->validate([ // Melakukan validasi data yang diterima dari form
            'name' => 'required|string|max:255', // Nama wajib, string, max 255 karakter
            'email' => 'required|string|email|max:255|unique:users', // Email wajib, string, format email, max 255 karakter, dan harus unik di tabel 'users'
            'password' => 'required|string|min:8|confirmed', // Password wajib, string, min 8 karakter, dan harus cocok dengan field konfirmasi password
            'role' => 'required|in:admin,pengurus,bendahara' // Role wajib, harus salah satu dari 'admin', 'pengurus', 'bendahara'
        ], [ // Pesan error kustom untuk validasi
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'email.unique' => 'Email sudah terdaftar'
        ]);

        try {
            DB::beginTransaction(); // Memulai transaksi database. Jika ada error di tengah proses, semua perubahan akan di-rollback.

            $validated['password'] = Hash::make($validated['password']); // Mengenkripsi password sebelum disimpan ke database

            $user = User::create($validated); // Membuat record user baru di database dengan data yang divalidasi

            DB::commit(); // Mengakhiri transaksi database dan menyimpan semua perubahan

            return redirect()->route('admin.users.index') // Redirect ke halaman daftar user
                           ->with('success', 'User berhasil ditambahkan'); // Dengan pesan sukses
        } catch (\Exception $e) { // Menangkap jika terjadi error selama proses
            DB::rollBack(); // Mengembalikan semua perubahan database yang telah dilakukan
            Log::error('Error creating user: ' . $e->getMessage()); // Mencatat error ke log Laravel
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Kembali ke form dengan input sebelumnya dan pesan error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user) // Metode untuk menampilkan detail satu user berdasarkan model binding
    {
        return view('admin.users.show', compact('user')); // Mengembalikan view 'admin.users.show' dengan data user
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) // Metode untuk menampilkan form edit user
    {
        return view('admin.pengguna.edit', compact('user')); // Mengembalikan view 'admin.pengguna.edit' dengan data user yang akan diedit
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) // Metode untuk memperbarui data user di database
    {
        $validated = $request->validate([ // Melakukan validasi data yang diterima dari form
            'name' => 'required|string|max:255', // Nama wajib, string, max 255 karakter
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Email wajib, string, format email, max 255 karakter, harus unik kecuali untuk ID user ini sendiri
            'role' => 'required|in:admin,pengurus,bendahara', // Role wajib, harus salah satu dari 'admin', 'pengurus', 'bendahara'
            'password' => 'nullable|string|min:8|confirmed' // Password opsional (nullable), string, min 8 karakter, dan harus cocok dengan field konfirmasi password
        ], [ // Pesan error kustom untuk validasi
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'email.unique' => 'Email sudah terdaftar'
        ]);

        try {
            DB::beginTransaction(); // Memulai transaksi database

            // Update password jika diisi
            if (!empty($validated['password'])) { // Jika field password tidak kosong di request
                $validated['password'] = Hash::make($validated['password']); // Enkripsi password baru
            } else {
                unset($validated['password']); // Hapus field password dari data yang divalidasi agar tidak mengupdate password jika tidak diisi
            }

            $user->update($validated); // Memperbarui record user di database

            DB::commit(); // Menyimpan perubahan

            return redirect()->route('admin.users.index') // Redirect ke halaman daftar user
                           ->with('success', 'User berhasil diperbarui'); // Dengan pesan sukses
        } catch (\Exception $e) {
            DB::rollBack(); // Mengembalikan perubahan jika terjadi error
            Log::error('Error updating user: ' . $e->getMessage()); // Mencatat error ke log
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Kembali ke form dengan input sebelumnya dan pesan error
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) // Metode untuk menghapus user
    {
        try {
            // Cek apakah user yang sedang login
            if (auth()->user()->id === $user->id) { // Memeriksa apakah ID user yang akan dihapus sama dengan ID user yang sedang login
                return response()->json([ // Jika ya, kembalikan respons JSON dengan error
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun yang sedang digunakan'
                ], 422); // Status kode 422 (Unprocessable Entity)
            }

            // Cek apakah user memiliki transaksi
            // Memeriksa apakah user memiliki record di tabel barang_masuk atau barang_keluar melalui relasi
            if ($user->barangMasuk()->exists() || $user->barangKeluar()->exists()) {
                return response()->json([ // Jika ya, kembalikan respons JSON dengan error
                    'success' => false,
                    'message' => 'User tidak dapat dihapus karena memiliki riwayat transaksi'
                ], 422); // Status kode 422 (Unprocessable Entity)
            }

            $user->delete(); // Melakukan penghapusan record user dari database (ini adalah penghapusan permanen karena model User tidak menggunakan SoftDeletes secara default, atau ini adalah penghapusan permanen jika SoftDeletes digunakan dan ini adalah forceDelete)

            return response()->json([ // Mengembalikan respons JSON dengan pesan sukses
                'success' => true,
                'message' => 'User berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage()); // Mencatat error ke log Laravel
            return response()->json([ // Mengembalikan respons JSON dengan error umum
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage()
            ], 500); // Status kode 500 (Internal Server Error)
        }
    }

    /**
     * Reset password user
     */
    public function resetPassword(Request $request, User $user) // Metode untuk mereset password user
    {
        $validated = $request->validate([ // Melakukan validasi untuk password baru
            'password' => 'required|string|min:8|confirmed' // Password wajib, string, min 8 karakter, dan harus cocok dengan konfirmasi
        ], [ // Pesan error kustom
            'password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);

        try {
            $user->update([ // Memperbarui password user
                'password' => Hash::make($validated['password']) // Enkripsi password baru
            ]);

            return response()->json([ // Mengembalikan respons JSON dengan pesan sukses
                'success' => true,
                'message' => 'Password berhasil direset'
            ]);
        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage()); // Mencatat error ke log
            return response()->json([ // Mengembalikan respons JSON dengan error umum
                'success' => false,
                'message' => 'Gagal mereset password: ' . $e->getMessage()
            ], 500); // Status kode 500 (Internal Server Error)
        }
    }
}