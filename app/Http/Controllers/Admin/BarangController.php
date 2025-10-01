<?php

namespace App\Http\Controllers\Admin;

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

        return view('admin.inventori.index', compact(
            'barangs',
            'kategoris',
            'stokHabis',
            'stokRendah',
            'stokAman'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.inventori.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi'   => 'nullable|string',
            'satuan'      => 'required|string|max:50',
            'stok'        => 'required|integer|min:0',
            'harga'       => 'required|numeric|min:0',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'kategori_id.required' => 'Kategori harus dipilih',
            'kategori_id.exists'   => 'Kategori tidak valid',
            'harga.min'            => 'Harga tidak boleh negatif'
        ]);

        try {
            DB::beginTransaction();

            // Generate kode barang unik
            $latestBarang = Barang::withTrashed()->latest('id')->first();
            $nextId = $latestBarang ? $latestBarang->id + 1 : 1;
            $kodeBarang = 'BRG-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

            // Handle upload gambar
            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $filename = time() . '_' . Str::slug($request->nama) . '.' . $gambar->getClientOriginalExtension();
                // Simpan ke storage/app/public/barang
                $path = $gambar->storeAs('barang', $filename, 'public');
                $validated['gambar'] = $filename;
            }

            $validated['kode_barang'] = $kodeBarang;
            $validated['status'] = 'aktif';

            $barang = Barang::create($validated);

            // Catat stok awal jika ada
            if ($validated['stok'] > 0) {
                BarangMasuk::create([
                    'barang_id'  => $barang->id,
                    'tanggal'    => now(),
                    'jumlah'     => $validated['stok'],
                    'keterangan' => 'Stok awal',
                    'user_id'    => auth()->id()
                ]);
            }

            DB::commit();

            return redirect()->route('admin.inventori.index')->with('success', 'Barang berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating barang: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        // Cek apakah barang sudah dihapus
        if ($barang->deleted_at) {
            return redirect()->route('admin.inventori.index')->with('error', 'Barang tidak ditemukan atau sudah diarsipkan');
        }

        // Ambil data transaksi terkait barang
        $barangMasuk = BarangMasuk::where('barang_id', $barang->id)->latest()->take(5)->get();
        $barangKeluar = BarangKeluar::where('barang_id', $barang->id)->latest()->take(5)->get();

        return view('admin.inventori.show', compact('barang', 'barangMasuk', 'barangKeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        // Cek apakah barang sudah dihapus
        if ($barang->deleted_at) {
            return redirect()->route('admin.inventori.index')->with('error', 'Barang tidak ditemukan atau sudah diarsipkan');
        }

        $kategoris = Kategori::all();
        return view('admin.inventori.edit', compact('barang', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        // Cek apakah barang sudah dihapus
        if ($barang->deleted_at) {
            return redirect()->route('admin.inventori.index')->with('error', 'Barang tidak ditemukan atau sudah diarsipkan');
        }

        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi'   => 'nullable|string',
            'satuan'      => 'required|string|max:50',
            'harga'       => 'required|numeric|min:0.01',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($barang->gambar && Storage::exists('public/barang/' . $barang->gambar)) {
                    Storage::delete('public/barang/' . $barang->gambar);
                }

                // Upload gambar baru
                $gambar = $request->file('gambar');
                $filename = time() . '_' . Str::slug($request->nama) . '.' . $gambar->getClientOriginalExtension();
                $gambar->storeAs('public/barang', $filename);
                $validated['gambar'] = $filename;
            }

            $barang->update($validated);

            return redirect()->route('admin.inventori.index')->with('success', 'Barang berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating barang: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        try {
            DB::beginTransaction();

            // Hapus gambar jika ada
            if ($barang->gambar && Storage::exists('public/barang/' . $barang->gambar)) {
                Storage::delete('public/barang/' . $barang->gambar);
            }

            // Gunakan soft delete
            $barang->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil diarsipkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting barang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengarsipkan barang: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display archived items.
     */
    public function archived()
    {
        $barangs = Barang::onlyTrashed()->with('kategori')->latest()->paginate(15);
        return view('admin.inventori.archived', compact('barangs'));
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        try {
            $barang = Barang::onlyTrashed()->findOrFail($id);
            $barang->restore();
            return redirect()->route('admin.inventori.archived')->with('success', 'Barang berhasil dipulihkan'); // Ubah dari admin.barang.archived
        } catch (\Exception $e) {
            Log::error('Error restoring barang: ' . $e->getMessage());
            return back()->with('error', 'Gagal memulihkan barang: ' . $e->getMessage());
        }
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();
            $barang = Barang::onlyTrashed()->findOrFail($id);
            // Hapus semua riwayat transaksi
            BarangMasuk::where('barang_id', $barang->id)->delete();
            BarangKeluar::where('barang_id', $barang->id)->delete();
            // Hapus gambar jika ada
            if ($barang->gambar && Storage::exists('public/barang/' . $barang->gambar)) {
                Storage::delete('public/barang/' . $barang->gambar);
            }
            // Hapus permanen
            $barang->forceDelete();
            DB::commit();
            return redirect()->route('admin.inventori.archived')->with('success', 'Barang berhasil dihapus permanen'); // Ubah dari admin.barang.archived
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error force deleting barang: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }
}
