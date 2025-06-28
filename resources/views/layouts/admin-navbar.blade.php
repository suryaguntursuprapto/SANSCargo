{{-- resources/views/layouts/admin-navbar.blade.php --}}
<div class="fixed top-0 left-0 right-0 h-16 bg-white shadow-sm z-50">
    <div class="flex items-center justify-between h-full px-4">
        <div class="flex items-center space-x-6">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/sans.jpeg') }}" alt="CSM Cargo" class="h-10">
            </a>
            <div class="hidden md:block">
                <span class="text-lg font-semibold text-gray-700">Admin Panel</span>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">           
            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 p-2">
                    @if(Auth::user()->profile_image)
                        <img src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}" 
                             alt="Profile" class="h-8 w-8 rounded-full object-cover border border-gray-200">
                    @else
                        <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <span class="hidden sm:inline text-sm font-medium">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                
                <div x-show="open" @click.away="open = false" x-cloak
                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                    <a href="{{ route('pengaturan.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>
                    <a href="{{ route('pengaturan.password.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-key mr-2"></i> Change Password
                    </a>
                    <div class="border-t border-gray-200"></div>
                    <a href="{{ route('pengiriman.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-eye mr-2"></i> View User Panel
                    </a>
                    <div class="border-t border-gray-200"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>