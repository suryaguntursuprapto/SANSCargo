<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPengiriman;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengirimanController extends Controller
{
    public function index(Request $request)
    {
        $query = DetailPengiriman::with(['barangPengiriman', 'opsiPengiriman', 'pengirimPenerima', 'informasiPembayaran', 'opsiPengiriman.branch'])
            ->select([
                'id', 'no_resi', 'asal', 'tujuan', 'detail_alamat', 'status', 
                'catatan', 'created_at', 'updated_at'
            ])
            ->orderBy('created_at', 'desc');

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_resi', 'like', "%{$search}%")
                  ->orWhere('asal', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhereHas('pengirimPenerima', function($q2) use ($search) {
                      $q2->where('nama_pengirim', 'like', "%{$search}%")
                         ->orWhere('nama_penerima', 'like', "%{$search}%")
                         ->orWhere('telepon_pengirim', 'like', "%{$search}%")
                         ->orWhere('telepon_penerima', 'like', "%{$search}%");
                  });
            });
        }

        // Apply status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Apply kota asal filter
        if ($request->has('kota_asal') && $request->kota_asal) {
            $query->where('asal', 'like', "%{$request->kota_asal}%");
        }

        // Apply kota tujuan filter
        if ($request->has('kota_tujuan') && $request->kota_tujuan) {
            $query->where('tujuan', 'like', "%{$request->kota_tujuan}%");
        }

        // Apply date filter
        if ($request->has('tanggal') && $request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Apply branch filter
        if ($request->has('branch') && $request->branch) {
            $query->whereHas('opsiPengiriman', function($q) use ($request) {
                $q->where('branch_id', $request->branch);
            });
        }

        // Get paginated results
        $pengiriman = $query->paginate(15);

        // Calculate statistics
        $totalPengiriman = DetailPengiriman::count();
        $menungguPickup = DetailPengiriman::where('status', 'Draft')->count();
        $dalamPerjalanan = DetailPengiriman::whereIn('status', ['processed', 'picked_up', 'in_transit'])->count();
        $terkirim = DetailPengiriman::where('status', 'delivered')->count();
        $bermasalah = DetailPengiriman::where('status', 'cancelled')->count();

        // Get filter options
        $kotaAsal = DetailPengiriman::distinct()->pluck('asal')->filter()->sort();
        $kotaTujuan = DetailPengiriman::distinct()->pluck('tujuan')->filter()->sort();
        $branches = Branch::where('status', true)->orderBy('nama_branch')->get();

        return view('admin.pengiriman.index', compact(
            'pengiriman',
            'totalPengiriman',
            'menungguPickup',
            'dalamPerjalanan',
            'terkirim',
            'bermasalah',
            'kotaAsal',
            'kotaTujuan',
            'branches'
        ));
    }

    public function show($id)
    {
        $pengiriman = DetailPengiriman::with([
            'barangPengiriman', 
            'opsiPengiriman.branch', 
            'pengirimPenerima', 
            'informasiPembayaran'
        ])->findOrFail($id);
        
        // Get tracking history
        $trackingHistory = $this->getTrackingHistory($pengiriman);
        
        return view('admin.pengiriman.show', compact('pengiriman', 'trackingHistory'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:Draft,processed,picked_up,in_transit,delivered,cancelled'
            ]);

            $pengiriman = DetailPengiriman::findOrFail($id);
            $oldStatus = $pengiriman->status;
            
            // Update status
            $pengiriman->update([
                'status' => $validated['status'],
                'updated_at' => now()
            ]);

            // Generate resi if changing to processed and no resi exists
            if ($validated['status'] === 'processed' && !$pengiriman->no_resi) {
                $resi = $this->generateResi();
                $pengiriman->update(['no_resi' => $resi]);
            }

            // Log status change if you have a tracking system
            $this->logStatusChange($pengiriman, $oldStatus, $validated['status']);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diupdate',
                'new_status' => $validated['status'],
                'new_resi' => $pengiriman->no_resi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pengiriman = DetailPengiriman::findOrFail($id);
            
            // Check if pengiriman can be deleted (only drafts and cancelled)
            if (in_array($pengiriman->status, ['processed', 'picked_up', 'in_transit', 'delivered'])) {
                return redirect()->back()->with('error', 'Pengiriman yang sudah diproses tidak dapat dihapus');
            }
            
            DB::beginTransaction();
            
            // Delete related records
            $pengiriman->barangPengiriman()->delete();
            $pengiriman->opsiPengiriman()->delete();
            $pengiriman->pengirimPenerima()->delete();
            $pengiriman->informasiPembayaran()->delete();
            
            // Delete pengiriman
            $pengiriman->delete();
            
            DB::commit();

            return redirect()->route('admin.pengiriman.index')
                            ->with('success', 'Pengiriman berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus pengiriman: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $query = DetailPengiriman::with(['barangPengiriman', 'opsiPengiriman', 'pengirimPenerima', 'informasiPembayaran']);

        // Apply same filters as index
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_resi', 'like', "%{$search}%")
                  ->orWhere('asal', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhereHas('pengirimPenerima', function($q2) use ($search) {
                      $q2->where('nama_pengirim', 'like', "%{$search}%")
                         ->orWhere('nama_penerima', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('kota_asal') && $request->kota_asal) {
            $query->where('asal', 'like', "%{$request->kota_asal}%");
        }

        if ($request->has('kota_tujuan') && $request->kota_tujuan) {
            $query->where('tujuan', 'like', "%{$request->kota_tujuan}%");
        }

        if ($request->has('tanggal') && $request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // If specific IDs provided (for bulk export)
        if ($request->has('ids') && is_array($request->ids)) {
            $query->whereIn('id', $request->ids);
        }

        $pengiriman = $query->get();

        // Create CSV
        $filename = 'pengiriman_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($pengiriman) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fputs($file, "\xEF\xBB\xBF");
            
            // Header row
            fputcsv($file, [
                'Nomor Resi',
                'Tanggal',
                'Nama Pengirim',
                'Telepon Pengirim',
                'Alamat Pengirim',
                'Nama Penerima', 
                'Telepon Penerima',
                'Alamat Penerima',
                'Kota Asal',
                'Kota Tujuan',
                'Jenis Layanan',
                'Tipe Pengiriman',
                'Total Berat (kg)',
                'Jumlah Barang',
                'Total Biaya',
                'Status',
                'Dibuat',
                'Terakhir Update'
            ]);

            // Data rows
            foreach ($pengiriman as $item) {
                $totalBerat = $item->barangPengiriman->sum('berat_barang');
                $jumlahBarang = $item->barangPengiriman->count();
                $totalBiaya = $item->informasiPembayaran->total_biaya_pengiriman ?? 0;
                
                fputcsv($file, [
                    $item->no_resi ?: 'N/A',
                    $item->created_at->format('d/m/Y H:i'),
                    $item->pengirimPenerima->nama_pengirim ?? 'N/A',
                    $item->pengirimPenerima->telepon_pengirim ?? 'N/A',
                    $item->pengirimPenerima->alamat_pengirim ?? 'N/A',
                    $item->pengirimPenerima->nama_penerima ?? 'N/A',
                    $item->pengirimPenerima->telepon_penerima ?? 'N/A',
                    $item->pengirimPenerima->alamat_penerima ?? 'N/A',
                    $item->asal,
                    $item->tujuan,
                    $item->opsiPengiriman->jenis_layanan ?? 'N/A',
                    $item->opsiPengiriman->tipe_pengiriman ?? 'N/A',
                    $totalBerat,
                    $jumlahBarang,
                    $totalBiaya,
                    $this->getStatusLabel($item->status),
                    $item->created_at->format('d/m/Y H:i'),
                    $item->updated_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function bulkUpdateStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:detail_pengiriman,id',
                'status' => 'required|in:Draft,processed,picked_up,in_transit,delivered,cancelled'
            ]);

            DB::beginTransaction();

            $updated = 0;
                            foreach ($validated['ids'] as $id) {
                $pengiriman = DetailPengiriman::find($id);
                if ($pengiriman) {
                    // Generate resi if changing to processed and no resi exists
                    if ($validated['status'] === 'processed' && !$pengiriman->no_resi) {
                        $resi = $this->generateResi();
                        $pengiriman->update(['no_resi' => $resi]);
                    }
                    
                    $pengiriman->update([
                        'status' => $validated['status'],
                        'updated_at' => now()
                    ]);
                    $updated++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Status {$updated} pengiriman berhasil diupdate",
                'updated_count' => $updated
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'total_hari_ini' => DetailPengiriman::whereDate('created_at', $today)->count(),
            'total_bulan_ini' => DetailPengiriman::where('created_at', '>=', $thisMonth)->count(),
            'pending_pickup' => DetailPengiriman::where('status', 'Draft')->count(),
            'dalam_perjalanan' => DetailPengiriman::whereIn('status', ['processed', 'picked_up', 'in_transit'])->count(),
            'total_pendapatan_bulan_ini' => DetailPengiriman::where('created_at', '>=', $thisMonth)
                                                      ->where('status', 'delivered')
                                                      ->whereHas('informasiPembayaran')
                                                      ->with('informasiPembayaran')
                                                      ->get()
                                                      ->sum(function($item) {
                                                          return $item->informasiPembayaran->total_biaya_pengiriman ?? 0;
                                                      }),
            'pengiriman_bermasalah' => DetailPengiriman::where('status', 'cancelled')->count()
        ];

        return response()->json($stats);
    }

    private function getTrackingHistory($pengiriman)
    {
        $history = [];
        
        $history[] = [
            'status' => 'Pengiriman dibuat',
            'description' => 'Pengiriman berhasil dibuat dan menunggu konfirmasi',
            'timestamp' => $pengiriman->created_at,
            'location' => $pengiriman->asal
        ];

        if (in_array($pengiriman->status, ['processed', 'picked_up', 'in_transit', 'delivered'])) {
            $history[] = [
                'status' => 'Pengiriman diproses',
                'description' => 'Pengiriman telah diproses dan siap untuk pickup',
                'timestamp' => $pengiriman->updated_at->subHours(rand(1, 8)),
                'location' => $pengiriman->asal
            ];
        }

        if (in_array($pengiriman->status, ['picked_up', 'in_transit', 'delivered'])) {
            $history[] = [
                'status' => 'Paket dijemput',
                'description' => 'Paket telah dijemput dari alamat pengirim',
                'timestamp' => $pengiriman->updated_at->subHours(rand(1, 6)),
                'location' => $pengiriman->asal
            ];
        }

        if (in_array($pengiriman->status, ['in_transit', 'delivered'])) {
            $history[] = [
                'status' => 'Dalam perjalanan',
                'description' => 'Paket sedang dalam perjalanan menuju tujuan',
                'timestamp' => $pengiriman->updated_at->subHours(rand(1, 4)),
                'location' => 'Dalam perjalanan'
            ];
        }

        if ($pengiriman->status === 'delivered') {
            $history[] = [
                'status' => 'Terkirim',
                'description' => 'Paket telah diterima oleh penerima',
                'timestamp' => $pengiriman->updated_at,
                'location' => $pengiriman->tujuan
            ];
        }

        return collect($history)->sortBy('timestamp');
    }

    private function logStatusChange($pengiriman, $oldStatus, $newStatus)
    {
        // If you have a tracking/log table, insert the status change here
        // For example:
        // TrackingLog::create([
        //     'pengiriman_id' => $pengiriman->id,
        //     'old_status' => $oldStatus,
        //     'new_status' => $newStatus,
        //     'changed_by' => auth()->id(),
        //     'changed_at' => now()
        // ]);
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'Draft' => 'Draft',
            'processed' => 'Diproses',
            'picked_up' => 'Dijemput',
            'in_transit' => 'Dalam Perjalanan',
            'delivered' => 'Terkirim',
            'cancelled' => 'Dibatalkan'
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Generate unique resi number
     */
    private function generateResi()
    {
        do {
            // Generate resi with pattern: CCM + YYYYMMDD + 4 digit random
            $resi = 'CCM' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (DetailPengiriman::where('no_resi', $resi)->exists());

        return $resi;
    }
}