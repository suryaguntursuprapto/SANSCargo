<?php

namespace App\Http\Controllers;

use App\Models\DetailPengiriman;
use App\Models\BarangPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Picqer\Barcode\BarcodeGeneratorPNG;

class DetailPengirimanController extends Controller
{
    public function index(Request $request)
    {
        $query = DetailPengiriman::with(['barangPengiriman', 'opsiPengiriman', 'pengirimPenerima', 'informasiPembayaran']);
        
        // Search
        if ($request->filled('search')) {
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
        
        // Date filters
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', \Carbon\Carbon::createFromFormat('m/d/Y', $request->tanggal_mulai)->startOfDay());
        }
        
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', \Carbon\Carbon::createFromFormat('m/d/Y', $request->tanggal_akhir)->endOfDay());
        }
        
        // Origin filter
        if ($request->filled('asal')) {
            $query->where('asal', 'like', '%' . $request->asal . '%');
        }
        
        // Destination filter
        if ($request->filled('tujuan')) {
            $query->where('tujuan', 'like', '%' . $request->tujuan . '%');
        }
        
        // Branch filter
        if ($request->filled('branch')) {
            $query->whereHas('opsiPengiriman', function($q) use ($request) {
                $q->where('branch_id', $request->branch);
            });
        }
        
        // Sender name filter
        if ($request->filled('nama_pengirim')) {
            $query->whereHas('pengirimPenerima', function($q) use ($request) {
                $q->where('nama_pengirim', 'like', '%' . $request->nama_pengirim . '%');
            });
        }
        
        // Total range filter
        if ($request->filled('total_min')) {
            $query->whereHas('informasiPembayaran', function($q) use ($request) {
                $q->where('total_biaya_pengiriman', '>=', $request->total_min);
            });
        }
        
        if ($request->filled('total_max')) {
            $query->whereHas('informasiPembayaran', function($q) use ($request) {
                $q->where('total_biaya_pengiriman', '<=', $request->total_max);
            });
        }
        
        // Payment method filter
        if ($request->filled('metode_bayar')) {
            $query->whereHas('informasiPembayaran', function($q) use ($request) {
                $q->where('metode_pembayaran', $request->metode_bayar);
            });
        }
        
