<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';
    protected $fillable = [
        'kode_pengajuan',
        'nama_barang',
        'spesifikasi',
        'jumlah',
        'satuan',
        'alasan',
        'kebutuhan',
        'user_id',
        'status',
        'keterangan',
        'file_pengajuan',
        // Tambahkan field untuk kriteria TOPSIS
        'urgensi', // K1 - Tingkat Urgensi Barang (Benefit)
        'ketersediaan_stok', // K2 - Ketersediaan Stok Barang (Cost)
        'ketersediaan_dana', // K3 - Ketersediaan Dana Pengadaan (Benefit)
    ];

    protected $casts = [
        'kebutuhan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function analisisTopsis()
    {
        return $this->hasOne(AnalisisTopsis::class);
    }

    public function nilaiPengadaanKriterias()
    {
        return $this->hasMany(NilaiPengadaanKriteria::class);
    }

    // Generate kode pengajuan otomatis
    public static function generateKode()
    {
        $prefix = 'PNG';
        $date = now()->format('Ymd');
        $last = self::whereDate('created_at', now())->count();
        $number = str_pad($last + 1, 3, '0', STR_PAD_LEFT);
        return $prefix . $date . $number;
    }

    /**
     * Get the kategori that owns the pengajuan.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
