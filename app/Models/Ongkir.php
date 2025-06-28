<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ongkir extends Model
{
    use HasFactory;

    protected $table = 'ongkir';

    protected $fillable = [
        'kota_asal',
        'kota_tujuan', 
        'jenis_layanan',
        'berat_minimum',
        'berat_maksimum',
        'harga_per_kg',
        'harga_minimum',
        'estimasi_hari',
        'status'
    ];

    protected $casts = [
        'berat_minimum' => 'decimal:2',
        'berat_maksimum' => 'decimal:2', 
        'harga_per_kg' => 'decimal:2',
        'harga_minimum' => 'decimal:2',
        'estimasi_hari' => 'integer',
        'status' => 'boolean'
    ];

    // Scope untuk ongkir aktif
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Method untuk menghitung ongkir berdasarkan berat
    public function hitungOngkir($berat)
    {
        if ($berat < $this->berat_minimum) {
            return $this->harga_minimum;
        }

        $harga = $berat * $this->harga_per_kg;
        return max($harga, $this->harga_minimum);
    }
}
