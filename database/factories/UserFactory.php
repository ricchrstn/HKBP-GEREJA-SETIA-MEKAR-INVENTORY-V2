<?php

namespace Database\Factories; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa ia berada di dalam direktori 'database/factories'.

use Illuminate\Database\Eloquent\Factories\Factory; // Mengimpor kelas dasar 'Factory' dari Eloquent, yang merupakan fondasi untuk membuat data dummy.
use Illuminate\Support\Facades\Hash; // Mengimpor facade 'Hash' untuk mengenkripsi kata sandi.
use Illuminate\Support\Str; // Mengimpor kelas 'Str' untuk menghasilkan string acak.

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User> // PHPDoc comment yang menunjukkan bahwa factory ini diperuntukkan bagi model 'App\Models\User'.
 */
class UserFactory extends Factory // Mendefinisikan kelas 'UserFactory' yang merupakan turunan dari kelas 'Factory'.
{
    /**
     * The current password being used by the factory. // PHPDoc comment untuk properti statis $password.
     */
    protected static ?string $password; // Properti statis untuk menyimpan kata sandi yang di-hash. Tanda '?' menunjukkan bahwa properti ini bisa null.

    /**
     * Define the model's default state. // PHPDoc comment yang menjelaskan fungsi di bawahnya.
     *
     * @return array<string, mixed> // Menunjukkan bahwa metode ini mengembalikan array dengan string sebagai kunci dan mixed (tipe data apapun) sebagai nilai.
     */
    public function definition(): array // Metode `definition` adalah inti dari factory. Di sinilah Anda mendefinisikan atribut default untuk model.
    {
        return [ // Mengembalikan array asosiatif yang merepresentasikan atribut dari sebuah user.
            'name' => fake()->name(), // Menggunakan faker (library untuk menghasilkan data palsu) untuk menghasilkan nama acak.
            'email' => fake()->unique()->safeEmail(), // Menggunakan faker untuk menghasilkan alamat email unik dan aman.
            'email_verified_at' => now(), // Mengatur 'email_verified_at' ke waktu saat ini (seolah-olah email sudah diverifikasi).
            'password' => static::$password ??= Hash::make('password'), // Mengatur kata sandi.
                                                                     // `static::$password ??=` adalah operator "null coalescing assignment".
                                                                     // Ini berarti: jika `static::$password` belum diatur (null), maka atur `static::$password`
                                                                     // dengan hasil dari `Hash::make('password')` dan gunakan nilai tersebut.
                                                                     // Jika sudah diatur, gunakan nilai yang sudah ada. Ini mencegah hashing password berulang kali.
            'remember_token' => Str::random(10), // Menghasilkan string acak sepanjang 10 karakter untuk 'remember_token'.
        ];
    }

    /**
     * Indicate that the model's email address should be unverified. // PHPDoc comment untuk metode unverified.
     */
    public function unverified(): static // Metode `unverified` adalah sebuah "state" kustom. Ini memungkinkan Anda untuk memodifikasi definisi default factory.
    {
        return $this->state(fn (array $attributes) => [ // Metode `state` menerima callback yang mengembalikan array atribut yang akan menggantikan atau menambahkan atribut default.
            'email_verified_at' => null, // Mengatur 'email_verified_at' menjadi null, menunjukkan bahwa email user belum diverifikasi.
        ]);
    }
}