@extends('layouts.app')

@section('content')
<div class="w-full max-w-6xl mx-auto px-4">
    <div class="mb-6">
        <nav aria-label="breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-primary">Dashboard</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('pengaturan.profile') }}" class="text-gray-600 hover:text-primary">Profil Pengguna</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-800 font-medium">Edit Profil</li>
            </ol>
        </nav>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
    <div class="bg-red-500 text-white p-4 rounded-md mb-4 shadow-lg" role="alert">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="font-bold">Gagal!</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
            <button type="button" class="ml-auto focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-green-500 text-white p-4 rounded-md mb-4 shadow-lg" role="alert">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="font-bold">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
            <button type="button" class="ml-auto focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 border-b px-6 py-4 flex justify-between items-center">
            <h5 class="font-medium text-lg text-gray-800">
                <i class="fas fa-user-edit text-primary mr-2"></i>Edit Profil
            </h5>
            <a href="{{ route('pengaturan.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="p-6">
            <!-- Profile Picture Section -->
            <div class="mb-8">
                <h4 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">
                    <i class="fas fa-camera text-primary mr-2"></i>Foto Profil
                </h4>
                
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-gray-100 shadow-md mb-4 sm:mb-0">
                        @if($user->profile_image)
                            <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" 
                                 alt="Profile Image" 
                                 class="w-full h-full object-cover" 
                                 id="profileImagePreview">
                        @else
                            <img src="{{ asset('images/default-profile.png') }}" 
                                 alt="Default Profile" 
                                 class="w-full h-full object-cover"
                                 id="profileImagePreview">
                        @endif
                    </div>
                    
                    <div class="flex flex-col w-full max-w-lg">
                        <div class="mb-4">
                            <input type="file" id="profileImageInput" class="hidden" accept="image/*">
                            <div class="flex flex-wrap gap-3">
                                <button type="button" id="uploadImageBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-upload mr-2"></i> Upload Foto
                                </button>
                                
                                @if($user->profile_image)
                                <button type="button" id="removeImageBtn" class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                    <i class="fas fa-trash-alt mr-2"></i> Hapus
                                </button>
                                @endif
                            </div>
                        </div>
                        
                        <div id="uploadProgress" class="hidden w-full bg-gray-200 rounded-full h-2.5 mb-4">
                            <div id="uploadProgressBar" class="bg-primary h-2.5 rounded-full" style="width: 0%"></div>
                        </div>
                        
                        <div id="uploadStatus" class="hidden text-sm mb-4 font-medium"></div>
                        
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">Format yang didukung: JPG, PNG, GIF</p>
                                    <p class="text-xs text-blue-600 mt-1">Ukuran maksimal: 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Profile Information Form -->
            <form action="{{ route('pengaturan.update') }}" method="POST">
                @csrf
                
                <h4 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">
                    <i class="fas fa-info-circle text-primary mr-2"></i>Informasi Pribadi
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap*</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" 
                                id="nama_lengkap" 
                                name="nama_lengkap" 
                                placeholder="Masukkan Nama Lengkap" 
                                value="{{ old('nama_lengkap', $user->nama_lengkap ?? '') }}" 
                                class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 {{ $errors->has('nama_lengkap') ? 'border-red-500' : '' }}"
                                required>
                        </div>
                        @error('nama_lengkap')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email*</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" 
                                id="email" 
                                name="email" 
                                placeholder="Masukkan Email" 
                                value="{{ old('email', $user->email ?? '') }}" 
                                class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 {{ $errors->has('email') ? 'border-red-500' : '' }}"
                                required>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon*</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone-alt text-gray-400"></i>
                            </div>
                            <div class="absolute inset-y-0 left-0 pl-10 flex items-center pointer-events-none">
                                <span class="text-gray-500">+62</span>
                            </div>
                            <input type="text" 
                                id="nomor_telepon" 
                                name="nomor_telepon" 
                                placeholder="Masukkan Nomor Telepon" 
                                value="{{ old('nomor_telepon', $user->nomor_telepon ?? '') }}" 
                                class="pl-20 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 {{ $errors->has('nomor_telepon') ? 'border-red-500' : '' }}"
                                required>
                        </div>
                        @error('nomor_telepon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="branch" class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <input type="text" 
                                id="branch" 
                                name="branch" 
                                placeholder="Masukkan Branch" 
                                value="{{ old('branch', $user->branch ?? '') }}" 
                                class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 {{ $errors->has('branch') ? 'border-red-500' : '' }}">
                        </div>
                        @error('branch')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute top-3 left-0 pl-3 flex items-start pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <textarea 
                                id="alamat" 
                                name="alamat" 
                                placeholder="Masukkan Alamat" 
                                rows="3" 
                                class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 {{ $errors->has('alamat') ? 'border-red-500' : '' }}">{{ old('alamat', $user->alamat ?? '') }}</textarea>
                        </div>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('pengaturan.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 mr-4">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto close alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.remove();
            });
        }, 5000);
        
        // Validasi nomor telepon hanya angka
        const phoneInput = document.getElementById('nomor_telepon');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });
        }
        
        // Profile Image Upload Functionality
        const profileImageInput = document.getElementById('profileImageInput');
        const uploadImageBtn = document.getElementById('uploadImageBtn');
        const removeImageBtn = document.getElementById('removeImageBtn');
        const profileImagePreview = document.getElementById('profileImagePreview');
        const uploadProgress = document.getElementById('uploadProgress');
        const uploadProgressBar = document.getElementById('uploadProgressBar');
        const uploadStatus = document.getElementById('uploadStatus');
        
        // Click on upload button to trigger file input
        if (uploadImageBtn) {
            uploadImageBtn.addEventListener('click', function() {
                profileImageInput.click();
            });
        }
        
        // Preview image before upload
        if (profileImageInput) {
            profileImageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    
                    // Check file size (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                        this.value = '';
                        return;
                    }
                    
                    // Check file type
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    if (!validTypes.includes(file.type)) {
                        alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                        this.value = '';
                        return;
                    }
                    
                    // Preview image
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profileImagePreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                    
                    // Upload the image using AJAX
                    uploadImageAjax(file);
                }
            });
        }
        
        // Upload image using AJAX
        function uploadImageAjax(file) {
            const formData = new FormData();
            formData.append('profile_image', file);
            formData.append('_token', '{{ csrf_token() }}');
            
            const xhr = new XMLHttpRequest();
            
            // Show progress
            uploadProgress.classList.remove('hidden');
            uploadStatus.classList.remove('hidden');
            uploadStatus.textContent = 'Mengupload...';
            uploadStatus.className = 'text-sm mb-4 font-medium text-blue-500';
            
            // Track upload progress
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    uploadProgressBar.style.width = percentComplete + '%';
                }
            });
            
            // Handle completed upload
            xhr.addEventListener('load', function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    // Success
                    uploadProgressBar.style.width = '100%';
                    uploadStatus.textContent = 'Upload berhasil!';
                    uploadStatus.className = 'text-sm mb-4 font-medium text-green-500';
                    
                    // Show success message
                    const successMessage = document.createElement('div');
                    successMessage.className = 'bg-green-500 text-white p-4 rounded-md mb-4 shadow-lg';
                    successMessage.setAttribute('role', 'alert');
                    successMessage.innerHTML = `
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="font-bold">Berhasil!</p>
                                <p class="text-sm">Foto profil berhasil diperbarui.</p>
                            </div>
                            <button type="button" class="ml-auto focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    
                    // Add to page
                    const container = document.querySelector('.w-full.max-w-6xl');
                    container.insertBefore(successMessage, container.firstChild);
                    
                    // Auto hide after 5 seconds
                    setTimeout(function() {
                        if (document.body.contains(successMessage)) {
                            successMessage.remove();
                        }
                    }, 5000);
                    
                    // Show remove button if not already visible
                    if (!removeImageBtn && document.querySelector('[id="removeImageBtn"]') === null) {
                        const btnContainer = document.querySelector('.flex.flex-wrap.gap-3');
                        const newBtn = document.createElement('button');
                        newBtn.id = 'removeImageBtn';
                        newBtn.className = 'inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50';
                        newBtn.innerHTML = '<i class="fas fa-trash-alt mr-2"></i> Hapus';
                        newBtn.addEventListener('click', handleRemoveImage);
                        btnContainer.appendChild(newBtn);
                    }
                    
                    // Hide progress after 1 second
                    setTimeout(function() {
                        uploadProgress.classList.add('hidden');
                        uploadStatus.classList.add('hidden');
                    }, 1000);
                } else {
                    // Error
                    uploadStatus.textContent = 'Gagal mengupload foto!';
                    uploadStatus.className = 'text-sm mb-4 font-medium text-red-500';
                    
                    // Reset progress bar
                    uploadProgressBar.style.width = '0%';
                }
            });
            
            // Handle network error
            xhr.addEventListener('error', function() {
                uploadStatus.textContent = 'Terjadi kesalahan jaringan!';
                uploadStatus.className = 'text-sm mb-4 font-medium text-red-500';
                uploadProgressBar.style.width = '0%';
            });
            
            // Send the AJAX request
            xhr.open('POST', '{{ route("pengaturan.update.image") }}', true);
            xhr.send(formData);
        }
        
        // Remove profile image handler
        function handleRemoveImage() {
            if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                // Use AJAX to delete profile image
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route("pengaturan.update.image") }}', true);
                
                // Set headers
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                
                // Show status
                uploadStatus.classList.remove('hidden');
                uploadStatus.textContent = 'Menghapus foto...';
                uploadStatus.className = 'text-sm mb-4 font-medium text-blue-500';
                
                // Handle response
                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        // Update image preview
                        profileImagePreview.src = '{{ asset("images/default-profile.png") }}';
                        
                        // Hide remove button
                        const removeBtn = document.getElementById('removeImageBtn');
                        if (removeBtn) {
                            removeBtn.remove();
                        }
                        
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.className = 'bg-green-500 text-white p-4 rounded-md mb-4 shadow-lg';
                        successMessage.setAttribute('role', 'alert');
                        successMessage.innerHTML = `
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="font-bold">Berhasil!</p>
                                    <p class="text-sm">Foto profil berhasil dihapus.</p>
                                </div>
                                <button type="button" class="ml-auto focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                        
                        // Add to page
                        const container = document.querySelector('.w-full.max-w-6xl');
                        container.insertBefore(successMessage, container.firstChild);
                        
                        // Auto hide after 5 seconds
                        setTimeout(function() {
                            if (document.body.contains(successMessage)) {
                                successMessage.remove();
                            }
                        }, 5000);
                        
                        // Hide status after 1 second
                        setTimeout(function() {
                            uploadStatus.classList.add('hidden');
                        }, 1000);
                    } else {
                        // Error
                        uploadStatus.textContent = 'Gagal menghapus foto!';
                        uploadStatus.className = 'text-sm mb-4 font-medium text-red-500';
                        
                        // Hide status after 2 seconds
                        setTimeout(function() {
                            uploadStatus.classList.add('hidden');
                        }, 2000);
                    }
                };
                
                // Handle network error
                xhr.onerror = function() {
                    uploadStatus.textContent = 'Terjadi kesalahan jaringan!';
                    uploadStatus.className = 'text-sm mb-4 font-medium text-red-500';
                    
                    // Hide status after 2 seconds
                    setTimeout(function() {
                        uploadStatus.classList.add('hidden');
                    }, 2000);
                };
                
                // Send request
                xhr.send('remove_image=1&_token={{ csrf_token() }}');
            }
        }
        
        // Attach remove handler to existing button
        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', handleRemoveImage);
        }
    });
</script>
@endsection