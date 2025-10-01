<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        // Statistik
        $totalAdmin = User::where('role', 'admin')->count();
        $totalPengurus = User::where('role', 'pengurus')->count();
        $totalBendahara = User::where('role', 'bendahara')->count();

        return view('admin.pengguna.index', compact(
            'users',
            'totalAdmin',
            'totalPengurus',
            'totalBendahara'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pengguna.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,pengurus,bendahara'
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'email.unique' => 'Email sudah terdaftar'
        ]);

        try {
            DB::beginTransaction();

            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);

            DB::commit();

            return redirect()->route('admin.users.index')
                           ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.pengguna.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,pengurus,bendahara',
            'password' => 'nullable|string|min:8|confirmed'
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'email.unique' => 'Email sudah terdaftar'
        ]);

        try {
            DB::beginTransaction();

            // Update password jika diisi
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            DB::commit();

            return redirect()->route('admin.users.index')
                           ->with('success', 'User berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Cek apakah user yang sedang login
            if (auth()->user()->id === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun yang sedang digunakan'
                ], 422);
            }

            // Cek apakah user memiliki transaksi
            if ($user->barangMasuk()->exists() || $user->barangKeluar()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak dapat dihapus karena memiliki riwayat transaksi'
                ], 422);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset password user
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);

        try {
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset'
            ]);
        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset password: ' . $e->getMessage()
            ], 500);
        }
    }
}
