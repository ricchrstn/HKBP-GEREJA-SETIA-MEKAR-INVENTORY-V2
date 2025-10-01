<?php
// app/Http/Controllers/Pengurus/AuditController.php
namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\Barang;
use App\Models\JadwalAudit; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        // Ambil audit mandiri yang dibuat oleh pengurus
        $queryMandiri = Audit::with(['barang', 'user'])->where('user_id', auth()->id());

        // Filter berdasarkan pencarian untuk audit mandiri
        if ($request->filled('search')) {
            $queryMandiri->where('keterangan', 'like', '%' . $request->search . '%')
                ->orWhereHas('barang', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%');
                });
        }

        // Filter berdasarkan kondisi untuk audit mandiri
        if ($request->filled('kondisi')) {
            $queryMandiri->where('kondisi', $request->kondisi);
        }

        // Filter berdasarkan tanggal untuk audit mandiri
        if ($request->filled('tanggal')) {
            $queryMandiri->whereDate('tanggal_audit', $request->tanggal);
        }

        $auditsMandiri = $queryMandiri->latest()->paginate(10, ['*'], 'mandiri_page');

        // Ambil jadwal audit dari admin untuk pengurus yang sedang login
        $queryJadwal = JadwalAudit::with(['barang', 'user'])
            ->where('user_id', auth()->id())
            ->whereIn('status', ['terjadwal', 'diproses']); // Hanya yang status terjadwal atau diproses

        // Filter berdasarkan pencarian untuk jadwal audit
        if ($request->filled('search')) {
            $queryJadwal->where('judul', 'like', '%' . $request->search . '%')
                ->orWhereHas('barang', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%');
                });
        }

        // Filter berdasarkan tanggal untuk jadwal audit
        if ($request->filled('tanggal')) {
            $queryJadwal->whereDate('tanggal_audit', $request->tanggal);
        }

        $jadwalAudits = $queryJadwal->latest()->paginate(10, ['*'], 'jadwal_page');

        return view('pengurus.audit.index', compact('auditsMandiri', 'jadwalAudits'));
    }
    public function create()
    {
        $barangs = Barang::all();
        $categories = \App\Models\Kategori::all(); // Tambahkan ini
        return view('pengurus.audit.create', compact('barangs', 'categories'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'tanggal_audit' => 'required|date',
            'kondisi' => 'required|in:baik,rusak,hilang,tidak_terpakai',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $data = $request->except('foto');
        $data['user_id'] = auth()->id();
        $data['status'] = 'selesai';
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('audit_foto', 'public');
            $data['foto'] = $path;
        }
        Audit::create($data);
        return redirect()->route('pengurus.audit.index')
            ->with('success', 'Audit barang berhasil ditambahkan');
    }

    public function selesaikanJadwal(Request $request, JadwalAudit $jadwalAudit)
    {
        // Pastikan hanya pengurus yang ditugaskan yang bisa menyelesaikan
        if ($jadwalAudit->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menyelesaikan jadwal audit ini'
            ], 403);
        }

        // Validasi input
        $validated = $request->validate([
            'kondisi' => 'required|in:baik,rusak,hilang,tidak_terpakai',
            'keterangan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update status jadwal audit
        $jadwalAudit->status = 'selesai';
        $jadwalAudit->save();

        // Buat audit mandiri berdasarkan jadwal audit
        $data = [
            'barang_id' => $jadwalAudit->barang_id,
            'user_id' => auth()->id(),
            'tanggal_audit' => $jadwalAudit->tanggal_audit,
            'kondisi' => $validated['kondisi'],
            'keterangan' => $validated['keterangan'],
            'status' => 'selesai',
        ];

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('audit_foto', 'public');
            $data['foto'] = $path;
        }

        Audit::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal audit berhasil diselesaikan'
        ]);
    }

    public function show(Audit $audit)
    {
        // Pastikan hanya pemilik audit yang bisa melihat
        if ($audit->user_id !== auth()->id()) {
            abort(403);
        }
        return view('pengurus.audit.show', compact('audit'));
    }

    public function showJadwal(JadwalAudit $jadwalAudit)
    {
        // Pastikan hanya pengurus yang ditugaskan yang bisa melihat
        if ($jadwalAudit->user_id !== auth()->id()) {
            abort(403);
        }

        return view('pengurus.audit.show-jadwal', compact('jadwalAudit'));
    }

    public function edit(Audit $audit)
    {
        // Pastikan hanya pemilik audit yang bisa mengedit
        if ($audit->user_id !== auth()->id()) {
            abort(403);
        }
        $barangs = Barang::all();
        $categories = \App\Models\Kategori::all(); // Tambahkan ini
        return view('pengurus.audit.edit', compact('audit', 'barangs', 'categories'));
    }
    public function update(Request $request, Audit $audit)
    {
        // Pastikan hanya pemilik audit yang bisa mengupdate
        if ($audit->user_id !== auth()->id()) {
            abort(403);
        }
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'tanggal_audit' => 'required|date',
            'kondisi' => 'required|in:baik,rusak,hilang,tidak_terpakai',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $data = $request->except('foto');
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($audit->foto) {
                Storage::disk('public')->delete($audit->foto);
            }
            $path = $request->file('foto')->store('audit_foto', 'public');
            $data['foto'] = $path;
        }
        $audit->update($data);
        return redirect()->route('pengurus.audit.index')
            ->with('success', 'Audit barang berhasil diperbarui');
    }
    public function destroy(Audit $audit)
    {
        // Pastikan hanya pemilik audit yang bisa menghapus
        if ($audit->user_id !== auth()->id()) {
            abort(403);
        }
        // Hapus foto jika ada
        if ($audit->foto) {
            Storage::disk('public')->delete($audit->foto);
        }
        $audit->delete();
        return redirect()->route('pengurus.audit.index')
            ->with('success', 'Audit barang berhasil dihapus');
    }
}
