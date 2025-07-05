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

        try {
            $pengiriman = DetailPengiriman::findOrFail($validated['pengiriman_id']);

            // Update with notes
            $pengiriman->update([
                'catatan' => $validated['catatan']
            ]);

            Log::info('Catatan updated successfully', ['pengiriman_id' => $pengiriman->id]);

            return redirect()->route('pengiriman.review', ['id' => $pengiriman->id])
                ->with('success', 'Catatan berhasil disimpan.');

        } catch (\Exception $e) {
            Log::error('Error in CatatanController@store: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan catatan.'])
                ->withInput();
        }
    }

    public function request(Request $request)
    {
        Log::info('Processing pengiriman request:', $request->all());
        
        $validated = $request->validate([
            'pengiriman_id' => 'required|exists:detail_pengiriman,id',
        ]);

        DB::beginTransaction();
        
        try {
            $pengiriman = DetailPengiriman::with([
                'barangPengiriman',
                'opsiPengiriman', 
                'pengirimPenerima',
                'informasiPembayaran'
            ])->findOrFail($validated['pengiriman_id']);
            
            Log::info('Found pengiriman:', [
                'id' => $pengiriman->id, 
                'current_resi' => $pengiriman->no_resi,
                'current_status' => $pengiriman->status
            ]);

            // Validate pengiriman completeness
            $this->validatePengirimanData($pengiriman);

            // Generate tracking number only if not already exists
            if (!$pengiriman->hasResi()) {
                $resi = $this->generateResiWithFallback();
                Log::info('Generated new resi:', ['resi' => $resi, 'length' => strlen($resi)]);
                
                // Try to update with multiple fallback strategies
                $updateSuccess = $this->updatePengirimanWithResi($pengiriman, $resi);
                
                if (!$updateSuccess) {
                    throw new \Exception('Failed to update pengiriman with resi after multiple attempts');
                }
                
                Log::info('Successfully updated pengiriman with resi:', [
                    'id' => $pengiriman->id, 
                    'resi' => $pengiriman->fresh()->no_resi,
                    'status' => $pengiriman->fresh()->status
                ]);
                
            } else {
                // Just update status if resi already exists
                $pengiriman->update(['status' => 'processed']);
                Log::info('Updated pengiriman status only:', ['id' => $pengiriman->id]);
            }

            DB::commit();

            Log::info('Transaction committed successfully, redirecting to success page');

            return redirect()->route('pengiriman.success', ['id' => $pengiriman->id])
                ->with('success', 'Pengiriman berhasil dibuat dengan nomor resi ' . $pengiriman->fresh()->no_resi);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error in CatatanController@request: ' . json_encode($e->errors()));
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in CatatanController@request: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memproses pengiriman: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function cancel(Request $request)
    {
        $validated = $request->validate([
            'pengiriman_id' => 'required|exists:detail_pengiriman,id',
        ]);

        try {
            $pengiriman = DetailPengiriman::findOrFail($validated['pengiriman_id']);

            if (!$pengiriman->canBeCancelled()) {
                return redirect()->back()
                    ->withErrors(['error' => 'Pengiriman ini tidak dapat dibatalkan.']);
            }

            // Mark as cancelled if it has a tracking number, or delete if it's a draft
            if ($pengiriman->hasResi()) {
                $pengiriman->update(['status' => 'cancelled']);
                $message = 'Pengiriman telah dibatalkan';
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
                    $message = 'Draft pengiriman telah dihapus';
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

            return redirect()->route('pengiriman.opsi.create')
                ->with('info', $message);

        } catch (\Exception $e) {
            Log::error('Error in CatatanController@cancel: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat membatalkan pengiriman.']);
        }
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
            
            // Pastikan pengiriman sudah memiliki resi
            if (!$pengiriman->hasResi()) {
                Log::warning('Success page accessed but no resi found:', ['id' => $id]);
                return redirect()->route('pengiriman.review', ['id' => $id])
                    ->with('error', 'Pengiriman belum diproses. Silakan klik konfirmasi dan proses terlebih dahulu.');
            }
            
            Log::info('Loading success page for pengiriman:', [
                'id' => $id, 
                'resi' => $pengiriman->no_resi,
                'status' => $pengiriman->status
            ]);
            
            return view('pengiriman.manual.success', compact('pengiriman'));
            
        } catch (\Exception $e) {
            Log::error('Error loading success page: ' . $e->getMessage());
            return redirect()->route('pengiriman.index')
                ->with('error', 'Pengiriman tidak ditemukan.');
        }
    }

    /**
     * Generate resi with multiple fallback formats
     */
    private function generateResiWithFallback()
    {
        // Try different formats from longest to shortest
        $formats = [
            // Format 1: SANS + YYMMDD + 3 digits (13 chars)
            function() {
                return 'SANS' . date('ymd') . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);
            },
            // Format 2: S + YYMMDD + 4 digits (11 chars)
            function() {
                return 'S' . date('ymd') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            },
            // Format 3: S + YYMMDD + 3 digits (10 chars)
            function() {
                return 'S' . date('ymd') . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);
            },
            // Format 4: Ultra short with timestamp (max 10 chars)
            function() {
                return 'S' . substr(time(), -6) . rand(100, 999);
            }
        ];

        foreach ($formats as $index => $formatFunction) {
            $maxAttempts = 5;
            $attempts = 0;
            
            do {
                $resi = $formatFunction();
                $exists = DetailPengiriman::where('no_resi', $resi)->exists();
                $attempts++;
            } while ($exists && $attempts < $maxAttempts);
            
            if (!$exists) {
                Log::info("Generated resi using format " . ($index + 1), [
                    'resi' => $resi, 
                    'length' => strlen($resi),
                    'attempts' => $attempts
                ]);
                return $resi;
            }
        }
        
        // Ultimate fallback - use timestamp
        $fallbackResi = 'S' . substr(time(), -9);
        Log::warning('Using fallback resi generation', ['resi' => $fallbackResi]);
        return $fallbackResi;
    }

    /**
     * Update pengiriman with resi with error handling
     */
    private function updatePengirimanWithResi($pengiriman, $resi)
    {
        try {
            // First attempt with provided resi
            $result = $pengiriman->update([
                'no_resi' => $resi,
                'status' => 'processed'
            ]);
            
            if ($result) {
                $pengiriman->refresh();
                return true;
            }
            
        } catch (\Illuminate\Database\QueryException $e) {
            Log::warning('Database error with resi, trying fallback', [
                'error' => $e->getMessage(),
                'original_resi' => $resi,
                'resi_length' => strlen($resi)
            ]);
            
            // If it's a data too long error, try shorter formats
            if (str_contains($e->getMessage(), 'Data too long for column')) {
                $shorterResi = $this->generateShorterResi();
                
                try {
                    $result = $pengiriman->update([
                        'no_resi' => $shorterResi,
                        'status' => 'processed'
                    ]);
                    
                    if ($result) {
                        Log::info('Successfully updated with shorter resi', [
                            'shorter_resi' => $shorterResi,
                            'length' => strlen($shorterResi)
                        ]);
                        $pengiriman->refresh();
                        return true;
                    }
                    
                } catch (\Exception $e2) {
                    Log::error('Failed even with shorter resi', ['error' => $e2->getMessage()]);
                }
            }
        }
        
        return false;
    }

    /**
     * Generate very short resi for constrained columns
     */
    private function generateShorterResi()
    {
        // Format: S + 6 digit timestamp + 2 digit random = 9 chars max
        $timestamp = substr(time(), -6);
        $random = str_pad(rand(10, 99), 2, '0', STR_PAD_LEFT);
        return 'S' . $timestamp . $random;
    }

    /**
     * Validate pengiriman data completeness
     */
    private function validatePengirimanData($pengiriman)
    {
        $errors = [];

        if (!$pengiriman->opsiPengiriman) {
            $errors[] = 'Data opsi pengiriman tidak ditemukan';
        }
        
        if (!$pengiriman->pengirimPenerima) {
            $errors[] = 'Data pengirim dan penerima tidak ditemukan';
        }
        
        if (!$pengiriman->informasiPembayaran) {
            $errors[] = 'Data pembayaran tidak ditemukan';
        }
        
        if ($pengiriman->barangPengiriman->isEmpty()) {
            $errors[] = 'Data barang pengiriman tidak ditemukan';
        }

        if (!empty($errors)) {
            throw new \Exception('Data pengiriman tidak lengkap: ' . implode(', ', $errors));
        }
    }
}