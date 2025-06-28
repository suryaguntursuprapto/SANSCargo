<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ongkir;
use Illuminate\Http\Request;

class OngkirController extends Controller
{
    public function index(Request $request)
    {
        $query = Ongkir::query();

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kota_asal', 'like', "%{$search}%")
                  ->orWhere('kota_tujuan', 'like', "%{$search}%")
                  ->orWhere('jenis_layanan', 'like', "%{$search}%");
            });
        }

        // Apply additional filters
        if ($request->has('kota_asal') && $request->kota_asal) {
            $query->where('kota_asal', $request->kota_asal);
        }

        if ($request->has('kota_tujuan') && $request->kota_tujuan) {
            $query->where('kota_tujuan', $request->kota_tujuan);
        }

        if ($request->has('jenis_layanan') && $request->jenis_layanan) {
            $query->where('jenis_layanan', $request->jenis_layanan);
        }

        // Get paginated results
        $ongkir = $query->paginate(10);

        // Calculate statistics using separate queries
        $totalTarif = Ongkir::count();
        $totalKotaAsal = Ongkir::distinct('kota_asal')->count('kota_asal');
        $totalJenisLayanan = Ongkir::distinct('jenis_layanan')->count('jenis_layanan');
        $tarifAktif = Ongkir::where('status', true)->count();

        // Get filter options
        $kotaAsal = Ongkir::distinct()->pluck('kota_asal')->sort();
        $kotaTujuan = Ongkir::distinct()->pluck('kota_tujuan')->sort();
        $jenisLayanan = Ongkir::distinct()->pluck('jenis_layanan')->sort();

        return view('admin.ongkir.index', compact(
            'ongkir',
            'totalTarif',
            'totalKotaAsal', 
            'totalJenisLayanan',
            'tarifAktif',
            'kotaAsal',
            'kotaTujuan',
            'jenisLayanan'
        ));
    }

    // Method untuk AJAX calculator
    public function calculateOngkir(Request $request)
    {
        try {
            $validated = $request->validate([
                'kota_asal' => 'required|string',
                'kota_tujuan' => 'required|string', 
                'berat' => 'required|numeric|min:0.1'
            ]);

            $kotaAsal = $validated['kota_asal'];
            $kotaTujuan = $validated['kota_tujuan'];
            $berat = $validated['berat'];

            // Cari tarif yang sesuai dari database
            $tarifs = Ongkir::where('kota_asal', $kotaAsal)
                            ->where('kota_tujuan', $kotaTujuan)
                            ->where('status', true)
                            ->where('berat_minimum', '<=', $berat)
                            ->where('berat_maksimum', '>=', $berat)
                            ->orderBy('jenis_layanan')
                            ->get();

            if ($tarifs->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarif tidak ditemukan untuk rute dan berat tersebut',
                    'results' => []
                ]);
            }

            $results = $tarifs->map(function($tarif) use ($berat) {
                $harga = max($tarif->harga_minimum, $tarif->harga_per_kg * $berat);
                
                $color = 'text-gray-600';
                switch($tarif->jenis_layanan) {
                    case 'Express':
                        $color = 'text-red-600';
                        break;
                    case 'Regular':
                        $color = 'text-blue-600';
                        break;
                    case 'Economy':
                        $color = 'text-green-600';
                        break;
                }

                return [
                    'id' => $tarif->id,
                    'jenis_layanan' => $tarif->jenis_layanan,
                    'estimasi_hari' => $tarif->estimasi_hari,
                    'harga' => $harga,
                    'harga_formatted' => number_format($harga, 0, ',', '.'),
                    'color' => $color,
                    'berat_minimum' => $tarif->berat_minimum,
                    'berat_maksimum' => $tarif->berat_maksimum,
                    'harga_per_kg' => $tarif->harga_per_kg,
                    'harga_minimum' => $tarif->harga_minimum
                ];
            });

            return response()->json([
                'success' => true,
                'results' => $results,
                'route_info' => [
                    'kota_asal' => $kotaAsal,
                    'kota_tujuan' => $kotaTujuan,
                    'berat' => $berat
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'results' => []
            ]);
        }
    }

    public function create()
    {
        return view('admin.ongkir.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kota_asal' => 'required|string|max:100',
            'kota_tujuan' => 'required|string|max:100',
            'jenis_layanan' => 'required|string|max:50',
            'berat_minimum' => 'required|numeric|min:0',
            'berat_maksimum' => 'required|numeric|min:0',
            'harga_per_kg' => 'required|numeric|min:0',
            'harga_minimum' => 'required|numeric|min:0',
            'estimasi_hari' => 'required|integer|min:1',
            'status' => 'required|boolean'
        ]);

        Ongkir::create($validated);

        return redirect()->route('admin.ongkir.index')
                        ->with('success', 'Ongkir berhasil ditambahkan.');
    }

    public function edit(Ongkir $ongkir)
    {
        return view('admin.ongkir.edit', compact('ongkir'));
    }

    public function update(Request $request, Ongkir $ongkir)
    {
        $validated = $request->validate([
            'kota_asal' => 'required|string|max:100',
            'kota_tujuan' => 'required|string|max:100',
            'jenis_layanan' => 'required|string|max:50',
            'berat_minimum' => 'required|numeric|min:0',
            'berat_maksimum' => 'required|numeric|min:0',
            'harga_per_kg' => 'required|numeric|min:0',
            'harga_minimum' => 'required|numeric|min:0',
            'estimasi_hari' => 'required|integer|min:1',
            'status' => 'required|boolean'
        ]);

        $ongkir->update($validated);

        return redirect()->route('admin.ongkir.index')
                        ->with('success', 'Ongkir berhasil diperbarui.');
    }

    public function destroy(Ongkir $ongkir)
    {
        $ongkir->delete();

        return redirect()->route('admin.ongkir.index')
                        ->with('success', 'Ongkir berhasil dihapus.');
    }
}