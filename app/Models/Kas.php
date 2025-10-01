<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_transaksi',
        'jenis',
        'jumlah',
        'tanggal',
        'keterangan',
        'sumber',
        'tujuan',
        'bukti_transaksi'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMasuk($query)
    {
        return $query->where('jenis', 'masuk');
    }

    public function scopeKeluar($query)
    {
        return $query->where('jenis', 'keluar');
    }

    public static function generateKode($jenis)
    {
        $prefix = $jenis == 'masuk' ? 'KM' : 'KK';
        $date = now()->format('Ymd');

        $last = self::where('jenis', $jenis)
            ->whereDate('created_at', now())
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->kode_transaksi, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public static function getSaldo()
    {
        $totalMasuk = self::masuk()->sum('jumlah');
        $totalKeluar = self::keluar()->sum('jumlah');
        return $totalMasuk - $totalKeluar;
    }
}
