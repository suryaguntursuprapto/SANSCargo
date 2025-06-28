@extends('layouts.app')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<!-- Leaflet Geocoder CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<!-- Custom Styles -->
<style>
.search-results {
    position: absolute;
    z-index: 1000;
    background: white;
    width: 100%;
    border-radius: 0.375rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    max-height: 200px;
    overflow-y: auto;
}
.search-result-item {
    padding: 0.5rem 1rem;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f3f4f6;
}
.search-result-item:hover {
    background-color: #f3f4f6;
}
.location-type {
    font-size: 0.75rem;
    color: #6b7280;
    margin-left: 0.5rem;
}
.location-name {
    flex: 1;
}
</style>
@endsection

@section('content')
<div class="container mx-auto">
    @include('layouts.pengiriman.navkirimmanual')

    <div class="bg-white rounded-md shadow-sm overflow-hidden">
        <div class="border-l-4 border-green-500 p-4">
            <h2 class="text-xl font-medium text-gray-800">Buat Pengiriman Baru</h2>
        </div>
        <div>
            <form action="{{ route('pengiriman.detail.store') }}" method="POST" id="pengirimanForm">
                @csrf
                <input type="hidden" name="pengiriman_id" value="{{ $pengiriman->id }}">
                
                @include('layouts.pengiriman.navformmanual')
                <!-- Detail Pengiriman Tab Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="asal" class="block text-sm font-medium text-gray-700 mb-1">
                                Asal<span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text"
                                       id="asal"
                                       name="asal"
                                       value="{{ $pengiriman->asal ?? old('asal') }}"
                                       placeholder="Masukkan Kota/Kecamatan/Kabupaten"
                                       class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                       required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <div id="asal-results" class="search-results hidden"></div>
                            </div>
                        </div>
                        
                        <div>
                            <label for="tujuan" class="block text-sm font-medium text-gray-700 mb-1">
                                Tujuan<span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text"
                                       id="tujuan"
                                       name="tujuan"
                                       value="{{ $pengiriman->tujuan ?? old('tujuan') }}"
                                       placeholder="Masukkan Kota/Kecamatan/Kabupaten"
                                       class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                       required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <div id="tujuan-results" class="search-results hidden"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="detail_alamat" class="block text-sm font-medium text-gray-700 mb-1">
                            Detail Alamat<span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="detail_alamat"
                               name="detail_alamat"
                               value="{{ $pengiriman->detail_alamat ?? old('detail_alamat') }}"
                               placeholder="Masukkan Detail Alamat"
                               class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                               required>
                    </div>
                    
                    <div class="w-full h-64 bg-gray-100 rounded-md mb-6" id="map"></div>
                    
                    <!-- Items Container -->
                    <div id="items-container">
                        @if(isset($pengiriman) && $pengiriman->barangPengiriman->count() > 0)
                            @foreach($pengiriman->barangPengiriman as $index => $barang)
                                <div class="mb-8 pb-6 border-b border-gray-200 item-template" data-item-id="{{ $index + 1 }}">
                                    <div class="mb-3">
                                        <strong>ID: <span class="item-id">{{ $index + 1 }}</span></strong>
                                        <input type="hidden" name="barang_id[]" value="{{ $barang->id }}">
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Nama Barang<span class="text-red-500">*</span>
                                            </label>
                                            <input type="text"
                                                   name="nama_barang[]"
                                                   value="{{ $barang->nama_barang }}"
                                                   placeholder="Masukkan Nama Barang"
                                                   class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                                   required>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Jenis Barang<span class="text-red-500">*</span>
                                            </label>
                                            <select name="jenis_barang[]"
                                                    class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                                    required>
                                                <option value="">Pilih Jenis Barang</option>
                                                <option value="Elektronik" {{ $barang->jenis_barang == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                                <option value="Pakaian" {{ $barang->jenis_barang == 'Pakaian' ? 'selected' : '' }}>Pakaian</option>
                                                <option value="Makanan" {{ $barang->jenis_barang == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                                                <option value="Dokumen" {{ $barang->jenis_barang == 'Dokumen' ? 'selected' : '' }}>Dokumen</option>
                                                <option value="Lainnya" {{ $barang->jenis_barang == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Deskripsi Barang<span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="deskripsi_barang[]"
                                                  rows="5"
                                                  placeholder="Deskripsikan..."
                                                  class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                                  required>{{ $barang->deskripsi_barang }}</textarea>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Berat Barang (Kg)<span class="text-red-500">*</span>
                                            </label>
                                            <input type="number"
                                                   step="0.01"
                                                   name="berat_barang[]"
                                                   value="{{ $barang->berat_barang }}"
                                                   placeholder="Masukkan Berat Total"
                                                   class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                                   required>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Panjang Barang (Cm)<span class="text-red-500">*</span>
                                            </label>
                                            <input type="number"
                                                   step="0.1"
                                                   name="panjang_barang[]"
                                                   value="{{ $barang->panjang_barang }}"
                                                   placeholder="Masukkan Panjang"
                                                   class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                                   required>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Lebar Barang (Cm)<span class="text-red-500">*</span>
                                            </label>
                                            <input type="number"
                                                   step="0.1"
                                                   name="lebar_barang[]"
                                                   value="{{ $barang->lebar_barang }}"
                                                   placeholder="Masukkan Lebar"
                                                   class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                                   required>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Tinggi Barang (Cm)<span class="text-red-500">*</span>
                                            </label>
                                            <input type="number"
                                                   step="0.1"
                                                   name="tinggi_barang[]"
                                                   value="{{ $barang->tinggi_barang }}"
                                                   placeholder="Masukkan Tinggi"
                                                   class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    @if($index > 0 || $pengiriman->barangPengiriman->count() > 1)
                                    <div class="flex justify-end">
                                        <button type="button"
                                                data-barang-id="{{ $barang->id }}"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm hapus-item-existing">
                                            Hapus Item
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="mb-8 pb-6 border-b border-gray-200 item-template" data-item-id="1">
                                <div class="mb-3">
                                    <strong>ID: <span class="item-id">1</span></strong>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Nama Barang<span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               name="nama_barang[]"
                                               placeholder="Masukkan Nama Barang"
                                               class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                               required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Jenis Barang<span class="text-red-500">*</span>
                                        </label>
                                        <select name="jenis_barang[]"
                                                class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                                required>
                                            <option value="">Pilih Jenis Barang</option>
                                            <option value="Elektronik">Elektronik</option>
                                            <option value="Pakaian">Pakaian</option>
                                            <option value="Makanan">Makanan</option>
                                            <option value="Dokumen">Dokumen</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Deskripsi Barang<span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="deskripsi_barang[]"
                                              rows="5"
                                              placeholder="Deskripsikan..."
                                              class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                              required></textarea>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Berat Barang (Kg)<span class="text-red-500">*</span>
                                        </label>
                                        <input type="number"
                                               step="0.01"
                                               name="berat_barang[]"
                                               placeholder="Masukkan Berat Total"
                                               class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                               required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Panjang Barang (Cm)<span class="text-red-500">*</span>
                                        </label>
                                        <input type="number"
                                               step="0.1"
                                               name="panjang_barang[]"
                                               placeholder="Masukkan Panjang"
                                               class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                               required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Lebar Barang (Cm)<span class="text-red-500">*</span>
                                        </label>
                                        <input type="number"
                                               step="0.1"
                                               name="lebar_barang[]"
                                               placeholder="Masukkan Lebar"
                                               class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                               required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Tinggi Barang (Cm)<span class="text-red-500">*</span>
                                        </label>
                                        <input type="number"
                                               step="0.1"
                                               name="tinggi_barang[]"
                                               placeholder="Masukkan Tinggi"
                                               class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                               required>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mb-8">
                        <button type="button"
                                id="tambah-item"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm flex items-center">
                            <i class="fas fa-plus mr-2"></i> Tambah Item
                        </button>
                    </div>
                    
                    <div class="flex justify-between mt-8">
                        <a href="{{ route('pengiriman.pengirim-penerima.create', ['id' => $pengiriman->id]) }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md text-sm flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        
                        <div class="flex space-x-3">
                            <button type="button"
                                    id="simpan-draft-btn"
                                    class="border border-yellow-400 text-yellow-700 hover:bg-yellow-50 px-4 py-2 rounded-md text-sm">
                                Simpan Draft
                            </button>
                            
                            <button type="submit"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm flex items-center">
                                Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Hapus Item
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteItemForm" action="{{ route('pengiriman.hapus-item') }}" method="POST">
                    @csrf
                    <input type="hidden" id="delete_barang_id" name="barang_id">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </form>
                <button type="button" id="cancelDelete" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<!-- Leaflet Geocoder -->
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<!-- Our JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('map').setView([-2.5489, 118.0149], 5); // Center on Indonesia
    
    // Add OpenStreetMap layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Initialize marker
    let marker = null;
    
    // Set initial marker if coordinates exist
    @if(isset($pengiriman) && $pengiriman->asal && $pengiriman->tujuan)
        // Try to geocode the destination to set initial marker
        const geocodeURL = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent('{{ $pengiriman->tujuan }}')}&countrycodes=id&limit=1`;
        
        fetch(geocodeURL)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    const latlng = L.latLng(lat, lon);
                    
                    map.setView(latlng, 13);
                    marker = L.marker(latlng).addTo(map);
                }
            })
            .catch(error => {
                console.error('Geocoding error:', error);
            });
    @endif
    
    // Add geocoder control
    const geocoder = L.Control.geocoder({
        defaultMarkGeocode: false,
        query: 'Indonesia',
        placeholder: 'Cari lokasi...',
        geocoder: L.Control.Geocoder.nominatim({
            geocodingQueryParams: {
                countrycodes: 'id',  // Limit to Indonesia
                limit: 10,
                addressdetails: 1
            }
        })
    }).addTo(map);
    
    // Listen for geocode results
    geocoder.on('markgeocode', function(e) {
        const result = e.geocode;
        
        // Set marker
        if (marker) {
            marker.setLatLng(result.center);
        } else {
            marker = L.marker(result.center).addTo(map);
        }
        
        // Set address info
        document.getElementById('detail_alamat').value = result.name;
        
        // Set origin field if empty
        if (document.getElementById('asal').value === '') {
            document.getElementById('asal').value = extractLocationName(result);
        }
        
        // Zoom to result
        map.fitBounds(result.bbox);
    });
    
    // Handle map clicks
    map.on('click', function(e) {
        // Set marker
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }
        
        // Reverse geocode to get address
        reverseGeocode(e.latlng);
    });
    
    // Function to search locations in Indonesia using the detailed Nominatim API
    function searchLocations(query, callback) {
        // Create Nominatim URL with Indonesia filter
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&addressdetails=1&limit=10`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Process and enhance location data
                const locations = data.map(item => {
                    // Determine the type of location (kota, kabupaten, kecamatan)
                    let locationType = 'Lainnya';
                    
                    if (item.type === 'administrative' && item.address) {
                        if (item.class === 'boundary' && item.address.city) {
                            locationType = 'Kota';
                        } else if (item.address.county || (item.address.state_district && !item.address.city)) {
                            locationType = 'Kabupaten';
                        } else if (item.address.suburb || item.address.neighbourhood || item.address.district) {
                            locationType = 'Kecamatan';
                        }
                    } else if (item.type === 'city' || item.type === 'town') {
                        locationType = 'Kota';
                    } else if (item.type === 'village' || item.type === 'suburb') {
                        locationType = 'Kecamatan';
                    }
                    
                    // Create a clean, proper display name
                    let displayName = item.display_name;
                    
                    // If we have address details, construct a cleaner name
                    if (item.address) {
                        const parts = [];
                        
                        // Add the most specific part first
                        if (item.address.suburb) parts.push(item.address.suburb);
                        if (item.address.city_district) parts.push(item.address.city_district);
                        if (item.address.city) parts.push(item.address.city);
                        if (item.address.county) parts.push(item.address.county);
                        if (item.address.state_district) parts.push(item.address.state_district);
                        if (item.address.state) parts.push(item.address.state);
                        
                        if (parts.length > 0) {
                            displayName = parts.join(', ');
                        }
                    }
                    
                    return {
                        name: displayName,
                        type: locationType,
                        lat: item.lat,
                        lon: item.lon,
                        original: item
                    };
                });
                
                callback(locations);
            })
            .catch(error => {
                console.error('Error searching locations:', error);
                callback([]);
            });
    }
    
    // Setup enhanced autocomplete for both asal and tujuan fields
    function setupEnhancedAutocomplete(inputId) {
        const input = document.getElementById(inputId);
        const resultsContainer = document.getElementById(`${inputId}-results`);
        
        let debounceTimer;
        
        input.addEventListener('input', function() {
            const value = this.value.trim();
            
            clearTimeout(debounceTimer);
            
            if (value.length < 2) {
                resultsContainer.classList.add('hidden');
                return;
            }
            
            // Debounce the search to avoid too many API calls
            debounceTimer = setTimeout(() => {
                searchLocations(value, locations => {
                    if (locations.length > 0) {
                        resultsContainer.innerHTML = '';
                        
                        locations.forEach(location => {
                            const div = document.createElement('div');
                            div.className = 'search-result-item';
                            
                            const nameSpan = document.createElement('span');
                            nameSpan.className = 'location-name';
                            nameSpan.textContent = location.name;
                            
                            const typeSpan = document.createElement('span');
                            typeSpan.className = 'location-type';
                            typeSpan.textContent = location.type;
                            
                            div.appendChild(nameSpan);
                            div.appendChild(typeSpan);
                            
                            div.addEventListener('click', function() {
                                input.value = location.name;
                                resultsContainer.classList.add('hidden');
                                
                                // Update map based on whether this is origin or destination
                                // CHANGED: Now updates map for both asal and tujuan,
                                // but prioritizes tujuan if both are set
                                const latlng = L.latLng(location.lat, location.lon);
                                
                                if (inputId === 'tujuan' || 
                                   (inputId === 'asal' && document.getElementById('tujuan').value === '')) {
                                    if (marker) {
                                        marker.setLatLng(latlng);
                                    } else {
                                        marker = L.marker(latlng).addTo(map);
                                    }
                                    
                                    map.setView(latlng, 13);
                                    
                                    // Update detail address if it's tujuan or if asal is selected and tujuan is empty
                                    if (inputId === 'tujuan') {
                                        document.getElementById('detail_alamat').value = location.name;
                                    } else if (inputId === 'asal' && document.getElementById('detail_alamat').value === '') {
                                        document.getElementById('detail_alamat').value = location.name;
                                    }
                                }
                            });
                            
                            resultsContainer.appendChild(div);
                        });
                        
                        resultsContainer.classList.remove('hidden');
                    } else {
                        resultsContainer.classList.add('hidden');
                    }
                });
            }, 300); // 300ms debounce
        });
        
        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target !== input && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });
    }
    
    // Extract location name from Nominatim result
    function extractLocationName(result) {
        // Try to get a clean location name from the result
        if (result.properties && result.properties.address) {
            const address = result.properties.address;
            
            // Return the most specific location name
            return address.city || 
                   address.town || 
                   address.village || 
                   address.suburb || 
                   address.county || 
                   address.state_district || 
                   address.state || 
                   result.name;
        }
        
        // Fallback to extracting from the full name
        if (result.name) {
            const parts = result.name.split(',');
            return parts[0].trim();
        }
        
        return '';
    }
    
    // Reverse geocode function with enhanced location data
    function reverseGeocode(latlng) {
        const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}&addressdetails=1&zoom=18`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Set address details
                document.getElementById('detail_alamat').value = data.display_name;
                
                const address = data.address;
                
                // Determine the most appropriate location name
                const locationName = address.city || 
                                    address.town || 
                                    address.village || 
                                    address.suburb || 
                                    address.county || 
                                    address.state_district || 
                                    address.state || 
                                    '';
                
                // Update fields based on what's already filled in
                // CHANGED: Now checks if tujuan is empty, fills that first
                if (document.getElementById('tujuan').value === '') {
                    document.getElementById('tujuan').value = locationName;
                } else if (document.getElementById('asal').value === '') {
                    document.getElementById('asal').value = locationName;
                }
            })
            .catch(error => {
                console.error('Error in reverse geocoding:', error);
            });
    }
    
    // Set up enhanced autocomplete for asal and tujuan
    setupEnhancedAutocomplete('asal');
    setupEnhancedAutocomplete('tujuan');
    
    // Try to get user's location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const latlng = L.latLng(position.coords.latitude, position.coords.longitude);
                
                // Only set map view to user location if we don't have existing data
                @if(!isset($pengiriman) || !$pengiriman->asal || !$pengiriman->tujuan)
                    // Set map view to user location
                    map.setView(latlng, 15);
                    
                    // Add marker
                    if (marker) {
                        marker.setLatLng(latlng);
                    } else {
                        marker = L.marker(latlng).addTo(map);
                    }
                    
                    // Get address details
                    reverseGeocode(latlng);
                @endif
            },
            function(error) {
                console.log('Geolocation error:', error);
            }
        );
    }
    
    // Items functionality
    let itemCounter = {{ isset($pengiriman) && $pengiriman->barangPengiriman->count() > 0 ? $pengiriman->barangPengiriman->count() : 1 }};
    const itemsContainer = document.getElementById('items-container');
    const itemTemplate = document.querySelector('.item-template');
    
    // Add item functionality
    document.getElementById('tambah-item').addEventListener('click', function() {
        itemCounter++;
        
        // Clone the template
        const newItem = itemTemplate.cloneNode(true);
        newItem.dataset.itemId = itemCounter;
        
        // Update the ID display
        newItem.querySelector('.item-id').textContent = itemCounter;
        
        // Clear input values
        newItem.querySelectorAll('input, textarea, select').forEach(function(input) {
            if (input.name !== 'barang_id[]') {
                input.value = '';
            }
        });
        
        // Add delete button if it doesn't exist
        if (!newItem.querySelector('.hapus-item')) {
            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.className = 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm hapus-item';
            deleteButton.textContent = 'Hapus Item';
            deleteButton.dataset.itemId = itemCounter;
            
            const deleteButtonContainer = document.createElement('div');
            deleteButtonContainer.className = 'flex justify-end';
            deleteButtonContainer.appendChild(deleteButton);
            
            newItem.appendChild(deleteButtonContainer);
            
            // Add event listener for the delete button
            deleteButton.addEventListener('click', function() {
                if (document.querySelectorAll('#items-container > div').length > 1) {
                    newItem.remove();
                }
            });
        }
        
        // Append to container
        itemsContainer.appendChild(newItem);
    });
    
    // Delete existing item functionality
    document.querySelectorAll('.hapus-item-existing').forEach(function(button) {
        button.addEventListener('click', function() {
            const barangId = this.dataset.barangId;
            document.getElementById('delete_barang_id').value = barangId;
            document.getElementById('deleteModal').classList.remove('hidden');
        });
    });
    
    // Hide delete modal
    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteModal').classList.add('hidden');
    });
    
    // Submit as draft
    document.getElementById('simpan-draft-btn').addEventListener('click', function() {
        const form = document.getElementById('pengirimanForm');
        form.action = '{{ route("pengiriman.simpan-draft") }}';
        form.submit();
    });
    
    // Handle delete item form submission via AJAX
    document.getElementById('deleteItemForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const barangId = document.getElementById('delete_barang_id').value;
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                barang_id: barangId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the item from DOM
                const itemElement = document.querySelector(`[data-barang-id="${barangId}"]`).closest('.item-template');
                itemElement.remove();
                
                // Hide modal
                document.getElementById('deleteModal').classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
</script>
@endsection