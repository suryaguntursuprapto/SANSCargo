<?php

namespace App\Http\Controllers;

use App\Models\DetailPengiriman;
use App\Models\InformasiPembayaran;
use App\Models\BarangPengiriman;
use Illuminate\Http\Request;
use App\Http\Controllers\KalkulatorPengirimanController;

class PembayaranController extends Controller
{
    public function create($id)
    {
        $pengiriman = DetailPengiriman::with(['barangPengiriman', 'opsiPengiriman', 'informasiPembayaran'])
            ->findOrFail($id);
        
        // Calculate total based on KalkulatorPengirimanController logic
        $kalkulatorController = new KalkulatorPengirimanController();
        
        // Get the total weight and dimensions
        $berat = 0;
        $volume = 0;
        
        foreach ($pengiriman->barangPengiriman as $item) {
            $berat += $item->berat_barang;
            $volume += $item->panjang_barang * $item->lebar_barang * $item->tinggi_barang;
        }
        
        // Prepare data for calculation
        $request = new Request([
            'asal' => $pengiriman->asal,
            'tujuan' => $pengiriman->tujuan,
            'kategori' => $pengiriman->barangPengiriman->first()->jenis_barang ?? 'Lainnya',
            'berat' => $berat,
            'panjang' => max($pengiriman->barangPengiriman->pluck('panjang_barang')->toArray() ?: [0]),
            'lebar' => max($pengiriman->barangPengiriman->pluck('lebar_barang')->toArray() ?: [0]),
            'tinggi' => max($pengiriman->barangPengiriman->pluck('tinggi_barang')->toArray() ?: [0]),
        ]);
        
        $calculationResult = $kalkulatorController->hitungOngkir($request)->getData();
        
        // Use the appropriate service based on opsiPengiriman
        $serviceKey = 'Regular'; // Default
        if ($pengiriman->opsiPengiriman) {
            switch ($pengiriman->opsiPengiriman->jenis_layanan) {
                case 'Express':
                    $serviceKey = 'Express';
                    break;
                case 'Same Day':
                    $serviceKey = 'Same Day';
                    break;
            }
        }
        
        $cost = $calculationResult->services->$serviceKey->price ?? 0;
        $additionalCost = 0;
        
        // Add costs for additional services
        if ($pengiriman->opsiPengiriman) {
            if ($pengiriman->opsiPengiriman->asuransi) {
                $additionalCost += $cost * 0.05; // 5% insurance
            }
            
            if ($pengiriman->opsiPengiriman->packing_tambahan) {
                $additionalCost += 25000; // Fixed cost for additional packing
            }
        }
        
        $totalCost = $cost + $additionalCost;
        
        return view('pengiriman.manual.pembayaran', compact('pengiriman', 'cost', 'additionalCost', 'totalCost'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengiriman_id' => 'required|exists:detail_pengiriman,id',
            'metode_pembayaran' => 'required|string',
        ]);
        
        // If raw values are provided, use them
        if ($request->has('raw_total_sub_biaya') && $request->has('raw_total_biaya_pengiriman')) {
            $totalSubBiaya = $request->raw_total_sub_biaya;
            $totalBiayaPengiriman = $request->raw_total_biaya_pengiriman;
        } else {
            // Otherwise, clean the formatted strings
            $totalSubBiaya = str_replace('.', '', $request->total_sub_biaya);
            $totalBiayaPengiriman = str_replace('.', '', $request->total_biaya_pengiriman);
        }
        
        // Create or update InformasiPembayaran
        $pembayaran = InformasiPembayaran::updateOrCreate(
            ['pengiriman_id' => $validated['pengiriman_id']],
            [
                'total_sub_biaya' => $totalSubBiaya,
                'total_biaya_pengiriman' => $totalBiayaPengiriman,
                'metode_pembayaran' => $validated['metode_pembayaran'],
            ]
        );
        
        return redirect()->route('pengiriman.catatan.create', ['id' => $validated['pengiriman_id']]);
    }
}