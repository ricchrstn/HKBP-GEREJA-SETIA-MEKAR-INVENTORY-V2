<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    // Tambahkan 'kategori_id' ke dalam $fillable
    protected $fillable = [
        'barang_id',
        'kategori_id',
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'jumlah',
        'peminjam',
        'kontak',
        'keperluan',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'datetime',
        'jumlah' => 'integer'
    ];

    /**
     * Get the barang that owns the peminjaman.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Get the user that owns the peminjaman.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the kategori that owns the peminjaman.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
