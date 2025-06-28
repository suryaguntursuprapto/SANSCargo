{{-- resources/views/layouts/navbar.blade.php --}}
<div class="fixed top-0 left-0 right-0 h-16 bg-white shadow-sm z-50">
    <div class="flex items-center justify-between h-full px-4">
        <div class="flex items-center space-x-6">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('images/sans.jpeg') }}" alt="CSM Cargo" class="h-10">
            </a>
            <div class="hidden md:block">
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2">
                    {{ trans('app.view_website') }}
                </a>
            </div>
            @auth
            <div>
                <a href="{{ route('pengiriman.opsi.create') }}" class="bg-primary hover:bg-green-600 text-white rounded-full py-2 px-4 flex items-center text-sm">
                    <i class="fas fa-plus mr-2"></i> {{ trans('app.create_new_shipment') }}
                </a>
            </div>
            @endauth
        </div>
        
        <div class="flex items-center space-x-3">
            <!-- Refresh Button -->
            <button class="text-gray-700 hover:text-gray-900 p-2" onclick="refreshPage()">
                <i class="fas fa-sync-alt"></i>
            </button>

            @auth
                <!-- Admin Panel Button (Only for Admin) -->
                @if(Auth::user()->status === 'Admin')
                    <div class="relative">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="bg-red-600 hover:bg-red-700 text-white rounded-full py-2 px-4 flex items-center text-sm transition-colors">
                            <i class="fas fa-crown mr-2"></i> 
                            Admin Panel
                        </a>
                    </div>
                @endif
            @endauth

            <!-- User Profile Dropdown -->
            <div class="relative ml-3" x-data="{ open: false }">
                <button @click="open = !open" class="text-gray-700 hover:text-gray-900 p-2">
                    @auth
                        <div class="flex items-center">
                            <!-- Profile Image with Better Fallback -->
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}" 
                                     alt="Profile" 
                                     class="h-8 w-8 rounded-full object-cover border border-gray-200"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <!-- Fallback if image fails to load -->
                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-600" style="display: none;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @else
                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="ml-2 hidden sm:inline text-sm font-medium">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </div>
                    @else
                        <i class="fas fa-user-circle text-xl"></i>
                    @endauth
                </button>
                
                <div x-show="open"
                    @click.away="open = false"
                    x-cloak
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-200">
                    @auth
                        <!-- User Info Header -->
                        <div class="px-4 py-2 border-b border-gray-200">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->nama_lengkap }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1
                                {{ Auth::user()->status === 'Admin' ? 'bg-red-100 text-red-800' : 
                                   (Auth::user()->status === 'Staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ Auth::user()->status }}
                            </span>
                        </div>

                        <!-- Profile Links -->
                        <a href="{{ route('pengaturan.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i> {{ trans('app.profile') }}
                        </a>
                        <a href="{{ route('pengaturan.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-edit mr-2"></i> {{ trans('app.edit_profile') }}
                        </a>
                        <a href="{{ route('pengaturan.password.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-key mr-2"></i> {{ trans('app.change_password') }}
                        </a>

                        @if(Auth::user()->status === 'Admin')
                            <div class="border-t border-gray-200"></div>
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                <i class="fas fa-crown mr-2"></i> Admin Dashboard
                            </a>
                        @endif

                        <div class="border-t border-gray-200"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> {{ trans('app.logout') }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-in-alt mr-2"></i> {{ trans('app.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-plus mr-2"></i> {{ trans('app.register') }}
                        </a>
                    @endauth
                </div>
            </div>
            <!-- Fullscreen Button -->
            <button class="text-gray-700 hover:text-gray-900 p-2" onclick="toggleFullscreen()">
                <i class="fas fa-expand" id="fullscreen-icon"></i>
            </button>
        </div>
    </div>
</div>

<script>
    // Function to refresh the page
    function refreshPage() {
        const refreshIcon = document.querySelector('.fa-sync-alt');
        refreshIcon.classList.add('fa-spin');
        
        setTimeout(function() {
            window.location.reload();
        }, 300);
    }
    
    // Function to toggle fullscreen
    function toggleFullscreen() {
        const icon = document.getElementById('fullscreen-icon');
        
        if (!document.fullscreenElement) {
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
                icon.classList.remove('fa-expand');
                icon.classList.add('fa-compress');
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
                icon.classList.remove('fa-compress');
                icon.classList.add('fa-expand');
            }
        }
    }
    
    // Listen for fullscreen change
    document.addEventListener('fullscreenchange', function() {
        const icon = document.getElementById('fullscreen-icon');
        if (document.fullscreenElement) {
            icon.classList.remove('fa-expand');
            icon.classList.add('fa-compress');
        } else {
            icon.classList.remove('fa-compress');
            icon.classList.add('fa-expand');
        }
    });

    // Debug storage link (remove after fixing)
    @auth
        @if(Auth::user()->profile_image)
            console.log('Profile image path:', '{{ asset("storage/profile_images/" . Auth::user()->profile_image) }}');
        @endif
    @endauth
</script>