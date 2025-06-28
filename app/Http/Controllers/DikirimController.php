<?php

namespace App\Http\Controllers;

use App\Models\DetailPengiriman;
use App\Models\BarangPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Picqer\Barcode\BarcodeGeneratorPNG;

class DikirimController extends Controller
{
     /**
     * Display a listing of shipments with 'processed' (shipped) status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dikirim(Request $request)
    {
        $query = DetailPengiriman::where('status', 'processed');

        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_resi', 'like', "%{$search}%")
                  ->orWhere('asal', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhereHas('pengirimPenerima', function($q) use ($search) {
                      $q->where('nama_pengirim', 'like', "%{$search}%");
                  });
            });
        }

        // Apply date range filter if provided
        if ($request->has('tanggal_mulai') && !empty($request->tanggal_mulai)) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }
        if ($request->has('tanggal_akhir') && !empty($request->tanggal_akhir)) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        // Apply asal filter if provided
        if ($request->has('asal') && !empty($request->asal)) {
            $query->where('asal', 'like', "%{$request->asal}%");
        }

        // Apply tujuan filter if provided
        if ($request->has('tujuan') && !empty($request->tujuan)) {
            $query->where('tujuan', 'like', "%{$request->tujuan}%");
        }

        // Apply branch filter if provided
        if ($request->has('branch') && !empty($request->branch)) {
            // Assuming branch is stored in a related model or a field
            // Adjust the logic based on your database structure
            $query->where('branch', 'like', "%{$request->branch}%");
        }

        // Apply nama_pengirim filter if provided
        if ($request->has('nama_pengirim') && !empty($request->nama_pengirim)) {
            $query->whereHas('pengirimPenerima', function($q) use ($request) {
                $q->where('nama_pengirim', 'like', "%{$request->nama_pengirim}%");
            });
        }

        // Apply total price range filter if provided
        if ($request->has('total_min') && !empty($request->total_min)) {
            $query->whereHas('informasiPembayaran', function($q) use ($request) {
                $q->where('total_biaya_pengiriman', '>=', $request->total_min);
            });
        }
        if ($request->has('total_max') && !empty($request->total_max)) {
            $query->whereHas('informasiPembayaran', function($q) use ($request) {
                $q->where('total_biaya_pengiriman', '<=', $request->total_max);
            });
        }

        // Apply metode_bayar filter if provided
        if ($request->has('metode_bayar') && !empty($request->metode_bayar)) {
            $method = ($request->metode_bayar === 'cash') ? 'Cash' : 'MidTrans';
            $query->whereHas('informasiPembayaran', function($q) use ($method) {
                $q->where('metode_pembayaran', $method);
            });
        }

        // Apply tipe filter if provided
        if ($request->has('tipe') && !empty($request->tipe)) {
            $tipe = ($request->tipe === 'publik') ? 'Publik' : 'Draf';
            $query->whereHas('opsiPengiriman', function($q) use ($tipe) {
                $q->where('tipe_pengiriman', $tipe);
            });
        }

        // Get per page value or default to 10
        $perPage = $request->per_page ?? 10;

        // Get the paginated results
        $pengiriman = $query->latest()->paginate($perPage);
        
        // Keep all filters in the pagination links
        $pengiriman->appends($request->all());

        return view('pengiriman.daftar.dikirim', compact('pengiriman'));
    }
     /**
     * Export shipments data based on format and filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $format = $request->format;
        $status = $request->status;

        // Logic for exporting data in different formats (PDF, Excel)
        // This is a placeholder and should be implemented based on your requirements
        
        if ($format === 'pdf') {
            // Generate PDF export
            return response()->download(/* PDF file path */);
        } elseif ($format === 'excel') {
            // Generate Excel export
            return response()->download(/* Excel file path */);
        }

        return redirect()->back()->with('error', 'Format ekspor tidak valid');
    }
}
