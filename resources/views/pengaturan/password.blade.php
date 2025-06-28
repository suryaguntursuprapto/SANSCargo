@extends('layouts.app')

@section('content')
<div class="w-full max-w-4xl mx-auto px-4">
    <div class="mb-6">
        <nav aria-label="breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-primary">Dashboard</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('pengaturan.profile') }}" class="text-gray-600 hover:text-primary">Profil Pengguna</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-800 font-medium">Ubah Password</li>
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
                <i class="fas fa-key text-primary mr-2"></i>Ubah Password
            </h5>
            <a href="{{ route('pengaturan.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="p-6">
            <!-- Password Information -->
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">Pedoman Password yang Baik:</p>
                        <ul class="list-disc ml-5 mt-1 text-xs text-blue-600 space-y-1">
                            <li>Minimal 8 karakter</li>
                            <li>Gunakan kombinasi huruf besar dan kecil</li>
                            <li>Sertakan angka dan karakter khusus</li>
                            <li>Hindari menggunakan informasi pribadi</li>
                            <li>Jangan gunakan password yang sama untuk akun lain</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Password Change Form -->
            <form action="{{ route('pengaturan.password.update') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini*</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" 
                                id="current_password" 
                                name="current_password" 
                                placeholder="Masukkan password saat ini" 
                                class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 {{ $errors->has('current_password') ? 'border-red-500' : '' }}"
                                required>
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 toggle-password" data-target="current_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru*</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Masukkan password baru" 
                                class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 {{ $errors->has('password') ? 'border-red-500' : '' }}"
                                required
                                minlength="8">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 toggle-password" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Password Strength Meter -->
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div id="password-strength" class="h-1.5 rounded-full bg-gray-400" style="width: 0%"></div>
                            </div>
                            <p id="password-strength-text" class="text-xs mt-1 text-gray-500">Kekuatan Password: Belum diisi</p>
                        </div>
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password*</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Konfirmasi password baru" 
                                class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 {{ $errors->has('password_confirmation') ? 'border-red-500' : '' }}"
                                required
                                minlength="8">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 toggle-password" data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p id="password-match" class="mt-1 text-xs hidden"></p>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('pengaturan.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 mr-4">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <i class="fas fa-save mr-2"></i> Simpan Password
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
        
        // Toggle password visibility
        const toggleButtons = document.querySelectorAll('.toggle-password');
        toggleButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Password strength meter
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('password-strength');
        const strengthText = document.getElementById('password-strength-text');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // If password is empty, reset the meter
            if (password.length === 0) {
                strengthBar.style.width = '0%';
                strengthBar.className = 'h-1.5 rounded-full bg-gray-400';
                strengthText.textContent = 'Kekuatan Password: Belum diisi';
                strengthText.className = 'text-xs mt-1 text-gray-500';
                return;
            }
            
            // Length check
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            
            // Character type checks
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[a-z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            // Update the strength meter based on strength value (0-6)
            const percentage = Math.min(100, Math.round((strength / 6) * 100));
            strengthBar.style.width = percentage + '%';
            
            // Set color and text based on strength
            if (strength <= 2) {
                strengthBar.className = 'h-1.5 rounded-full bg-red-500';
                strengthText.textContent = 'Kekuatan Password: Lemah';
                strengthText.className = 'text-xs mt-1 text-red-500';
            } else if (strength <= 4) {
                strengthBar.className = 'h-1.5 rounded-full bg-yellow-500';
                strengthText.textContent = 'Kekuatan Password: Sedang';
                strengthText.className = 'text-xs mt-1 text-yellow-600';
            } else {
                strengthBar.className = 'h-1.5 rounded-full bg-green-500';
                strengthText.textContent = 'Kekuatan Password: Kuat';
                strengthText.className = 'text-xs mt-1 text-green-500';
            }
        });
        
        // Password confirmation check
        const confirmInput = document.getElementById('password_confirmation');
        const matchText = document.getElementById('password-match');
        
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmInput.value;
            
            if (confirmPassword.length === 0) {
                matchText.classList.add('hidden');
                return;
            }
            
            matchText.classList.remove('hidden');
            
            if (password === confirmPassword) {
                matchText.textContent = 'Password cocok';
                matchText.className = 'mt-1 text-xs text-green-500';
                confirmInput.classList.remove('border-red-500');
                confirmInput.classList.add('border-green-500');
            } else {
                matchText.textContent = 'Password tidak cocok';
                matchText.className = 'mt-1 text-xs text-red-500';
                confirmInput.classList.remove('border-green-500');
                confirmInput.classList.add('border-red-500');
            }
        }
        
        confirmInput.addEventListener('input', checkPasswordMatch);
        passwordInput.addEventListener('input', function() {
            if (confirmInput.value.length > 0) {
                checkPasswordMatch();
            }
        });
        
        // Form validation before submit
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirmPassword = confirmInput.value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                matchText.textContent = 'Password tidak cocok';
                matchText.className = 'mt-1 text-xs text-red-500';
                matchText.classList.remove('hidden');
                confirmInput.classList.add('border-red-500');
                
                // Show error message
                const errorMessage = document.createElement('div');
                errorMessage.className = 'bg-red-500 text-white p-4 rounded-md mb-4 shadow-lg';
                errorMessage.setAttribute('role', 'alert');
                errorMessage.innerHTML = `
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="font-bold">Gagal!</p>
                            <p class="text-sm">Password dan konfirmasi password tidak cocok.</p>
                        </div>
                        <button type="button" class="ml-auto focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                
                // Add to page
                const container = document.querySelector('.w-full.max-w-4xl');
                container.insertBefore(errorMessage, container.firstChild);
                
                // Auto hide after 5 seconds
                setTimeout(function() {
                    if (document.body.contains(errorMessage)) {
                        errorMessage.remove();
                    }
                }, 5000);
                
                // Scroll to top
                window.scrollTo({top: 0, behavior: 'smooth'});
            }
        });
    });
</script>
@endsection