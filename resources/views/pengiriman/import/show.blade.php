{{-- resources/views/pengiriman/import/show.blade.php --}}
@extends('layouts.app')

@section('content')
<!-- Breadcrumb -->
<div class="flex items-center text-sm text-gray-600 mb-6">
    <a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a>
    <span class="mx-2">/</span>
    <a href="{{ route('pengiriman.index') }}" class="hover:text-primary">Pengiriman</a>
    <span class="mx-2">/</span>
    <a href="{{ route('pengiriman.import.index') }}" class="hover:text-primary">Impor</a>
    <span class="mx-2">/</span>
    <span class="text-primary">Detail Impor</span>
</div>

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">Detail Impor Pengiriman</h1>
    <div class="h-1 w-16 bg-primary mt-2"></div>
</div>

<!-- Content -->
<div class="bg-white shadow rounded-lg p-6">
    
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border-l-4 border-primary text-green-700">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
        {{ session('error') }}
    </div>
    @endif

    <!-- Import Details -->
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-800 mb-4">Informasi File</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Nama File</p>
                    <p class="font-medium">{{ $import->original_filename }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Ukuran File</p>
                    <p class="font-medium">{{ number_format($import->file_size / 1024, 2) }} KB</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Tipe File</p>
                    <p class="font-medium">{{ $import->file_type }}</p>
                </div>
            </div>
            
            <div>
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Tanggal Unggah</p>
                    <p class="font-medium">{{ $import->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="font-medium">
                        @if($import->status == 'pending')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Menunggu Proses</span>
                        @elseif($import->status == 'processing')
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Sedang Diproses</span>
                        @elseif($import->status == 'processed')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                        @elseif($import->status == 'failed')
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Gagal</span>
                        @endif
                    </p>
                </div>
                
                @if($import->processed_at)
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Tanggal Proses</p>
                    <p class="font-medium">{{ $import->processed_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Processing Results (if processed) -->
    @if($import->status == 'processed' || $import->status == 'failed')
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-800 mb-4">Hasil Proses</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Total Data</p>
                <p class="text-xl font-bold text-gray-800">{{ $import->total_records ?? 0 }}</p>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Berhasil</p>
                <p class="text-xl font-bold text-green-600">{{ $import->successful_records ?? 0 }}</p>
            </div>
            
            <div class="bg-red-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Gagal</p>
                <p class="text-xl font-bold text-red-600">{{ $import->failed_records ?? 0 }}</p>
            </div>
        </div>
        
        @if($import->notes)
        <div class="mt-4">
            <p class="text-sm text-gray-500 mb-2">Catatan:</p>
            <div class="bg-gray-50 p-4 rounded-lg text-sm whitespace-pre-line">{{ $import->notes }}</div>
        </div>
        @endif
    </div>
    @endif
    
    <!-- Actions -->
    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('pengiriman.import.index') }}"class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Kembali
        </a>
        
        @if($import->status == 'pending')
        <form action="{{ route('pengiriman.import.process', $import->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-green-600">
                Proses Sekarang
            </button>
        </form>
        
        <form action="{{ route('pengiriman.import.cancel', $import->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan impor ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50">
                Batalkan
            </button>
        </form>
        @endif
    </div>
</div>
@endsection