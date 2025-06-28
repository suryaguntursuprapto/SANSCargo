<?php

namespace App\Http\Controllers;

use App\Models\DetailPengiriman;
use App\Models\PengirimPenerima;
use Illuminate\Http\Request;

class PengirimPenerimaController extends Controller
{
    public function create($id)
    {
        $pengiriman = DetailPengiriman::with('pengirimPenerima')->findOrFail($id);
        
        return view('pengiriman.manual.pengirim_penerima', compact('pengiriman'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengiriman_id' => 'required|exists:detail_pengiriman,id',
            'nama_pengirim' => 'required|string',
            'telepon_pengirim' => 'required|string',
            'email_pengirim' => 'nullable|email',
            'alamat_pengirim' => 'required|string',
            'nama_penerima' => 'required|string',
            'telepon_penerima' => 'required|string',
            'email_penerima' => 'nullable|email',
            'alamat_penerima' => 'required|string',
        ]);
        
        $pengiriman = DetailPengiriman::findOrFail($validated['pengiriman_id']);
        
        // Create or update PengirimPenerima
        $pengirimPenerima = PengirimPenerima::updateOrCreate(
            ['pengiriman_id' => $pengiriman->id],
            $validated
        );
        
        return redirect()->route('pengiriman.detail.create', ['id' => $pengiriman->id]);
    }
}