{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-gray-600 mt-1">Selamat datang di panel administrasi SANS Cargo</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">{{ now()->format('d F Y') }}</p>
                <p class="text-lg font-semibold text-primary">{{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($data['total_users']) }}</h3>
                    <p class="text-sm text-gray-600">Total Users</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-green-600">+{{ $data['users_bulan_ini'] }}</span>
                    <span class="text-gray-500 ml-2">bulan ini</span>
                </div>
            </div>
        </div>

        <!-- Total Branches -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($data['total_branches']) }}</h3>
                    <p class="text-sm text-gray-600">Total Branches</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.branches.index') }}" class="text-sm text-primary hover:underline">
                    Kelola Branches →
                </a>
            </div>
        </div>

        <!-- Total Pengiriman -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($data['total_pengiriman']) }}</h3>
                    <p class="text-sm text-gray-600">Total Pengiriman</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-green-600">+{{ $data['pengiriman_bulan_ini'] }}</span>
                    <span class="text-gray-500 ml-2">bulan ini</span>
                </div>
            </div>
        </div>

        <!-- Total Ongkir -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-calculator text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($data['total_ongkir']) }}</h3>
                    <p class="text-sm text-gray-600">Total Tarif Ongkir</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.ongkir.index') }}" class="text-sm text-primary hover:underline">
                    Kelola Ongkir →
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">User Terbaru</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-primary hover:underline">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($data['recent_users'] as $user)
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex items-center space-x-3">
                        @if($user->profile_image)
                            <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" 
                                 alt="Profile" class="h-10 w-10 rounded-full object-cover">
                        @else
                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-600">
                                    {{ substr($user->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $user->nama_lengkap }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $user->status === 'Admin' ? 'bg-red-100 text-red-800' : 
                                   ($user->status === 'Staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ $user->status }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-gray-500">
                    Belum ada user terdaftar
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Pengiriman -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Pengiriman Terbaru</h3>
                    <a href="{{ route('admin.pengiriman.index') }}" class="text-sm text-primary hover:underline">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($data['recent_pengiriman'] as $pengiriman)
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $pengiriman->no_resi }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $pengiriman->asal }} → {{ $pengiriman->tujuan }}
                            </p>
                            @if($pengiriman->pengirimPenerima)
                            <p class="text-xs text-gray-400">
                                {{ $pengiriman->pengirimPenerima->nama_pengirim }}
                            </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $pengiriman->status === 'Dikirim' ? 'bg-blue-100 text-blue-800' : 
                                   ($pengiriman->status === 'Diterima' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $pengiriman->status }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $pengiriman->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-gray-500">
                    Belum ada pengiriman
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.create') }}" 
               class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary hover:bg-primary/5 transition-colors">
                <div class="text-center">
                    <i class="fas fa-user-plus text-2xl text-gray-400 mb-2"></i>
                    <p class="text-sm font-medium text-gray-600">Tambah User</p>
                </div>
            </a>
            
            <a href="{{ route('admin.branches.create') }}" 
               class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary hover:bg-primary/5 transition-colors">
                <div class="text-center">
                    <i class="fas fa-building text-2xl text-gray-400 mb-2"></i>
                    <p class="text-sm font-medium text-gray-600">Tambah Branch</p>
                </div>
            </a>
            
            <a href="{{ route('admin.ongkir.create') }}" 
               class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary hover:bg-primary/5 transition-colors">
                <div class="text-center">
                    <i class="fas fa-calculator text-2xl text-gray-400 mb-2"></i>
                    <p class="text-sm font-medium text-gray-600">Tambah Ongkir</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection