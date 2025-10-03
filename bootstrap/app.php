<?php

use Illuminate\Foundation\Application; // Mengimpor kelas Application, yang merupakan inti dari aplikasi Laravel.
use Illuminate\Foundation\Configuration\Exceptions; // Mengimpor kelas Exceptions untuk mengonfigurasi penanganan pengecualian.
use Illuminate\Foundation\Configuration\Middleware; // Mengimpor kelas Middleware untuk mengonfigurasi middleware.

return Application::configure(basePath: dirname(__DIR__)) // Ini adalah titik masuk utama untuk mengonfigurasi aplikasi Laravel.
                                                     // `Application::configure()` memulai proses konfigurasi.
                                                     // `basePath: dirname(__DIR__)` secara otomatis menentukan direktori dasar proyek Anda (satu tingkat di atas folder 'bootstrap').
    ->withRouting( // Bagian ini mengonfigurasi bagaimana aplikasi akan menangani routing.
        web: __DIR__.'/../routes/web.php', // Menentukan file routing untuk rute web (rute yang diakses melalui browser).
                                         // `__DIR__.'/../routes/web.php'` adalah path relatif ke file `routes/web.php`.
        commands: __DIR__.'/../routes/console.php', // Menentukan file routing untuk perintah konsol (Artisan commands).
                                                   // `__DIR__.'/../routes/console.php'` adalah path relatif ke file `routes/console.php`.
        health: '/up', // Menentukan rute khusus `/up` yang digunakan untuk pemeriksaan kesehatan aplikasi (health check).
    )
    ->withMiddleware(function (Middleware $middleware) { // Bagian ini mengonfigurasi middleware aplikasi.
        // Register custom middleware
        $middleware->alias([ // Metode `alias` digunakan untuk memberikan alias pendek ke middleware agar mudah digunakan di rute.
            'role' => \App\Http\Middleware\RoleMiddleware::class, // Mendaftarkan `RoleMiddleware` dengan alias 'role'.
                                                                 // Ini berarti Anda bisa menggunakan `middleware('role:admin')` di rute Anda.
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) { // Bagian ini mengonfigurasi bagaimana aplikasi menangani pengecualian (errors).
        // Blok ini saat ini kosong, yang berarti penanganan pengecualian default Laravel akan digunakan.
        // Di sini Anda bisa mendaftarkan handler pengecualian kustom, melaporkan pengecualian, dll.
    })->create(); // Setelah semua konfigurasi selesai, `->create()` akan membangun dan mengembalikan instance aplikasi Laravel yang sudah dikonfigurasi.