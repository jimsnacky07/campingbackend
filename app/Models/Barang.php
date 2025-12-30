<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'id_kategori',
        'nama_barang',
        'deskripsi',
        'harga_sewa',
        'stok',
        'foto',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori');
    }

    public function detailSewa()
    {
        return $this->hasMany(DetailSewa::class, 'id_barang');
    }

    public function detailPengembalian()
    {
        return $this->hasMany(DetailPengembalian::class, 'id_barang');
    }
}


