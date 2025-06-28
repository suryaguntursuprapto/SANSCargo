<!-- layouts/pengiriman/navformmanual.blade.php -->
<div class="border-b border-gray-200">
    <nav class="flex -mb-px">
        <a href="{{ route('pengiriman.opsi.create', isset($pengiriman) ? ['id' => $pengiriman->id] : []) }}" 
           class="py-4 px-6 text-sm font-medium {{ request()->routeIs('pengiriman.opsi.*') ? 'text-green-600 border-b-2 border-green-500' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
            Opsi Pengiriman & Layanan
        </a>
        
        <a href="{{ isset($pengiriman) ? route('pengiriman.pengirim-penerima.create', ['id' => $pengiriman->id]) : '#' }}" 
           class="py-4 px-6 text-sm font-medium {{ request()->routeIs('pengiriman.pengirim-penerima.*') ? 'text-green-600 border-b-2 border-green-500' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
            Pengirim & Penerima
        </a>
        
        <a href="{{ isset($pengiriman) ? route('pengiriman.detail.create', ['id' => $pengiriman->id]) : '#' }}" 
           class="py-4 px-6 text-sm font-medium {{ request()->routeIs('pengiriman.detail.*') ? 'text-green-600 border-b-2 border-green-500' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
            Detail Pengiriman
        </a>
        
        <a href="{{ isset($pengiriman) ? route('pengiriman.pembayaran.create', ['id' => $pengiriman->id]) : '#' }}" 
           class="py-4 px-6 text-sm font-medium {{ request()->routeIs('pengiriman.pembayaran.*') ? 'text-green-600 border-b-2 border-green-500' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
            Informasi Pembayaran
        </a>
        
        <a href="{{ isset($pengiriman) ? route('pengiriman.catatan.create', ['id' => $pengiriman->id]) : '#' }}" 
           class="py-4 px-6 text-sm font-medium {{ request()->routeIs('pengiriman.catatan.*') ? 'text-green-600 border-b-2 border-green-500' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300' }}">
            Catatan
        </a>
    </nav>
</div>