{{-- resources/views/admin/ongkir/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Tarif Ongkir')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Tarif Ongkir</h1>
                <p class="text-gray-600 mt-1">Atur tarif pengiriman antar kota dan jenis layanan</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <button type="button" id="openCalculator" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-calculator mr-2"></i>
                    Test Kalkulator
                </button>
                <a href="{{ route('admin.ongkir.create') }}" 
                   class="bg-primary hover:bg-green-600 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Tarif
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-calculator text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $totalTarif ?? 0 }}</h3>
                    <p class="text-sm text-gray-600">Total Tarif</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-map-marker-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $totalKotaAsal ?? 0 }}</h3>
                    <p class="text-sm text-gray-600">Kota Asal</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-shipping-fast text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $totalJenisLayanan ?? 0 }}</h3>
                    <p class="text-sm text-gray-600">Jenis Layanan</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $tarifAktif ?? 0 }}</h3>
                    <p class="text-sm text-gray-600">Tarif Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" action="{{ route('admin.ongkir.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        Cari Rute
                    </label>
                    <input type="text" id="search" name="search" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                           placeholder="Kota asal/tujuan..."
                           value="{{ request('search') }}">
                </div>

                <!-- Kota Asal -->
                <div>
                    <label for="kota_asal" class="block text-sm font-medium text-gray-700 mb-2">
                        Kota Asal
                    </label>
                    <select id="kota_asal" name="kota_asal" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Kota Asal</option>
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
                        <option value="">Semua Kota Tujuan</option>
                        @if(isset($kotaTujuan))
                            @foreach($kotaTujuan as $kota)
                                <option value="{{ $kota }}" {{ request('kota_tujuan') == $kota ? 'selected' : '' }}>
                                    {{ $kota }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Jenis Layanan -->
                <div>
                    <label for="jenis_layanan" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Layanan
                    </label>
                    <select id="jenis_layanan" name="jenis_layanan" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Layanan</option>
                        @if(isset($jenisLayanan))
                            @foreach($jenisLayanan as $layanan)
                                <option value="{{ $layanan }}" {{ request('jenis_layanan') == $layanan ? 'selected' : '' }}>
                                    {{ $layanan }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md flex-1">
                        <i class="fas fa-search mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.ongkir.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Ongkir Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    Daftar Tarif Ongkir ({{ $ongkir->total() }} total)
                </h2>
                <div class="flex items-center space-x-3">
                    <div class="text-sm text-gray-500">
                        @if($ongkir->total() > 0)
                            Menampilkan {{ $ongkir->firstItem() }}-{{ $ongkir->lastItem() }} dari {{ $ongkir->total() }} tarif
                        @else
                            Tidak ada data
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" class="text-gray-400 hover:text-gray-600" title="Export Excel">
                            <i class="fas fa-file-excel"></i>
                        </button>
                        <button type="button" class="text-gray-400 hover:text-gray-600" title="Import Excel">
                            <i class="fas fa-upload"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if($ongkir->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rute
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Layanan & Estimasi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Berat (Kg)
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tarif (Rp)
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
                        @foreach($ongkir as $tarif)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-route text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $tarif->kota_asal }} → {{ $tarif->kota_tujuan }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ID: {{ $tarif->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $tarif->jenis_layanan === 'Express' ? 'bg-red-100 text-red-800' : 
                                           ($tarif->jenis_layanan === 'Regular' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $tarif->jenis_layanan }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $tarif->estimasi_hari }} hari
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div>Min: {{ number_format($tarif->berat_minimum, 1) }} kg</div>
                                <div class="text-gray-500">Max: {{ number_format($tarif->berat_maksimum, 1) }} kg</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-gray-900">
                                    Rp {{ number_format($tarif->harga_per_kg) }}/kg
                                </div>
                                <div class="text-gray-500">
                                    Min: Rp {{ number_format($tarif->harga_minimum) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $tarif->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $tarif->status ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                    {{ $tarif->status ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                                <div class="text-xs text-gray-500 mt-1">
                                    Update {{ $tarif->updated_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button type="button" 
                                            onclick="testTarif({{ $tarif->id }}, '{{ $tarif->kota_asal }}', '{{ $tarif->kota_tujuan }}', '{{ $tarif->jenis_layanan }}')"
                                            class="text-purple-600 hover:text-purple-900" 
                                            title="Test Tarif">
                                        <i class="fas fa-calculator"></i>
                                    </button>
                                    <a href="{{ route('admin.ongkir.edit', $tarif) }}" 
                                       class="text-green-600 hover:text-green-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.ongkir.destroy', $tarif) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Yakin ingin menghapus tarif {{ $tarif->kota_asal }} → {{ $tarif->kota_tujuan }} ({{ $tarif->jenis_layanan }})?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $ongkir->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-calculator"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada tarif ongkir</h3>
                <p class="text-gray-500 mb-6">Mulai dengan menambahkan tarif ongkir pertama.</p>
                <a href="{{ route('admin.ongkir.create') }}" 
                   class="bg-primary hover:bg-green-600 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Tarif
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Calculator Modal -->
<div id="calculatorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-calculator mr-2"></i>
                        Test Kalkulator Ongkir
                    </h3>
                    <button type="button" onclick="closeCalculator()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="calculatorForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kota Asal</label>
                            <select id="calc_kota_asal" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Kota Asal</option>
                                @if(isset($kotaAsal))
                                    @foreach($kotaAsal as $kota)
                                        <option value="{{ $kota }}">{{ $kota }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kota Tujuan</label>
                            <select id="calc_kota_tujuan" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Kota Tujuan</option>
                                @if(isset($kotaTujuan))
                                    @foreach($kotaTujuan as $kota)
                                        <option value="{{ $kota }}">{{ $kota }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Berat (kg)</label>
                        <input type="number" id="calc_berat" step="0.1" min="0.1" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                               placeholder="Masukkan berat">
                    </div>
                    
                    <button type="button" onclick="calculateOngkir()" 
                            class="w-full bg-primary hover:bg-green-600 text-white py-2 px-4 rounded-md">
                        <i class="fas fa-calculator mr-2"></i>
                        Hitung Ongkir
                    </button>
                </form>
                
                <div id="calculatorResult" class="mt-4 hidden">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-3">Hasil Perhitungan:</h4>
                        <div id="resultContent"></div>
                    </div>
                </div>
                
                <div id="loadingCalculator" class="mt-4 hidden">
                    <div class="flex items-center justify-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                        <span class="ml-2 text-gray-600">Menghitung...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Open calculator modal
    document.getElementById('openCalculator').addEventListener('click', function() {
        document.getElementById('calculatorModal').classList.remove('hidden');
    });
    
    // Close calculator modal
    function closeCalculator() {
        document.getElementById('calculatorModal').classList.add('hidden');
        document.getElementById('calculatorResult').classList.add('hidden');
        document.getElementById('loadingCalculator').classList.add('hidden');
        document.getElementById('calculatorForm').reset();
    }
    
    // Test specific tarif
    function testTarif(tarifId, kotaAsal, kotaTujuan, jenisLayanan) {
        document.getElementById('calc_kota_asal').value = kotaAsal;
        document.getElementById('calc_kota_tujuan').value = kotaTujuan;
        document.getElementById('calculatorModal').classList.remove('hidden');
    }
    
    // Calculate ongkir using real database data
    function calculateOngkir() {
        const kotaAsal = document.getElementById('calc_kota_asal').value;
        const kotaTujuan = document.getElementById('calc_kota_tujuan').value;
        const berat = parseFloat(document.getElementById('calc_berat').value);
        
        if (!kotaAsal || !kotaTujuan || !berat) {
            alert('Mohon lengkapi semua field');
            return;
        }
        
        if (berat <= 0) {
            alert('Berat harus lebih dari 0 kg');
            return;
        }
        
        // Show loading
        document.getElementById('loadingCalculator').classList.remove('hidden');
        document.getElementById('calculatorResult').classList.add('hidden');
        
        // AJAX request to real database
        fetch('{{ route("admin.ongkir.calculate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                kota_asal: kotaAsal,
                kota_tujuan: kotaTujuan,
                berat: berat
            })
        })
        .then(response => response.json())
        .then(data => {
            // Hide loading
            document.getElementById('loadingCalculator').classList.add('hidden');
            
            const resultDiv = document.getElementById('resultContent');
            
            if (!data.success) {
                resultDiv.innerHTML = `
                    <div class="text-center py-4">
                        <div class="text-yellow-600 text-4xl mb-2">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h4 class="font-medium text-gray-900 mb-2">Tarif Tidak Ditemukan</h4>
                        <p class="text-sm text-gray-600">${data.message}</p>
                        <div class="mt-3 text-xs text-gray-500">
                            <p>Kemungkinan penyebab:</p>
                            <ul class="list-disc list-inside mt-1">
                                <li>Rute tidak tersedia</li>
                                <li>Berat melebihi batas maksimum</li>
                                <li>Tarif belum aktif</li>
                            </ul>
                        </div>
                    </div>
                `;
                document.getElementById('calculatorResult').classList.remove('hidden');
                return;
            }
            
            let resultHTML = `
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Rute:</span>
                        <span class="font-medium">${data.route_info.kota_asal} → ${data.route_info.kota_tujuan}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Berat:</span>
                        <span class="font-medium">${data.route_info.berat} kg</span>
                    </div>
                    <hr class="my-2">
                    <div class="space-y-1">
            `;
            
            if (data.results && data.results.length > 0) {
                data.results.forEach(result => {
                    let estimasiText;
                    if (result.estimasi_hari === 1) {
                        estimasiText = '1 hari';
                    } else {
                        estimasiText = `${result.estimasi_hari} hari`;
                    }
                    
                    resultHTML += `
                        <div class="bg-white p-3 rounded border border-gray-200 mb-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getServiceBadgeClass(result.jenis_layanan)}">
                                        ${result.jenis_layanan}
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-clock mr-1"></i>
                                        ${estimasiText}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        ${result.berat_minimum}kg - ${result.berat_maksimum}kg
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium ${result.color}">Rp ${result.harga_formatted}</div>
                                    <div class="text-xs text-gray-500">
                                        @Rp ${result.harga_per_kg.toLocaleString('id-ID')}/kg
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                resultHTML += `
                    <div class="text-center text-gray-500 py-4">
                        Tidak ada tarif yang tersedia
                    </div>
                `;
            }
            
            resultHTML += `
                    </div>
                </div>
            `;
            
            resultDiv.innerHTML = resultHTML;
            document.getElementById('calculatorResult').classList.remove('hidden');
        })
        .catch(error => {
            // Hide loading
            document.getElementById('loadingCalculator').classList.add('hidden');
            
            console.error('Error:', error);
            const resultDiv = document.getElementById('resultContent');
            resultDiv.innerHTML = `
                <div class="text-center py-4">
                    <div class="text-red-600 text-4xl mb-2">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h4 class="font-medium text-gray-900 mb-2">Terjadi Kesalahan</h4>
                    <p class="text-sm text-gray-600">Silakan coba lagi dalam beberapa saat</p>
                </div>
            `;
            document.getElementById('calculatorResult').classList.remove('hidden');
        });
    }
    
    // Helper function untuk styling badge layanan
    function getServiceBadgeClass(jenisLayanan) {
        switch(jenisLayanan) {
            case 'Express':
                return 'bg-red-100 text-red-800';
            case 'Regular':
                return 'bg-blue-100 text-blue-800';
            case 'Economy':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
    
    // Close modal when clicking outside
    document.getElementById('calculatorModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCalculator();
        }
    });
    
    // Prevent form submission
    document.getElementById('calculatorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        calculateOngkir();
    });
</script>
@endsection