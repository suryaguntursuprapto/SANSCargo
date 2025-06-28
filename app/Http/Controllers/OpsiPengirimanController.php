<?php

namespace App\Http\Controllers;

use App\Models\DetailPengiriman;
use App\Models\OpsiPengiriman;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OpsiPengirimanController extends Controller
{
    /**
     * Show the form for creating opsi pengiriman
     */
    public function create($id = null)
    {
        try {
            // Get active branches for dropdown
            $branches = Branch::where('status', true)
                             ->orderBy('nama_branch', 'asc')
                             ->get();

            // If no active branches, create default ones
            if ($branches->isEmpty()) {
                $this->createDefaultBranches();
                $branches = Branch::where('status', true)
                                 ->orderBy('nama_branch', 'asc')
                                 ->get();
            }

            $pengiriman = null;
            if ($id) {
                $pengiriman = DetailPengiriman::with('opsiPengiriman')->find($id);
                if (!$pengiriman) {
                    return redirect()->route('pengiriman.index')
                        ->with('error', 'Data pengiriman tidak ditemukan.');
                }
            }

            return view('pengiriman.manual.opsi', compact('pengiriman', 'branches'));

        } catch (\Exception $e) {
            Log::error('Error in OpsiPengirimanController@create: ' . $e->getMessage());
            return redirect()->route('pengiriman.index')
                ->with('error', 'Terjadi kesalahan saat memuat form opsi pengiriman.');
        }
    }

    /**
     * Store opsi pengiriman
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'pengiriman_id' => 'nullable|exists:detail_pengiriman,id',
                'tipe_pengiriman' => 'required|in:Dijemput,Diantar',
                'jenis_layanan' => 'required|in:Regular,Express,Same Day',
                'asuransi' => 'nullable|boolean',
                'packing_tambahan' => 'nullable|boolean',
                'branch_id' => 'required|exists:branches,id'
            ], [
                'tipe_pengiriman.required' => 'Pilih tipe pengiriman.',
                'tipe_pengiriman.in' => 'Tipe pengiriman tidak valid.',
                'jenis_layanan.required' => 'Pilih jenis layanan.',
                'jenis_layanan.in' => 'Jenis layanan tidak valid.',
                'branch_id.required' => 'Pilih branch.',
                'branch_id.exists' => 'Branch yang dipilih tidak valid.'
            ]);

            DB::beginTransaction();

            // Convert checkbox values
            $validated['asuransi'] = $request->has('asuransi') ? 1 : 0;
            $validated['packing_tambahan'] = $request->has('packing_tambahan') ? 1 : 0;

            // Check if pengiriman_id exists in validated data
            $pengirimanId = $validated['pengiriman_id'] ?? null;

            // Get or create pengiriman
            if ($pengirimanId) {
                $pengiriman = DetailPengiriman::find($pengirimanId);
                if (!$pengiriman) {
                    throw new \Exception('Data pengiriman tidak ditemukan.');
                }
            } else {
                // Create new pengiriman (no resi yet, will be generated at the end)
                $pengiriman = DetailPengiriman::create([
                    'no_resi' => null,
                    'status' => 'Draft'
                ]);
            }

            // Update or create opsi pengiriman
            $opsi = OpsiPengiriman::updateOrCreate(
                ['pengiriman_id' => $pengiriman->id],
                [
                    'tipe_pengiriman' => $validated['tipe_pengiriman'],
                    'jenis_layanan' => $validated['jenis_layanan'],
                    'asuransi' => $validated['asuransi'],
                    'packing_tambahan' => $validated['packing_tambahan'],
                    'branch_id' => $validated['branch_id']
                ]
            );

            DB::commit();

            // Check if this is a draft save
            if ($request->has('draft')) {
                return redirect()->route('pengiriman.index')
                    ->with('success', 'Draft pengiriman berhasil disimpan.');
            }

            return redirect()->route('pengiriman.pengirim-penerima.create', $pengiriman->id)
                ->with('success', 'Opsi pengiriman berhasil disimpan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Ada kesalahan dalam pengisian form.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in OpsiPengirimanController@store: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan opsi pengiriman: ' . $e->getMessage());
        }
    }

    /**
     * Save as draft
     */
    public function saveDraft(Request $request)
    {
        try {
            $validated = $request->validate([
                'pengiriman_id' => 'nullable|exists:detail_pengiriman,id',
                'tipe_pengiriman' => 'nullable|in:Dijemput,Diantar',
                'jenis_layanan' => 'nullable|in:Regular,Express,Same Day',
                'asuransi' => 'nullable|boolean',
                'packing_tambahan' => 'nullable|boolean',
                'branch_id' => 'nullable|exists:branches,id'
            ]);

            DB::beginTransaction();

            // Convert checkbox values
            $validated['asuransi'] = $request->has('asuransi') ? 1 : 0;
            $validated['packing_tambahan'] = $request->has('packing_tambahan') ? 1 : 0;

            // Check if pengiriman_id exists in validated data
            $pengirimanId = $validated['pengiriman_id'] ?? null;

            // Get or create pengiriman
            if ($pengirimanId) {
                $pengiriman = DetailPengiriman::find($pengirimanId);
                if (!$pengiriman) {
                    throw new \Exception('Data pengiriman tidak ditemukan.');
                }
            } else {
                // Create new pengiriman as draft
                $pengiriman = DetailPengiriman::create([
                    'no_resi' => null, // No resi for draft
                    'status' => 'Draft'
                ]);
            }

            // Save opsi pengiriman (allow partial data for draft)
            $opsiData = array_filter([
                'tipe_pengiriman' => $validated['tipe_pengiriman'] ?? null,
                'jenis_layanan' => $validated['jenis_layanan'] ?? null,
                'asuransi' => $validated['asuransi'],
                'packing_tambahan' => $validated['packing_tambahan'],
                'branch_id' => $validated['branch_id'] ?? null
            ], function($value) {
                return $value !== null && $value !== '';
            });

            if (!empty($opsiData)) {
                OpsiPengiriman::updateOrCreate(
                    ['pengiriman_id' => $pengiriman->id],
                    $opsiData
                );
            }

            DB::commit();

            return redirect()->route('pengiriman.index')
                ->with('success', 'Draft pengiriman berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in OpsiPengirimanController@saveDraft: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan draft: ' . $e->getMessage());
        }
    }

    /**
     * Create default branches if none exist
     */
    private function createDefaultBranches()
    {
        try {
            $defaultBranches = [
                [
                    'kode_branch' => 'BRYGY001',
                    'nama_branch' => 'CCM Cargo Yogyakarta',
                    'alamat' => 'Jl. Malioboro No. 123, Yogyakarta',
                    'kota' => 'Yogyakarta',
                    'provinsi' => 'Daerah Istimewa Yogyakarta',
                    'kode_pos' => '55213',
                    'telepon' => '0274-123456',
                    'email' => 'yogyakarta@ccmcargo.com',
                    'status' => true,
                ],
                [
                    'kode_branch' => 'BRJKT001',
                    'nama_branch' => 'CCM Cargo Jakarta',
                    'alamat' => 'Jl. Sudirman No. 456, Jakarta',
                    'kota' => 'Jakarta',
                    'provinsi' => 'DKI Jakarta',
                    'kode_pos' => '10220',
                    'telepon' => '021-123456',
                    'email' => 'jakarta@ccmcargo.com',
                    'status' => true,
                ],
            ];

            foreach ($defaultBranches as $branch) {
                Branch::firstOrCreate(
                    ['kode_branch' => $branch['kode_branch']],
                    $branch
                );
            }

        } catch (\Exception $e) {
            Log::error('Error creating default branches: ' . $e->getMessage());
        }
    }
}