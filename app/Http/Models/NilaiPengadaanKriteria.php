<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiPengadaanKriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'kriteria_id',
        'nilai'
    ];

    protected $casts = [
        'nilai' => 'float'
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
