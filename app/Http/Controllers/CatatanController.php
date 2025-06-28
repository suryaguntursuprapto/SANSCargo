<?php

namespace App\Http\Controllers;

use App\Models\DetailPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CatatanController extends Controller
{
    public function create($id)
    {
        $pengiriman = DetailPengiriman::with([
            'barangPengiriman',
            'opsiPengiriman',
            'pengirimPenerima',
            'informasiPembayaran'
        ])->findOrFail($id);
        
        return view('pengiriman.manual.catatan', compact('pengiriman'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengiriman_id' => 'required|exists:detail_pengiriman,id',
            'catatan' => 'nullable|string',
            'setuju' => 'required|boolean',
        ]);

        $pengiriman = DetailPengiriman::findOrFail($validated['pengiriman_id']);

        // Update with notes
        $pengiriman->update([
            'catatan' => $validated['catatan']
        ]);

        return redirect()->route('pengiriman.review', ['id' => $pengiriman->id]);
    }

    public function request(Request $request)
    {
        Log::info('Processing pengiriman request:', $request->all());
        
        $validated = $request->validate([
            'pengiriman_id' => 'required|exists:detail_pengiriman,id',
        ]);

        DB::beginTransaction();
        
        try {
            $pengiriman = DetailPengiriman::findOrFail($validated['pengiriman_id']);
            
            Log::info('Found pengiriman:', ['id' => $pengiriman->id, 'current_resi' => $pengiriman->no_resi]);

            // Generate tracking number only if not already exists
            if (!$pengiriman->no_resi) {
                $resi = $this->generateResi();
                Log::info('Generated new resi:', ['resi' => $resi]);
                
                // Update status and add tracking number
                $pengiriman->update([
                    'no_resi' => $resi,
                    'status' => 'processed'
                ]);
                
                Log::info('Updated pengiriman with resi:', ['id' => $pengiriman->id, 'resi' => $resi]);
            } else {
                // Just update status if resi already exists
                $pengiriman->update(['status' => 'processed']);
                Log::info('Updated pengiriman status only:', ['id' => $pengiriman->id]);
            }

            DB::commit();

            Log::info('Transaction committed, redirecting to success page');

            return redirect()->route('pengiriman.success', ['id' => $pengiriman->id])
                ->with('success', 'Pengiriman berhasil dibuat dengan nomor resi ' . $pengiriman->no_resi);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in CatatanController@request: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withErrors('Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function cancel(Request $request)
    {
        $validated = $request->validate([
            'pengiriman_id' => 'required|exists:detail_pengiriman,id',
        ]);

        $pengiriman = DetailPengiriman::findOrFail($validated['pengiriman_id']);

        // Mark as cancelled if it has a tracking number, or delete if it's a draft
        if ($pengiriman->no_resi) {
            $pengiriman->update(['status' => 'cancelled']);
        } else {
            // Delete all related records
            DB::beginTransaction();
            try {
                $pengiriman->barangPengiriman()->delete();
                $pengiriman->opsiPengiriman()->delete();
                $pengiriman->pengirimPenerima()->delete();
                $pengiriman->informasiPembayaran()->delete();
                $pengiriman->delete();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        return redirect()->route('pengiriman.opsi.create')
            ->with('info', 'Pengiriman telah dibatalkan');
    }

    public function review($id)
    {
        try {
            $pengiriman = DetailPengiriman::with([
                'barangPengiriman',
                'opsiPengiriman',
                'pengirimPenerima',
                'informasiPembayaran'
            ])->findOrFail($id);
            
            Log::info('Loading review page for pengiriman:', ['id' => $id]);
            
            return view('pengiriman.manual.review', compact('pengiriman'));
        } catch (\Exception $e) {
            Log::error('Error loading review page: ' . $e->getMessage());
            return redirect()->route('pengiriman.index')
                ->with('error', 'Pengiriman tidak ditemukan.');
        }
    }

    public function success($id)
    {
        try {
            $pengiriman = DetailPengiriman::findOrFail($id);
            
            Log::info('Loading success page for pengiriman:', ['id' => $id, 'resi' => $pengiriman->no_resi]);
            
            return view('pengiriman.manual.success', compact('pengiriman'));
        } catch (\Exception $e) {
            Log::error('Error loading success page: ' . $e->getMessage());
            return redirect()->route('pengiriman.index')
                ->with('error', 'Pengiriman tidak ditemukan.');
        }
    }

    /**
     * Generate unique resi number
     */
    private function generateResi()
    {
        do {
            // Generate shorter resi: CCM + YYMMDD + 3 digits = 12 characters total
            $resi = 'SANS' . date('ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        } while (DetailPengiriman::where('no_resi', $resi)->exists());

        return $resi;
    }
}