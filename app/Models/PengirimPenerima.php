<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengirimPenerima extends Model
{
    protected $table = 'pengirim_penerima';
    
    protected $fillable = [
        'pengiriman_id',
        'nama_pengirim',
        'telepon_pengirim',
        'email_pengirim',
        'alamat_pengirim',
        'nama_penerima',
        'telepon_penerima',
        'email_penerima',
        'alamat_penerima'
    ];
    
    public function detailPengiriman()
    {
        return $this->belongsTo(DetailPengiriman::class, 'pengiriman_id');
    }
}