<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kas::with('user')->latest();

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', '%' . $search . '%')
                    ->orWhere('keterangan', 'like', '%' . $search . '%')
                    ->orWhere('sumber', 'like', '%' . $search . '%')
                    ->orWhere('tujuan', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter berdasarkan bulan dan tahun
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $kas = $query->paginate(10)->withQueryString();

        // Hitung total pemasukan dan pengeluaran
        $totalMasuk = Kas::masuk()->sum('jumlah');
        $totalKeluar = Kas::keluar()->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        // Data untuk grafik
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $pemasukanBulanan = Kas::masuk()
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('jumlah');

        $pengeluaranBulanan = Kas::keluar()
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('jumlah');

        return view('bendahara.kas.index', compact(
            'kas',
            'totalMasuk',
            'totalKeluar',
            'saldo',
            'pemasukanBulanan',
            'pengeluaranBulanan'
        ));
    }

    public function create()
    {

        return view('bendahara.kas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:masuk,keluar',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'sumber' => 'required_if:jenis,masuk|nullable|string|max:255',
            'tujuan' => 'required_if:jenis,keluar|nullable|string|max:255',
            'bukti_transaksi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'jenis.required' => 'Jenis transaksi harus dipilih',
            'jumlah.required' => 'Jumlah transaksi harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah tidak boleh negatif',
            'tanggal.required' => 'Tanggal transaksi harus diisi',
            'keterangan.required' => 'Keterangan transaksi harus diisi',
            'sumber.required_if' => 'Sumber pemasukan harus diisi',
            'tujuan.required_if' => 'Tujuan pengeluaran harus diisi',
            'bukti_transaksi.mimes' => 'Format file bukti transaksi tidak valid',
            'bukti_transaksi.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['jenis', 'jumlah', 'tanggal', 'keterangan', 'sumber', 'tujuan']);
            $data['user_id'] = auth()->id();
            $data['kode_transaksi'] = Kas::generateKode($request->jenis);

            if ($request->hasFile('bukti_transaksi')) {
                $path = $request->file('bukti_transaksi')->store('bukti_transaksi', 'public');
                $data['bukti_transaksi'] = $path;
            }

            Kas::create($data);

            DB::commit();

            return redirect()->route('bendahara.kas.index')
                ->with('success', 'Transaksi kas berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $kas = Kas::with('user')->findOrFail($id);
        return view('bendahara.kas.show', compact('kas'));
    }

    public function edit($id)
    {
        $kas = Kas::findOrFail($id);
        return view('bendahara.kas.edit', compact('kas'));
    }

    public function update(Request $request, $id)
    {
        $kas = Kas::findOrFail($id);

        $request->validate([
            'jenis' => 'required|in:masuk,keluar',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'sumber' => 'required_if:jenis,masuk|nullable|string|max:255',
            'tujuan' => 'required_if:jenis,keluar|nullable|string|max:255',
            'bukti_transaksi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'jenis.required' => 'Jenis transaksi harus dipilih',
            'jumlah.required' => 'Jumlah transaksi harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah tidak boleh negatif',
            'tanggal.required' => 'Tanggal transaksi harus diisi',
            'keterangan.required' => 'Keterangan transaksi harus diisi',
            'sumber.required_if' => 'Sumber pemasukan harus diisi',
            'tujuan.required_if' => 'Tujuan pengeluaran harus diisi',
            'bukti_transaksi.mimes' => 'Format file bukti transaksi tidak valid',
            'bukti_transaksi.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['jenis', 'jumlah', 'tanggal', 'keterangan', 'sumber', 'tujuan']);

            // Jika jenis berubah, generate kode baru
            if ($kas->jenis != $request->jenis) {
                $data['kode_transaksi'] = Kas::generateKode($request->jenis);
            }

            if ($request->hasFile('bukti_transaksi')) {
                // Hapus file lama jika ada
                if ($kas->bukti_transaksi) {
                    Storage::disk('public')->delete($kas->bukti_transaksi);
                }
                $path = $request->file('bukti_transaksi')->store('bukti_transaksi', 'public');
                $data['bukti_transaksi'] = $path;
            }

            $kas->update($data);

            DB::commit();

            return redirect()->route('bendahara.kas.index')
                ->with('success', 'Transaksi kas berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $kas = Kas::findOrFail($id);

            // Hapus file bukti transaksi jika ada
            if ($kas->bukti_transaksi) {
                Storage::disk('public')->delete($kas->bukti_transaksi);
            }

            $kas->forceDelete();

            return redirect()->route('bendahara.kas.index')
                ->with('success', 'Transaksi kas berhasil dihapus');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error deleting kas: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function laporan(Request $request)
    {
        $query = Kas::query();

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $transaksi = $query->orderBy('tanggal', 'asc')->get();

        $totalMasuk = $transaksi->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = $transaksi->where('jenis', 'keluar')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        return view('bendahara.kas.laporan', compact(
            'transaksi',
            'totalMasuk',
            'totalKeluar',
            'saldo'
        ));
    }
}
