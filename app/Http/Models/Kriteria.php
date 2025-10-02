<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'bobot',
        'tipe'
    ];

    protected $casts = [
        'bobot' => 'float'
    ];

    public function nilaiPengadaanKriterias()
    {
        return $this->hasMany(NilaiPengadaanKriteria::class);
    }
}
