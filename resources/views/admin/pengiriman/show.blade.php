{{-- resources/views/admin/pengiriman/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Pengiriman')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pengiriman</h1>
                <p class="text-gray-600 mt-1">Nomor Resi: {{ $pengiriman->no_resi ?: 'Draft-' . $pengiriman->id }}</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                @if($pengiriman->no_resi)
                <button type="button" onclick="printLabel()" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-print mr-2"></i>
                    Print Label
                </button>
                @endif
                <button type="button" onclick="openStatusModal()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-edit mr-2"></i>
                    Update Status
                </button>
                <a href="{{ route('admin.pengiriman.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Status & Tracking -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Current Status -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Status Saat Ini
            </h3>
            <div class="text-center">
                <div class="inline-flex px-4 py-2 text-sm font-semibold rounded-full mb-3
                    {{ 
                        $pengiriman->status === 'Draft' ? 'bg-gray-100 text-gray-800' :
                        ($pengiriman->status === 'processed' ? 'bg-blue-100 text-blue-800' :
                        ($pengiriman->status === 'picked_up' ? 'bg-yellow-100 text-yellow-800' :
                        ($pengiriman->status === 'in_transit' ? 'bg-orange-100 text-orange-800' :
                        ($pengiriman->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))))
                    }}">
                    {{ ucwords(str_replace('_', ' ', $pengiriman->status)) }}
                </div>
                <div class="text-sm text-gray-600">
                    Terakhir update: {{ $pengiriman->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-tools text-green-600 mr-2"></i>
                Quick Actions
            </h3>
            <div class="space-y-2">
                <button onclick="updateQuickStatus('processed')" 
                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 rounded-md">
                    <i class="fas fa-cog text-blue-600 mr-2"></i>
                    Mark as Diproses
                </button>
                <button onclick="updateQuickStatus('picked_up')" 
                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 rounded-md">
                    <i class="fas fa-hand-paper text-yellow-600 mr-2"></i>
                    Mark as Dijemput
                </button>
                <button onclick="updateQuickStatus('in_transit')" 
                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 rounded-md">
                    <i class="fas fa-truck text-orange-600 mr-2"></i>
                    Mark as Dalam Perjalanan
                </button>
                <button onclick="updateQuickStatus('delivered')" 
                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 rounded-md">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                    Mark as Terkirim
                </button>
                <button onclick="updateQuickStatus('cancelled')" 
                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 rounded-md">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                    Mark as Dibatalkan
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                Informasi
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Dibuat:</span>
                    <span class="font-medium">{{ $pengiriman->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">User:</span>
                    <span class="font-medium">{{ $pengiriman->user->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Biaya:</span>
                    <span class="font-medium text-green-600">Rp {{ number_format($pengiriman->total_biaya) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Estimasi:</span>
                    <span class="font-medium">{{ $pengiriman->estimasi_hari ?? 'N/A' }} hari</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking Timeline -->
    @if(isset($trackingHistory) && $trackingHistory->count() > 0)
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-6">
            <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
            Timeline Tracking
        </h3>
        <div class="flow-root">
            <ul class="-mb-8">
                @foreach($trackingHistory as $index => $track)
                <li>
                    <div class="relative pb-8">
                        @if(!$loop->last)
                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        @endif
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full 
                                    {{ $loop->first ? 'bg-green-500' : 'bg-gray-400' }} 
                                    flex items-center justify-center ring-8 ring-white">
                                    <i class="fas fa-{{ $loop->first ? 'check' : 'circle' }} text-white text-xs"></i>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $track['status'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $track['description'] }}</p>
                                    @if(isset($track['location']))
                                    <p class="text-xs text-gray-400">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $track['location'] }}
                                    </p>
                                    @endif
                                </div>
                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                    {{ $track['timestamp']->format('d/m/Y') }}
                                    <br>
                                    <span class="text-xs">{{ $track['timestamp']->format('H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Pengiriman Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pengirim & Penerima -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-users text-blue-600 mr-2"></i>
                Pengirim & Penerima
            </h3>
            
            <!-- Pengirim -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-user text-green-600 mr-2"></i>
                    Pengirim
                </h4>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-medium ml-2">{{ $pengiriman->pengirimPenerima->nama_pengirim ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Telepon:</span>
                        <span class="font-medium ml-2">{{ $pengiriman->pengirimPenerima->telepon_pengirim ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium ml-2">{{ $pengiriman->pengirimPenerima->email_pengirim ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Alamat:</span>
                        <span class="font-medium ml-2">{{ $pengiriman->pengirimPenerima->alamat_pengirim ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Penerima -->
            <div>
                <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-user-check text-orange-600 mr-2"></i>
                    Penerima
                </h4>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-medium ml-2">{{ $pengiriman->pengirimPenerima->nama_penerima ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Telepon:</span>
                        <span class="font-medium ml-2">{{ $pengiriman->pengirimPenerima->telepon_penerima ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium ml-2">{{ $pengiriman->pengirimPenerima->email_penerima ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Alamat:</span>
                        <span class="font-medium ml-2">{{ $pengiriman->pengirimPenerima->alamat_penerima ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pengiriman -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-shipping-fast text-purple-600 mr-2"></i>
                Informasi Pengiriman
            </h3>
            
            <div class="space-y-4">
                <!-- Rute -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm text-gray-600">Rute:</span>
                            <div class="font-medium text-gray-900">
                                {{ $pengiriman->asal }} → {{ $pengiriman->tujuan }}
                            </div>
                        </div>
                        <div class="text-right">
                            @if($pengiriman->opsiPengiriman)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $pengiriman->opsiPengiriman->jenis_layanan === 'Express' ? 'bg-red-100 text-red-800' : 
                                   ($pengiriman->opsiPengiriman->jenis_layanan === 'Regular' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ $pengiriman->opsiPengiriman->jenis_layanan }}
                            </span>
                            @else
                            <span class="text-gray-400">N/A</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Detail Lainnya -->
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Berat Total:</span>
                        <div class="font-medium">{{ number_format($pengiriman->barangPengiriman->sum('berat_barang'), 1) }} kg</div>
                    </div>
                    <div>
                        <span class="text-gray-600">Jumlah Barang:</span>
                        <div class="font-medium">{{ $pengiriman->barangPengiriman->count() }} item</div>
                    </div>
                    <div>
                        <span class="text-gray-600">Tipe Pengiriman:</span>
                        <div class="font-medium">
                            @if($pengiriman->opsiPengiriman)
                                {{ $pengiriman->opsiPengiriman->tipe_pengiriman }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-600">Branch:</span>
                        <div class="font-medium">
                            @if($pengiriman->opsiPengiriman && $pengiriman->opsiPengiriman->branch)
                                {{ $pengiriman->opsiPengiriman->branch->nama_branch }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-600">Asuransi:</span>
                        <div class="font-medium">
                            @if($pengiriman->opsiPengiriman)
                                {{ $pengiriman->opsiPengiriman->asuransi ? 'Ya' : 'Tidak' }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-600">Packing Tambahan:</span>
                        <div class="font-medium">
                            @if($pengiriman->opsiPengiriman)
                                {{ $pengiriman->opsiPengiriman->packing_tambahan ? 'Ya' : 'Tidak' }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Biaya -->
                @if($pengiriman->informasiPembayaran)
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-green-700">Biaya Ongkir:</span>
                            <span class="font-medium">Rp {{ number_format($pengiriman->informasiPembayaran->biaya_ongkir ?? 0) }}</span>
                        </div>
                        @if($pengiriman->informasiPembayaran->biaya_asuransi > 0)
                        <div class="flex justify-between">
                            <span class="text-green-700">Biaya Asuransi:</span>
                            <span class="font-medium">Rp {{ number_format($pengiriman->informasiPembayaran->biaya_asuransi) }}</span>
                        </div>
                        @endif
                        @if($pengiriman->informasiPembayaran->biaya_packing > 0)
                        <div class="flex justify-between">
                            <span class="text-green-700">Biaya Packing:</span>
                            <span class="font-medium">Rp {{ number_format($pengiriman->informasiPembayaran->biaya_packing) }}</span>
                        </div>
                        @endif
                        <div class="border-t border-green-200 pt-2">
                            <div class="flex justify-between font-medium text-lg">
                                <span class="text-green-800">Total:</span>
                                <span class="text-green-800">Rp {{ number_format($pengiriman->informasiPembayaran->total_biaya_pengiriman) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-green-700">Metode Pembayaran:</span>
                            <span class="font-medium">{{ $pengiriman->informasiPembayaran->metode_pembayaran ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-center">Informasi pembayaran belum tersedia</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Daftar Barang -->
    @if($pengiriman->barangPengiriman && $pengiriman->barangPengiriman->count() > 0)
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-boxes text-orange-600 mr-2"></i>
                Daftar Barang ({{ $pengiriman->barangPengiriman->count() }} item)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Berat (kg)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dimensi (cm)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pengiriman->barangPengiriman as $index => $barang)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $barang->nama_barang }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $barang->jenis_barang }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($barang->berat_barang, 1) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $barang->panjang_barang }}×{{ $barang->lebar_barang }}×{{ $barang->tinggi_barang }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $barang->deskripsi_barang ?: '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-sm font-medium text-gray-900">Total:</td>
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">
                            {{ number_format($pengiriman->barangPengiriman->sum('berat_barang'), 1) }} kg
                        </td>
                        <td colspan="2" class="px-6 py-3 text-sm text-gray-500">
                            {{ $pengiriman->barangPengiriman->count() }} item
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    <!-- Catatan -->
    @if($pengiriman->catatan)
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
            Catatan
        </h3>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-gray-700">{{ $pengiriman->catatan }}</p>
        </div>
    </div>
    @endif
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-edit mr-2"></i>
                        Update Status Pengiriman
                    </h3>
                    <button type="button" onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-blue-800">
                            Status saat ini: <span class="font-medium">{{ ucwords(str_replace('_', ' ', $pengiriman->status)) }}</span>
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                        <select id="newStatus" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="Draft" {{ $pengiriman->status === 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="processed" {{ $pengiriman->status === 'processed' ? 'selected' : '' }}>Diproses</option>
                            <option value="picked_up" {{ $pengiriman->status === 'picked_up' ? 'selected' : '' }}>Dijemput</option>
                            <option value="in_transit" {{ $pengiriman->status === 'in_transit' ? 'selected' : '' }}>Dalam Perjalanan</option>
                            <option value="delivered" {{ $pengiriman->status === 'delivered' ? 'selected' : '' }}>Terkirim</option>
                            <option value="cancelled" {{ $pengiriman->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" onclick="closeStatusModal()" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                            Batal
                        </button>
                        <button type="button" onclick="updateStatus()" 
                                class="bg-primary hover:bg-green-600 text-white px-4 py-2 rounded-md">
                            Update Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openStatusModal() {
        document.getElementById('statusModal').classList.remove('hidden');
    }
    
    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }
    
    function updateStatus() {
        const newStatus = document.getElementById('newStatus').value;
        const currentStatus = '{{ $pengiriman->status }}';
        
        if (newStatus === currentStatus) {
            alert('Status yang dipilih sama dengan status saat ini');
            return;
        }
        
        if (!confirm('Yakin ingin mengubah status pengiriman?')) {
            return;
        }
        
        fetch(`/admin/pengiriman/{{ $pengiriman->id }}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let message = 'Status berhasil diupdate';
                if (data.new_resi) {
                    message += '. Nomor resi: ' + data.new_resi;
                }
                alert(message);
                location.reload();
            } else {
                alert('Gagal mengupdate status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
    
    function updateQuickStatus(status) {
        if (!confirm(`Yakin ingin mengubah status menjadi "${status.replace('_', ' ')}"?`)) {
            return;
        }
        
        fetch(`/admin/pengiriman/{{ $pengiriman->id }}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status berhasil diupdate');
                location.reload();
            } else {
                alert('Gagal mengupdate status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
    
    function printLabel() {
        window.open(`/pengiriman/{{ $pengiriman->id }}/print-label`, '_blank');
    }
    
    // Close modal when clicking outside
    document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStatusModal();
        }
    });
</script>
@endsection