<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangPengiriman extends Model
{
    use HasFactory;

    protected $table = 'barang_pengiriman';
    
    protected $fillable = [
        'pengiriman_id',
        'nama_barang',
        'jenis_barang',
        'deskripsi_barang',
        'berat_barang',
        'panjang_barang',
        'lebar_barang',
        'tinggi_barang',
    ];

    // Relationship with shipment
    public function detailpengiriman()
    {
        return $this->belongsTo(DetailPengiriman::class, 'pengiriman_id');
    }

      // Relationship with shipment
      public function pengirimanPDF()
      {
          return $this->belongsTo(PengirimanPDF::class);
      }
}