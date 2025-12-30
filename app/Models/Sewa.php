<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sewa extends Model
{
    use HasFactory;

    protected $table = 'sewa';

    protected $fillable = [
        'id_pelanggan',
        'tanggal_sewa',
        'tanggal_kembali',
        'total_harga',
        'status',
        'bukti_bayar',
        'foto_ktp',
        'catatan',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'payment_type',
        'paid_at',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function detailSewa()
    {
        return $this->hasMany(DetailSewa::class, 'id_sewa');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'id_sewa');
    }
}


