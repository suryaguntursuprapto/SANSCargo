<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $branches = [
            [
                'kode_branch' => 'BRYGY001',
                'nama_branch' => 'CCM Cargo Yogyakarta',
                'alamat' => 'Jl. Malioboro No. 123',
                'kota' => 'Yogyakarta',
                'provinsi' => 'Daerah Istimewa Yogyakarta',
                'kode_pos' => '55213',
                'telepon' => '0274-123456',
                'email' => 'yogyakarta@csmcargo.com',
                'status' => true,
            ],
            [
                'kode_branch' => 'BRJKT001',
                'nama_branch' => 'CCM Cargo Jakarta',
                'alamat' => 'Jl. Sudirman No. 456',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => '10220',
                'telepon' => '021-123456',
                'email' => 'jakarta@csmcargo.com',
                'status' => true,
            ],
            [
                'kode_branch' => 'BRSBY001',
                'nama_branch' => 'CCM Cargo Surabaya',
                'alamat' => 'Jl. Tunjungan No. 789',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '60264',
                'telepon' => '031-123456',
                'email' => 'surabaya@csmcargo.com',
                'status' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
