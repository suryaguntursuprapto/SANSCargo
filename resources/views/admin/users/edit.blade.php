{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit User - ' . $user->nama_lengkap)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
                    <p class="text-gray-600 mt-1">Perbarui informasi pengguna {{ $user->nama_lengkap }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times text-xl"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Current User Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center">
            @if($user->profile_image)
                <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" 
                     alt="Profile" class="h-12 w-12 rounded-full object-cover">
            @else
                <div class="h-12 w-12 rounded-full bg-blue-200 flex items-center justify-center">
                    <span class="text-blue-600 font-medium">{{ substr($user->name, 0, 1) }}</span>
                </div>
            @endif
            <div class="ml-4">
                <h3 class="font-medium text-blue-900">{{ $user->nama_lengkap }}</h3>
                <p class="text-sm text-blue-700">{{ $user->email }} • {{ $user->status }} • {{ $user->branch }}</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Update Informasi User</h2>
            <p class="text-sm text-gray-600">Perbarui data user sesuai kebutuhan</p>
        </div>
        
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
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
                               value="{{ old('name', $user->name) }}" 
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
                               value="{{ old('nama_lengkap', $user->nama_lengkap) }}" 
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
                               value="{{ old('email', $user->email) }}" 
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
                               value="{{ old('nomor_telepon', $user->nomor_telepon) }}" 
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
                          required>{{ old('alamat', $user->alamat) }}</textarea>
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
                            <option value="Admin" {{ old('status', $user->status) == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Staff" {{ old('status', $user->status) == 'Staff' ? 'selected' : '' }}>Staff</option>
                            <option value="Customer" {{ old('status', $user->status) == 'Customer' ? 'selected' : '' }}>Customer</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        @if($user->status !== old('status', $user->status))
                            <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                <div class="flex">
                                    <i class="fas fa-exclamation-triangle text-yellow-400 mr-2 mt-0.5"></i>
                                    <div class="text-sm">
                                        <p class="font-medium text-yellow-800">Perhatian!</p>
                                        <p class="text-yellow-700">Mengubah role akan mempengaruhi hak akses user ke sistem.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                                <option value="{{ $branchKey }}" {{ old('branch', $user->branch) == $branchKey ? 'selected' : '' }}>
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

            <!-- Password Change (Optional) -->
            <div>
                <h3 class="text-md font-medium text-gray-900 mb-4">Ubah Password (Opsional)</h3>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="change_password" name="change_password" 
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="change_password" class="ml-2 text-sm font-medium text-gray-900">
                            Saya ingin mengubah password user ini
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Centang jika ingin mengubah password user</p>
                </div>
                
                <div id="password_fields" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru
                        </label>
                        <input type="password" id="password" name="password" 
                               class="w-full @error('password') border-red-500 @enderror"
                               placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               class="w-full @error('password_confirmation') border-red-500 @enderror"
                               placeholder="Ulangi password baru">
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Info -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Informasi Akun</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">User ID:</span> {{ $user->id }}
                    </div>
                    <div>
                        <span class="font-medium">Bergabung:</span> {{ $user->created_at->format('d M Y, H:i') }} WIB
                    </div>
                    <div>
                        <span class="font-medium">Terakhir Update:</span> {{ $user->updated_at->format('d M Y, H:i') }} WIB
                    </div>
                    <div>
                        <span class="font-medium">Status:</span> 
                        <span class="px-2 py-1 text-xs rounded-full {{ $user->status === 'Admin' ? 'bg-red-100 text-red-800' : ($user->status === 'Staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ $user->status }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.users.show', $user) }}" 
                       class="px-6 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="text-sm text-gray-500 hover:text-gray-700">
                        Kembali ke daftar users
                    </a>
                </div>
                <button type="submit" 
                        class="px-6 py-2 bg-primary text-white rounded-md hover:bg-green-600 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Toggle password fields
    document.getElementById('change_password').addEventListener('change', function() {
        const passwordFields = document.getElementById('password_fields');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        
        if (this.checked) {
            passwordFields.style.display = 'grid';
            passwordInput.required = true;
            confirmPasswordInput.required = true;
        } else {
            passwordFields.style.display = 'none';
            passwordInput.required = false;
            confirmPasswordInput.required = false;
            passwordInput.value = '';
            confirmPasswordInput.value = '';
        }
    });

    // Auto generate username from nama lengkap (optional)
    document.getElementById('nama_lengkap').addEventListener('input', function() {
        // Only suggest if user wants it - could add a checkbox for this
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const changePassword = document.getElementById('change_password').checked;
        
        if (changePassword) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak sama!');
                return false;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password minimal 8 karakter!');
                return false;
            }
        }
    });

    // Show warning if email is changed
    const originalEmail = '{{ $user->email }}';
    document.getElementById('email').addEventListener('change', function() {
        if (this.value !== originalEmail && this.value.length > 0) {
            // Could show a warning about email change
        }
    });

    // Show role change warning
    const originalStatus = '{{ $user->status }}';
    document.getElementById('status').addEventListener('change', function() {
        const warningDiv = document.querySelector('.bg-yellow-50');
        if (this.value !== originalStatus && this.value.length > 0) {
            if (!warningDiv) {
                // Warning already shown in HTML
            }
        }
    });
</script>
@endsection