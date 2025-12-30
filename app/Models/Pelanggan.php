<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'id_user',
        'nik',
        'alamat',
        'telp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function sewa()
    {
        return $this->hasMany(Sewa::class, 'id_pelanggan');
    }
}


