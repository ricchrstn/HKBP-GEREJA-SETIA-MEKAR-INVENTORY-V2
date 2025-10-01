<?php
namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Perawatan;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PerawatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Perawatan::with(['barang.kategori', 'user'])
            ->whereHas('barang');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('barang', function ($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%")
                          ->orWhere('kode_barang', 'like', "%{$search}%");
                })
                ->orWhere('jenis_perawatan', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_perawatan', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_perawatan', '<=', $request->tanggal_selesai);
        }

        $perawatans = $query->latest()->paginate(15)->withQueryString();

        return view('pengurus.perawatan.index', compact('perawatans'));
    }

/**
 * Show the form for creating a new resource.
 */
public function create()
{
    $kategoris = Kategori::orderBy('nama')->get();
    $barangs = Barang::where('status', 'aktif')->get();
    return view('pengurus.perawatan.create', compact('kategoris', 'barangs'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id'         => 'required|exists:barang,id',
            'tanggal_perawatan' => 'required|date',
            'jenis_perawatan'   => 'required|string|max:255',
            'biaya'             => 'nullable|numeric|min:0',
            'keterangan'        => 'nullable|string|max:255'
        ], [
            'barang_id.required'         => 'Barang harus dipilih',
            'tanggal_perawatan.required' => 'Tanggal perawatan harus diisi',
            'jenis_perawatan.required'   => 'Jenis perawatan harus diisi',
            'biaya.numeric'                => 'Biaya harus berupa angka',
            'biaya.min'                   => 'Biaya tidak boleh negatif'
        ]);

        try {
            DB::beginTransaction();

            // Tambahkan user_id dari yang sedang login
            $validated['user_id'] = auth()->id();
            $validated['status'] = 'proses';

            // Format tanggal jika diperlukan
            if (is_string($validated['tanggal_perawatan'])) {
                $validated['tanggal_perawatan'] = Carbon::parse($validated['tanggal_perawatan'])->format('Y-m-d');
            }

            // Pastikan biaya dan keterangan tidak null
            $validated['biaya'] = $validated['biaya'] ?? 0;
            $validated['keterangan'] = $validated['keterangan'] ?? '';

            // Simpan data perawatan
            $perawatan = Perawatan::create($validated);

            // Update status barang menjadi perawatan jika masih aktif
            $barang = Barang::find($validated['barang_id']);
            if ($barang->status === 'aktif') {
                $barang->status = 'perawatan';
                $barang->save();
            }

            DB::commit();

            return redirect()->route('pengurus.perawatan.index')
                ->with('success', 'Data perawatan berhasil dicatat');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating perawatan: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

        /**
     * Get barang by kategori ID.
     */
    public function getBarangByKategori($kategoriId)
    {
        try {
            $barangs = Barang::where('kategori_id', $kategoriId)
                ->where('status', 'aktif') // Hanya barang yang aktif
                ->orderBy('nama')
                ->get();

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

    /**
     * Display the specified resource.
     */
    public function show(Perawatan $perawatan)
    {
        $perawatan->load(['barang.kategori', 'user']);
        return view('pengurus.perawatan.show', compact('perawatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perawatan $perawatan)
    {
        $barangs = Barang::where('status', 'aktif')->get();
        return view('pengurus.perawatan.edit', compact('perawatan', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perawatan $perawatan)
    {
        $validated = $request->validate([
            'barang_id'         => 'required|exists:barang,id',
            'tanggal_perawatan' => 'required|date',
            'jenis_perawatan'   => 'required|string|max:255',
            'biaya'             => 'nullable|numeric|min:0',
            'keterangan'        => 'nullable|string|max:255',
            'status'            => 'required|in:proses,selesai,dibatalkan'
        ], [
            'barang_id.required'         => 'Barang harus dipilih',
            'tanggal_perawatan.required' => 'Tanggal perawatan harus diisi',
            'jenis_perawatan.required'   => 'Jenis perawatan harus diisi',
            'biaya.numeric'                => 'Biaya harus berupa angka',
            'biaya.min'                   => 'Biaya tidak boleh negatif'
        ]);

        try {
            DB::beginTransaction();

            // Format tanggal jika diperlukan
            if (is_string($validated['tanggal_perawatan'])) {
                $validated['tanggal_perawatan'] = Carbon::parse($validated['tanggal_perawatan'])->format('Y-m-d');
            }

            // Pastikan biaya dan keterangan tidak null
            $validated['biaya'] = $validated['biaya'] ?? 0;
            $validated['keterangan'] = $validated['keterangan'] ?? '';

            // Cek perubahan status
            $statusChanged = $perawatan->status !== $validated['status'];

            // Update data perawatan
            $perawatan->update($validated);

            // Update status barang jika status perawatan berubah
            if ($statusChanged) {
                $barang = Barang::find($validated['barang_id']);

                if ($validated['status'] === 'selesai') {
                    // Jika perawatan selesai, kembalikan status barang ke aktif
                    $barang->status = 'aktif';
                } elseif ($validated['status'] === 'dibatalkan') {
                    // Jika perawatan dibatalkan, kembalikan status barang ke aktif
                    $barang->status = 'aktif';
                } else {
                    // Jika status proses, set status barang menjadi perawatan
                    $barang->status = 'perawatan';
                }

                $barang->save();
            }

            DB::commit();

            return redirect()->route('pengurus.perawatan.index')
                ->with('success', 'Data perawatan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating perawatan: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perawatan $perawatan)
    {
        try {
            DB::beginTransaction();

            // Cek status perawatan
            if ($perawatan->status === 'proses') {
                // Jika masih dalam proses, kembalikan status barang ke aktif
                $barang = Barang::find($perawatan->barang_id);
                if ($barang->status === 'perawatan') {
                    $barang->status = 'aktif';
                    $barang->save();
                }
            }

            // Hapus data perawatan
            $perawatan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data perawatan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting perawatan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status perawatan menjadi selesai
     */
    public function selesaikan(Request $request, Perawatan $perawatan)
    {
        try {
            DB::beginTransaction();

            // Update status perawatan
            $perawatan->status = 'selesai';
            $perawatan->save();

            // Update status barang menjadi aktif
            $barang = Barang::find($perawatan->barang_id);
            if ($barang->status === 'perawatan') {
                $barang->status = 'aktif';
                $barang->save();
            }

            DB::commit();

            return redirect()->route('pengurus.perawatan.index')
                ->with('success', 'Perawatan barang telah selesai');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error completing perawatan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
                    'kategori' => $barang->kategori->nama ?? '-',
                    'status' => $barang->status
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
}
