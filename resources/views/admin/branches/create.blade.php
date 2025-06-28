{{-- resources/views/admin/branches/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Branch')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Branch Baru</h1>
                <p class="text-gray-600 mt-1">Buat cabang baru CSM Cargo</p>
            </div>
            <a href="{{ route('admin.branches.index') }}" 
               class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-times text-xl"></i>
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Informasi Branch</h2>
            <p class="text-sm text-gray-600">Lengkapi form di bawah untuk menambahkan branch baru</p>
        </div>
        
        <form action="{{ route('admin.branches.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div>
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-building text-green-600 mr-2"></i>
                    Informasi Dasar
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_branch" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Branch <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_branch" name="nama_branch" 
                               class="w-full @error('nama_branch') border-red-500 @enderror"
                               value="{{ old('nama_branch') }}" 
                               placeholder="contoh: CSM Cargo Jakarta"
                               required>
                        @error('nama_branch')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Nama lengkap cabang yang akan ditampilkan</p>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" 
                                class="w-full @error('status') border-red-500 @enderror" 
                                required>
                            <option value="">Pilih Status</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div class="mt-1 space-y-1">
                            <div class="flex items-center text-xs text-gray-600">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                <span><strong>Aktif:</strong> Branch dapat menerima pengiriman</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-600">
                                <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                                <span><strong>Tidak Aktif:</strong> Branch temporary tidak beroperasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div>
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                    Informasi Lokasi
                </h3>
                <div class="space-y-6">
                    <!-- Address -->
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea id="alamat" name="alamat" rows="3" 
                                  class="w-full @error('alamat') border-red-500 @enderror" 
                                  placeholder="Masukkan alamat lengkap branch"
                                  required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- City, Province, Postal Code -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="kota" class="block text-sm font-medium text-gray-700 mb-2">
                                Kota <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="kota" name="kota" 
                                   class="w-full @error('kota') border-red-500 @enderror"
                                   value="{{ old('kota') }}" 
                                   placeholder="contoh: Jakarta"
                                   required>
                            @error('kota')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Provinsi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="provinsi" name="provinsi" 
                                   class="w-full @error('provinsi') border-red-500 @enderror"
                                   value="{{ old('provinsi') }}" 
                                   placeholder="contoh: DKI Jakarta"
                                   required>
                            @error('provinsi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Pos <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="kode_pos" name="kode_pos" 
                                   class="w-full @error('kode_pos') border-red-500 @enderror"
                                   value="{{ old('kode_pos') }}" 
                                   placeholder="contoh: 12345"
                                   pattern="[0-9]{5}"
                                   maxlength="5"
                                   required>
                            @error('kode_pos')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div>
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-phone text-purple-600 mr-2"></i>
                    Informasi Kontak
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">
                            Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="telepon" name="telepon" 
                               class="w-full @error('telepon') border-red-500 @enderror"
                               value="{{ old('telepon') }}" 
                               placeholder="contoh: 021-12345678"
                               required>
                        @error('telepon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Nomor telepon kantor branch</p>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" 
                               class="w-full @error('email') border-red-500 @enderror"
                               value="{{ old('email') }}" 
                               placeholder="contoh: jakarta@csmcargo.com"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Email resmi branch untuk komunikasi</p>
                    </div>
                </div>
            </div>

            <!-- GPS Coordinates (Optional) -->
            <div>
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-globe text-indigo-600 mr-2"></i>
                    Koordinat GPS <span class="text-sm font-normal text-gray-500">(Opsional)</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Latitude
                        </label>
                        <input type="number" step="any" id="latitude" name="latitude" 
                               class="w-full @error('latitude') border-red-500 @enderror"
                               value="{{ old('latitude') }}" 
                               placeholder="contoh: -6.2088">
                        @error('latitude')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Koordinat lintang lokasi branch</p>
                    </div>
                    
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Longitude
                        </label>
                        <input type="number" step="any" id="longitude" name="longitude" 
                               class="w-full @error('longitude') border-red-500 @enderror"
                               value="{{ old('longitude') }}" 
                               placeholder="contoh: 106.8456">
                        @error('longitude')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Koordinat bujur lokasi branch</p>
                    </div>
                </div>
                
                <!-- Get Current Location Button -->
                <div class="mt-4">
                    <button type="button" id="getCurrentLocation" 
                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-location-arrow mr-2"></i>
                        Gunakan Lokasi Saat Ini
                    </button>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Koordinat GPS membantu dalam perhitungan jarak dan routing pengiriman
                    </p>
                </div>
            </div>

            <!-- Auto-generated Info -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Informasi Otomatis</h4>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>Kode Branch:</strong> Akan dibuat otomatis saat menyimpan (format: BR + 4 karakter acak)</p>
                    <p><strong>Tanggal Dibuat:</strong> {{ now()->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.branches.index') }}" 
                   class="px-6 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-primary text-white rounded-md hover:bg-green-600 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Branch
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Auto-generate email from branch name and city
    function updateEmail() {
        const namaBranch = document.getElementById('nama_branch').value;
        const kota = document.getElementById('kota').value;
        const emailField = document.getElementById('email');
        
        if (namaBranch && kota && !emailField.value) {
            const cleanKota = kota.toLowerCase().replace(/\s+/g, '');
            const suggestedEmail = `${cleanKota}@csmcargo.com`;
            emailField.value = suggestedEmail;
        }
    }

    document.getElementById('nama_branch').addEventListener('blur', updateEmail);
    document.getElementById('kota').addEventListener('blur', updateEmail);

    // Format phone number
    document.getElementById('telepon').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        // Format untuk nomor telepon Indonesia
        if (value.startsWith('62')) {
            value = '+' + value;
        } else if (value.startsWith('0')) {
            // Format untuk nomor lokal
        }
        
        // Batasi panjang
        if (value.length > 15) {
            value = value.substring(0, 15);
        }
        
        e.target.value = value;
    });

    // Format postal code (hanya angka, 5 digit)
    document.getElementById('kode_pos').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '').substring(0, 5);
    });

    // Get current location for GPS coordinates
    document.getElementById('getCurrentLocation').addEventListener('click', function() {
        const button = this;
        const originalText = button.innerHTML;
        
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mendapatkan lokasi...';
        button.disabled = true;
        
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                    document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                    
                    button.innerHTML = '<i class="fas fa-check mr-2"></i>Lokasi berhasil didapat';
                    button.className = 'bg-green-100 hover:bg-green-200 text-green-700 px-4 py-2 rounded-md text-sm';
                    
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.className = 'bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-md text-sm';
                        button.disabled = false;
                    }, 3000);
                },
                function(error) {
                    alert('Tidak bisa mendapatkan lokasi: ' + error.message);
                    button.innerHTML = originalText;
                    button.disabled = false;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            alert('Geolocation tidak didukung oleh browser ini.');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const requiredFields = ['nama_branch', 'alamat', 'kota', 'provinsi', 'kode_pos', 'telepon', 'email', 'status'];
        let isValid = true;
        
        requiredFields.forEach(function(fieldName) {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (!field.value.trim()) {
                isValid = false;
                field.focus();
                return false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi.');
        }
    });
</script>
@endsection