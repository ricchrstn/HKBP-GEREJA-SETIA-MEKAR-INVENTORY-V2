<?php

use Illuminate\Support\Facades\Route;

// Test routes untuk debugging
Route::get('/test-server', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'Laravel server is running',
        'timestamp' => now(),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version()
    ]);
});

Route::get('/test-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user() ? [
            'id' => auth()->user()->id,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role
        ] : null,
        'session_id' => session()->getId()
    ]);
});

Route::post('/test-csrf', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'CSRF token is valid',
        'data' => request()->all()
    ]);
});