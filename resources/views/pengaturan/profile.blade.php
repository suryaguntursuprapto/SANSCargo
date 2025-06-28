@extends('layouts.app')

@section('content')
<div class="w-full max-w-6xl mx-auto px-4">
    <div class="mb-6">
        <nav aria-label="breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-primary">Dashboard</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-800 font-medium">Profil Pengguna</li>
            </ol>
        </nav>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
    <div class="bg-red-500 text-white p-4 rounded-md mb-4 shadow-lg transform transition-all duration-300 hover:shadow-xl" role="alert">
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
    <div class="bg-green-500 text-white p-4 rounded-md mb-4 shadow-lg transform transition-all duration-300 hover:shadow-xl" role="alert">
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

    <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
        <!-- Header with gradient background -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 border-b px-6 py-4 flex justify-between items-center">
            <h5 class="font-medium text-lg text-gray-800">
                <i class="fas fa-user-circle text-primary mr-2"></i>Profil Pengguna
            </h5>
            <a href="{{ route('pengaturan.edit') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 hover:text-primary transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i> Edit Profil
            </a>
        </div>

        <div class="p-6">
            <div class="flex flex-col md:flex-row">
                <!-- Profile Picture & Basic Info Section -->
                <div class="md:w-1/3 mb-6 md:mb-0 flex flex-col items-center">
                    <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-gray-100 shadow-md mb-4 transform transition-transform duration-300 hover:scale-105">
                        @if($user->profile_image)
                            <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}"
                                alt="Profile Image"
                                class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/default-profile.png') }}"
                                alt="Default Profile"
                                class="w-full h-full object-cover">
                        @endif
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-1">{{ $user->nama_lengkap }}</h3>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mb-3">
                        <i class="fas fa-circle text-xs mr-1"></i>
                        {{ $user->status ?? 'User' }}
                    </div>
                    
                    <!-- Quick Stats Cards -->
                    <div class="grid grid-cols-1 gap-3 w-full max-w-xs mt-3">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="rounded-full bg-blue-100 p-2 mr-3">
                                <i class="fas fa-envelope text-blue-500"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="text-sm font-medium text-gray-800 truncate max-w-[150px]">{{ $user->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="rounded-full bg-green-100 p-2 mr-3">
                                <i class="fas fa-phone-alt text-green-500"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Telepon</p>
                                <p class="text-sm font-medium text-gray-800">+62 {{ $user->nomor_telepon }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Detailed Profile Information -->
                <div class="md:w-2/3 md:pl-10">
                    <h4 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">
                        <i class="fas fa-info-circle mr-2 text-primary"></i>Informasi Detail
                    </h4>
                    
                    <div class="space-y-6">
                        <div class="flex">
                            <div class="w-1/3">
                                <h5 class="text-sm font-medium text-gray-500">Nama Lengkap</h5>
                            </div>
                            <div class="w-2/3">
                                <p class="text-gray-800">{{ $user->nama_lengkap }}</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="w-1/3">
                                <h5 class="text-sm font-medium text-gray-500">Email</h5>
                            </div>
                            <div class="w-2/3">
                                <p class="text-gray-800">{{ $user->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="w-1/3">
                                <h5 class="text-sm font-medium text-gray-500">Nomor Telepon</h5>
                            </div>
                            <div class="w-2/3">
                                <p class="text-gray-800">+62 {{ $user->nomor_telepon }}</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="w-1/3">
                                <h5 class="text-sm font-medium text-gray-500">Branch</h5>
                            </div>
                            <div class="w-2/3">
                                <p class="text-gray-800">{{ $user->branch ?? '-' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="w-1/3">
                                <h5 class="text-sm font-medium text-gray-500">Alamat</h5>
                            </div>
                            <div class="w-2/3">
                                <p class="text-gray-800">{{ $user->alamat ?? '-' }}</p>
                            </div>
                        </div>
                        
                        @if(isset($user->created_at))
                        <div class="flex">
                            <div class="w-1/3">
                                <h5 class="text-sm font-medium text-gray-500">Bergabung Sejak</h5>
                            </div>
                            <div class="w-2/3">
                                <p class="text-gray-800">{{ $user->created_at->format('d F Y') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('pengaturan.edit') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                <i class="fas fa-edit mr-2"></i> Edit Profil
                            </a>
                            
                            <a href="{{ route('pengaturan.password.edit') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                <i class="fas fa-key mr-2"></i> Ubah Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Add custom animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>
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
        
        // Add fadeIn animation to elements
        const elements = document.querySelectorAll('.bg-white, .flex, .grid');
        elements.forEach(function(element, index) {
            element.classList.add('animate-fadeIn');
            element.style.animationDelay = (index * 0.1) + 's';
        });
    });
</script>
@endsection