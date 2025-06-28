<?php

namespace App\Http\Controllers;

use App\Models\PengirimanImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Jobs\ProcessPengirimanImport;

class PengirimanImportController extends Controller
{
    /**
     * Display the import shipment form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all imports with pagination
        $imports = PengirimanImport::orderBy('created_at', 'desc')->paginate(10);
        
        // Also retrieve draft imports to display them
        $draftImports = PengirimanImport::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pengiriman.import.index', compact('imports', 'draftImports'));
    }
    
    /**
     * Display the form to create a new import.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all draft imports (status = pending)
        $draftImports = PengirimanImport::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get the most recent active import (if any)
        $activeImport = PengirimanImport::whereIn('status', ['processing'])
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Get recent imports for the history section
        $recentImports = PengirimanImport::whereIn('status', ['processed', 'failed'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('pengiriman.import.create', compact('draftImports', 'activeImport', 'recentImports'));
    }

    /**
     * Store an uploaded file and create an import record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'file' => 'required|file|mimes:doc,docx,pdf,xls,xlsx,csv|max:25600', // 25MB max
        ]);

        // Check if file exists
        if (!$request->hasFile('file')) {
            return redirect()->back()
                ->with('error', 'Tidak ada file yang diunggah');
        }

        $file = $request->file('file');
        
        // Generate a unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        // Store the file in the storage/app/pengiriman-imports directory
        $path = $file->storeAs('pengiriman-imports', $filename);
        
        if (!$path) {
            return redirect()->back()
                ->with('error', 'Gagal mengunggah file. Silakan coba lagi');
        }
        
        // Create a new import record
        $import = new PengirimanImport([
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'file_type' => $file->getClientMimeType(),
            'status' => 'pending',
        ]);
        
        $import->save();
        
        // Redirect to a success page
        if ($request->has('save_draft')) {
            return redirect()->back()
                ->with('success', 'File berhasil diunggah sebagai draft')
                ->with('showSuccessModal', true)
                ->with('successMessage', 'File berhasil disimpan sebagai draft.')
                ->with('importId', $import->id);
        }
        
        // Update status to processing
        $import->status = 'processing';
        $import->save();
        
        // Dispatch the job to process the import
        dispatch(new ProcessPengirimanImport($import));
        
        return redirect()->back()
            ->with('success', 'File berhasil diunggah dan sedang diproses')
            ->with('showSuccessModal', true)
            ->with('successMessage', 'File Anda telah berhasil diunggah dan sedang diproses.')
            ->with('importId', $import->id);
    }

    /**
     * Show the import details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $import = PengirimanImport::findOrFail($id);
        
        return view('pengiriman.import.show', compact('import'));
    }

    /**
     * Show the draft import details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function draft($id)
    {
        $import = PengirimanImport::findOrFail($id);
        
        return view('pengiriman.import.draft', compact('import'));
    }

    /**
     * Process a draft import.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process($id)
    {
        $import = PengirimanImport::findOrFail($id);
        
        if ($import->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Import ini sudah diproses');
        }
        
        // Update status to processing so we can show it immediately
        $import->status = 'processing';
        $import->save();
        
        // Dispatch the job to process the import
        dispatch(new ProcessPengirimanImport($import));
        
        return redirect()->route('pengiriman.import.show', $import->id)
            ->with('success', 'Import sedang diproses');
    }
    
    /**
     * Check import status via AJAX.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatus($id)
    {
        $import = PengirimanImport::findOrFail($id);
        
        // Check if processing has timed out
        if ($import->status === 'processing') {
            $startedAt = $import->processing_started_at;
            
            // If processing has been going on for more than 10 minutes, mark as failed
            if ($startedAt && $startedAt->diffInMinutes(now()) > 10) {
                $import->status = 'failed';
                $import->error_message = 'Proses impor timeout. Waktu pemrosesan melebihi batas maksimum (10 menit).';
                $import->save();
            }
        }
        
        return response()->json([
            'status' => $import->status,
            'processed_at' => $import->processed_at ? $import->processed_at->format('d M Y, H:i') : null,
            'total_records' => $import->total_records,
            'successful_records' => $import->successful_records,
            'failed_records' => $import->failed_records,
            'error_message' => $import->error_message,
            'progress_percentage' => $import->progress_percentage ?? ($import->status === 'processing' ? 50 : 0),
            'estimated_completion_at' => $import->estimated_completion_at ? $import->estimated_completion_at->format('d M Y, H:i:s') : null,
            'remaining_time' => $import->remaining_time,
            'formatted_remaining_time' => $import->formatted_remaining_time,
        ]);
    }

    /**
     * Download the template file.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadTemplate()
    {
        $path = public_path('templates/pengiriman-import-template.docx');
        
        return response()->download($path, 'Template Import Pengiriman.docx');
    }

    /**
     * Cancel an import.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($id)
    {
        $import = PengirimanImport::findOrFail($id);
        
        if ($import->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Import ini tidak dapat dibatalkan');
        }
        
        // Delete the file
        Storage::delete($import->file_path);
        
        // Delete the import record
        $import->delete();
        
        return redirect()->route('pengiriman.import.create')
            ->with('success', 'Import berhasil dibatalkan');
    }
}