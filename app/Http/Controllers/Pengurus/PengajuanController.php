<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with('user')->where('user_id', auth()->id());

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

        return view('pengurus.pengajuan.index', compact('pengajuans'));
    }

    public function create()
    {
        return view('pengurus.pengajuan.create');
    }

    // app/Http/Controllers/Pengurus/PengajuanController.php
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'alasan' => 'required|string',
            'kebutuhan' => 'required|date|after_or_equal:today',
            'file_pengajuan' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            // Tambahkan validasi untuk kriteria TOPSIS
            'urgensi' => 'required|integer|min:1|max:10',
            'ketersediaan_stok' => 'required|integer|in:2,4,6,8,10',
            'ketersediaan_dana' => 'required|integer|in:2,4,6,8,10',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['kode_pengajuan'] = Pengajuan::generateKode();
        $data['status'] = 'pending';

        if ($request->hasFile('file_pengajuan')) {
            $path = $request->file('file_pengajuan')->store('pengajuan_files', 'public');
            $data['file_pengajuan'] = $path;
        }

        Pengajuan::create($data);

        return redirect()->route('pengurus.pengajuan.index')
            ->with('success', 'Pengajuan pengadaan berhasil ditambahkan');
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        // Pastikan hanya pemilik pengajuan yang bisa mengupdate dan status masih pending
        if ($pengajuan->user_id !== auth()->id() || $pengajuan->status !== 'pending') {
            abort(403);
        }

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'alasan' => 'required|string',
            'kebutuhan' => 'required|date|after_or_equal:today',
            'file_pengajuan' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            // Tambahkan validasi untuk kriteria TOPSIS
            'urgensi' => 'required|integer|min:1|max:10',
            'ketersediaan_stok' => 'required|integer|in:2,4,6,8,10',
            'ketersediaan_dana' => 'required|integer|in:2,4,6,8,10',
        ]);

        $data = $request->except('file_pengajuan');

        if ($request->hasFile('file_pengajuan')) {
            // Hapus file lama jika ada
            if ($pengajuan->file_pengajuan) {
                Storage::disk('public')->delete($pengajuan->file_pengajuan);
            }
            $path = $request->file('file_pengajuan')->store('pengajuan_files', 'public');
            $data['file_pengajuan'] = $path;
        }

        $pengajuan->update($data);

        return redirect()->route('pengurus.pengajuan.index')
            ->with('success', 'Pengajuan pengadaan berhasil diperbarui');
    }

    public function show(Pengajuan $pengajuan)
    {
        // Pastikan hanya pemilik pengajuan yang bisa melihat
        if ($pengajuan->user_id !== auth()->id()) {
            abort(403);
        }

        return view('pengurus.pengajuan.show', compact('pengajuan'));
    }

    public function edit(Pengajuan $pengajuan)
    {
        // Pastikan hanya pemilik pengajuan yang bisa mengedit dan status masih pending
        if ($pengajuan->user_id !== auth()->id() || $pengajuan->status !== 'pending') {
            abort(403);
        }

        return view('pengurus.pengajuan.edit', compact('pengajuan'));
    }

    public function destroy(Pengajuan $pengajuan)
    {
        // Pastikan hanya pemilik pengajuan yang bisa menghapus dan status masih pending
        if ($pengajuan->user_id !== auth()->id() || $pengajuan->status !== 'pending') {
            abort(403);
        }

        // Hapus file jika ada
        if ($pengajuan->file_pengajuan) {
            Storage::disk('public')->delete($pengajuan->file_pengajuan);
        }

        $pengajuan->delete();

        return redirect()->route('pengurus.pengajuan.index')
            ->with('success', 'Pengajuan pengadaan berhasil dihapus');
    }
}
