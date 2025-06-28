{{-- resources/views/admin/pengiriman/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Pengiriman')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Pengiriman</h1>
                <p class="text-gray-600 mt-1">Monitor dan kelola semua pengiriman yang sedang berjalan</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <button type="button" onclick="refreshData()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-boxes text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $totalPengiriman ?? 0 }}</h3>
                    <p class="text-sm text-gray-600">Total Pengiriman</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $menungguPickup ?? 0 }}</h3>
                    <p class="text-sm text-gray-600">Menunggu Pickup</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-shipping-fast text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $dalamPerjalanan ?? 0 }}</h3>
                    <p class="text-sm text-gray-600">Dalam Perjalanan</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $terkirim ?? 0 }}</h3>
                    <p class="text-sm text-gray-600">Terkirim</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $bermasalah ?? 0 }}</h3>
                    <p class="text-sm text-gray-600">Bermasalah</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" action="{{ route('admin.pengiriman.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        Cari
                    </label>
                    <input type="text" id="search" name="search" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                           placeholder="Resi/Pengirim/Penerima..."
                           value="{{ request('search') }}">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>
                    <select id="status" name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Diproses</option>
                        <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>Dijemput</option>
                        <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>Dalam Perjalanan</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Terkirim</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <!-- Branch -->
                <div>
                    <label for="branch" class="block text-sm font-medium text-gray-700 mb-2">
                        Branch
                    </label>
                    <select id="branch" name="branch" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Branch</option>
                        @if(isset($branches))
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->nama_branch }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Kota Asal -->
                <div>
                    <label for="kota_asal" class="block text-sm font-medium text-gray-700 mb-2">
                        Kota Asal
                    </label>
                    <select id="kota_asal" name="kota_asal" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Kota</option>
                        @if(isset($kotaAsal))
                            @foreach($kotaAsal as $kota)
                                <option value="{{ $kota }}" {{ request('kota_asal') == $kota ? 'selected' : '' }}>
                                    {{ $kota }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Kota Tujuan -->
                <div>
                    <label for="kota_tujuan" class="block text-sm font-medium text-gray-700 mb-2">
                        Kota Tujuan
                    </label>
                    <select id="kota_tujuan" name="kota_tujuan" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Kota</option>
                        @if(isset($kotaTujuan))
                            @foreach($kotaTujuan as $kota)
                                <option value="{{ $kota }}" {{ request('kota_tujuan') == $kota ? 'selected' : '' }}>
                                    {{ $kota }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Tanggal -->
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal
                    </label>
                    <input type="date" id="tanggal" name="tanggal" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           value="{{ request('tanggal') }}">
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md flex-1">
                        <i class="fas fa-search mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.pengiriman.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Pengiriman Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    Daftar Pengiriman 
                    @if(isset($pengiriman))
                        ({{ $pengiriman->total() }} total)
                    @endif
                </h2>
                <div class="flex items-center space-x-3">
                    <div class="text-sm text-gray-500">
                        @if(isset($pengiriman) && $pengiriman->total() > 0)
                            Menampilkan {{ $pengiriman->firstItem() }}-{{ $pengiriman->lastItem() }} dari {{ $pengiriman->total() }} pengiriman
                        @else
                            Tidak ada data
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="bulkAction('update_status')" class="text-gray-400 hover:text-gray-600" title="Bulk Update Status">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" onclick="bulkAction('export')" class="text-gray-400 hover:text-gray-600" title="Export Selected">
                            <i class="fas fa-file-export"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($pengiriman) && $pengiriman->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengiriman
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengirim & Penerima
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rute & Layanan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Detail
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pengiriman as $item)
                        <tr class="hover:bg-gray-50" data-id="{{ $item->id }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="row-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="{{ $item->id }}">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-box text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $item->no_resi ?: 'Draft-' . $item->id }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $item->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="space-y-1">
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $item->pengirimPenerima->nama_pengirim ?? 'N/A' }}</span>
                                        <div class="text-gray-500">{{ $item->pengirimPenerima->telepon_pengirim ?? 'N/A' }}</div>
                                    </div>
                                    <div class="border-t pt-1">
                                        <span class="font-medium text-gray-900">{{ $item->pengirimPenerima->nama_penerima ?? 'N/A' }}</span>
                                        <div class="text-gray-500">{{ $item->pengirimPenerima->telepon_penerima ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="space-y-1">
                                    <div class="font-medium text-gray-900">
                                        {{ $item->asal }} → {{ $item->tujuan }}
                                    </div>
                                    <div>
                                        @if($item->opsiPengiriman)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $item->opsiPengiriman->jenis_layanan === 'Express' ? 'bg-red-100 text-red-800' : 
                                               ($item->opsiPengiriman->jenis_layanan === 'Regular' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                            {{ $item->opsiPengiriman->jenis_layanan }}
                                        </span>
                                        @else
                                        <span class="text-gray-400">N/A</span>
                                        @endif
                                    </div>
                                    @if($item->opsiPengiriman && $item->opsiPengiriman->branch)
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-building mr-1"></i>{{ $item->opsiPengiriman->branch->nama_branch }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="space-y-1">
                                    <div>Berat: {{ number_format($item->barangPengiriman->sum('berat_barang'), 1) }} kg</div>
                                    <div>Jumlah: {{ $item->barangPengiriman->count() }} item</div>
                                    <div class="font-medium">
                                        @if($item->informasiPembayaran)
                                            Rp {{ number_format($item->informasiPembayaran->total_biaya_pengiriman) }}
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <select onchange="updateStatus({{ $item->id }}, this.value)" 
                                        class="text-xs border-0 rounded-full px-3 py-1 font-semibold
                                        {{ 
                                            $item->status === 'Draft' ? 'bg-gray-100 text-gray-800' :
                                            ($item->status === 'processed' ? 'bg-blue-100 text-blue-800' :
                                            ($item->status === 'picked_up' ? 'bg-yellow-100 text-yellow-800' :
                                            ($item->status === 'in_transit' ? 'bg-orange-100 text-orange-800' :
                                            ($item->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))))
                                        }}">
                                    <option value="Draft" {{ $item->status === 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="processed" {{ $item->status === 'processed' ? 'selected' : '' }}>Diproses</option>
                                    <option value="picked_up" {{ $item->status === 'picked_up' ? 'selected' : '' }}>Dijemput</option>
                                    <option value="in_transit" {{ $item->status === 'in_transit' ? 'selected' : '' }}>Dalam Perjalanan</option>
                                    <option value="delivered" {{ $item->status === 'delivered' ? 'selected' : '' }}>Terkirim</option>
                                    <option value="cancelled" {{ $item->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                                <div class="text-xs text-gray-500 mt-1">
                                    Update {{ $item->updated_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.pengiriman.show', $item->id) }}" 
                                       class="text-blue-600 hover:text-blue-900" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($item->no_resi)
                                    <button type="button" 
                                            onclick="trackPengiriman('{{ $item->no_resi }}')"
                                            class="text-green-600 hover:text-green-900" 
                                            title="Tracking">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </button>
                                    <button type="button" 
                                            onclick="printLabel('{{ $item->id }}')"
                                            class="text-purple-600 hover:text-purple-900" 
                                            title="Print Label">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    @endif
                                    @if(in_array($item->status, ['Draft', 'cancelled']))
                                    <form action="{{ route('admin.pengiriman.destroy', $item->id) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Yakin ingin menghapus pengiriman {{ $item->no_resi ?: 'Draft-' . $item->id }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $pengiriman->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pengiriman</h3>
                <p class="text-gray-500 mb-6">Data pengiriman akan muncul di sini setelah ada yang dibuat.</p>
            </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusUpdateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
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
                
                <div id="statusUpdateContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tracking Modal -->
<div id="trackingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        Tracking Pengiriman
                    </h3>
                    <button type="button" onclick="closeTrackingModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div id="trackingContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Update status pengiriman
    function updateStatus(id, status) {
        if (!confirm('Yakin ingin mengubah status pengiriman ini?')) {
            location.reload();
            return;
        }
        
        fetch(`/admin/pengiriman/${id}/status`, {
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
                // Update UI if needed
                showNotification('Status berhasil diupdate', 'success');
            } else {
                showNotification('Gagal mengupdate status', 'error');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
            location.reload();
        });
    }
    
    // Track pengiriman
    function trackPengiriman(resi) {
        document.getElementById('trackingContent').innerHTML = `
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                <span class="ml-2 text-gray-600">Loading tracking info...</span>
            </div>
        `;
        
        document.getElementById('trackingModal').classList.remove('hidden');
        
        // Simulate tracking data - replace with real API call
        setTimeout(() => {
            const trackingData = `
                <div class="space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-2">Nomor Resi: ${resi}</h4>
                        <p class="text-sm text-blue-800">Status terkini pengiriman</p>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Paket dalam perjalanan</div>
                                <div class="text-xs text-gray-500">24/06/2025 14:30</div>
                                <div class="text-xs text-gray-600">Paket sedang dalam perjalanan menuju tujuan</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Paket dijemput</div>
                                <div class="text-xs text-gray-500">24/06/2025 08:15</div>
                                <div class="text-xs text-gray-600">Paket telah dijemput dari alamat pengirim</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Paket dibuat</div>
                                <div class="text-xs text-gray-500">23/06/2025 16:45</div>
                                <div class="text-xs text-gray-600">Pengiriman dibuat dan menunggu pickup</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('trackingContent').innerHTML = trackingData;
        }, 1000);
    }
    
    // Print label
    function printLabel(id) {
        window.open(`/pengiriman/${id}/print-label`, '_blank');
    }
    
    // Bulk actions
    function bulkAction(action) {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        if (selected.length === 0) {
            alert('Pilih setidaknya satu pengiriman');
            return;
        }
        
        const ids = Array.from(selected).map(cb => cb.value);
        
        if (action === 'export') {
            // Export selected items
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '/admin/pengiriman/export';
            
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        } else if (action === 'update_status') {
            // Show bulk status update modal
            showBulkStatusModal(ids);
        }
    }
    
    // Show bulk status update modal
    function showBulkStatusModal(ids) {
        const content = `
            <div class="space-y-4">
                <p class="text-sm text-gray-600">Mengupdate status untuk ${ids.length} pengiriman</p>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                    <select id="bulkStatus" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="menunggu_pickup">Menunggu Pickup</option>
                        <option value="dijemput">Dijemput</option>
                        <option value="dalam_perjalanan">Dalam Perjalanan</option>
                        <option value="tiba_di_tujuan">Tiba di Tujuan</option>
                        <option value="terkirim">Terkirim</option>
                        <option value="bermasalah">Bermasalah</option>
                    </select>
                </div>
                
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                        Batal
                    </button>
                    <button type="button" onclick="executeBulkStatusUpdate([${ids.join(',')}])" 
                            class="bg-primary hover:bg-green-600 text-white px-4 py-2 rounded-md">
                        Update Status
                    </button>
                </div>
            </div>
        `;
        
        document.getElementById('statusUpdateContent').innerHTML = content;
        document.getElementById('statusUpdateModal').classList.remove('hidden');
    }
    
    // Execute bulk status update
    function executeBulkStatusUpdate(ids) {
        const status = document.getElementById('bulkStatus').value;
        
        // Implementation for bulk update
        console.log('Bulk updating', ids, 'to status', status);
        closeStatusModal();
        showNotification(`Status ${ids.length} pengiriman berhasil diupdate`, 'success');
    }
    
    // Utility functions
    function refreshData() {
        location.reload();
    }
    
    function exportData() {
        window.location.href = '/admin/pengiriman/export';
    }
    
    function closeStatusModal() {
        document.getElementById('statusUpdateModal').classList.add('hidden');
    }
    
    function closeTrackingModal() {
        document.getElementById('trackingModal').classList.add('hidden');
    }
    
    function showNotification(message, type) {
        // Simple notification - you can enhance this
        alert(message);
    }
    
    // Select all functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
    
    // Close modals when clicking outside
    document.getElementById('statusUpdateModal').addEventListener('click', function(e) {
        if (e.target === this) closeStatusModal();
    });
    
    document.getElementById('trackingModal').addEventListener('click', function(e) {
        if (e.target === this) closeTrackingModal();
    });
</script>
@endsection