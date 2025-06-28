{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah User Baru</h1>
                <p class="text-gray-600 mt-1">Buat akun pengguna baru dalam sistem</p>
            </div>
            <a href="{{ route('admin.users.index') }}" 
               class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-times text-xl"></i>
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Informasi User</h2>
            <p class="text-sm text-gray-600">Lengkapi form di bawah untuk menambahkan user baru</p>
        </div>
        
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div>
                <h3 class="text-md font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" 
                               class="w-full @error('name') border-red-500 @enderror"
                               value="{{ old('name') }}" 
                               placeholder="Masukkan username"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Username untuk login (tanpa spasi)</p>
                    </div>
                    
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" 
                               class="w-full @error('nama_lengkap') border-red-500 @enderror"
                               value="{{ old('nama_lengkap') }}" 
                               placeholder="Masukkan nama lengkap"
                               required>
                        @error('nama_lengkap')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div>
                <h3 class="text-md font-medium text-gray-900 mb-4">Informasi Kontak</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" 
                               class="w-full @error('email') border-red-500 @enderror"
                               value="{{ old('email') }}" 
                               placeholder="user@example.com"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nomor_telepon" name="nomor_telepon" 
                               class="w-full @error('nomor_telepon') border-red-500 @enderror"
                               value="{{ old('nomor_telepon') }}" 
                               placeholder="08123456789"
                               required>
                        @error('nomor_telepon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <textarea id="alamat" name="alamat" rows="3" 
                          class="w-full @error('alamat') border-red-500 @enderror" 
                          placeholder="Masukkan alamat lengkap"
                          required>{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role & Branch -->
            <div>
                <h3 class="text-md font-medium text-gray-900 mb-4">Role & Penempatan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status/Role <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" 
                                class="w-full @error('status') border-red-500 @enderror" 
                                required>
                            <option value="">Pilih Status</option>
                            <option value="Admin" {{ old('status') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Staff" {{ old('status') == 'Staff' ? 'selected' : '' }}>Staff</option>
                            <option value="Customer" {{ old('status') == 'Customer' ? 'selected' : '' }}>Customer</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 space-y-1">
                            <div class="flex items-center text-xs text-gray-600">
                                <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                                <span><strong>Admin:</strong> Akses penuh ke semua fitur</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-600">
                                <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                                <span><strong>Staff:</strong> Akses terbatas untuk operasional</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-600">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                <span><strong>Customer:</strong> Akses untuk membuat pengiriman</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="branch" class="block text-sm font-medium text-gray-700 mb-2">
                            Branch <span class="text-red-500">*</span>
                        </label>
                        <select id="branch" name="branch" 
                                class="w-full @error('branch') border-red-500 @enderror" 
                                required>
                            <option value="">Pilih Branch</option>
                            @foreach($branches as $branchKey => $branchName)
                                <option value="{{ $branchKey }}" {{ old('branch') == $branchKey ? 'selected' : '' }}>
                                    {{ $branchName }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div>
                <h3 class="text-md font-medium text-gray-900 mb-4">Keamanan Akun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password" name="password" 
                               class="w-full @error('password') border-red-500 @enderror"
                               placeholder="Minimal 8 karakter"
                               required>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div class="mt-1 text-xs text-gray-500">
                            <ul class="list-disc list-inside">
                                <li>Minimal 8 karakter</li>
                                <li>Kombinasi huruf dan angka direkomendasikan</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               class="w-full @error('password_confirmation') border-red-500 @enderror"
                               placeholder="Ulangi password"
                               required>
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-6 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-primary text-white rounded-md hover:bg-green-600 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Auto generate username from nama lengkap
    document.getElementById('nama_lengkap').addEventListener('input', function() {
        const namaLengkap = this.value;
        const username = namaLengkap.toLowerCase()
            .replace(/\s+/g, '_')
            .replace(/[^a-z0-9_]/g, '');
        
        // Only set if name field is empty
        const nameField = document.getElementById('name');
        if (!nameField.value) {
            nameField.value = username;
        }
    });

    // Password strength indicator
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthText = document.getElementById('password-strength');
        
        if (password.length < 8) {
            // Add visual feedback if needed
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak sama!');
            return false;
        }
    });
</script>
@endsection