<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $fillable = [
        'id_sewa',
        'tanggal_pengembalian',
        'total_denda',
    ];

    public function sewa()
    {
        return $this->belongsTo(Sewa::class, 'id_sewa');
    }

    public function detailPengembalian()
    {
        return $this->hasMany(DetailPengembalian::class, 'id_pengembalian');
    }
}


