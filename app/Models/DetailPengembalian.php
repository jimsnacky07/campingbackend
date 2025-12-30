<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengembalian extends Model
{
    use HasFactory;

    protected $table = 'detail_pengembalian';

    protected $fillable = [
        'id_pengembalian',
        'id_barang',
        'kondisi',
        'denda',
    ];

    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'id_pengembalian');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}


