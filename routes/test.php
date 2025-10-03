<?php

use Illuminate\Support\Facades\Route; // Mengimpor fasad Route dari Laravel, yang digunakan untuk mendefinisikan rute HTTP.

// Test routes untuk debugging
// Blok komentar ini menandakan bahwa rute-rute di bawah ini ditujukan untuk tujuan pengujian atau debugging.

// Rute GET untuk menguji status server Laravel.
Route::get('/test-server', function () { // Mendefinisikan rute yang merespons permintaan GET ke '/test-server'.
    return response()->json([ // Mengembalikan respons dalam format JSON.
        'status' => 'OK', // Menunjukkan status operasional server.
        'message' => 'Laravel server is running', // Pesan konfirmasi bahwa server berjalan.
        'timestamp' => now(), // Waktu saat ini ketika permintaan diproses. Fungsi 'now()' adalah helper Laravel.
        'php_version' => PHP_VERSION, // Versi PHP yang sedang digunakan. 'PHP_VERSION' adalah konstanta bawaan PHP.
        'laravel_version' => app()->version() // Versi Laravel yang sedang berjalan. 'app()->version()' adalah helper Laravel.
    ]);
});

// Rute GET untuk menguji status otentikasi pengguna.
Route::get('/test-auth', function () { // Mendefinisikan rute yang merespons permintaan GET ke '/test-auth'.
    return response()->json([ // Mengembalikan respons dalam format JSON.
        'authenticated' => auth()->check(), // Memeriksa apakah ada pengguna yang sedang terotentikasi (login). Mengembalikan true atau false.
        'user' => auth()->user() ? [ // Jika ada pengguna yang login (auth()->user() mengembalikan objek user non-null), maka tampilkan detailnya.
            'id' => auth()->user()->id, // ID pengguna yang login.
            'email' => auth()->user()->email, // Email pengguna yang login.
            'role' => auth()->user()->role // Peran (role) pengguna yang login.
        ] : null, // Jika tidak ada pengguna yang login, nilai 'user' adalah null.
        'session_id' => session()->getId() // Menampilkan ID sesi HTTP saat ini.
    ]);
});

// Rute POST untuk menguji validitas token CSRF.
Route::post('/test-csrf', function () { // Mendefinisikan rute yang merespons permintaan POST ke '/test-csrf'.
    // Rute POST ini secara otomatis akan memverifikasi token CSRF. Jika token tidak valid, Laravel akan menolak permintaan sebelum mencapai fungsi ini.
    return response()->json([ // Mengembalikan respons dalam format JSON.
        'status' => 'OK', // Menunjukkan status keberhasilan. Jika fungsi ini tercapai, berarti token CSRF valid.
        'message' => 'CSRF token is valid', // Pesan konfirmasi bahwa token CSRF valid.
        'data' => request()->all() // Menampilkan semua data yang dikirimkan dalam permintaan POST. 'request()->all()' adalah helper Laravel.
    ]);
});