@extends('layouts.guest')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            <div class="bg-white shadow-md rounded-lg overflow-hidden mt-10">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-xl font-semibold text-gray-800">Registrasi</h5>
                </div>
                <div class="px-6 py-4">
                    @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 relative" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                @foreach ($errors->all() as $error)
                                <p class="text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                        <button type="button" class="absolute top-0 right-0 mt-4 mr-4" data-bs-dismiss="alert" aria-label="Close">
                            <svg class="h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    @endif
                    
                    <form method="POST" action="{{ route('register.process') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Username</label>
                                <input type="text" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}" 
                                       id="name" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="nama_lengkap" class="block text-gray-700 text-sm font-medium mb-2">Nama Lengkap</label>
                                <input type="text" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('nama_lengkap') ? 'border-red-500' : 'border-gray-300' }}" 
                                       id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                                @error('nama_lengkap')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                                <input type="email" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="nomor_telepon" class="block text-gray-700 text-sm font-medium mb-2">Nomor Telepon</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md">
                                        +62
                                    </span>
                                    <input type="text" class="flex-1 px-4 py-2 border rounded-r-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('nomor_telepon') ? 'border-red-500' : 'border-gray-300' }}" 
                                           id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required>
                                </div>
                                @error('nomor_telepon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="alamat" class="block text-gray-700 text-sm font-medium mb-2">Alamat</label>
                            <textarea class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('alamat') ? 'border-red-500' : 'border-gray-300' }}" 
                                     id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                                <input type="password" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}" 
                                       id="password" name="password" required>
                                @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">Konfirmasi Password</label>
                                <input type="password" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }}" 
                                       id="password_confirmation" name="password_confirmation" required>
                                @error('password_confirmation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded {{ $errors->has('terms') ? 'border-red-500' : '' }}" 
                                       type="checkbox" id="terms" name="terms" required>
                                <label class="ml-2 block text-gray-700 text-sm" for="terms">
                                    Saya menyetujui syarat dan ketentuan
                                </label>
                            </div>
                            @error('terms')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                Daftar
                            </button>
                        </div>
                    </form>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-medium text-green-700 hover:text-green-800 hover:underline">
                            Login
                        </a>
                    </p>
                </div>
            </div>
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
                alert.style.display = 'none';
            });
        }, 5000);
        
        // Validasi nomor telepon hanya angka
        const phoneInput = document.getElementById('nomor_telepon');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });
        }
    });
</script>
@endsection