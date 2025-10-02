<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';
    protected $fillable = [
        'kode_barang',
        'nama',
        'deskripsi',
        'kategori_id',
        'satuan',
        'stok',
        'status',
        'harga',
        'gambar'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer'
    ];

    /**
     * Get the kategori that owns the barang.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class);
    }

    public function jadwalAudits()
    {
        return $this->hasMany(JadwalAudit::class);
    }
}
