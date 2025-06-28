{{-- resources/views/admin/branches/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Branch - ' . $branch->nama_branch)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.branches.show', $branch) }}" 
                   class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Branch</h1>
                    <p class="text-gray-600 mt-1">Perbarui informasi {{ $branch->nama_branch }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.branches.show', $branch) }}" 
                   class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times text-xl"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Current Branch Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center">
            <div class="h-12 w-12 rounded-lg bg-blue-200 flex items-center justify-center">
                <i class="fas fa-building text-blue-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="font-medium text-blue-900">{{ $branch->nama_branch }}</h3>
                <p class="text-sm text-blue-700">
                    {{ $branch->kode_branch }} • {{ $branch->kota }}, {{ $branch->provinsi }} • 
                    <span class="px-2 py-1 text-xs rounded-full {{ $branch->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $branch->status ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                    • {{ $branch->users->count() }} users
                </p>
            </div>
        </div>
    </div>

    <!-- Warning if branch has users -->
    @if($branch->users->count() > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex">
            <i class="fas fa-exclamation-triangle text-yellow-400 mr-3 mt-0.5"></i>
            <div>
                <h3 class="font-medium text-yellow-800">Perhatian!</h3>
                <p class="text-sm text-yellow-700 mt-1">
                    Branch ini memiliki {{ $branch->users->count() }} users aktif. 
                    Perubahan informasi branch akan mempengaruhi semua users tersebut.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Update Informasi Branch</h2>
            <p class="text-sm text-gray-600">Perbarui data branch sesuai kebutuhan</p>
        </div>
        
        <form action="{{ route('admin.branches.update', $branch) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
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
                               value="{{ old('nama_branch', $branch->nama_branch) }}" 
                               placeholder="contoh: CSM Cargo Jakarta"
                               required>
                        @error('nama_branch')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Nama lengkap cabang yang akan ditampilkan</p>
                        @if($branch->users->count() > 0)
                            <p class="text-xs text-yellow-600 mt-1">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Perubahan nama akan mempengaruhi {{ $branch->users->count() }} users
                            </p>
                        @endif
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" 
                                class="w-full @error('status') border-red-500 @enderror" 
                                required>
                            <option value="">Pilih Status</option>
                            <option value="1" {{ old('status', $branch->status) == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status', $branch->status) == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if($branch->users->count() > 0 && !$branch->status)
                            <p class="text-xs text-red-600 mt-1">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Menonaktifkan branch akan mempengaruhi akses {{ $branch->users->count() }} users
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Read-only Kode Branch -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Branch
                    </label>
                    <input type="text" 
                           class="w-full bg-gray-50 text-gray-500 cursor-not-allowed"
                           value="{{ $branch->kode_branch }}" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Kode branch tidak dapat diubah setelah dibuat</p>
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
                                  required>{{ old('alamat', $branch->alamat) }}</textarea>
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
                                   value="{{ old('kota', $branch->kota) }}" 
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
                                   value="{{ old('provinsi', $branch->provinsi) }}" 
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
                                   value="{{ old('kode_pos', $branch->kode_pos) }}" 
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
                               value="{{ old('telepon', $branch->telepon) }}" 
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
                               value="{{ old('email', $branch->email) }}" 
                               placeholder="contoh: jakarta@csmcargo.com"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Email resmi branch untuk komunikasi</p>
                    </div>
                </div>
            </div>

            <!-- GPS Coordinates -->
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
                               value="{{ old('latitude', $branch->latitude) }}" 
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
                               value="{{ old('longitude', $branch->longitude) }}" 
                               placeholder="contoh: 106.8456">
                        @error('longitude')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Koordinat bujur lokasi branch</p>
                    </div>
                </div>
                
                <!-- GPS Actions -->
                <div class="mt-4 flex items-center space-x-4">
                    <button type="button" id="getCurrentLocation" 
                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-location-arrow mr-2"></i>
                        Update Lokasi Saat Ini
                    </button>
                    
                    @if($branch->latitude && $branch->longitude)
                        <a href="https://maps.google.com/?q={{ $branch->latitude }},{{ $branch->longitude }}" 
                           target="_blank"
                           class="bg-green-100 hover:bg-green-200 text-green-700 px-4 py-2 rounded-md text-sm">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Lihat Lokasi Saat Ini
                        </a>
                    @endif
                </div>
            </div>

            <!-- Branch Statistics -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Informasi Branch</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Branch ID:</span> {{ $branch->id }}
                    </div>
                    <div>
                        <span class="font-medium">Kode Branch:</span> {{ $branch->kode_branch }}
                    </div>
                    <div>
                        <span class="font-medium">Total Users:</span> {{ $branch->users->count() }}
                    </div>
                    <div>
                        <span class="font-medium">Dibuat:</span> {{ $branch->created_at->format('d M Y, H:i') }} WIB
                    </div>
                    <div>
                        <span class="font-medium">Terakhir Update:</span> {{ $branch->updated_at->format('d M Y, H:i') }} WIB
                    </div>
                    <div>
                        <span class="font-medium">Status:</span> 
                        <span class="px-2 py-1 text-xs rounded-full {{ $branch->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $branch->status ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.branches.show', $branch) }}" 
                       class="px-6 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <a href="{{ route('admin.branches.index') }}" 
                       class="text-sm text-gray-500 hover:text-gray-700">
                        Kembali ke daftar branches
                    </a>
                </div>
                <button type="submit" 
                        class="px-6 py-2 bg-primary text-white rounded-md hover:bg-green-600 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Update Branch
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Format phone number
    document.getElementById('telepon').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.startsWith('62')) {
            value = '+' + value;
        }
        
        if (value.length > 15) {
            value = value.substring(0, 15);
        }
        
        e.target.value = value;
    });

    // Format postal code
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
                    
                    button.innerHTML = '<i class="fas fa-check mr-2"></i>Lokasi berhasil diupdate';
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

    // Show warning for critical changes
    const originalName = '{{ $branch->nama_branch }}';
    const originalStatus = '{{ $branch->status }}';
    const userCount = {{ $branch->users->count() }};
    
    document.getElementById('nama_branch').addEventListener('change', function() {
        if (this.value !== originalName && userCount > 0) {
            // Could show additional warning
        }
    });
    
    document.getElementById('status').addEventListener('change', function() {
        if (this.value != originalStatus && userCount > 0) {
            if (this.value == '0') {
                if (!confirm(`Menonaktifkan branch akan mempengaruhi ${userCount} users. Yakin ingin melanjutkan?`)) {
                    this.value = originalStatus;
                }
            }
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