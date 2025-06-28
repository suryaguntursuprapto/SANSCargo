@extends('layouts.app')

@section('styles')
<style>
    .barcode {
        float: right;
        height: 80px;
    }
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .status-badge.draft {
        background-color: #f9e8a0;
        color: #92400e;
    }
    .status-badge.processed {
        background-color: #c6f6d5;
        color: #276749;
    }
    .status-badge.received {
        background-color: #c3ddfd;
        color: #2a4365;
    }
    .notification {
        position: fixed;
        top: 1rem;
        right: 1rem;
        left: 1rem;
        z-index: 50;
        animation: slideDown 0.3s ease-out forwards;
    }
    @keyframes slideDown {
        from { transform: translateY(-100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .table-container {
        border-radius: 0.375rem;
        overflow: hidden;
    }
    .table-container table {
        width: 100%;
        border-collapse: collapse;
    }
    .table-container th {
        background-color: #f9fafb;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #6b7280;
        padding: 0.75rem 1rem;
    }
    .table-container td {
        padding: 0.75rem 1rem;
        border-top: 1px solid #e5e7eb;
    }
    .info-section {
        margin-bottom: 2rem;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    .info-item {
        margin-bottom: 0.75rem;
    }
    .info-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }
    .info-value {
        font-size: 0.875rem;
        color: #111827;
    }
    .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        border-radius: 0.375rem;
        transition: all 0.2s;
    }
    .action-button.primary {
        background-color: #4caf50;
        color: white;
    }
    .action-button.primary:hover {
        background-color: #43a047;
    }
    .action-button i {
        margin-right: 0.5rem;
    }
    .print-modal {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
    }
    .print-modal-content {
        background-color: white;
        border-radius: 0.5rem;
        width: 100%;
        max-width: 28rem;
        overflow: hidden;
    }
    .print-modal-header {
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e5e7eb;
    }
    .print-modal-body {
        padding: 1.5rem;
        text-align: center;
    }
    .print-modal-footer {
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        border-top: 1px solid #e5e7eb;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto py-6">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('pengiriman.index') }}" class="flex items-center text-green-600 hover:text-green-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Success Notification -->
    @if(session('success'))
    <div id="successNotification" class="notification bg-green-500 text-white p-4 rounded-md shadow flex items-start">
        <div class="flex-shrink-0 mr-3">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="flex-grow">
            <h3 class="font-bold">Berhasil!</h3>
            <p>{{ session('success') }}</p>
        </div>
        <button onclick="document.getElementById('successNotification').remove()" class="text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Error Notification -->
    @if(session('error'))
    <div id="errorNotification" class="notification bg-red-500 text-white p-4 rounded-md shadow flex items-start">
        <div class="flex-shrink-0 mr-3">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="flex-grow">
            <h3 class="font-bold">Gagal!</h3>
            <p>{{ session('error') }}</p>
        </div>
        <button onclick="document.getElementById('errorNotification').remove()" class="text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 mb-6 fade-in">
            <div class="p-6">
                <!-- Logo & Resi Number Section -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 pb-6 border-b border-gray-200">
                    <div class="flex items-center mb-4 sm:mb-0">
                        <img src="{{ asset('images/csmcargo.png') }}" alt="Manaje" class="h-10 mr-2">
                        <span class="font-medium text-green-600 text-xl">®</span>
                    </div>
                    <div class="flex flex-col items-end">
                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($pengiriman->no_resi, 'C128', 3, 80) }}" alt="Barcode" class="h-16 mb-2">
                    </div>
                </div>

            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div>
                    <div class="flex mb-2">
                        <div class="w-40 font-medium">Nomor Resi</div>
                        <div class="text-gray-800">: {{ $pengiriman->no_resi }}</div>
                    </div>
                    <div class="flex mb-2">
                        <div class="w-40 font-medium">Tanggal Pengiriman</div>
                        <div class="text-gray-800">: {{ $pengiriman->created_at->format('d M, Y') }}</div>
                    </div>
                    <div class="flex mb-2">
                        <div class="w-40 font-medium">Asal</div>
                        <div class="text-gray-800">: {{ $pengiriman->asal }}</div>
                    </div>
                    <div class="flex mb-2">
                        <div class="w-40 font-medium">Tujuan</div>
                        <div class="text-gray-800">: {{ $pengiriman->tujuan }}</div>
                    </div>
                </div>
                <div>
                    <div class="flex mb-2">
                        <div class="w-40 font-medium">Status</div>
                        <div class="text-gray-800">: 
                            @if($pengiriman->status == 'draft')
                                Draf (Branch sesuai alamat Semarang)
                            @elseif($pengiriman->status == 'processed')
                                Diproses
                            @else
                                {{ $pengiriman->status }}
                            @endif
                        </div>
                    </div>
                    <div class="flex mb-2">
                        <div class="w-40 font-medium">Tipe</div>
                        <div class="text-gray-800">: {{ $pengiriman->opsiPengiriman->tipe_pengiriman ?? 'Diantar' }}</div>
                    </div>
                </div>
            </div>

            <!-- Pengirim & Penerima -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="info-section">
                    <h3 class="section-title">Informasi Pengirim</h3>
                    <div class="mt-3">
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div>
                                <div class="info-label">Branch</div>
                                <div class="info-value">CSM Jakarta</div>
                            </div>
                            <div>
                                <div class="info-label">Tipe Pengiriman</div>
                                <div class="info-value">{{ $pengiriman->opsiPengiriman->tipe_pengiriman ?? 'Diantar' }}</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div>
                                <div class="info-label">Nama Pengirim</div>
                                <div class="info-value">{{ $pengiriman->pengirimPenerima->nama_pengirim ?? 'Mawar Melati' }}</div>
                            </div>
                            <div>
                                <div class="info-label">Nomor Telepon</div>
                                <div class="info-value">{{ $pengiriman->pengirimPenerima->telepon_pengirim ?? '+628737181082' }}</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="info-label">Email Pengirim</div>
                                <div class="info-value">{{ $pengiriman->pengirimPenerima->email_pengirim ?? 'mawarmelati@gmail.com' }}</div>
                            </div>
                            <div>
                                <div class="info-label">Alamat Pengirim</div>
                                <div class="info-value">{{ $pengiriman->pengirimPenerima->alamat_pengirim ?? 'Solo' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="info-section">
                    <h3 class="section-title">Informasi Penerima</h3>
                    <div class="mt-3">
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div>
                                <div class="info-label">Nama Penerima</div>
                                <div class="info-value">{{ $pengiriman->pengirimPenerima->nama_penerima ?? 'Putih Abu' }}</div>
                            </div>
                            <div>
                                <div class="info-label">Nomor Telepon</div>
                                <div class="info-value">{{ $pengiriman->pengirimPenerima->telepon_penerima ?? '+6289746197112' }}</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="info-label">Email Penerima</div>
                                <div class="info-value">{{ $pengiriman->pengirimPenerima->email_penerima ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="info-label">Alamat Penerima</div>
                                <div class="info-value">{{ $pengiriman->pengirimPenerima->alamat_penerima ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Layanan & Pembayaran -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="info-section">
                    <h3 class="section-title">Layanan</h3>
                    <div class="mt-3">
                        <div class="mb-3">
                            <div class="info-label">Jenis Layanan</div>
                            <div class="info-value">{{ $pengiriman->opsiPengiriman->jenis_layanan ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">Asuransi Pengiriman</div>
                            <div class="info-value">{{ $pengiriman->opsiPengiriman->asuransi ? 'Ya' : '-' }}</div>
                        </div>
                        <div>
                            <div class="info-label">Packing Pengiriman</div>
                            <div class="info-value">{{ $pengiriman->opsiPengiriman->packing_tambahan ? 'Ya' : '-' }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="info-section">
                    <h3 class="section-title">Pembayaran</h3>
                    <div class="mt-3">
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div>
                                <div class="info-label">Metode Pembayaran</div>
                                <div class="info-value">{{ $pengiriman->informasiPembayaran->metode_pembayaran ?? 'Cash' }}</div>
                            </div>
                            <div>
                                <div class="info-label">Diskon</div>
                                <div class="info-value">-</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="info-label">Total Sub Biaya</div>
                                <div class="info-value">{{ number_format($pengiriman->informasiPembayaran->total_sub_biaya ?? 69000, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <div class="info-label">Total Biaya Pengiriman</div>
                                <div class="info-value">{{ number_format($pengiriman->informasiPembayaran->total_biaya_pengiriman ?? 45000, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Tambahan -->
            <div class="mb-8">
                <h3 class="section-title">Catatan Tambahan</h3>
                <div class="mt-2 p-4 bg-gray-50 rounded-md text-gray-600 min-h-[100px]">
                    {{ $pengiriman->catatan ?? 'Tidak ada catatan tambahan' }}
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-8">
                <div class="table-container border border-gray-200">
                    <table>
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAMA BARANG</th>
                                <th>JENIS BARANG</th>
                                <th>DESKRIPSI BARANG</th>
                                <th>BERAT BARANG (KG)</th>
                                <th>PANJANG BARANG (CM)</th>
                                <th>LEBAR BARANG (CM)</th>
                                <th>TINGGI BARANG (CM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengiriman->barangPengiriman as $index => $barang)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->jenis_barang }}</td>
                                    <td>{{ $barang->deskripsi_barang }}</td>
                                    <td>{{ $barang->berat_barang }}</td>
                                    <td>{{ $barang->panjang_barang }}</td>
                                    <td>{{ $barang->lebar_barang }}</td>
                                    <td>{{ $barang->tinggi_barang }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td>1</td>
                                    <td>Baju</td>
                                    <td>Pakaian</td>
                                    <td>Baju branded</td>
                                    <td>0.2</td>
                                    <td>60</td>
                                    <td>46</td>
                                    <td>70</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Celana</td>
                                    <td>Pakaian</td>
                                    <td>Celana mahal</td>
                                    <td>0.5</td>
                                    <td>130</td>
                                    <td>46</td>
                                    <td>160</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between mt-8">
                <button type="button" id="cetakLabelBtn" class="action-button primary">
                    <i class="fas fa-print"></i> Cetak Label
                </button>
                
                <button type="button" id="lacakBtn" class="action-button primary">
                    <i class="fas fa-search-location"></i> Lacak
                </button>
            </div>
        </div>
    </div>

    <!-- Status History -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-4">Riwayat Log Status</h3>
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Waktu
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Diedit Oleh
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pengiriman->updated_at ? $pengiriman->updated_at->format('d F, Y H:i') : '24 Juni, 2024 13:59' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pengiriman->pengirimPenerima->nama_pengirim ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-badge draft">
                                Dalam Perjalanan
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Print Modal -->
<div id="printModal" class="print-modal hidden">
    <div class="print-modal-content">
        <div class="print-modal-header bg-green-500 text-white">
            <h3 class="text-lg font-medium">Cetak Label Pengiriman</h3>
            <button id="closePrintModal" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="print-modal-body">
            <div class="mb-6">
                <i class="fas fa-print text-5xl text-green-500"></i>
            </div>
            <p class="text-gray-600">Anda ingin cetak label pengiriman?</p>
        </div>
        <div class="print-modal-footer">
            <button id="cancelPrint" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Batal
            </button>
            <button id="confirmPrint" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                Lanjut
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Print Label button
    const cetakLabelBtn = document.getElementById('cetakLabelBtn');
    const printModal = document.getElementById('printModal');
    const closePrintModal = document.getElementById('closePrintModal');
    const cancelPrint = document.getElementById('cancelPrint');
    const confirmPrint = document.getElementById('confirmPrint');
    
    // Show print modal
    cetakLabelBtn.addEventListener('click', function() {
        printModal.classList.remove('hidden');
    });
    
    // Hide print modal
    closePrintModal.addEventListener('click', function() {
        printModal.classList.add('hidden');
    });
    
    cancelPrint.addEventListener('click', function() {
        printModal.classList.add('hidden');
    });
    
    // Handle print confirmation
    confirmPrint.addEventListener('click', function() {
        printModal.classList.add('hidden');
        
        // Simulate printing
        window.location.href = "{{ route('pengiriman.print.label', ['id' => $pengiriman->id]) }}";
    });
    
    // Track button
    const lacakBtn = document.getElementById('lacakBtn');
    lacakBtn.addEventListener('click', function() {
        window.location.href = "{{ route('pengiriman.track.direct', ['resi' => $pengiriman->no_resi]) }}";
    });
    
    // Auto-hide notifications after 5 seconds
    const notifications = document.querySelectorAll('.notification');
    if (notifications.length > 0) {
        setTimeout(function() {
            notifications.forEach(function(notification) {
                notification.remove();
            });
        }, 5000);
    }
});
</script>
@endsection