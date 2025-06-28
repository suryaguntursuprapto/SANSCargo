{{-- resources/views/admin/ongkir/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Tarif Ongkir')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Tarif Ongkir</h1>
                <p class="text-gray-600 mt-1">Buat tarif pengiriman baru untuk rute tertentu</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.ongkir.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.ongkir.store') }}" method="POST" id="ongkirForm">
            @csrf
            
            <!-- Route Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-route text-blue-600 mr-2"></i>
                    Informasi Rute
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kota Asal -->
                    <div>
                        <label for="kota_asal" class="block text-sm font-medium text-gray-700 mb-2">
                            Kota Asal <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="kota_asal" 
                               name="kota_asal" 
                               value="{{ old('kota_asal') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('kota_asal') border-red-500 @enderror" 
                               placeholder="Contoh: Jakarta"
                               required>
                        @error('kota_asal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kota Tujuan -->
                    <div>
                        <label for="kota_tujuan" class="block text-sm font-medium text-gray-700 mb-2">
                            Kota Tujuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="kota_tujuan" 
                               name="kota_tujuan" 
                               value="{{ old('kota_tujuan') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('kota_tujuan') border-red-500 @enderror" 
                               placeholder="Contoh: Surabaya"
                               required>
                        @error('kota_tujuan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Service Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-shipping-fast text-green-600 mr-2"></i>
                    Informasi Layanan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jenis Layanan -->
                    <div>
                        <label for="jenis_layanan" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Layanan <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_layanan" 
                                name="jenis_layanan" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('jenis_layanan') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Jenis Layanan</option>
                            <option value="Express" {{ old('jenis_layanan') == 'Express' ? 'selected' : '' }}>Express</option>
                            <option value="Regular" {{ old('jenis_layanan') == 'Regular' ? 'selected' : '' }}>Regular</option>
                            <option value="Economy" {{ old('jenis_layanan') == 'Economy' ? 'selected' : '' }}>Economy</option>
                        </select>
                        @error('jenis_layanan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estimasi Hari -->
                    <div>
                        <label for="estimasi_hari" class="block text-sm font-medium text-gray-700 mb-2">
                            Estimasi Pengiriman (Hari) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="estimasi_hari" 
                               name="estimasi_hari" 
                               value="{{ old('estimasi_hari') }}"
                               min="1" 
                               max="30"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('estimasi_hari') border-red-500 @enderror" 
                               placeholder="Contoh: 2"
                               required>
                        @error('estimasi_hari')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Weight Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-weight-hanging text-yellow-600 mr-2"></i>
                    Batas Berat
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Berat Minimum -->
                    <div>
                        <label for="berat_minimum" class="block text-sm font-medium text-gray-700 mb-2">
                            Berat Minimum (kg) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="berat_minimum" 
                               name="berat_minimum" 
                               value="{{ old('berat_minimum') }}"
                               step="0.1" 
                               min="0"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('berat_minimum') border-red-500 @enderror" 
                               placeholder="0.1"
                               required>
                        @error('berat_minimum')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Berat Maksimum -->
                    <div>
                        <label for="berat_maksimum" class="block text-sm font-medium text-gray-700 mb-2">
                            Berat Maksimum (kg) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="berat_maksimum" 
                               name="berat_maksimum" 
                               value="{{ old('berat_maksimum') }}"
                               step="0.1" 
                               min="0"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('berat_maksimum') border-red-500 @enderror" 
                               placeholder="10.0"
                               required>
                        @error('berat_maksimum')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-money-bill-wave text-purple-600 mr-2"></i>
                    Informasi Harga
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Harga per KG -->
                    <div>
                        <label for="harga_per_kg" class="block text-sm font-medium text-gray-700 mb-2">
                            Harga per KG (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number" 
                                   id="harga_per_kg" 
                                   name="harga_per_kg" 
                                   value="{{ old('harga_per_kg') }}"
                                   min="0"
                                   class="w-full pl-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('harga_per_kg') border-red-500 @enderror" 
                                   placeholder="10000"
                                   required>
                        </div>
                        @error('harga_per_kg')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Minimum -->
                    <div>
                        <label for="harga_minimum" class="block text-sm font-medium text-gray-700 mb-2">
                            Harga Minimum (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number" 
                                   id="harga_minimum" 
                                   name="harga_minimum" 
                                   value="{{ old('harga_minimum') }}"
                                   min="0"
                                   class="w-full pl-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('harga_minimum') border-red-500 @enderror" 
                                   placeholder="5000"
                                   required>
                        </div>
                        @error('harga_minimum')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Price Calculator Preview -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Preview Perhitungan Harga:</h4>
                    <div id="pricePreview" class="text-sm text-gray-600">
                        Masukkan harga per kg dan harga minimum untuk melihat preview
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-toggle-on text-indigo-600 mr-2"></i>
                    Status Tarif
                </h3>
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="status" 
                           name="status" 
                           value="1" 
                           {{ old('status', '1') ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="status" class="ml-2 block text-sm text-gray-900">
                        Aktifkan tarif ini (tarif akan langsung tersedia untuk digunakan)
                    </label>
                </div>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.ongkir.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="button" 
                        onclick="previewTarif()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                    <i class="fas fa-eye mr-2"></i>
                    Preview
                </button>
                <button type="submit" 
                        class="bg-primary hover:bg-green-600 text-white px-6 py-2 rounded-md">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Tarif
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-eye mr-2"></i>
                        Preview Tarif
                    </h3>
                    <button type="button" onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div id="previewContent" class="space-y-4">
                    <!-- Content will be populated by JavaScript -->
                </div>
                
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <button type="button" 
                            onclick="closePreview()" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                        Tutup
                    </button>
                    <button type="button" 
                            onclick="submitForm()" 
                            class="bg-primary hover:bg-green-600 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Tarif
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Price preview calculator
    function updatePricePreview() {
        const hargaPerKg = parseFloat(document.getElementById('harga_per_kg').value) || 0;
        const hargaMinimum = parseFloat(document.getElementById('harga_minimum').value) || 0;
        const previewDiv = document.getElementById('pricePreview');
        
        if (hargaPerKg > 0 && hargaMinimum > 0) {
            let previewHTML = '<div class="grid grid-cols-3 gap-4 text-xs">';
            
            // Sample weights for preview
            const sampleWeights = [0.5, 1.0, 2.5, 5.0, 10.0];
            
            sampleWeights.forEach(weight => {
                const calculatedPrice = Math.max(hargaMinimum, hargaPerKg * weight);
                previewHTML += `
                    <div class="text-center">
                        <div class="font-medium">${weight} kg</div>
                        <div class="text-gray-600">Rp ${calculatedPrice.toLocaleString('id-ID')}</div>
                    </div>
                `;
            });
            
            previewHTML += '</div>';
            previewDiv.innerHTML = previewHTML;
        } else {
            previewDiv.innerHTML = 'Masukkan harga per kg dan harga minimum untuk melihat preview';
        }
    }
    
    // Validate weight fields
    function validateWeights() {
        const beratMin = parseFloat(document.getElementById('berat_minimum').value) || 0;
        const beratMax = parseFloat(document.getElementById('berat_maksimum').value) || 0;
        
        if (beratMin > 0 && beratMax > 0 && beratMin >= beratMax) {
            alert('Berat maksimum harus lebih besar dari berat minimum');
            return false;
        }
        return true;
    }
    
    // Preview tarif function
    function previewTarif() {
        const form = document.getElementById('ongkirForm');
        const formData = new FormData(form);
        
        // Validate required fields
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        if (!validateWeights()) {
            return;
        }
        
        // Create preview content
        const kotaAsal = formData.get('kota_asal');
        const kotaTujuan = formData.get('kota_tujuan');
        const jenisLayanan = formData.get('jenis_layanan');
        const estimasiHari = formData.get('estimasi_hari');
        const beratMin = parseFloat(formData.get('berat_minimum'));
        const beratMax = parseFloat(formData.get('berat_maksimum'));
        const hargaPerKg = parseFloat(formData.get('harga_per_kg'));
        const hargaMin = parseFloat(formData.get('harga_minimum'));
        const status = formData.get('status') ? 'Aktif' : 'Tidak Aktif';
        
        const serviceColors = {
            'Express': 'bg-red-100 text-red-800',
            'Regular': 'bg-blue-100 text-blue-800',
            'Economy': 'bg-green-100 text-green-800'
        };
        
        const previewContent = `
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                        <i class="fas fa-route text-blue-600"></i>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">${kotaAsal} → ${kotaTujuan}</div>
                        <div class="text-sm text-gray-500">Rute pengiriman</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Layanan:</span>
                        <div class="mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${serviceColors[jenisLayanan] || 'bg-gray-100 text-gray-800'}">
                                ${jenisLayanan}
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-600">Estimasi:</span>
                        <div class="font-medium">${estimasiHari} hari</div>
                    </div>
                    <div>
                        <span class="text-gray-600">Berat:</span>
                        <div class="font-medium">${beratMin} - ${beratMax} kg</div>
                    </div>
                    <div>
                        <span class="text-gray-600">Status:</span>
                        <div class="font-medium ${status === 'Aktif' ? 'text-green-600' : 'text-red-600'}">${status}</div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600 mb-2">Struktur Harga:</div>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span>Harga per kg:</span>
                            <span class="font-medium">Rp ${hargaPerKg.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Harga minimum:</span>
                            <span class="font-medium">Rp ${hargaMin.toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('previewContent').innerHTML = previewContent;
        document.getElementById('previewModal').classList.remove('hidden');
    }
    
    // Close preview modal
    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
    }
    
    // Submit form from preview
    function submitForm() {
        document.getElementById('ongkirForm').submit();
    }
    
    // Event listeners
    document.getElementById('harga_per_kg').addEventListener('input', updatePricePreview);
    document.getElementById('harga_minimum').addEventListener('input', updatePricePreview);
    document.getElementById('berat_minimum').addEventListener('change', validateWeights);
    document.getElementById('berat_maksimum').addEventListener('change', validateWeights);
    
    // Close modal when clicking outside
    document.getElementById('previewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePreview();
        }
    });
    
    // Form validation before submit
    document.getElementById('ongkirForm').addEventListener('submit', function(e) {
        if (!validateWeights()) {
            e.preventDefault();
        }
    });
    
    // Auto-format currency inputs
    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = parseInt(value);
        }
    }
    
    document.getElementById('harga_per_kg').addEventListener('blur', function() {
        formatCurrency(this);
    });
    
    document.getElementById('harga_minimum').addEventListener('blur', function() {
        formatCurrency(this);
    });
</script>
@endsection