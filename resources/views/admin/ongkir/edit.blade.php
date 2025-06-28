{{-- resources/views/admin/ongkir/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Tarif Ongkir')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Tarif Ongkir</h1>
                <p class="text-gray-600 mt-1">
                    Edit tarif pengiriman: {{ $ongkir->kota_asal }} → {{ $ongkir->kota_tujuan }} ({{ $ongkir->jenis_layanan }})
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <button type="button" id="testCurrentTarif" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-calculator mr-2"></i>
                    Test Tarif Ini
                </button>
                <a href="{{ route('admin.ongkir.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Current vs New Comparison -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Current Data -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-blue-900 mb-4">
                <i class="fas fa-info-circle mr-2"></i>
                Data Saat Ini
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-blue-700">Rute:</span>
                    <span class="font-medium text-blue-900">{{ $ongkir->kota_asal }} → {{ $ongkir->kota_tujuan }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-blue-700">Layanan:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $ongkir->jenis_layanan === 'Express' ? 'bg-red-100 text-red-800' : 
                           ($ongkir->jenis_layanan === 'Regular' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                        {{ $ongkir->jenis_layanan }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-blue-700">Estimasi:</span>
                    <span class="font-medium text-blue-900">{{ $ongkir->estimasi_hari }} hari</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-blue-700">Berat:</span>
                    <span class="font-medium text-blue-900">{{ number_format($ongkir->berat_minimum, 1) }} - {{ number_format($ongkir->berat_maksimum, 1) }} kg</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-blue-700">Harga/kg:</span>
                    <span class="font-medium text-blue-900">Rp {{ number_format($ongkir->harga_per_kg) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-blue-700">Harga Min:</span>
                    <span class="font-medium text-blue-900">Rp {{ number_format($ongkir->harga_minimum) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-blue-700">Status:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $ongkir->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $ongkir->status ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
                <div class="pt-2 border-t border-blue-200">
                    <span class="text-blue-700 text-xs">Terakhir diupdate:</span>
                    <br>
                    <span class="font-medium text-blue-900 text-xs">{{ $ongkir->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-edit mr-2"></i>
                Edit Data
            </h3>
            
            <form action="{{ route('admin.ongkir.update', $ongkir) }}" method="POST" id="editOngkirForm">
                @csrf
                @method('PUT')
                
                <!-- Route Information -->
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Informasi Rute</h4>
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Kota Asal -->
                        <div>
                            <label for="kota_asal" class="block text-sm font-medium text-gray-700 mb-2">
                                Kota Asal <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="kota_asal" 
                                   name="kota_asal" 
                                   value="{{ old('kota_asal', $ongkir->kota_asal) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('kota_asal') border-red-500 @enderror" 
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
                                   value="{{ old('kota_tujuan', $ongkir->kota_tujuan) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('kota_tujuan') border-red-500 @enderror" 
                                   required>
                            @error('kota_tujuan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Service Information -->
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Informasi Layanan</h4>
                    <div class="grid grid-cols-1 gap-4">
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
                                <option value="Express" {{ old('jenis_layanan', $ongkir->jenis_layanan) == 'Express' ? 'selected' : '' }}>Express</option>
                                <option value="Regular" {{ old('jenis_layanan', $ongkir->jenis_layanan) == 'Regular' ? 'selected' : '' }}>Regular</option>
                                <option value="Economy" {{ old('jenis_layanan', $ongkir->jenis_layanan) == 'Economy' ? 'selected' : '' }}>Economy</option>
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
                                   value="{{ old('estimasi_hari', $ongkir->estimasi_hari) }}"
                                   min="1" 
                                   max="30"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('estimasi_hari') border-red-500 @enderror" 
                                   required>
                            @error('estimasi_hari')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Weight Information -->
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Batas Berat</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Berat Minimum -->
                        <div>
                            <label for="berat_minimum" class="block text-sm font-medium text-gray-700 mb-2">
                                Min (kg) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="berat_minimum" 
                                   name="berat_minimum" 
                                   value="{{ old('berat_minimum', $ongkir->berat_minimum) }}"
                                   step="0.1" 
                                   min="0"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('berat_minimum') border-red-500 @enderror" 
                                   required>
                            @error('berat_minimum')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Berat Maksimum -->
                        <div>
                            <label for="berat_maksimum" class="block text-sm font-medium text-gray-700 mb-2">
                                Max (kg) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="berat_maksimum" 
                                   name="berat_maksimum" 
                                   value="{{ old('berat_maksimum', $ongkir->berat_maksimum) }}"
                                   step="0.1" 
                                   min="0"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('berat_maksimum') border-red-500 @enderror" 
                                   required>
                            @error('berat_maksimum')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing Information -->
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Informasi Harga</h4>
                    <div class="grid grid-cols-1 gap-4">
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
                                       value="{{ old('harga_per_kg', $ongkir->harga_per_kg) }}"
                                       min="0"
                                       class="w-full pl-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('harga_per_kg') border-red-500 @enderror" 
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
                                       value="{{ old('harga_minimum', $ongkir->harga_minimum) }}"
                                       min="0"
                                       class="w-full pl-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('harga_minimum') border-red-500 @enderror" 
                                       required>
                            </div>
                            @error('harga_minimum')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Price Calculator Preview -->
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <h5 class="text-xs font-medium text-gray-700 mb-2">Preview Perhitungan Harga:</h5>
                        <div id="pricePreview" class="text-xs text-gray-600">
                            Masukkan harga per kg dan harga minimum untuk melihat preview
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Status Tarif</h4>
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="status" 
                               name="status" 
                               value="1" 
                               {{ old('status', $ongkir->status) ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="status" class="ml-2 block text-sm text-gray-900">
                            Aktifkan tarif ini
                        </label>
                    </div>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.ongkir.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="button" 
                            onclick="previewChanges()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-eye mr-2"></i>
                        Preview
                    </button>
                    <button type="submit" 
                            class="bg-primary hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-save mr-2"></i>
                        Update Tarif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-eye mr-2"></i>
                        Preview Perubahan
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
                        Update Tarif
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Calculator Modal -->
<div id="testModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-calculator mr-2"></i>
                        Test Tarif Saat Ini
                    </h3>
                    <button type="button" onclick="closeTest()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-2">Tarif yang akan ditest:</h4>
                        <p class="text-sm text-blue-800">{{ $ongkir->kota_asal }} → {{ $ongkir->kota_tujuan }} ({{ $ongkir->jenis_layanan }})</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Berat (kg)</label>
                        <input type="number" id="test_berat" step="0.1" min="{{ $ongkir->berat_minimum }}" max="{{ $ongkir->berat_maksimum }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                               placeholder="Masukkan berat ({{ $ongkir->berat_minimum }}-{{ $ongkir->berat_maksimum }} kg)">
                    </div>
                    
                    <button type="button" onclick="testCurrentTarif()" 
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-md">
                        <i class="fas fa-calculator mr-2"></i>
                        Hitung
                    </button>
                    
                    <div id="testResult" class="hidden">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Hasil Perhitungan:</h4>
                            <div id="testResultContent"></div>
                        </div>
                    </div>
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
            let previewHTML = '<div class="grid grid-cols-5 gap-2 text-xs">';
            
            const sampleWeights = [0.5, 1.0, 2.5, 5.0, 10.0];
            
            sampleWeights.forEach(weight => {
                const calculatedPrice = Math.max(hargaMinimum, hargaPerKg * weight);
                previewHTML += `
                    <div class="text-center">
                        <div class="font-medium">${weight}kg</div>
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

    // Preview changes function
    function previewChanges() {
        const form = document.getElementById('editOngkirForm');
        const formData = new FormData(form);
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        if (!validateWeights()) {
            return;
        }
        
        // Get form values
        const kotaAsal = formData.get('kota_asal');
        const kotaTujuan = formData.get('kota_tujuan');
        const jenisLayanan = formData.get('jenis_layanan');
        const estimasiHari = formData.get('estimasi_hari');
        const beratMin = parseFloat(formData.get('berat_minimum'));
        const beratMax = parseFloat(formData.get('berat_maksimum'));
        const hargaPerKg = parseFloat(formData.get('harga_per_kg'));
        const hargaMin = parseFloat(formData.get('harga_minimum'));
        const status = formData.get('status') ? 'Aktif' : 'Tidak Aktif';
        
        // Original values
        const original = {
            kotaAsal: '{{ $ongkir->kota_asal }}',
            kotaTujuan: '{{ $ongkir->kota_tujuan }}',
            jenisLayanan: '{{ $ongkir->jenis_layanan }}',
            estimasiHari: {{ $ongkir->estimasi_hari }},
            beratMin: {{ $ongkir->berat_minimum }},
            beratMax: {{ $ongkir->berat_maksimum }},
            hargaPerKg: {{ $ongkir->harga_per_kg }},
            hargaMin: {{ $ongkir->harga_minimum }},
            status: {{ $ongkir->status ? 'true' : 'false' }}
        };
        
        function getChangeClass(oldVal, newVal) {
            return oldVal != newVal ? 'text-orange-600 font-medium' : 'text-gray-900';
        }
        
        const serviceColors = {
            'Express': 'bg-red-100 text-red-800',
            'Regular': 'bg-blue-100 text-blue-800',
            'Economy': 'bg-green-100 text-green-800'
        };
        
        const previewContent = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Data Lama</h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-gray-600">Rute:</span> ${original.kotaAsal} → ${original.kotaTujuan}</div>
                        <div><span class="text-gray-600">Layanan:</span> ${original.jenisLayanan}</div>
                        <div><span class="text-gray-600">Estimasi:</span> ${original.estimasiHari} hari</div>
                        <div><span class="text-gray-600">Berat:</span> ${original.beratMin} - ${original.beratMax} kg</div>
                        <div><span class="text-gray-600">Harga/kg:</span> Rp ${original.hargaPerKg.toLocaleString('id-ID')}</div>
                        <div><span class="text-gray-600">Harga Min:</span> Rp ${original.hargaMin.toLocaleString('id-ID')}</div>
                        <div><span class="text-gray-600">Status:</span> ${original.status ? 'Aktif' : 'Tidak Aktif'}</div>
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Data Baru</h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-gray-600">Rute:</span> <span class="${getChangeClass(original.kotaAsal + ' → ' + original.kotaTujuan, kotaAsal + ' → ' + kotaTujuan)}">${kotaAsal} → ${kotaTujuan}</span></div>
                        <div><span class="text-gray-600">Layanan:</span> <span class="${getChangeClass(original.jenisLayanan, jenisLayanan)}">${jenisLayanan}</span></div>
                        <div><span class="text-gray-600">Estimasi:</span> <span class="${getChangeClass(original.estimasiHari, estimasiHari)}">${estimasiHari} hari</span></div>
                        <div><span class="text-gray-600">Berat:</span> <span class="${getChangeClass(original.beratMin + '-' + original.beratMax, beratMin + '-' + beratMax)}">${beratMin} - ${beratMax} kg</span></div>
                        <div><span class="text-gray-600">Harga/kg:</span> <span class="${getChangeClass(original.hargaPerKg, hargaPerKg)}">Rp ${hargaPerKg.toLocaleString('id-ID')}</span></div>
                        <div><span class="text-gray-600">Harga Min:</span> <span class="${getChangeClass(original.hargaMin, hargaMin)}">Rp ${hargaMin.toLocaleString('id-ID')}</span></div>
                        <div><span class="text-gray-600">Status:</span> <span class="${getChangeClass(original.status, status.toLowerCase())}">${status}</span></div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('previewContent').innerHTML = previewContent;
        document.getElementById('previewModal').classList.remove('hidden');
    }
    
    // Test current tarif
    function testCurrentTarif() {
        const berat = parseFloat(document.getElementById('test_berat').value);
        
        if (!berat) {
            alert('Masukkan berat terlebih dahulu');
            return;
        }
        
        const hargaPerKg = {{ $ongkir->harga_per_kg }};
        const hargaMin = {{ $ongkir->harga_minimum }};
        const harga = Math.max(hargaMin, hargaPerKg * berat);
        
        const resultContent = `
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Berat:</span>
                    <span class="font-medium">${berat} kg</span>
                </div>
                <div class="flex justify-between">
                    <span>Harga per kg:</span>
                    <span>Rp ${hargaPerKg.toLocaleString('id-ID')}</span>
                </div>
                <div class="flex justify-between">
                    <span>Harga minimum:</span>
                    <span>Rp ${hargaMin.toLocaleString('id-ID')}</span>
                </div>
                <hr>
                <div class="flex justify-between font-medium text-lg">
                    <span>Total:</span>
                    <span class="text-primary">Rp ${harga.toLocaleString('id-ID')}</span>
                </div>
            </div>
        `;
        
        document.getElementById('testResultContent').innerHTML = resultContent;
        document.getElementById('testResult').classList.remove('hidden');
    }
    
    // Modal functions
    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
    }
    
    function closeTest() {
        document.getElementById('testModal').classList.add('hidden');
        document.getElementById('testResult').classList.add('hidden');
    }
    
    function submitForm() {
        document.getElementById('editOngkirForm').submit();
    }
    
    // Event listeners
    document.getElementById('harga_per_kg').addEventListener('input', updatePricePreview);
    document.getElementById('harga_minimum').addEventListener('input', updatePricePreview);
    document.getElementById('berat_minimum').addEventListener('change', validateWeights);
    document.getElementById('berat_maksimum').addEventListener('change', validateWeights);
    
    document.getElementById('testCurrentTarif').addEventListener('click', function() {
        document.getElementById('testModal').classList.remove('hidden');
    });
    
    // Form validation
    document.getElementById('editOngkirForm').addEventListener('submit', function(e) {
        if (!validateWeights()) {
            e.preventDefault();
        }
    });
    
    // Initialize
    updatePricePreview();
    
    // Close modals when clicking outside
    document.getElementById('previewModal').addEventListener('click', function(e) {
        if (e.target === this) closePreview();
    });
    
    document.getElementById('testModal').addEventListener('click', function(e) {
        if (e.target === this) closeTest();
    });
</script>
@endsection