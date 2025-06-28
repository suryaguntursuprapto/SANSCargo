{{-- resources/views/pengiriman/import/draft.blade.php --}}
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
    <span class="text-primary">Draft Impor</span>
</div>

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">Draft Impor Pengiriman</h1>
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

    <!-- Import Draft Details -->
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-800 mb-4">Informasi Draft</h2>
        
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
                    <p class="font-medium">{{ $import->created_at->format('d M Y, H:i') }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="font-medium">
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Draft</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Import Instructions -->
    <div class="mb-6 p-5 bg-gray-50 rounded-lg border border-gray-200">
        <h3 class="text-md font-medium text-gray-800 mb-3">Petunjuk Impor:</h3>
        <ul class="text-sm text-gray-600 list-disc pl-5 space-y-2">
            <li>File upload sudah berhasil disimpan sebagai draft.</li>
            <li>Anda dapat memproses file ini sekarang atau nanti.</li>
            <li>Proses impor akan membaca data dari file dan membuat entri pengiriman secara otomatis.</li>
            <li>Pastikan format data dalam file sudah sesuai dengan template yang disediakan.</li>
            <li>Proses impor dapat memakan waktu, tergantung dari jumlah data yang diimpor.</li>
        </ul>
    </div>
    
    <!-- Pre-process Confirmation -->
    <div class="bg-yellow-50 p-5 rounded-lg border border-yellow-200 mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0 mt-0.5">
                <svg class="h-5 w-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Konfirmasi Impor</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Setelah Anda memulai proses impor, sistem akan membaca file dan mencoba membuat data pengiriman berdasarkan informasi dalam file tersebut. Pastikan format data sudah benar untuk menghindari kesalahan.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('pengiriman.import.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Kembali
        </a>
        
        <form action="{{ route('pengiriman.import.index', $import->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan impor ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50">
                Batalkan
            </button>
        </form>
        
        <form action="{{ route('pengiriman.import.process', $import->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-green-600">
                Proses Sekarang
            </button>
        </form>
    </div>
</div>
@endsection