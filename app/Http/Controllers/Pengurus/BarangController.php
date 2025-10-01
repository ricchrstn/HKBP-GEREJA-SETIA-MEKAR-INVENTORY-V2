<?php
namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Barang::with('kategori')->whereNull('deleted_at');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode_barang', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter status stok
        if ($request->filled('stok_status')) {
            switch ($request->stok_status) {
                case 'habis':
                    $query->where('stok', 0);
                    break;
                case 'rendah':
                    $query->where('stok', '>', 0)->where('stok', '<=', 5);
                    break;
                case 'aman':
                    $query->where('stok', '>', 5);
                    break;
            }
        }

        $barangs = $query->latest()->paginate(15)->withQueryString();

        // Statistik
        $stokHabis = Barang::whereNull('deleted_at')->where('stok', 0)->count();
        $stokRendah = Barang::whereNull('deleted_at')->where('stok', '>', 0)->where('stok', '<=', 5)->count();
        $stokAman = Barang::whereNull('deleted_at')->where('stok', '>', 5)->count();

        // Data kategori untuk filter
        $kategoris = Kategori::orderBy('nama')->get();

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
    public function createMasuk()
    {
        // Hanya tampilkan barang yang belum dihapus
        $barangs = Barang::whereNull('deleted_at')->get();
        return view('pengurus.barang.masuk.create', compact('barangs'));
    }

    /**
     * Show form for barang keluar
     */
    public function createKeluar()
    {
        // Hanya tampilkan barang yang belum dihapus dan stok > 0
        $barangs = Barang::whereNull('deleted_at')->where('stok', '>', 0)->get();
        return view('pengurus.barang.keluar.create', compact('barangs'));
    }

    /**
     * Process barang masuk
     */
    public function barangMasuk(Request $request, Barang $barang)
    {
        // Cek apakah barang sudah dihapus
        if ($barang->deleted_at) {
            return back()->with('error', 'Barang tidak ditemukan atau sudah diarsipkan');
        }

        $validated = $request->validate([
            'jumlah'     => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Update stok
            $barang->increment('stok', $validated['jumlah']);

            // Catat transaksi
            BarangMasuk::create([
                'barang_id'  => $barang->id,
                'tanggal'    => now(),
                'jumlah'     => $validated['jumlah'],
                'keterangan' => $validated['keterangan'] ?? 'Barang masuk',
                'user_id'    => auth()->id()
            ]);

            DB::commit();

            return back()->with('success', 'Barang masuk berhasil dicatat');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error recording barang masuk: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process barang keluar
     */
    public function barangKeluar(Request $request, Barang $barang)
    {
        // Cek apakah barang sudah dihapus
        if ($barang->deleted_at) {
            return back()->with('error', 'Barang tidak ditemukan atau sudah diarsipkan');
        }

        $validated = $request->validate([
            'jumlah'     => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cek stok
        if ($validated['jumlah'] > $barang->stok) {
            return back()->with('error', 'Stok barang tidak mencukupi untuk dikeluarkan');
        }

        try {
            DB::beginTransaction();

            // Update stok
            $barang->decrement('stok', $validated['jumlah']);

            // Catat transaksi
            BarangKeluar::create([
                'barang_id'  => $barang->id,
                'tanggal'    => now(),
                'jumlah'     => $validated['jumlah'],
                'keterangan' => $validated['keterangan'] ?? 'Barang keluar',
                'user_id'    => auth()->id()
            ]);

            DB::commit();

            return back()->with('success', 'Barang keluar berhasil dicatat');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error recording barang keluar: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
