<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Query dengan eager loading dan filter hanya yang memiliki barang valid
        $query = BarangMasuk::with(['barang.kategori', 'user'])
            ->whereHas('barang'); // Pastikan hanya data yang memiliki barang valid

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('barang', function ($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('kode_barang', 'like', "%{$search}%");
                })
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $barangMasuks = $query->latest()->paginate(15)->withQueryString();

        return view('pengurus.barang.masuk.index', compact('barangMasuks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama', 'asc')->get();
        // Tidak perlu mengirim semua barang, karena akan diambil via AJAX
        return view('pengurus.barang.masuk.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'barang_id'  => 'required|exists:barang,id',
            'tanggal'    => 'required|date',
            'jumlah'     => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255'
        ], [
            'kategori_id.required' => 'Kategori harus dipilih',
            'barang_id.required' => 'Barang harus dipilih',
            'jumlah.min'        => 'Jumlah harus minimal 1'
        ]);

        try {
            DB::beginTransaction();

            // Tambahkan user_id dari yang sedang login
            $validated['user_id'] = auth()->id();

            // Format tanggal jika diperlukan
            if (is_string($validated['tanggal'])) {
                $validated['tanggal'] = Carbon::parse($validated['tanggal'])->format('Y-m-d');
            }

            // Simpan data barang masuk
            $barangMasuk = BarangMasuk::create($validated);

            // Update stok barang
            $barang = Barang::find($validated['barang_id']);
            $barang->stok += $validated['jumlah'];
            $barang->save();

            DB::commit();

            return redirect()->route('pengurus.barang.masuk')
                ->with('success', 'Barang masuk berhasil dicatat');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating barang masuk: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load(['barang.kategori', 'user']);
        return view('pengurus.barang.masuk.show', compact('barangMasuk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangMasuk $barangMasuk)
    {
        $kategoris = Kategori::orderBy('nama', 'asc')->get();
        // Ambil semua barang aktif untuk dropdown
        $barangs = Barang::where('status', 'aktif')->orderBy('nama', 'asc')->get();

        return view('pengurus.barang.masuk.edit', compact('barangMasuk', 'kategoris', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'barang_id'  => 'required|exists:barang,id',
            'tanggal'    => 'required|date',
            'jumlah'     => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            // Hitung selisih jumlah untuk update stok
            $selisih = $validated['jumlah'] - $barangMasuk->jumlah;

            // Format tanggal jika diperlukan
            if (is_string($validated['tanggal'])) {
                $validated['tanggal'] = Carbon::parse($validated['tanggal'])->format('Y-m-d');
            }

            // Update data barang masuk
            $barangMasuk->update($validated);

            // Update stok barang hanya jika barang_id tidak berubah
            if ($barangMasuk->barang_id == $validated['barang_id']) {
                $barang = Barang::find($validated['barang_id']);
                $barang->stok += $selisih;
                $barang->save();
            } else {
                // Jika barang berubah, kembalikan stok lama dan tambahkan ke stok baru
                $barangLama = Barang::find($barangMasuk->barang_id);
                $barangLama->stok -= $barangMasuk->jumlah;
                $barangLama->save();

                $barangBaru = Barang::find($validated['barang_id']);
                $barangBaru->stok += $validated['jumlah'];
                $barangBaru->save();
            }

            DB::commit();

            return redirect()->route('pengurus.barang.masuk')
                ->with('success', 'Data barang masuk berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating barang masuk: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangMasuk $barangMasuk)
    {
        try {
            DB::beginTransaction();

            // Kurangi stok barang
            $barang = Barang::find($barangMasuk->barang_id);

            // Pastikan stok tidak negatif
            if ($barang->stok < $barangMasuk->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus karena stok barang tidak mencukupi'
                ], 400);
            }

            $barang->stok -= $barangMasuk->jumlah;
            $barang->save();

            // Hapus data barang masuk
            $barangMasuk->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data barang masuk berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting barang masuk: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get barang details for AJAX request
     */
    public function getBarangDetails($id)
    {
        try {
            $barang = Barang::with('kategori')->find($id);

            if (!$barang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barang tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $barang->id,
                    'nama' => $barang->nama,
                    'kode_barang' => $barang->kode_barang,
                    'stok' => $barang->stok,
                    'satuan' => $barang->satuan,
                    'harga' => $barang->harga,
                    'kategori' => $barang->kategori->nama ?? '-'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting barang details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get barang by kategori for AJAX request
     */
    public function getBarangByKategori($kategoriId)
    {
        try {
            $barangs = Barang::with('kategori')
                ->where('kategori_id', $kategoriId)
                ->where('status', 'aktif')
                ->orderBy('nama', 'asc')
                ->get()
                ->map(function ($barang) {
                    return [
                        'id' => $barang->id,
                        'nama' => $barang->nama,
                        'kode_barang' => $barang->kode_barang,
                        'stok' => $barang->stok,
                        'satuan' => $barang->satuan,
                        'harga' => $barang->harga,
                        'gambar' => $barang->gambar,
                        'kategori_nama' => $barang->kategori->nama ?? '-'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $barangs
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting barang by kategori: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
