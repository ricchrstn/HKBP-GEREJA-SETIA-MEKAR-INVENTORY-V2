<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class VerifikasiPengadaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with('user')->whereIn('status', ['pending', 'disetujui', 'ditolak', 'proses']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_pengajuan', 'like', '%' . $request->search . '%')
                  ->orWhere('alasan', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $pengajuans = $query->latest()->paginate(10);

        return view('bendahara.verifikasi.index', compact('pengajuans'));
    }

    public function show(Pengajuan $pengajuan)
    {
        return view('bendahara.verifikasi.show', compact('pengajuan'));
    }

    public function verifikasi(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak,proses',
            'keterangan' => 'nullable|string|max:1000'
        ]);

        $pengajuan->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('bendahara.verifikasi.index')
                         ->with('success', 'Pengajuan berhasil diverifikasi');
    }
}
