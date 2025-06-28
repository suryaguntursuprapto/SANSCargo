{{-- resources/views/layouts/admin-sidebar.blade.php --}}
<div class="fixed top-0 left-0 w-56 h-full bg-white shadow-sm pt-16 z-40">
    <ul class="py-4">
        <!-- Dashboard -->
        <li class="{{ request()->routeIs('admin.dashboard') ? 'border-l-4 border-primary bg-gray-50' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                <i class="fas fa-chart-line w-6"></i> 
                <span>Dashboard</span>
            </a>
        </li>
        
        <!-- User Management -->
        <li class="{{ request()->routeIs('admin.users.*') ? 'border-l-4 border-primary bg-gray-50' : '' }}" x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">
            <a @click="open = !open" class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-gray-50 cursor-pointer">
                <div class="flex items-center">
                    <i class="fas fa-users w-6"></i> 
                    <span>User Management</span>
                </div>
                <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </a>
            <ul x-show="open" class="pl-10 pr-2 py-1">
                <li class="py-1">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-700 hover:text-primary pl-4 {{ request()->routeIs('admin.users.index') ? 'text-primary font-medium' : '' }}">
                        Daftar Users
                    </a>
                </li>
                <li class="py-1">
                    <a href="{{ route('admin.users.create') }}" class="text-sm text-gray-700 hover:text-primary pl-4 {{ request()->routeIs('admin.users.create') ? 'text-primary font-medium' : '' }}">
                        Tambah User
                    </a>
                </li>
            </ul>
        </li>
        
        <!-- Branch Management -->
        <li class="{{ request()->routeIs('admin.branches.*') ? 'border-l-4 border-primary bg-gray-50' : '' }}" x-data="{ open: {{ request()->routeIs('admin.branches.*') ? 'true' : 'false' }} }">
            <a @click="open = !open" class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-gray-50 cursor-pointer">
                <div class="flex items-center">
                    <i class="fas fa-building w-6"></i> 
                    <span>Branch Management</span>
                </div>
                <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </a>
            <ul x-show="open" class="pl-10 pr-2 py-1">
                <li class="py-1">
                    <a href="{{ route('admin.branches.index') }}" class="text-sm text-gray-700 hover:text-primary pl-4 {{ request()->routeIs('admin.branches.index') ? 'text-primary font-medium' : '' }}">
                        Daftar Branches
                    </a>
                </li>
                <li class="py-1">
                    <a href="{{ route('admin.branches.create') }}" class="text-sm text-gray-700 hover:text-primary pl-4 {{ request()->routeIs('admin.branches.create') ? 'text-primary font-medium' : '' }}">
                        Tambah Branch
                    </a>
                </li>
            </ul>
        </li>
        
        <!-- Pengiriman Management -->
        <li class="{{ request()->routeIs('admin.pengiriman.*') ? 'border-l-4 border-primary bg-gray-50' : '' }}">
            <a href="{{ route('admin.pengiriman.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                <i class="fas fa-box w-6"></i> 
                <span>Kelola Pengiriman</span>
            </a>
        </li>
        
        <!-- Ongkir Management -->
        <li class="{{ request()->routeIs('admin.ongkir.*') ? 'border-l-4 border-primary bg-gray-50' : '' }}" x-data="{ open: {{ request()->routeIs('admin.ongkir.*') ? 'true' : 'false' }} }">
            <a @click="open = !open" class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-gray-50 cursor-pointer">
                <div class="flex items-center">
                    <i class="fas fa-calculator w-6"></i> 
                    <span>Ongkir Management</span>
                </div>
                <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </a>
            <ul x-show="open" class="pl-10 pr-2 py-1">
                <li class="py-1">
                    <a href="{{ route('admin.ongkir.index') }}" class="text-sm text-gray-700 hover:text-primary pl-4 {{ request()->routeIs('admin.ongkir.index') ? 'text-primary font-medium' : '' }}">
                        Daftar Tarif
                    </a>
                </li>
                <li class="py-1">
                    <a href="{{ route('admin.ongkir.create') }}" class="text-sm text-gray-700 hover:text-primary pl-4 {{ request()->routeIs('admin.ongkir.create') ? 'text-primary font-medium' : '' }}">
                        Tambah Tarif
                    </a>
                </li>
            </ul>
        </li>
        
        <!-- Settings -->
        <!-- <li class="{{ request()->routeIs('admin.settings.*') ? 'border-l-4 border-primary bg-gray-50' : '' }}">
            <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                <i class="fas fa-cog w-6"></i> 
                <span>Settings</span>
            </a>
        </li> -->
        
        <hr class="my-4 mx-4">
        
        <!-- Back to User Panel -->
        <li>
            <a href="{{ route('pengiriman.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left w-6"></i> 
                <span>Back to User Panel</span>
            </a>
        </li>
    </ul>
</div>