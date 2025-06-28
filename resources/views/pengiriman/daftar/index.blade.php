@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .pagination-item {
        @apply px-3 py-1 border border-gray-300 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-green-500;
    }
    .pagination-item.active {
        @apply bg-green-500 text-white border-green-500 hover:bg-green-600;
    }
    .accordion-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }
    .notification {
        animation: slideDown 0.3s ease-out forwards, fadeOut 0.5s ease-out 4.5s forwards;
    }
    @keyframes slideDown {
        from { transform: translateY(-100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Pengiriman</h1>
        
        <div class="flex items-center space-x-3">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="mx-2 text-gray-500">/</span>
                            <a href="{{ route('pengiriman.index') }}" class="text-gray-500 hover:text-gray-700">
                                Pengiriman
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <span class="mx-2 text-gray-500">/</span>
                            <span class="text-green-500">Daftar Pengiriman</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Success notification -->
    @if(session('success'))
    <div id="successNotification" class="bg-green-500 text-white p-4 rounded-md mb-4 flex items-start">
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

    <div class="bg-white rounded-md shadow-sm overflow-hidden">
        <!-- Search and Actions Bar -->
        <div class="p-4 flex flex-wrap items-center gap-2">
            <div class="relative flex-grow max-w-md">
                <form action="{{ route('pengiriman.index') }}" method="GET" class="flex">
                    <input type="text" 
                           name="search" 
                           placeholder="Pencarian" 
                           value="{{ request('search') }}"
                           class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </form>
            </div>
            
            <div class="flex items-center space-x-2">
                <div class="relative">
                    <select id="perPage" 
                            name="per_page" 
                            class="appearance-none block rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                            onchange="this.form.submit()">
                        <option value="5" {{ request('per_page', '10') == '5' ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', '10') == '10' ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', '10') == '25' ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', '10') == '50' ? 'selected' : '' }}>50</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
                
                <button id="refreshBtn" class="p-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    <i class="fas fa-sync-alt text-gray-600"></i>
                </button>
                
                <button id="filterBtn" class="flex items-center p-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    <i class="fas fa-filter text-gray-600 mr-2"></i>
                    Filter
                </button>
                
                <a href="{{ route('pengiriman.opsi.create') }}" class="flex items-center bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                    Tambah Pengiriman
                </a>
            </div>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" id="select-all" class="h-4 w-4 text-green-500 focus:ring-green-400">
                                <span class="ml-2">#</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                <span>RESI</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                <span>DIBUAT PADA</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                <span>ASAL</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                <span>TUJUAN</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                <span>BRANCH</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                <span>NAMA PENGIRIM</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                <span>TOTAL</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                <span>METODE BAYAR</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                <span>TIPE</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                <span>STATUS</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            AKSI
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pengiriman as $key => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="select-item h-4 w-4 text-green-500 focus:ring-green-400">
                                <span class="ml-2">{{ $pengiriman->firstItem() + $key }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-green-500 font-medium">
                            {{ $item->no_resi ?: ('bil' . str_pad($item->id, 6, '0', STR_PAD_LEFT)) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->created_at ? $item->created_at->format('d M, Y') : '-' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->asal ?: 'Jakarta' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->tujuan ?: 'Semarang' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            CSM Jombang
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->pengirimPenerima->nama_pengirim ?? 'Andi' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->informasiPembayaran ? number_format($item->informasiPembayaran->total_biaya_pengiriman, 0, ',', '.') : '69.000' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="{{ $item->informasiPembayaran && $item->informasiPembayaran->metode_pembayaran == 'Cash On Delivery' ? 'text-orange-500' : 'text-green-500' }}">
                                {{ $item->informasiPembayaran ? ($item->informasiPembayaran->metode_pembayaran == 'Cash On Delivery' ? 'Cash-Unpaid' : 'Cash-Paid') : 'Cash-Paid' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->opsiPengiriman->tipe_pengiriman ?? ($loop->index % 2 == 0 ? 'Publik' : 'Draf') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $item->status == 'processed' ? 'bg-green-100 text-green-800' : 
                                   ($item->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ $item->status == 'processed' ? 'Diantar (Dikirim)' : 
                                   ($item->status == 'draft' ? 'Draf' : 'Diambil (Diterima)') }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('pengiriman.show', ['id' => $item->id]) }}" class="text-green-500 hover:text-green-700">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                                <p class="text-lg font-medium">Tidak ada data pengiriman</p>
                                <p class="text-sm">Mulai tambahkan pengiriman baru untuk melihat data di sini</p>
                                <a href="{{ route('pengiriman.opsi.create') }}" class="mt-4 bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    Tambah Pengiriman
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="{{ $pengiriman->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
                <a href="{{ $pengiriman->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $pengiriman->firstItem() ?: 0 }}</span> hingga <span class="font-medium">{{ $pengiriman->lastItem() ?: 0 }}</span> dari <span class="font-medium">{{ $pengiriman->total() }}</span> entri
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a href="{{ $pengiriman->url(1) }}" class="pagination-item rounded-l-md">
                            <span class="sr-only">First</span>
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                        <a href="{{ $pengiriman->previousPageUrl() }}" class="pagination-item">
                            <span class="sr-only">Previous</span>
                            <i class="fas fa-angle-left"></i>
                        </a>
                        
                        @for ($i = 1; $i <= $pengiriman->lastPage(); $i++)
                            <a href="{{ $pengiriman->url($i) }}" 
                               class="pagination-item {{ $i == $pengiriman->currentPage() ? 'active' : '' }}">
                                {{ $i }}
                            </a>
                        @endfor
                        
                        <a href="{{ $pengiriman->nextPageUrl() }}" class="pagination-item">
                            <span class="sr-only">Next</span>
                            <i class="fas fa-angle-right"></i>
                        </a>
                        <a href="{{ $pengiriman->url($pengiriman->lastPage()) }}" class="pagination-item rounded-r-md">
                            <span class="sr-only">Last</span>
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Popup -->
<div id="filterPopup" class="fixed inset-0 bg-black bg-opacity-30 flex items-start justify-center z-50 pt-16 hidden">
    <div class="bg-white rounded-md shadow-lg w-full max-w-2xl">
        <div class="bg-green-500 text-white p-4 flex justify-between items-center">
            <h3 class="text-lg font-medium">Filter</h3>
            <button id="closeFilterBtn" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="p-6">
            <form id="filterForm" action="{{ route('pengiriman.index') }}" method="GET">
                <!-- Tanggal Filter -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-700">Tanggal</h4>
                        <button type="button" class="text-green-500 hover:text-green-700 accordion-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div class="accordion-content">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Mulai</label>
                                <div class="relative">
                                    <input type="text" 
                                           name="tanggal_mulai" 
                                           placeholder="MM / DD / YYYY" 
                                           class="block w-full rounded-md border border-gray-300 px-3 py-2 pr-10 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                           id="tanggal_mulai"
                                           value="{{ request('tanggal_mulai') }}">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Akhir</label>
                                <div class="relative">
                                    <input type="text" 
                                           name="tanggal_akhir" 
                                           placeholder="MM / DD / YYYY" 
                                           class="block w-full rounded-md border border-gray-300 px-3 py-2 pr-10 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                           id="tanggal_akhir"
                                           value="{{ request('tanggal_akhir') }}">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Asal Filter -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-700">Asal</h4>
                        <button type="button" class="text-green-500 hover:text-green-700 accordion-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div class="accordion-content">
                        <div class="relative">
                            <input type="text" 
                                   name="asal" 
                                   placeholder="Cari Asal" 
                                   class="block w-full rounded-md border border-gray-300 px-3 py-2 pr-10 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                   value="{{ request('asal') }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tujuan Filter -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-700">Tujuan</h4>
                        <button type="button" class="text-green-500 hover:text-green-700 accordion-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div class="accordion-content">
                        <div class="relative">
                            <input type="text" 
                                   name="tujuan" 
                                   placeholder="Cari Tujuan" 
                                   class="block w-full rounded-md border border-gray-300 px-3 py-2 pr-10 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                   value="{{ request('tujuan') }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Branch Filter -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-700">Branch</h4>
                        <button type="button" class="text-green-500 hover:text-green-700 accordion-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div class="accordion-content">
                        <div class="relative">
                            <input type="text" 
                                   name="branch" 
                                   placeholder="Cari Branch" 
                                   class="block w-full rounded-md border border-gray-300 px-3 py-2 pr-10 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                   value="{{ request('branch') }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Nama Pengirim Filter -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-700">Nama Pengirim</h4>
                        <button type="button" class="text-green-500 hover:text-green-700 accordion-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div class="accordion-content">
                        <div class="relative">
                            <input type="text" 
                                   name="nama_pengirim" 
                                   placeholder="Masukkan Nama Pengirim" 
                                   class="block w-full rounded-md border border-gray-300 px-3 py-2 pr-10 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                   value="{{ request('nama_pengirim') }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Filter -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-700">Total</h4>
                        <button type="button" class="text-green-500 hover:text-green-700 accordion-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div class="accordion-content">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Minimal</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 rounded-l-md border border-r-0 border-gray-300">
                                        Rp.
                                    </span>
                                    <input type="text" 
                                           name="total_min" 
                                           placeholder="0" 
                                           class="block w-full rounded-none rounded-r-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                           value="{{ request('total_min') }}">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Maksimal</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 rounded-l-md border border-r-0 border-gray-300">
                                        Rp.
                                    </span>
                                    <input type="text" 
                                           name="total_max" 
                                           placeholder="0" 
                                           class="block w-full rounded-none rounded-r-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                           value="{{ request('total_max') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Metode Bayar Filter -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-700">Metode Bayar</h4>
                        <button type="button" class="text-green-500 hover:text-green-700 accordion-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div class="accordion-content">
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="metode_bayar" value="cash" class="h-4 w-4 text-green-500 focus:ring-green-400"
                                       {{ request('metode_bayar') == 'cash' ? 'checked' : '' }}>
                                <span class="ml-2">Cash</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="radio" name="metode_bayar" value="metrans" class="h-4 w-4 text-green-500 focus:ring-green-400"
                                       {{ request('metode_bayar') == 'metrans' ? 'checked' : '' }}>
                                <span class="ml-2">MidTrans</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Tipe Filter -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-700">Tipe</h4>
                        <button type="button" class="text-green-500 hover:text-green-700 accordion-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div class="accordion-content">
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="tipe" value="publik" class="h-4 w-4 text-green-500 focus:ring-green-400"
                                       {{ request('tipe') == 'publik' ? 'checked' : '' }}>
                                <span class="ml-2">Publik</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="radio" name="tipe" value="draf" class="h-4 w-4 text-green-500 focus:ring-green-400"
                                       {{ request('tipe') == 'draf' ? 'checked' : '' }}>
                                <span class="ml-2">Draf</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Status Filter -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-700">Status</h4>
                        <button type="button" class="text-green-500 hover:text-green-700 accordion-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div class="accordion-content">
                        <select name="status" 
                                class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="">Pilih Status</option>
                            <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Diantar (Dikirim)</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draf</option>
                            <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Diambil (Diterima)</option>
                        </select>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end mt-8 space-x-3">
                    <button type="button" id="resetFilterBtn" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md text-sm">
                        Reset
                    </button>
                    
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter button click
    document.getElementById('filterBtn').addEventListener('click', function() {
        document.getElementById('filterPopup').classList.remove('hidden');
    });
    
    // Close filter popup
    document.getElementById('closeFilterBtn').addEventListener('click', function() {
        document.getElementById('filterPopup').classList.add('hidden');
    });
    
    // Reset filter form
    document.getElementById('resetFilterBtn').addEventListener('click', function() {
        const form = document.getElementById('filterForm');
        const inputs = form.querySelectorAll('input:not([type="submit"])');
        
        inputs.forEach(function(input) {
            if (input.type === 'checkbox' || input.type === 'radio') {
                input.checked = false;
            } else {
                input.value = '';
            }
        });
        
        form.querySelectorAll('select').forEach(function(select) {
            select.selectedIndex = 0;
        });
    });
    
    // Accordion toggles
    document.querySelectorAll('.accordion-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const content = this.closest('div').nextElementSibling;
            const icon = this.querySelector('i');
            
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        });
        
        // Expand all accordions by default
        const content = toggle.closest('div').nextElementSibling;
        const icon = toggle.querySelector('i');
        content.style.maxHeight = content.scrollHeight + 'px';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    });
    
    // Initialize datepickers for date fields
    flatpickr('#tanggal_mulai', {
        dateFormat: 'm/d/Y',
        allowInput: true
    });
    
    flatpickr('#tanggal_akhir', {
        dateFormat: 'm/d/Y',
        allowInput: true
    });
    
    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', function() {
        window.location.href = '{{ route("pengiriman.index") }}';
    });
    
    // Select all checkboxes
    document.getElementById('select-all').addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.select-item').forEach(function(checkbox) {
            checkbox.checked = checked;
        });
    });
    
    // Check if at least one item is selected
    document.querySelectorAll('.select-item').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(document.querySelectorAll('.select-item')).every(item => item.checked);
            document.getElementById('select-all').checked = allChecked;
        });
    });

    // Check for success notification in localStorage
    const status = localStorage.getItem('pengirimanStatus');
    
    if (status === 'success') {
        // Show success notification
        const successNotif = document.createElement('div');
        successNotif.className = 'fixed top-0 right-0 left-0 flex justify-center items-start pt-4 z-50 notification';
        successNotif.innerHTML = `
            <div class="bg-green-500 text-white p-4 rounded-md shadow-lg flex items-start max-w-xl w-full">
                <div class="flex-shrink-0 mr-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div class="flex-grow">
                    <h3 class="font-bold">Berhasil!</h3>
                    <p>Pengiriman baru berhasil disimpan.</p>
                </div>
                <button class="close-notif text-white hover:text-gray-200 ml-3">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(successNotif);
        
        // Clear localStorage
        localStorage.removeItem('pengirimanStatus');
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            successNotif.remove();
        }, 5000);
        
        // Close button
        successNotif.querySelector('.close-notif').addEventListener('click', function() {
            successNotif.remove();
        });
    }
});
</script>
@endsection