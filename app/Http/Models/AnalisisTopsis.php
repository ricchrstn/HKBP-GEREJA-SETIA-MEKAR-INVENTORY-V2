<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalisisTopsis extends Model
{
    use HasFactory;

        protected $table = 'analisis_topsis'; // Tambahkan baris ini

    protected $fillable = [
        'pengajuan_id',
        'nilai_preferensi',
        'ranking'
    ];

    protected $casts = [
        'nilai_preferensi' => 'float',
        'ranking' => 'integer'
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
