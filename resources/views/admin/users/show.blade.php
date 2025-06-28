{{-- resources/views/admin/users/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail User - ' . $user->nama_lengkap)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail User</h1>
                    <p class="text-gray-600 mt-1">Informasi lengkap pengguna {{ $user->nama_lengkap }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="bg-primary hover:bg-green-600 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-edit mr-2"></i>
                    Edit User
                </a>
                @if($user->id !== Auth::id())
                    <form action="{{ route('admin.users.destroy', $user) }}" 
                          method="POST" 
                          class="inline-block"
                          onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus User
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-center">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" 
                             alt="Profile" 
                             class="h-32 w-32 rounded-full object-cover mx-auto border-4 border-gray-200">
                    @else
                        <div class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center mx-auto border-4 border-gray-200">
                            <span class="text-4xl font-bold text-gray-600">
                                {{ substr($user->name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                    
                    <h3 class="text-xl font-bold text-gray-900 mt-4">{{ $user->nama_lengkap }}</h3>
                    <p class="text-gray-600">{{ $user->name }}</p>
                    
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full mt-3
                        {{ $user->status === 'Admin' ? 'bg-red-100 text-red-800' : 
                           ($user->status === 'Staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                        <i class="fas {{ $user->status === 'Admin' ? 'fa-crown' : ($user->status === 'Staff' ? 'fa-user-tie' : 'fa-user') }} mr-1"></i>
                        {{ $user->status }}
                    </span>
                </div>

                <div class="mt-6 space-y-4">
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Informasi Kontak</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-400 w-5"></i>
                                <span class="ml-3 text-sm text-gray-900">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 w-5"></i>
                                <span class="ml-3 text-sm text-gray-900">{{ $user->nomor_telepon }}</span>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-gray-400 w-5 mt-0.5"></i>
                                <span class="ml-3 text-sm text-gray-900">{{ $user->alamat }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Informasi Branch</h4>
                        <div class="flex items-center">
                            <i class="fas fa-building text-gray-400 w-5"></i>
                            <span class="ml-3 text-sm text-gray-900">{{ $user->branch }}</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Aktivitas Akun</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Bergabung:</span>
                                <span class="text-gray-900">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Terakhir update:</span>
                                <span class="text-gray-900">{{ $user->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="lg:col-span-2">
            <div class="space-y-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-box text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">0</h3>
                                <p class="text-sm text-gray-600">Total Pengiriman</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">0</h3>
                                <p class="text-sm text-gray-600">Pengiriman Selesai</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $user->created_at->diffInDays() }}</h3>
                                <p class="text-sm text-gray-600">Hari Bergabung</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Details -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Detail</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Username</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->nama_lengkap }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->nomor_telepon }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status/Role</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $user->status === 'Admin' ? 'bg-red-100 text-red-800' : 
                                           ($user->status === 'Staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $user->status }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Branch</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->branch }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->alamat }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-4">
                                <i class="fas fa-history"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Belum ada aktivitas</h4>
                            <p class="text-gray-500">Aktivitas user akan muncul di sini</p>
                        </div>
                    </div>
                </div>

                <!-- Permissions & Access -->
                @if($user->status !== 'Customer')
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Hak Akses & Permissions</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($user->status === 'Admin')
                                <div class="flex items-center p-3 bg-red-50 rounded-lg">
                                    <i class="fas fa-crown text-red-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-red-900">Full Admin Access</p>
                                        <p class="text-sm text-red-700">Akses penuh ke semua fitur sistem</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-green-50 rounded-lg">
                                    <i class="fas fa-users-cog text-green-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-green-900">User Management</p>
                                        <p class="text-sm text-green-700">Dapat mengelola semua user</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                                    <i class="fas fa-building text-blue-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-blue-900">Branch Management</p>
                                        <p class="text-sm text-blue-700">Dapat mengelola semua branch</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                                    <i class="fas fa-cog text-purple-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-purple-900">System Settings</p>
                                        <p class="text-sm text-purple-700">Dapat mengubah pengaturan sistem</p>
                                    </div>
                                </div>
                            @elseif($user->status === 'Staff')
                                <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                                    <i class="fas fa-box text-blue-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-blue-900">Pengiriman Management</p>
                                        <p class="text-sm text-blue-700">Dapat mengelola pengiriman</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-green-50 rounded-lg">
                                    <i class="fas fa-eye text-green-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-green-900">View Reports</p>
                                        <p class="text-sm text-green-700">Dapat melihat laporan</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection