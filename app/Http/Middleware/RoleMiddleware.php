<?php

namespace App\Http\Middleware; // Mendefinisikan namespace untuk file ini. Ini membantu mengorganisir kode dan menghindari konflik nama.

use Closure; // Mengimpor kelas Closure, yang digunakan untuk merepresentasikan fungsi anonim (callback).
use Illuminate\Http\Request; // Mengimpor kelas Request dari Laravel, yang merepresentasikan permintaan HTTP masuk.
use Illuminate\Support\Facades\Auth; // Mengimpor facade Auth dari Laravel, yang menyediakan cara mudah untuk berinteraksi dengan sistem otentikasi.

class RoleMiddleware // Mendefinisikan kelas Middleware yang bertanggung jawab untuk memeriksa peran pengguna.
{
    /**
     * Handle an incoming request. // Ini adalah komentar DocBlock yang menjelaskan tujuan metode 'handle'.
     *
     * @param  \Illuminate\Http\Request  $request // Parameter $request adalah instance dari Illuminate\Http\Request, merepresentasikan permintaan saat ini.
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next // Parameter $next adalah Closure yang akan menjalankan permintaan ke middleware berikutnya atau ke controller.
     * @param  string  $role // Parameter $role adalah string yang akan diterima dari definisi route, contohnya 'admin' atau 'pengurus'.
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse // Metode ini bisa mengembalikan Response atau RedirectResponse.
     */
    public function handle(Request $request, Closure $next, $role) // Metode 'handle' adalah inti dari setiap middleware.
    {
        if (!Auth::check()) { // Memeriksa apakah pengguna saat ini belum login (tidak terotentikasi).
            return redirect('/login'); // Jika belum login, arahkan pengguna ke halaman login.
        }

        $user = Auth::user(); // Mendapatkan objek pengguna yang sedang login.

        if ($user->role !== $role) { // Memeriksa apakah peran (role) pengguna yang sedang login TIDAK SAMA dengan peran yang diharapkan oleh middleware.
            // Redirect to appropriate dashboard based on user role // Komentar: Arahkan ke dashboard yang sesuai berdasarkan peran pengguna.
            switch ($user->role) { // Menggunakan pernyataan switch untuk mengarahkan pengguna berdasarkan perannya.
                case 'admin': // Jika peran pengguna adalah 'admin'...
                    return redirect()->route('admin.dashboard'); // Arahkan ke route bernama 'admin.dashboard'.
                case 'pengurus': // Jika peran pengguna adalah 'pengurus'...
                    return redirect()->route('pengurus.dashboard'); // Arahkan ke route bernama 'pengurus.dashboard'.
                case 'bendahara': // Jika peran pengguna adalah 'bendahara'...
                    return redirect()->route('bendahara.dashboard'); // Arahkan ke route bernama 'bendahara.dashboard'.
                default: // Jika peran pengguna tidak cocok dengan kasus di atas (atau tidak terdefinisi)...
                    return redirect('/login'); // Arahkan kembali ke halaman login (ini mungkin terjadi jika ada peran yang tidak terdaftar di switch).
            }
        }

        return $next($request); // Jika pengguna sudah login DAN perannya cocok dengan yang diharapkan, lanjutkan permintaan ke middleware berikutnya atau controller.
    }
}