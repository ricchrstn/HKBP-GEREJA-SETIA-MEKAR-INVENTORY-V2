<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalAudit extends Model
{
    use HasFactory;

    protected $table = 'jadwal_audit';
    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_audit',
        'status',
        'barang_id',
        'user_id',
    ];

    protected $casts = [
        'tanggal_audit' => 'date',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
