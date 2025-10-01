<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi dengan BarangMasuk
     */
    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }

    /**
     * Relasi dengan BarangKeluar
     */
    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class);
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah pengurus
     */
    public function isPengurus()
    {
        return $this->role === 'pengurus';
    }

    /**
     * Cek apakah user adalah bendahara
     */
    public function isBendahara()
    {
        return $this->role === 'bendahara';
    }

    /**
     * Scope untuk mendapatkan user yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
