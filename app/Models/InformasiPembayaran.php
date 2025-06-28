<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformasiPembayaran extends Model
{
    protected $table = 'informasi_pembayaran';
    
    protected $fillable = [
        'pengiriman_id',
        'total_sub_biaya',
        'total_biaya_pengiriman',
        'metode_pembayaran'
    ];
    
    public function detailPengiriman()
    {
        return $this->belongsTo(DetailPengiriman::class, 'pengiriman_id');
    }
}