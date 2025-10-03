<?php

namespace App\Http\Controllers\Admin; // Mendefinisikan namespace untuk controller ini, mengindikasikan bahwa ini adalah bagian dari fungsionalitas admin

use App\Http\Controllers\Controller; // Mengimpor base Controller dari Laravel
use App\Models\Kategori; // Mengimpor model Kategori untuk berinteraksi dengan tabel kategori
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani HTTP request dari pengguna
use Illuminate\Support\Facades\Log; // Mengimpor facade Log untuk mencatat pesan log (error, info, dll.)

class KategoriController extends Controller // Mendefinisikan kelas KategoriController yang mewarisi dari base Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Metode untuk menampilkan daftar semua kategori
    {
        $query = Kategori::query(); // Menginisialisasi query untuk mengambil semua kategori

        // Filter pencarian
        if ($request->filled('search')) { // Memeriksa apakah ada parameter 'search' di request
            $search = $request->search; // Mengambil nilai dari parameter 'search'
            $query->where(function ($q) use ($search) { // Menambahkan kondisi pencarian ke query
                $q->where('nama', 'like', "%{$search}%") // Mencari berdasarkan nama kategori
                    ->orWhere('deskripsi', 'like', "%{$search}%"); // Atau berdasarkan deskripsi kategori
            });
        }

        $kategoris = $query->latest()->paginate(10)->withQueryString(); // Menjalankan query, mengurutkan berdasarkan terbaru, memaginasi 10 item per halaman, dan mempertahankan parameter query pada link paginasi

        return view('admin.kategori.index', compact('kategoris')); // Mengembalikan view 'admin.kategori.index' dengan data kategori
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Metode untuk menyimpan data kategori baru ke database
    {
        try { // Memulai blok try-catch untuk menangani potensi error
            $validated = $request->validate([ // Melakukan validasi data yang diterima dari form
                'nama' => 'required|string|max:255|unique:kategori,nama', // Nama wajib, string, max 255 karakter, dan harus unik di tabel 'kategori' kolom 'nama'
                'deskripsi' => 'nullable|string', // Deskripsi opsional, string
            ], [ // Pesan error kustom untuk validasi
                'nama.required' => 'Nama kategori harus diisi',
                'nama.unique' => 'Nama kategori sudah digunakan',
            ]);

            $kategori = Kategori::create($validated); // Membuat record kategori baru di database dengan data yang divalidasi

            // Cek jika request AJAX
            if ($request->ajax() || $request->wantsJson()) { // Memeriksa apakah request berasal dari AJAX atau mengharapkan JSON
                return response()->json([ // Mengembalikan respons JSON
                    'success' => true,
                    'message' => 'Kategori berhasil ditambahkan',
                    'kategori' => $kategori // Mengirim kembali data kategori yang baru dibuat
                ]);
            }

            // Jika bukan AJAX, redirect dengan flash message
            return redirect()->route('admin.kategori.index') // Redirect ke halaman daftar kategori
                ->with('success', 'Kategori berhasil ditambahkan'); // Dengan pesan sukses
        } catch (\Illuminate\Validation\ValidationException $e) { // Menangkap jika terjadi error validasi
            if ($request->ajax() || $request->wantsJson()) { // Memeriksa apakah request berasal dari AJAX atau mengharapkan JSON
                return response()->json([ // Mengembalikan respons JSON dengan error validasi
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors() // Mengirimkan detail error validasi
                ], 422); // Status kode 422 (Unprocessable Entity)
            }

            return redirect()->back() // Kembali ke halaman sebelumnya
                ->withErrors($e->errors()) // Mengirimkan error validasi ke view
                ->withInput(); // Mengembalikan input sebelumnya agar tidak perlu mengisi ulang
        } catch (\Exception $e) { // Menangkap jenis error lainnya
            Log::error('Error creating kategori: ' . $e->getMessage()); // Mencatat error ke log Laravel

            if ($request->ajax() || $request->wantsJson()) { // Memeriksa apakah request berasal dari AJAX atau mengharapkan JSON
                return response()->json([ // Mengembalikan respons JSON dengan error umum
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500); // Status kode 500 (Internal Server Error)
            }

            return redirect()->back() // Kembali ke halaman sebelumnya
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage()) // Dengan pesan error
                ->withInput(); // Mengembalikan input sebelumnya
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori) // Metode untuk menampilkan detail satu kategori berdasarkan model binding
    {
        return view('admin.kategori.show', compact('kategori')); // Mengembalikan view 'admin.kategori.show' dengan data kategori
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori) // Metode untuk menampilkan form edit kategori
    {
        return view('admin.kategori.edit', compact('kategori')); // Mengembalikan view 'admin.kategori.edit' dengan data kategori yang akan diedit
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori) // Metode untuk memperbarui data kategori di database
    {
        try { // Memulai blok try-catch untuk menangani potensi error
            $validated = $request->validate([ // Melakukan validasi data yang diterima dari form
                'nama' => 'required|string|max:255|unique:kategori,nama,' . $kategori->id, // Nama wajib, string, max 255 karakter, dan harus unik kecuali untuk ID kategori itu sendiri
                'deskripsi' => 'nullable|string', // Deskripsi opsional, string
            ], [ // Pesan error kustom untuk validasi
                'nama.required' => 'Nama kategori harus diisi',
                'nama.unique' => 'Nama kategori sudah digunakan',
            ]);

            $kategori->update($validated); // Memperbarui record kategori di database dengan data yang divalidasi

            if ($request->ajax()) { // Memeriksa apakah request berasal dari AJAX
                return response()->json([ // Mengembalikan respons JSON
                    'success' => true,
                    'message' => 'Kategori berhasil diperbarui',
                    'kategori' => $kategori // Mengirim kembali data kategori yang baru diperbarui
                ]);
            }

            return redirect()->route('admin.kategori.index') // Redirect ke halaman daftar kategori
                ->with('success', 'Kategori berhasil diperbarui'); // Dengan pesan sukses
        } catch (\Illuminate\Validation\ValidationException $e) { // Menangkap jika terjadi error validasi
            if ($request->ajax()) { // Memeriksa apakah request berasal dari AJAX
                return response()->json([ // Mengembalikan respons JSON dengan error validasi
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422); // Status kode 422 (Unprocessable Entity)
            }

            return redirect()->back() // Kembali ke halaman sebelumnya
                ->withErrors($e->errors()) // Mengirimkan error validasi ke view
                ->withInput(); // Mengembalikan input sebelumnya
        } catch (\Exception $e) { // Menangkap jenis error lainnya
            Log::error('Error updating kategori: ' . $e->getMessage()); // Mencatat error ke log Laravel

            if ($request->ajax()) { // Memeriksa apakah request berasal dari AJAX
                return response()->json([ // Mengembalikan respons JSON dengan error umum
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500); // Status kode 500 (Internal Server Error)
            }

            return redirect()->back() // Kembali ke halaman sebelumnya
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage()) // Dengan pesan error
                ->withInput(); // Mengembalikan input sebelumnya
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori) // Metode untuk menghapus kategori
    {
        try { // Memulai blok try-catch untuk menangani potensi error
            // Check if category has related items
            if ($kategori->barangs()->count() > 0) { // Memeriksa apakah ada barang yang terkait dengan kategori ini (menggunakan relasi 'barangs' di model Kategori)
                return response()->json([ // Jika ada, kembalikan respons JSON dengan error
                    'success' => false,
                    'message' => 'Kategori tidak dapat dihapus karena masih digunakan oleh barang'
                ], 422); // Status kode 422 (Unprocessable Entity)
            }

            $kategori->delete(); // Melakukan penghapusan record kategori dari database (ini adalah penghapusan permanen karena model Kategori tidak menggunakan SoftDeletes)

            return response()->json([ // Mengembalikan respons JSON dengan pesan sukses
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (\Exception $e) { // Menangkap jenis error lainnya
            Log::error('Error deleting kategori: ' . $e->getMessage()); // Mencatat error ke log Laravel

            return response()->json([ // Mengembalikan respons JSON dengan error umum
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500); // Status kode 500 (Internal Server Error)
        }
    }
}