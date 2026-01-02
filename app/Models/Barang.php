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
        'status',
    ];

    /**
     * Get the proper photo URL.
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto && (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://'))) {
            return $this->foto;
        }
        return $this->foto ? url('storage/' . $this->foto) : url('storage/barang/default.png');
    }

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


