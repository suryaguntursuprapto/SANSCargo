<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ongkir;

class OngkirSeeder extends Seeder
{
    public function run()
    {
        $ongkir = [
            // Yogyakarta ke Jakarta
            [
                'kota_asal' => 'Yogyakarta',
                'kota_tujuan' => 'Jakarta',
                'jenis_layanan' => 'Express',
                'berat_minimum' => 0.5,
                'berat_maksimum' => 100.0,
                'harga_per_kg' => 15000,
                'harga_minimum' => 10000,
                'estimasi_hari' => 1,
                'status' => true,
            ],
            [
                'kota_asal' => 'Yogyakarta',
                'kota_tujuan' => 'Jakarta',
                'jenis_layanan' => 'Regular',
                'berat_minimum' => 0.5,
                'berat_maksimum' => 100.0,
                'harga_per_kg' => 12000,
                'harga_minimum' => 8000,
                'estimasi_hari' => 2,
                'status' => true,
            ],
            [
                'kota_asal' => 'Yogyakarta',
                'kota_tujuan' => 'Jakarta',
                'jenis_layanan' => 'Economy',
                'berat_minimum' => 1.0,
                'berat_maksimum' => 100.0,
                'harga_per_kg' => 8000,
                'harga_minimum' => 6000,
                'estimasi_hari' => 3,
                'status' => true,
            ],
            
            // Jakarta ke Surabaya
            [
                'kota_asal' => 'Jakarta',
                'kota_tujuan' => 'Surabaya',
                'jenis_layanan' => 'Express',
                'berat_minimum' => 0.5,
                'berat_maksimum' => 100.0,
                'harga_per_kg' => 13000,
                'harga_minimum' => 9000,
                'estimasi_hari' => 1,
                'status' => true,
            ],
            [
                'kota_asal' => 'Jakarta',
                'kota_tujuan' => 'Surabaya',
                'jenis_layanan' => 'Regular',
                'berat_minimum' => 0.5,
                'berat_maksimum' => 100.0,
                'harga_per_kg' => 10000,
                'harga_minimum' => 7000,
                'estimasi_hari' => 2,
                'status' => true,
            ],
            
            // Yogyakarta ke Surabaya
            [
                'kota_asal' => 'Yogyakarta',
                'kota_tujuan' => 'Surabaya',
                'jenis_layanan' => 'Express',
                'berat_minimum' => 0.5,
                'berat_maksimum' => 100.0,
                'harga_per_kg' => 14000,
                'harga_minimum' => 9500,
                'estimasi_hari' => 1,
                'status' => true,
            ],
        ];

        foreach ($ongkir as $tarif) {
            Ongkir::create($tarif);
        }
    }
}