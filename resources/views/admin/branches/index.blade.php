{{-- resources/views/admin/branches/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Branches')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Branches</h1>
                <p class="text-gray-600 mt-1">Kelola semua cabang CSM Cargo</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.branches.create') }}" 
                   class="bg-primary hover:bg-green-600 text-white px-4 py-2 rounded-md flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Branch
                </a>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" action="{{ route('admin.branches.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        Cari Branch
                    </label>
                    <input type="text" id="search" name="search" 
                           class="w-full" 
                           placeholder="Nama branch, kode, kota..."
                           value="{{ request('search') }}">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>
                    <select id="status" name="status" class="w-full">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md flex-1">
                        <i class="fas fa-search mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.branches.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $branches->total() }}</h3>
                    <p class="text-sm text-gray-600">Total Branches</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $branches->where('status', true)->count() }}
                    </h3>
                    <p class="text-sm text-gray-600">Aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $branches->where('status', false)->count() }}
                    </h3>
                    <p class="text-sm text-gray-600">Tidak Aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-map-marker-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $branches->unique('kota')->count() }}
                    </h3>
                    <p class="text-sm text-gray-600">Kota</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Branches Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    Daftar Branches ({{ $branches->total() }} total)
                </h2>
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $branches->firstItem() }}-{{ $branches->lastItem() }} dari {{ $branches->total() }} branches
                </div>
            </div>
        </div>

        @if($branches->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Branch Info
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lokasi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kontak
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Users
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($branches as $branch)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-building text-green-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $branch->nama_branch }}</div>
                                        <div class="text-sm text-gray-500">{{ $branch->kode_branch }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $branch->kota }}, {{ $branch->provinsi }}</div>
                                <div class="text-sm text-gray-500">{{ $branch->kode_pos }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ Str::limit($branch->alamat, 40) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    {{ $branch->telepon }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    {{ $branch->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $branch->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $branch->status ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                    {{ $branch->status ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                                <div class="text-xs text-gray-500 mt-1">
                                    Dibuat {{ $branch->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $branch->users_count ?? $branch->users->count() }} users
                                </div>
                                @if($branch->users->count() > 0)
                                    <div class="flex -space-x-1 mt-1">
                                        @foreach($branch->users->take(3) as $user)
                                            @if($user->profile_image)
                                                <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" 
                                                     alt="{{ $user->name }}" 
                                                     class="h-6 w-6 rounded-full border-2 border-white"
                                                     title="{{ $user->name }}">
                                            @else
                                                <div class="h-6 w-6 rounded-full border-2 border-white bg-gray-300 flex items-center justify-center text-xs"
                                                     title="{{ $user->name }}">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        @endforeach
                                        @if($branch->users->count() > 3)
                                            <div class="h-6 w-6 rounded-full border-2 border-white bg-gray-100 flex items-center justify-center text-xs">
                                                +{{ $branch->users->count() - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.branches.show', $branch) }}" 
                                       class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.branches.edit', $branch) }}" 
                                       class="text-green-600 hover:text-green-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($branch->users->count() == 0)
                                        <form action="{{ route('admin.branches.destroy', $branch) }}" 
                                              method="POST" 
                                              class="inline-block"
                                              onsubmit="return confirm('Yakin ingin menghapus branch {{ $branch->nama_branch }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400" title="Tidak bisa dihapus karena masih ada users">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $branches->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-building"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada branches</h3>
                <p class="text-gray-500 mb-6">Mulai dengan menambahkan branch pertama.</p>
                <a href="{{ route('admin.branches.create') }}" 
                   class="bg-primary hover:bg-green-600 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Branch
                </a>
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Auto-refresh page every 5 minutes to update user counts
    setTimeout(function() {
        location.reload();
    }, 300000);

    // Tooltip for user avatars
    document.querySelectorAll('[title]').forEach(function(element) {
        element.addEventListener('mouseenter', function() {
            // You can add custom tooltip implementation here
        });
    });
</script>
@endsection