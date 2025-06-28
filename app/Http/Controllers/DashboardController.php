<?php

namespace App\Http\Controllers;

use App\Models\DetailPengiriman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();

    if ($user->status === 'Customer') {
        return redirect()->route('dashboard');
    }

    // Ambil total pengiriman user
    $totalPengiriman = \App\Models\DetailPengiriman::where('user_id', $user->id)->count();

    // Ambil pengiriman aktif
    $pengirimanAktif = \App\Models\DetailPengiriman::where('user_id', $user->id)
                        ->where('status', '!=', 'Selesai')
                        ->count();

    // Ambil 5 pengiriman terakhir
    $pengirimanTerakhir = \App\Models\DetailPengiriman::where('user_id', $user->id)
                            ->latest()
                            ->take(5)
                            ->get();

    return view('dashboard', compact('totalPengiriman', 'pengirimanAktif', 'pengirimanTerakhir'));
}

}
