<?php

namespace App\Http\Controllers\Auth; // Mendefinisikan namespace untuk controller ini. Ini membantu mengorganisir kode.

use App\Http\Controllers\Controller; // Mengimpor kelas Controller dasar dari Laravel. Controller ini akan mewarisi fungsionalitas dasar dari sana.
use Illuminate\Http\Request; // Mengimpor kelas Request, yang digunakan untuk mengakses data dari permintaan HTTP.
use Illuminate\Support\Facades\Auth; // Mengimpor facade Auth, yang menyediakan metode untuk otentikasi pengguna.
use Illuminate\Support\Facades\Hash; // Mengimpor facade Hash, yang digunakan untuk meng-hash password (meskipun tidak digunakan langsung di sini, tapi penting untuk otentikasi).
use App\Models\User; // Mengimpor model User, yang merepresentasikan tabel pengguna di database.

class LoginController extends Controller // Mendefinisikan kelas LoginController yang mewarisi dari Controller dasar.
{
    // Metode ini menampilkan formulir login
    public function showLoginForm()
    {
        if (Auth::check()) { // Memeriksa apakah pengguna sudah login.
            return $this->redirectToDashboard(); // Jika sudah login, langsung arahkan ke dashboard yang sesuai.
        }
        
        return view('auth.login'); // Jika belum login, tampilkan view (tampilan) formulir login yang berada di 'resources/views/auth/login.blade.php'.
    }
    
    // Metode ini menangani proses login ketika formulir disubmit
    public function login(Request $request)
    {
        // Validasi data input dari formulir login
        $request->validate([
            'email' => 'required|email', // Bidang 'email' wajib diisi dan harus berformat email yang valid.
            'password' => 'required|min:6', // Bidang 'password' wajib diisi dan minimal 6 karakter.
        ], [
            // Pesan error kustom untuk validasi
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);
        
        // Mengambil kredensial (email dan password) dari permintaan.
        $credentials = $request->only('email', 'password');
        // Memeriksa apakah checkbox 'remember me' dicentang oleh pengguna.
        $remember = $request->has('remember');
        
        // Mencoba untuk mengotentikasi pengguna menggunakan kredensial yang diberikan.
        // Auth::attempt akan mencari pengguna dengan email yang cocok dan memverifikasi password yang di-hash.
        if (Auth::attempt($credentials, $remember)) {
            // Jika otentikasi berhasil:
            $request->session()->regenerate(); // Regenerasi ID sesi untuk mencegah serangan fiksasi sesi.
            
            return $this->redirectToDashboard(); // Arahkan pengguna ke dashboard yang sesuai dengan perannya.
        }
        
        // Jika otentikasi gagal:
        return back()->withErrors([ // Kembali ke halaman sebelumnya (formulir login) dengan pesan error.
            'email' => 'Email atau password salah.', // Pesan error untuk bidang email.
        ])->withInput($request->except('password')); // Mengisi ulang formulir dengan input sebelumnya, kecuali password (demi keamanan).
    }
    
    // Metode ini menangani proses logout pengguna
    public function logout(Request $request)
    {
        Auth::logout(); // Log out pengguna saat ini dari aplikasi.
        
        $request->session()->invalidate(); // Menghapus semua data dari sesi pengguna.
        $request->session()->regenerateToken(); // Meregenerasi token CSRF untuk keamanan.
        
        return redirect('/login')->with('success', 'Anda telah berhasil logout'); // Arahkan kembali ke halaman login dengan pesan sukses.
    }
    
    // Metode pribadi (helper) untuk mengarahkan pengguna ke dashboard yang sesuai berdasarkan peran mereka
    private function redirectToDashboard()
    {
        $user = Auth::user(); // Mendapatkan objek pengguna yang sedang login.
        
        switch ($user->role) { // Menggunakan pernyataan switch untuk mengarahkan berdasarkan peran pengguna.
            case 'admin': // Jika peran pengguna adalah 'admin'...
                return redirect()->route('admin.dashboard'); // Arahkan ke route bernama 'admin.dashboard'.
            case 'pengurus': // Jika peran pengguna adalah 'pengurus'...
                return redirect()->route('pengurus.dashboard'); // Arahkan ke route bernama 'pengurus.dashboard'.
            case 'bendahara': // Jika peran pengguna adalah 'bendahara'...
                return redirect()->route('bendahara.dashboard'); // Arahkan ke route bernama 'bendahara.dashboard'.
            default: // Jika peran pengguna tidak cocok dengan kasus di atas (misalnya, peran baru yang belum ditangani)...
                return redirect()->route('pengurus.dashboard'); // Arahkan ke dashboard pengurus sebagai default (atau bisa juga ke halaman lain, tergantung kebijakan).
        }
    }
}