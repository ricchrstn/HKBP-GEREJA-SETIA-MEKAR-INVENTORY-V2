<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $table = 'audit';
    protected $fillable = [
        'barang_id',
        'user_id',
        'tanggal_audit',
        'kondisi',
        'keterangan',
        'status',
        'foto',
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
