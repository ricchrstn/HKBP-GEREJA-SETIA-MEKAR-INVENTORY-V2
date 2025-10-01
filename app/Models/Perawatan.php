<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perawatan extends Model
{
    use HasFactory;

    protected $table = 'perawatan';
    protected $fillable = [
        'barang_id',
        'tanggal_perawatan',
        'jenis_perawatan',
        'biaya',
        'keterangan',
        'status',
        'user_id'
    ];

    protected $casts = [
        'tanggal_perawatan' => 'date',
        'biaya' => 'decimal:2'
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
