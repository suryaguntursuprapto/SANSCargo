@extends('layouts.app')

@section('styles')
<style>
    /* Animations */
    .fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .slide-in { animation: slideIn 0.3s ease-out; }
    @keyframes slideIn {
        from { transform: translateY(-10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    /* Badge styles */
    .badge { @apply px-2.5 py-1 rounded-full text-xs font-medium inline-flex items-center; }
    .badge-green { @apply bg-green-100 text-green-800; }
    .badge-yellow { @apply bg-yellow-100 text-yellow-800; }
    .badge-blue { @apply bg-blue-100 text-blue-800; }
    .badge-gray { @apply bg-gray-100 text-gray-800; }
    
    /* Timeline with improved spacing */
    .timeline-container {
        @apply relative pl-10 pb-6 last:pb-0;
    }
    .timeline-container::before {
        content: '';
        @apply absolute left-3.5 top-4 bottom-0 w-0.5 bg-gray-300;
    }
    .timeline-container:last-child::before {
        @apply h-0;
    }
    .timeline-dot {
        @apply absolute left-1.5 top-1.5 rounded-full h-4 w-4 border-2 z-10;
    }
    .timeline-dot-current {
        @apply border-green-500 bg-white;
    }
    .timeline-dot-completed {
        @apply border-green-500 bg-green-500;
    }
    .timeline-dot-pending {
        @apply border-gray-400 bg-white;
    }
    
    /* Timeline content spacing */
    .timeline-content {
        @apply mb-2 bg-white p-3 rounded-md border border-gray-200;
    }
    .timeline-date {
        @apply text-xs font-medium text-gray-500 mb-2;
    }
    .timeline-title {
        @apply font-medium text-gray-800 mb-2;
    }
    .tracking-info {
        @apply text-sm text-gray-600 mb-2 leading-relaxed;
    }
    
    /* Input search styles */
    .search-input {
        @apply w-full max-w-md rounded-md border border-gray-300 py-2 pl-10 pr-4 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50;
    }
    .search-input-icon {
        @apply absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none;
    }
    
    /* Card styles */
    .info-card {
        @apply bg-white p-4 rounded-lg border border-gray-200 shadow-sm;
    }
    
    /* Section styles */
    .section-title {
        @apply text-base font-semibold text-gray-800 mb-4 flex items-center;
    }
    
    /* Timeline section */
    .timeline-section {
        @apply bg-gray-50 rounded-lg border border-gray-200 p-5;
    }
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen py-6">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
         <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('pengiriman.index') }}" class="flex items-center text-green-600 hover:text-green-800">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
        @if(isset($pengiriman))
        <!-- Tracking Result -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 mb-6 fade-in">
            <!-- Header with Resi Number -->
            <div class="p-5 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <div class="flex items-center mb-3 sm:mb-0">
                        <img src="{{ asset('images/csmcargo.png') }}" alt="Manaje" class="h-8 mr-2">
                        <span class="font-medium text-green-600 text-xl">®</span>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">No. Resi:</div>
                        <div class="font-semibold text-lg">{{ $pengiriman->no_resi }}</div>
                    </div>
                </div>
            </div>

            <div class="p-5">
                <!-- Status Summary -->
                <div class="mb-6">
                    <div class="flex flex-col sm:flex-row justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-1">Status Pengiriman</h2>
                            <div class="flex items-center">
                                <span class="badge {{ $pengiriman->status == 'draft' ? 'badge-yellow' : ($pengiriman->status == 'processed' ? 'badge-green' : 'badge-blue') }} mr-2">
                                    <i class="fas fa-truck mr-1"></i>
                                    @if($pengiriman->status == 'draft')
                                    Draf
                                    @elseif($pengiriman->status == 'processed')
                                    Dalam Perjalanan
                                    @else
                                    {{ $pengiriman->status }}
                                    @endif
                                </span>
                                <span class="text-sm text-gray-500">Diperbarui pada {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Estimasi Tiba</div>
                            <div class="font-semibold">{{ \Carbon\Carbon::now()->addDays(2)->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Route Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Asal</div>
                        <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                            <div class="font-medium">{{ $pengiriman->asal }}</div>
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                                CSM Cargo {{ $pengiriman->asal }}
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Tujuan</div>
                        <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                            <div class="font-medium">{{ $pengiriman->tujuan }}</div>
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt text-green-500 mr-1"></i>
                                {{ $pengiriman->pengirimPenerima->alamat_penerima ?? 'Alamat Penerima' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipment Details -->
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Detail Pengiriman</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-3 text-sm">
                        <div>
                            <div class="text-gray-500">Pengirim</div>
                            <div class="font-medium">{{ $pengiriman->pengirimPenerima->nama_pengirim ?? 'Mawar Melati' }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500">Penerima</div>
                            <div class="font-medium">{{ $pengiriman->pengirimPenerima->nama_penerima ?? 'Putih Abu' }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500">Jenis Layanan</div>
                            <div class="font-medium">{{ $pengiriman->opsiPengiriman->jenis_layanan ?? 'Layanan Instan' }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500">Berat Total</div>
                            <div class="font-medium">0.7 kg</div>
                        </div>
                        <div>
                            <div class="text-gray-500">Jumlah Barang</div>
                            <div class="font-medium">2 item</div>
                        </div>
                        <div>
                            <div class="text-gray-500">Tanggal Pengiriman</div>
                            <div class="font-medium">{{ $pengiriman->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Tracking Timeline -->
                <div class="mb-6">
                    <h3 class="section-title">
                        <i class="fas fa-history text-green-500 mr-2"></i>
                        Riwayat Pengiriman
                    </h3>
                    
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 mb-6 timeline-section">
                        <!-- Timeline Items -->
                        <div class="timeline-container">
                            <div class="timeline-dot timeline-dot-current"></div>
                            <div class="timeline-content">
                                <div class="timeline-date">{{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</div>
                                <div class="timeline-title">Dalam Perjalanan</div>
                                <div class="tracking-info">
                                    Pengiriman sedang dalam perjalanan dari {{ $pengiriman->asal }} ke {{ $pengiriman->tujuan }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="timeline-container">
                            <div class="timeline-dot timeline-dot-completed"></div>
                            <div class="timeline-content">
                                <div class="timeline-date">{{ \Carbon\Carbon::now()->subHours(5)->format('d M Y, H:i') }}</div>
                                <div class="timeline-title">Packing Selesai</div>
                                <div class="tracking-info">
                                    Barang telah dikemas dan siap untuk dikirim
                                </div>
                            </div>
                        </div>
                        
                        <div class="timeline-container">
                            <div class="timeline-dot timeline-dot-completed"></div>
                            <div class="timeline-content">
                                <div class="timeline-date">{{ \Carbon\Carbon::now()->subHours(12)->format('d M Y, H:i') }}</div>
                                <div class="timeline-title">Barang Diterima di Branch {{ $pengiriman->asal }}</div>
                                <div class="tracking-info">
                                    Barang telah diterima oleh kurir di CSM Cargo {{ $pengiriman->asal }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="timeline-container">
                            <div class="timeline-dot timeline-dot-completed"></div>
                            <div class="timeline-content">
                                <div class="timeline-date">{{ \Carbon\Carbon::now()->subDay()->format('d M Y, H:i') }}</div>
                                <div class="timeline-title">Pengiriman Dibuat</div>
                                <div class="tracking-info">
                                    Pengiriman telah dibuat oleh {{ $pengiriman->pengirimPenerima->nama_pengirim ?? 'Mawar Melati' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Timeline -->
                <div class="mb-6">
                    <h3 class="section-title">
                        <i class="fas fa-clock text-gray-500 mr-2"></i>
                        Proses Pengiriman Selanjutnya
                    </h3>
                    
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 mb-6 timeline-section">
                        <div class="timeline-container">
                            <div class="timeline-dot timeline-dot-pending"></div>
                            <div class="timeline-content">
                                <div class="timeline-title text-gray-600">Tiba di Branch {{ $pengiriman->tujuan }}</div>
                                <div class="tracking-info">
                                    Pengiriman akan tiba di CSM Cargo {{ $pengiriman->tujuan }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="timeline-container">
                            <div class="timeline-dot timeline-dot-pending"></div>
                            <div class="timeline-content">
                                <div class="timeline-title text-gray-600">Pengiriman ke Alamat Tujuan</div>
                                <div class="tracking-info">
                                    Kurir akan mengirimkan barang ke alamat penerima
                                </div>
                            </div>
                        </div>
                        
                        <div class="timeline-container">
                            <div class="timeline-dot timeline-dot-pending"></div>
                            <div class="timeline-content">
                                <div class="timeline-title text-gray-600">Diterima oleh Penerima</div>
                                <div class="tracking-info">
                                    Barang akan diterima oleh {{ $pengiriman->pengirimPenerima->nama_penerima ?? 'Putih Abu' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 border-t border-gray-200">
                    <a href="{{ route('pengiriman.show', ['id' => $pengiriman->id]) }}" class="inline-flex items-center justify-center bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-eye mr-2"></i> Detail Pengiriman
                    </a>
                </div>
            </div>
        </div>
        @elseif(isset($resi) && !isset($pengiriman))
        <!-- Not Found State -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 p-6 text-center fade-in">
            <div class="w-16 h-16 bg-red-100 mx-auto rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Nomor Resi Tidak Ditemukan</h3>
            <p class="text-gray-600 mb-4">Maaf, kami tidak dapat menemukan data pengiriman dengan nomor resi <span class="font-medium">{{ $resi }}</span>.</p>
            <div class="text-sm text-gray-500 mb-6">
                Pastikan nomor resi yang Anda masukkan sudah benar atau hubungi layanan pelanggan kami untuk bantuan lebih lanjut.
            </div>
            <a href="{{ route('pengiriman.track') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                <i class="fas fa-search mr-2"></i> Coba Lagi
            </a>
        </div>
        @else
        <!-- Initial State -->
        <div class="info-card text-center py-8 fade-in">
            <img src="{{ asset('images/tracking-illustration.png') }}" alt="Tracking Illustration" class="h-40 mx-auto mb-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Lacak Pengiriman Anda</h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                Masukkan nomor resi pada kolom pencarian di atas untuk melacak status pengiriman Anda secara real-time.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('pengiriman.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-list mr-2"></i> Lihat Semua Pengiriman
                </a>
                <a href="{{ route('pengiriman.opsi.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-plus mr-2"></i> Buat Pengiriman Baru
                </a>
            </div>
        </div>
        
        <!-- How to Track Section -->
        <div class="mt-8">
            <h3 class="text-center text-lg font-semibold text-gray-800 mb-6">Cara Melacak Pengiriman</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-4 rounded-lg border border-gray-200 text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-barcode text-green-500 text-lg"></i>
                    </div>
                    <h4 class="font-medium text-gray-800 mb-2">1. Masukkan Nomor Resi</h4>
                    <p class="text-sm text-gray-600">Masukkan nomor resi yang Anda dapatkan saat membuat pengiriman.</p>
                </div>
                
                <div class="bg-white p-4 rounded-lg border border-gray-200 text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-search text-green-500 text-lg"></i>
                    </div>
                    <h4 class="font-medium text-gray-800 mb-2">2. Lihat Status Pengiriman</h4>
                    <p class="text-sm text-gray-600">Dapatkan informasi real-time tentang status dan lokasi paket Anda.</p>
                </div>
                
                <div class="bg-white p-4 rounded-lg border border-gray-200 text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-box text-green-500 text-lg"></i>
                    </div>
                    <h4 class="font-medium text-gray-800 mb-2">3. Terima Paket Anda</h4>
                    <p class="text-sm text-gray-600">Pantau kapan paket Anda akan tiba di tujuan.</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animations to sections
    const sections = document.querySelectorAll('.info-card, .timeline-container');
    sections.forEach((section, index) => {
        section.classList.add('slide-in');
        section.style.animationDelay = (index * 0.05) + 's';
    });
    
    // Handle form submission
    const trackingForm = document.querySelector('form[action="{{ route("pengiriman.track") }}"]');
    if (trackingForm) {
        trackingForm.addEventListener('submit', function(e) {
            // Only validate the form - do not prevent submission
            const resiInput = this.querySelector('input[name="resi"]');
            if (!resiInput.value.trim()) {
                e.preventDefault();
                resiInput.classList.add('border-red-500');
                resiInput.focus();
                
                // Create and show error message
                const errorMsg = document.createElement('p');
                errorMsg.className = 'text-red-500 text-sm mt-1';
                errorMsg.textContent = 'Masukkan nomor resi untuk melacak pengiriman';
                
                // Remove any existing error message
                const existingError = resiInput.parentNode.querySelector('.text-red-500');
                if (existingError) {
                    existingError.remove();
                }
                
                resiInput.parentNode.appendChild(errorMsg);
            }
        });
    }
});
</script>
@endsection