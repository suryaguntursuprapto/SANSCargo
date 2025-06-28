<div class="flex items-center pb-4">
        <nav class="text-sm">
            <ol class="list-none p-0 flex">
                <li class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
                    <span class="mx-2 text-gray-500">/</span>
                </li>
                <li class="flex items-center">
                    <a href="{{ route('pengiriman.index') }}" class="text-gray-500 hover:text-gray-700">Pengiriman</a>
                    <span class="mx-2 text-gray-500">/</span>
                </li>
                <li class="flex items-center">
                    <a href="{{ route('pengiriman.opsi.create') }}" class="text-gray-500 hover:text-gray-700">Buat Pengiriman</a>
                    <span class="mx-2 text-gray-500">/</span>
                </li>
                <li class="text-gray-700 font-medium">Manual</li>
            </ol>
        </nav>
    </div>