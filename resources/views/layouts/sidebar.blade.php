<div class="fixed top-0 left-0 w-56 h-full bg-white shadow-sm pt-16 z-40">
    <ul class="py-4">
    <li class="{{ request()->is('dashboard') || request()->is('admin/dashboard') ? 'border-l-4 border-primary bg-gray-50' : '' }}">
            @if(auth()->check() && auth()->user()->status === 'Admin')
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span>Dashboard Admin</span>
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-home w-6"></i>
                    <span>Dashboard</span>
                </a>
            @endif
        </li>



        <li class="{{ request()->is('pengiriman*') ? 'border-l-4 border-primary bg-gray-50' : '' }}" x-data="{ open: true, openBuat: true }">
            <a @click="open = !open" class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-gray-50 cursor-pointer">
                <div class="flex items-center">
                    <i class="fas fa-box w-6"></i> 
                    <span>Pengiriman</span>
                </div>
                <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </a>
            
            <ul x-show="open" class="pl-10 pr-2 py-1">
                <li class="py-1">
                    <div>
                        <a @click="openBuat = !openBuat" class="flex items-center justify-between text-sm text-gray-700 hover:text-primary cursor-pointer {{ request()->routeIs('pengiriman.index') || request()->routeIs('pengiriman.create') || request()->routeIs('pengiriman.manual*') ? 'text-primary font-medium' : '' }}">
                            <div class="flex items-center">
                                <span class="h-2 w-2 rounded-full bg-primary mr-2"></span>
                                <span>Buat Pengiriman</span>
                            </div>
                            <i class="fas fa-xs" :class="openBuat ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </a>
                        
                        <ul x-show="openBuat" class="mt-1 border-l border-gray-200 ml-1">
                            <li class="py-1">
                                <a href="{{ route('pengiriman.opsi.create') }}" class="flex items-center text-sm text-gray-600 hover:text-primary pl-3 {{ request()->routeIs('pengiriman.manual*') ? 'text-primary font-medium' : '' }}">
                                    <i class="fas fa-angle-right text-xs mr-2"></i>
                                    Manual
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                <li class="py-1">
                    <a href="{{ route('kalkulator.index') }}" class="text-sm text-gray-700 hover:text-primary pl-4">
                        Kalkulator Pengiriman
                    </a>
                </li>
                
                <li class="py-1">
                    <a href="{{ route('pengiriman.index') }}" class="text-sm text-gray-700 hover:text-primary pl-4">
                        Daftar Pengiriman
                    </a>
                </li>
                
            </ul>
        </li>
        
        <!-- <li class="{{ request()->is('transaksi*') ? 'border-l-4 border-primary bg-gray-50' : '' }}">
            <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                <i class="fas fa-exchange-alt w-6"></i> 
                <span>Transaksi</span>
            </a>
        </li> -->
        
        <li class="{{ request()->is('pengaturan*') ? 'border-l-4 border-primary bg-gray-50' : '' }}">
            <a href="{{ route('pengaturan.profile') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                <i class="fas fa-cog w-6"></i> 
                <span>Pengaturan</span>
            </a>
        </li>
    </ul>
</div>