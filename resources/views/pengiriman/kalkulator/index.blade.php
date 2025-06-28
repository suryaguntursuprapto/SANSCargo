@extends('layouts.app')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<!-- Leaflet Geocoder CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
@endsection

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-md shadow-sm p-6">
        <h1 class="text-center text-3xl font-medium text-green-700 mb-8">Cek Ongkir</h1>
        
        <div class="max-w-4xl mx-auto border border-gray-200 rounded-lg p-8">
            <p class="mb-6 text-gray-700">Masukkan data informasi di bawah ini dengan benar.</p>
            
            <form id="cekOngkirForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="asal" class="block text-sm font-medium text-gray-700 mb-1">
                            Asal
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="asal"
                                   name="asal"
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
                            Tujuan
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="tujuan"
                                   name="tujuan"
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
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">
                            Kategori
                        </label>
                        <select id="kategori" name="kategori" class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Box">Box</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Pakaian">Pakaian</option>
                            <option value="Dokumen">Dokumen</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="berat" class="block text-sm font-medium text-gray-700 mb-1">
                            Berat Barang (Kg)/ Volume
                        </label>
                        <input type="number"
                               id="berat"
                               name="berat"
                               step="0.1"
                               min="0.1"
                               placeholder="Masukkan Berat"
                               class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                               required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div>
                        <label for="panjang" class="block text-sm font-medium text-gray-700 mb-1">
                            Panjang Barang (Cm)
                        </label>
                        <input type="number"
                               id="panjang"
                               name="panjang"
                               step="0.1"
                               min="1"
                               placeholder="Masukkan Panjang"
                               class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="lebar" class="block text-sm font-medium text-gray-700 mb-1">
                            Lebar Barang (Cm)
                        </label>
                        <input type="number"
                               id="lebar"
                               name="lebar"
                               step="0.1"
                               min="1"
                               placeholder="Masukkan Lebar"
                               class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="tinggi" class="block text-sm font-medium text-gray-700 mb-1">
                            Tinggi Barang (Cm)
                        </label>
                        <input type="number"
                               id="tinggi"
                               name="tinggi"
                               step="0.1"
                               min="1"
                               placeholder="Masukkan Tinggi"
                               class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                               required>
                    </div>
                </div>
                
                <div class="flex justify-center">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-md text-sm font-medium w-full max-w-md">
                        Cek Ongkir
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Results Section - Initially Hidden -->
        <div id="results-section" class="mt-8 hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                JENIS LAYANAN
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ESTIMASI WAKTU
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                BIAYA
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                AKSI
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="services-table-body">
                        <!-- Services will be inserted here -->
                    </tbody>
                </table>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Setup location search
    function setupLocationSearch(inputId, resultsId) {
        const input = document.getElementById(inputId);
        const resultsContainer = document.getElementById(resultsId);
        
        let debounceTimer;
        
        input.addEventListener('input', function() {
            const value = this.value.trim();
            
            clearTimeout(debounceTimer);
            
            if (value.length < 2) {
                resultsContainer.classList.add('hidden');
                return;
            }
            
            debounceTimer = setTimeout(() => {
                // Search locations using Nominatim
                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(value)}&countrycodes=id&addressdetails=1&limit=5`;
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            resultsContainer.innerHTML = '';
                            resultsContainer.classList.remove('hidden');
                            
                            data.forEach(location => {
                                const locationName = location.display_name.split(',')[0] + ', ' + 
                                                    (location.address.city || location.address.county || location.address.state || '');
                                
                                const div = document.createElement('div');
                                div.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                                div.textContent = locationName;
                                
                                div.addEventListener('click', function() {
                                    input.value = locationName;
                                    resultsContainer.classList.add('hidden');
                                });
                                
                                resultsContainer.appendChild(div);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error searching locations:', error);
                    });
            }, 300);
        });
        
        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target !== input && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });
    }
    
    // Setup location search for origin and destination
    setupLocationSearch('asal', 'asal-results');
    setupLocationSearch('tujuan', 'tujuan-results');
    
    // Form submission
    document.getElementById('cekOngkirForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            asal: formData.get('asal'),
            tujuan: formData.get('tujuan'),
            kategori: formData.get('kategori'),
            berat: formData.get('berat'),
            panjang: formData.get('panjang'),
            lebar: formData.get('lebar'),
            tinggi: formData.get('tinggi')
        };
        
        // Fetch API to calculate shipping cost
        fetch('{{ route("kalkulator.hitung") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            // Display results
            const tableBody = document.getElementById('services-table-body');
            tableBody.innerHTML = '';
            
            for (const [service, details] of Object.entries(data.services)) {
                const row = document.createElement('tr');
                
                // Service type
                const serviceCell = document.createElement('td');
                serviceCell.className = 'px-6 py-4 whitespace-nowrap';
                serviceCell.textContent = service;
                row.appendChild(serviceCell);
                
                // Estimated time
                const timeCell = document.createElement('td');
                timeCell.className = 'px-6 py-4 whitespace-nowrap';
                timeCell.textContent = details.duration;
                row.appendChild(timeCell);
                
                // Cost
                const costCell = document.createElement('td');
                costCell.className = 'px-6 py-4 whitespace-nowrap';
                costCell.textContent = 'Rp' + details.price.toLocaleString('id-ID');
                row.appendChild(costCell);
                
                // Action button
                const actionCell = document.createElement('td');
                actionCell.className = 'px-6 py-4 whitespace-nowrap';
                
                const actionButton = document.createElement('button');
                actionButton.className = 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm';
                actionButton.textContent = 'Kirim Sekarang';
                actionButton.addEventListener('click', function() {
                    alert('Mengarahkan ke form pengiriman dengan layanan ' + service);
                    window.location.href = '{{ route("pengiriman.opsi.create") }}';
                });
                
                actionCell.appendChild(actionButton);
                row.appendChild(actionCell);
                
                tableBody.appendChild(row);
            }
            
            // Show results section
            document.getElementById('results-section').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error calculating shipping cost:', error);
            alert('Terjadi kesalahan saat menghitung ongkir. Silakan coba lagi.');
        });
    });
});
</script>
@endsection