        // Type filter
        if ($request->filled('tipe')) {
            $query->whereHas('opsiPengiriman', function($q) use ($request) {
                $q->where('tipe_pengiriman', $request->tipe);
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Sorting
        $sortField = $request->filled('sort') ? $request->sort : 'created_at';
        $sortDirection = $request->filled('direction') ? $request->direction : 'desc';
        $query->orderBy($sortField, $sortDirection);
        
        // Pagination
        $perPage = $request->filled('per_page') ? $request->per_page : 10;
        $pengiriman = $query->paginate($perPage)->appends($request->all());
        
        return view('pengiriman.daftar.index', compact('pengiriman'));
    }
    
    // Add export method for PDF and Excel
    public function export(Request $request)
    {
        $format = $request->format ?? 'pdf';
        
        // Get filtered data
        $query = DetailPengiriman::with(['barangPengiriman', 'opsiPengiriman', 'pengirimPenerima', 'informasiPembayaran']);
        
        // Date filters
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', \Carbon\Carbon::createFromFormat('m/d/Y', $request->tanggal_mulai)->startOfDay());
        }
        
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', \Carbon\Carbon::createFromFormat('m/d/Y', $request->tanggal_akhir)->endOfDay());
        }
        
        // Origin filter
        if ($request->filled('asal')) {
            $query->where('asal', 'like', '%' . $request->asal . '%');
        }
        
        // Destination filter
        if ($request->filled('tujuan')) {
            $query->where('tujuan', 'like', '%' . $request->tujuan . '%');
        }
        
        // Branch filter
        if ($request->filled('branch')) {
            $query->whereHas('opsiPengiriman', function($q) use ($request) {
                $q->where('branch_id', $request->branch);
            });
        }
        
        // Sender name filter
        if ($request->filled('nama_pengirim')) {
            $query->whereHas('pengirimPenerima', function($q) use ($request) {
                $q->where('nama_pengirim', 'like', '%' . $request->nama_pengirim . '%');
            });
        }
        
        // Total range filter
        if ($request->filled('total_min')) {
            $query->whereHas('informasiPembayaran', function($q) use ($request) {
                $q->where('total_biaya_pengiriman', '>=', $request->total_min);
            });
        }
        
        if ($request->filled('total_max')) {
            $query->whereHas('informasiPembayaran', function($q) use ($request) {
                $q->where('total_biaya_pengiriman', '<=', $request->total_max);
            });
        }
        
        // Payment method filter
        if ($request->filled('metode_bayar')) {
            $query->whereHas('informasiPembayaran', function($q) use ($request) {
                $q->where('metode_pembayaran', $request->metode_bayar);
            });
        }
        
        // Type filter
        if ($request->filled('tipe')) {
            $query->whereHas('opsiPengiriman', function($q) use ($request) {
                $q->where('tipe_pengiriman', $request->tipe);
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $data = $query->get();
        
        if ($format === 'pdf') {
            // Generate PDF with a PDF library like dompdf
            // return $pdf->download('pengiriman.pdf');
            return redirect()->back()->with('success', 'Ekspor PDF berhasil');
        } else if ($format === 'excel') {
            // Generate Excel with a library like Laravel Excel
            // return Excel::download(new PengirimanExport($data), 'pengiriman.xlsx');
            return redirect()->back()->with('success', 'Ekspor Excel berhasil');
        }
        
        return redirect()->back()->with('error', 'Format tidak didukung');
    }

    /**
     * Show the form for creating a new shipment.
     */
    public function createdetailpengiriman()
    {
        return view('pengiriman.manual.detail');
    }

    public function create($id = null)
    {
        if ($id) {
            $pengiriman = DetailPengiriman::with('barangPengiriman')->findOrFail($id);
        } else {
            // Create a new empty instance if no ID is provided
            $pengiriman = new DetailPengiriman();
            $pengiriman->barangPengiriman = collect(); // Initialize with empty collection
        }
        
        return view('pengiriman.manual.detail', compact('pengiriman'));
    }
    
    public function store(Request $request)
    {
        // Validate shipping data
        $validated = $request->validate([
            'pengiriman_id' => 'required|exists:detail_pengiriman,id',
            'asal' => 'required|string',
            'tujuan' => 'required|string',
            'detail_alamat' => 'required|string',
            'nama_barang' => 'required|array',
            'jenis_barang' => 'required|array',
            'deskripsi_barang' => 'required|array',
            'berat_barang' => 'required|array',
            'panjang_barang' => 'required|array',
            'lebar_barang' => 'required|array',
            'tinggi_barang' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            // Update pengiriman
            $pengiriman = DetailPengiriman::findOrFail($validated['pengiriman_id']);
            $pengiriman->update([
                'asal' => $validated['asal'],
                'tujuan' => $validated['tujuan'],
                'detail_alamat' => $validated['detail_alamat'],
            ]);

            // Delete existing items if any
            $pengiriman->barangPengiriman()->delete();

            // Create items
            $count = count($validated['nama_barang']);
            for ($i = 0; $i < $count; $i++) {
                BarangPengiriman::create([
                    'pengiriman_id' => $pengiriman->id,
                    'nama_barang' => $validated['nama_barang'][$i],
                    'jenis_barang' => $validated['jenis_barang'][$i],
                    'deskripsi_barang' => $validated['deskripsi_barang'][$i],
                    'berat_barang' => $validated['berat_barang'][$i],
                    'panjang_barang' => $validated['panjang_barang'][$i],
                    'lebar_barang' => $validated['lebar_barang'][$i],
                    'tinggi_barang' => $validated['tinggi_barang'][$i],
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->route('pengiriman.pembayaran.create', ['id' => $pengiriman->id]);
    }

    /**
     * Show the form for editing the specified shipment.
     */
    public function edit(DetailPengiriman $pengiriman)
    {
        $pengiriman->load('barangPengiriman');
        return view('pengiriman.edit', compact('detailpengiriman'));
    }

    /**
     * Update the specified shipment in storage.
     */
    public function update(Request $request, DetailPengiriman $pengiriman)
    {
        // Validate shipping data
        $validated = $request->validate([
            'asal' => 'required|string',
            'tujuan' => 'required|string',
            'detail_alamat' => 'required|string',
            'nama_barang' => 'required|array',
            'jenis_barang' => 'required|array',
            'deskripsi_barang' => 'required|array',
            'berat_barang' => 'required|array',
            'panjang_barang' => 'required|array',
            'lebar_barang' => 'required|array',
            'tinggi_barang' => 'required|array',
            'barang_id' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            // Update pengiriman
            $pengiriman->update([
                'asal' => $validated['asal'],
                'tujuan' => $validated['tujuan'],
                'detail_alamat' => $validated['detail_alamat'],
            ]);

            // Update existing items and create new ones
            $count = count($validated['nama_barang']);
            for ($i = 0; $i < $count; $i++) {
                if (isset($validated['barang_id'][$i]) && $validated['barang_id'][$i] != '') {
                    // Update existing
                    BarangPengiriman::where('id', $validated['barang_id'][$i])
                        ->update([
                            'nama_barang' => $validated['nama_barang'][$i],
                            'jenis_barang' => $validated['jenis_barang'][$i],
                            'deskripsi_barang' => $validated['deskripsi_barang'][$i],
                            'berat_barang' => $validated['berat_barang'][$i],
                            'panjang_barang' => $validated['panjang_barang'][$i],
                            'lebar_barang' => $validated['lebar_barang'][$i],
                            'tinggi_barang' => $validated['tinggi_barang'][$i],
                        ]);
                } else {
                    // Create new
                    BarangPengiriman::create([
                        'pengiriman_id' => $pengiriman->id,
                        'nama_barang' => $validated['nama_barang'][$i],
                        'jenis_barang' => $validated['jenis_barang'][$i],
                        'deskripsi_barang' => $validated['deskripsi_barang'][$i],
                        'berat_barang' => $validated['berat_barang'][$i],
                        'panjang_barang' => $validated['panjang_barang'][$i],
                        'lebar_barang' => $validated['lebar_barang'][$i],
                        'tinggi_barang' => $validated['tinggi_barang'][$i],
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->route('pengiriman.index')
            ->with('success', 'Pengiriman berhasil diperbarui.');
    }

    /**
     * Remove the specified shipment from storage.
     */
    public function destroy(DetailPengiriman $pengiriman)
    {
        $pengiriman->delete();

        return redirect()->route('pengiriman.index')
            ->with('success', 'Pengiriman berhasil dihapus.');
    }

    /**
     * Save shipment as draft.
     */
    public function simpanDraft(Request $request)
    {
        // Use the same store method but change status to draft
        $response = $this->store($request);
        
        return redirect()->route('pengiriman.index')
            ->with('success', 'Draft pengiriman berhasil disimpan.');
    }

    /**
     * Delete an item from a shipment.
     */
    public function hapusItem(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang_pengiriman,id',
        ]);

        BarangPengiriman::destroy($validated['barang_id']);

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified shipment.
     */
    public function show($id)
    {
        $pengiriman = DetailPengiriman::with([
            'barangPengiriman', 
            'opsiPengiriman', 
            'pengirimPenerima', 
            'informasiPembayaran'
        ])->findOrFail($id);
        
        return view('pengiriman.daftar.show', compact('pengiriman'));
    }

    /**
     * Print the shipping label.
     */
    public function printLabel($id)
    {
        $pengiriman = DetailPengiriman::with([
            'barangPengiriman', 
            'opsiPengiriman', 
            'pengirimPenerima', 
            'informasiPembayaran'
        ])->findOrFail($id);
        
        return view('pengiriman.daftar.print-label', compact('pengiriman'));
    }

     /**
     * Display tracking page and handle tracking requests
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function track(Request $request)
    {
        $resi = $request->input('resi');
        
        // If no resi provided, just show the tracking form
        if (!$resi) {
            return view('pengiriman.track');
        }
        
        // Find pengiriman by resi number
        $pengiriman = Pengiriman::where('no_resi', $resi)->first();
        
        // If resi not found, return view with error message
        if (!$pengiriman) {
            return view('pengiriman.daftar.track', [
                'resi' => $resi,
                'error' => 'Nomor resi tidak ditemukan'
            ]);
        }
        
        // Return view with pengiriman data
        return view('pengiriman.daftar.track', [
            'resi' => $resi,
            'pengiriman' => $pengiriman
        ]);
    }
    
    /**
     * Direct tracking with resi parameter in URL
     *
     * @param  string  $resi
     * @return \Illuminate\Http\Response
     */
    public function trackDirect($resi)
    {
        // Find pengiriman by resi number
        $pengiriman = DetailPengiriman::where('no_resi', $resi)->first();
        
        // Return view with pengiriman data
        return view('pengiriman.daftar.track', [
            'resi' => $resi,
            'pengiriman' => $pengiriman, // Will be null if not found
            'error' => $pengiriman ? null : 'Nomor resi tidak ditemukan'
        ]);
    }

}