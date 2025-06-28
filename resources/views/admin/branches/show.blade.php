{{-- resources/views/admin/branches/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Branch - ' . $branch->nama_branch)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.branches.index') }}" 
                   class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Branch</h1>
                    <p class="text-gray-600 mt-1">Informasi lengkap {{ $branch->nama_branch }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.branches.edit', $branch) }}" 
                   class="bg-primary hover:bg-green-600 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Branch
                </a>
                @if($branch->users->count() == 0)
                    <form action="{{ route('admin.branches.destroy', $branch) }}" 
                          method="POST" 
                          class="inline-block"
                          onsubmit="return confirm('Yakin ingin menghapus branch {{ $branch->nama_branch }}? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus Branch
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Branch Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Branch Info Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-center">
                    <div class="h-20 w-20 rounded-full bg-green-100 flex items-center justify-center mx-auto">
                        <i class="fas fa-building text-3xl text-green-600"></i>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mt-4">{{ $branch->nama_branch }}</h3>
                    <p class="text-gray-600">{{ $branch->kode_branch }}</p>
                    
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full mt-3
                        {{ $branch->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas {{ $branch->status ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                        {{ $branch->status ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>

                <div class="mt-6 space-y-4">
                    <!-- Location -->
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Lokasi</h4>
                        <div class="space-y-2">
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-gray-400 w-5 mt-0.5"></i>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">{{ $branch->alamat }}</p>
                                    <p class="text-sm text-gray-500">{{ $branch->kota }}, {{ $branch->provinsi }} {{ $branch->kode_pos }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Kontak</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 w-5"></i>
                                <span class="ml-3 text-sm text-gray-900">{{ $branch->telepon }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-400 w-5"></i>
                                <span class="ml-3 text-sm text-gray-900">{{ $branch->email }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- GPS Coordinates -->
                    @if($branch->latitude && $branch->longitude)
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Koordinat GPS</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Latitude:</span>
                                <span class="text-gray-900">{{ $branch->latitude }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Longitude:</span>
                                <span class="text-gray-900">{{ $branch->longitude }}</span>
                            </div>
                            <a href="https://maps.google.com/?q={{ $branch->latitude }},{{ $branch->longitude }}" 
                               target="_blank"
                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                Lihat di Google Maps
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Branch Info -->
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Informasi Branch</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Dibuat:</span>
                                <span class="text-gray-900">{{ $branch->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Terakhir update:</span>
                                <span class="text-gray-900">{{ $branch->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics & Details -->
        <div class="lg:col-span-2">
            <div class="space-y-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $branch->users->count() }}</h3>
                                <p class="text-sm text-gray-600">Total Users</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
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
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-calendar text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $branch->created_at->diffInDays() }}</h3>
                                <p class="text-sm text-gray-600">Hari Beroperasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Branch Details -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Detail</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kode Branch</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $branch->kode_branch }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Branch</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->nama_branch }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kota</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->kota }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Provinsi</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->provinsi }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kode Pos</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->kode_pos }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $branch->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $branch->status ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Alamat Lengkap</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $branch->alamat }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Users in Branch -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Users di Branch ({{ $branch->users->count() }})
                            </h3>
                            @if($branch->users->count() > 0)
                                <a href="{{ route('admin.users.index', ['branch' => $branch->nama_branch]) }}" 
                                   class="text-sm text-primary hover:underline">
                                    Lihat semua users →
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        @if($branch->users->count() > 0)
                            <div class="space-y-4">
                                @foreach($branch->users as $user)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
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
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $user->nama_lengkap }}</p>
                                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $user->status === 'Admin' ? 'bg-red-100 text-red-800' : 
                                               ($user->status === 'Staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                            {{ $user->status }}
                                        </span>
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 text-4xl mb-4">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Belum ada users</h4>
                                <p class="text-gray-500 mb-4">Branch ini belum memiliki users yang terdaftar</p>
                                <a href="{{ route('admin.users.create') }}" 
                                   class="bg-primary hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah User
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.users.create') }}?branch={{ $branch->nama_branch }}" 
                           class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary hover:bg-primary/5 transition-colors">
                            <i class="fas fa-user-plus text-2xl text-gray-400 mb-2"></i>
                            <span class="text-sm font-medium text-gray-600">Tambah User</span>
                        </a>
                        
                        <a href="{{ route('admin.branches.edit', $branch) }}" 
                           class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary hover:bg-primary/5 transition-colors">
                            <i class="fas fa-edit text-2xl text-gray-400 mb-2"></i>
                            <span class="text-sm font-medium text-gray-600">Edit Branch</span>
                        </a>
                        
                        @if($branch->latitude && $branch->longitude)
                        <a href="https://maps.google.com/?q={{ $branch->latitude }},{{ $branch->longitude }}" 
                           target="_blank"
                           class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary hover:bg-primary/5 transition-colors">
                            <i class="fas fa-map text-2xl text-gray-400 mb-2"></i>
                            <span class="text-sm font-medium text-gray-600">Lihat Map</span>
                        </a>
                        @endif
                        
                        <a href="{{ route('admin.users.index', ['branch' => $branch->nama_branch]) }}" 
                           class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary hover:bg-primary/5 transition-colors">
                            <i class="fas fa-users text-2xl text-gray-400 mb-2"></i>
                            <span class="text-sm font-medium text-gray-600">Lihat Users</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Copy coordinates to clipboard
    function copyCoordinates() {
        const coords = "{{ $branch->latitude }},{{ $branch->longitude }}";
        navigator.clipboard.writeText(coords).then(function() {
            // Show toast or notification
            console.log('Coordinates copied to clipboard');
        });
    }
</script>
@endsection