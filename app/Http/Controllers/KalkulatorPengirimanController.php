<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KalkulatorPengirimanController extends Controller
{
    /**
     * Display the shipping calculator page.
     */
    public function index()
    {
        return view('pengiriman.kalkulator.index');
    }

    /**
     * Calculate shipping cost
     */
    public function hitungOngkir(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'asal' => 'required|string',
            'tujuan' => 'required|string',
            'kategori' => 'required|string',
            'berat' => 'required|numeric',
            'panjang' => 'required|numeric',
            'lebar' => 'required|numeric',
            'tinggi' => 'required|numeric',
        ]);

        // In a real application, you would calculate the distance between locations
        // using coordinates and a distance formula or an API

        // For this demo, we'll create a simple calculation
        $volumeWeight = ($validated['panjang'] * $validated['lebar'] * $validated['tinggi']) / 6000;
        $actualWeight = $validated['berat'];
        
        // Use the greater weight for calculation (volumetric or actual)
        $calculatedWeight = max($volumeWeight, $actualWeight);
        
        // Calculate base price (simplified)
        $basePrice = $calculatedWeight * 12500;
        
        // Define services with their multipliers and durations
        $services = [
            'Regular' => [
                'multiplier' => 1.0,
                'duration' => '3-5 hari',
                'price' => ceil($basePrice / 1000) * 1000
            ],
            'Express' => [
                'multiplier' => 1.5,
                'duration' => '2-3 hari',
                'price' => ceil(($basePrice * 1.5) / 1000) * 1000
            ],
            'Same Day' => [
                'multiplier' => 2.0,
                'duration' => '1 hari',
                'price' => ceil(($basePrice * 2.0) / 1000) * 1000
            ],
        ];
        
        return response()->json([
            'services' => $services,
            'from' => $validated['asal'],
            'to' => $validated['tujuan'],
        ]);
    }
}