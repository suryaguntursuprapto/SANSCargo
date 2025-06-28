<?php

namespace App\Http\Controllers;

use App\Models\DetailPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    /**
     * Display the landing page
     */
    public function index()
    {
        // If user is already logged in, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Statistics for landing page
        $stats = [
            'total_shipments' => DetailPengiriman::count(),
            'active_shipments' => DetailPengiriman::whereIn('status', ['processed', 'shipped'])->count(),
            'cities_covered' => 100, // Static for now, could be dynamic
            'satisfaction_rate' => 99.8 // Static for now, could be calculated
        ];

        return view('landing.index', compact('stats'));
    }

    /**
     * Handle tracking request from landing page
     */
    public function track(Request $request)
    {
        $resi = $request->input('resi');
        
        if (!$resi) {
            return view('landing.track');
        }

        // Find pengiriman by resi number
        $pengiriman = DetailPengiriman::with(['pengirimPenerima', 'opsiPengiriman'])
            ->where('no_resi', $resi)
            ->first();

        return view('landing.track', [
            'resi' => $resi,
            'pengiriman' => $pengiriman,
            'error' => $pengiriman ? null : 'Nomor resi tidak ditemukan'
        ]);
    }

    /**
     * Show pricing/calculator page
     */
    public function pricing()
    {
        return view('landing.pricing');
    }

    /**
     * Show about page
     */
    public function about()
    {
        return view('landing.about');
    }

    /**
     * Show contact page
     */
    public function contact()
    {
        return view('landing.contact');
    }

    /**
     * Handle contact form submission
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000'
        ]);

        // TODO: Send email or save to database
        // For now, just return with success message

        return redirect()->route('landing.contact')
            ->with('success', 'Pesan Anda telah terkirim. Tim kami akan menghubungi Anda segera.');
    }

    /**
     * Show FAQ page
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'Bagaimana cara melacak paket saya?',
                'answer' => 'Anda dapat melacak paket dengan memasukkan nomor resi di halaman tracking atau login ke akun Anda untuk melihat semua pengiriman.'
            ],
            [
                'question' => 'Berapa lama waktu pengiriman?',
                'answer' => 'Waktu pengiriman bervariasi: Regular 2-3 hari kerja, Express 1 hari kerja, Same Day untuk area tertentu.'
            ],
            [
                'question' => 'Apakah ada asuransi untuk paket?',
                'answer' => 'Ya, semua paket dilindungi asuransi dasar gratis. Anda juga dapat menambah asuransi premium untuk nilai barang yang lebih tinggi.'
            ],
            [
                'question' => 'Bagaimana cara menghitung ongkos kirim?',
                'answer' => 'Ongkos kirim dihitung berdasarkan berat, dimensi, jarak, dan jenis layanan. Gunakan kalkulator ongkir untuk estimasi.'
            ],
            [
                'question' => 'Apakah bisa pickup di tempat?',
                'answer' => 'Ya, kami menyediakan layanan pickup untuk area tertentu. Silakan pilih opsi "Dijemput" saat membuat pesanan.'
            ]
        ];

        return view('landing.faq', compact('faqs'));
    }
}