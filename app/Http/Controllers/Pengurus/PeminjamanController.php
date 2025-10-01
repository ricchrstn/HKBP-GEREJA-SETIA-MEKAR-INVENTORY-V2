<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Str; // Tambahkan ini untuk Str::limit

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Cek dan update status terlambat setiap kali halaman dimuat
        $this->checkAndUpdateOverdue();

        // Mulai dengan query builder agar bisa ditambahkan filter
        $query = Peminjaman::with(['barang', 'user', 'kategori']);

        // Filter pencarian berdasarkan nama barang, kode barang, peminjam, atau keperluan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('barang', function ($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%")
                          ->orWhere('kode_barang', 'like', "%{$search}%");
                })
                ->orWhere('peminjam', 'like', "%{$search}%")
                ->orWhere('keperluan', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status (dipinjam, dikembalikan, terlambat)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan rentang tanggal pinjam
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_selesai);
        }

        // Gunakan paginate() dan simpan dalam variabel $peminjamans (plural)
        $peminjamans = $query->orderBy('tanggal_pinjam', 'desc')->paginate(15)->withQueryString();

        // Kirim variabel $peminjamans ke view
        return view('pengurus.peminjaman.index', compact('peminjamans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('pengurus.peminjaman.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
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
            DB::beginTransaction();

            // Get barang
            $barang = Barang::findOrFail($request->barang_id);

            // Check if stok is sufficient
            if ($barang->stok < $request->jumlah) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jumlah' => 'Stok tidak mencukupi! Stok tersedia: ' . $barang->stok]);
            }

            // Create peminjaman
            $peminjaman = Peminjaman::create([
                'barang_id' => $request->barang_id,
                'kategori_id' => $request->kategori_id,
                'user_id' => Auth::id(),
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'jumlah' => $request->jumlah,
                'peminjam' => $request->nama_peminjam,
                'kontak' => $request->kontak,
                'keperluan' => $request->keperluan,
                'keterangan' => $request->keterangan,
                'status' => 'dipinjam',
            ]);

            // Update barang stok
            $barang->stok -= $request->jumlah;
            $barang->save();

            DB::commit();

            return redirect()->route('pengurus.peminjaman.index')
                ->with('success', 'Data peminjaman berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['barang', 'user', 'kategori']);
        return view('pengurus.peminjaman.show', compact('peminjaman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman)
    {
        $kategoris = Kategori::all();
        $peminjaman->load(['barang']);
        return view('pengurus.peminjaman.edit', compact('peminjaman', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
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
            DB::beginTransaction();

            // Get old and new barang
            $oldBarang = Barang::findOrFail($peminjaman->barang_id);
            $newBarang = Barang::findOrFail($request->barang_id);

            // If barang changed, restore old barang stok and check new barang stok
            if ($oldBarang->id != $newBarang->id) {
                // Restore old barang stok
                $oldBarang->stok += $peminjaman->jumlah;
                $oldBarang->save();

                // Check if new barang stok is sufficient
                if ($newBarang->stok < $request->jumlah) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['jumlah' => 'Stok tidak mencukupi! Stok tersedia: ' . $newBarang->stok]);
                }

                // Update new barang stok
                $newBarang->stok -= $request->jumlah;
                $newBarang->save();
            } else {
                // If same barang, adjust stok based on quantity change
                $stokDifference = $peminjaman->jumlah - $request->jumlah;

                if ($stokDifference > 0) { // Returned more than borrowed
                    if ($newBarang->stok < $stokDifference) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['jumlah' => 'Stok tidak mencukupi! Stok tersedia: ' . $newBarang->stok]);
                    }
                    $newBarang->stok += $stokDifference;
                } else { // Borrowed more than returned
                    $newBarang->stok += $stokDifference; // stokDifference is negative
                }

                $newBarang->save();
            }

            // Update peminjaman
            $peminjaman->update([
                'barang_id' => $request->barang_id,
                'kategori_id' => $request->kategori_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'jumlah' => $request->jumlah,
                'peminjam' => $request->nama_peminjam,
                'kontak' => $request->kontak,
                'keperluan' => $request->keperluan,
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();

            return redirect()->route('pengurus.peminjaman.index')
                ->with('success', 'Data peminjaman berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        try {
            DB::beginTransaction();

            // Get barang
            $barang = Barang::findOrFail($peminjaman->barang_id);

            // Restore barang stok
            $barang->stok += $peminjaman->jumlah;
            $barang->save();

            // Delete peminjaman
            $peminjaman->delete();

            DB::commit();

            return redirect()->route('pengurus.peminjaman.index')
                ->with('success', 'Data peminjaman berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }

    /**
     * Return the borrowed item.
     */
    public function kembalikan(Peminjaman $peminjaman)
    {
        // Cek apakah status masih 'dipinjam' atau 'terlambat' untuk mencegah pengembalian ganda
        if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
            return redirect()->back()->with('error', 'Barang ini sudah dikembalikan atau statusnya tidak valid.');
        }

        try {
            DB::beginTransaction();

            // Get barang
            $barang = Barang::findOrFail($peminjaman->barang_id);

            // Restore barang stok
            $barang->stok += $peminjaman->jumlah;
            $barang->save();

            // Update peminjaman status dan tanggal dikembalikan
            $peminjaman->status = 'dikembalikan';
            $peminjaman->tanggal_dikembalikan = now();
            $peminjaman->save();

            DB::commit();

            return redirect()->route('pengurus.peminjaman.index')
                ->with('success', 'Barang berhasil dikembalikan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengembalikan barang.');
        }
    }

    /**
     * Check and update overdue items.
     * Method ini bisa dipanggil di method index() atau via Artisan Command.
     */
    public function checkAndUpdateOverdue()
    {
        // Cari semua peminjaman yang statusnya 'dipinjam' dan sudah melewati tanggal kembali
        $overduePeminjaman = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', now()->startOfDay())
            ->get();

        // Update statusnya menjadi 'terlambat'
        $count = $overduePeminjaman->count();
        if ($count > 0) {
            Peminjaman::whereIn('id', $overduePeminjaman->pluck('id'))
                ->update(['status' => 'terlambat']);
        }

        return $count; // Mengembalikan jumlah item yang terlambat
    }

    /**
     * Get barang details by ID.
     */
    public function getBarangDetails($id)
    {
        $barang = Barang::with('kategori')->findOrFail($id);

        return response()->json([
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
     * Get barang by kategori ID.
     */
    public function getBarangByKategori($kategoriId)
    {
        $barangs = Barang::where('kategori_id', $kategoriId)
            ->where('status', 'Aktif')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $barangs
        ]);
    }
}
