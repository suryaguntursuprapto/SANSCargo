<!-- success.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-md shadow-sm overflow-hidden">
        <div class="border-l-4 border-green-500 p-4">
            <h2 class="text-xl font-medium text-gray-800">Pengiriman Berhasil</h2>
        </div>
        
        <div class="p-6 text-center">
            <div class="mb-6">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100">
                    <i class="fas fa-check-circle text-5xl text-green-500"></i>
                </div>
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Terima Kasih!</h3>
            <p class="text-lg mb-8">Pengiriman Anda telah berhasil dibuat dan sedang diproses.</p>
            
            <div class="bg-gray-50 p-6 rounded-lg inline-block mb-8">
                <p class="text-sm text-gray-700 mb-1">Nomor Resi</p>
                <p class="text-3xl font-bold text-green-600">{{ $pengiriman->no_resi }}</p>
            </div>
            
            <div class="mb-8">
                <p class="text-gray-600">
                    Simpan nomor resi ini untuk melacak status pengiriman Anda.<br>
                    Anda juga akan menerima informasi pengiriman melalui email dan SMS.
                </p>
            </div>
            
            <div class="flex justify-center space-x-4">
                <a href="{{ route('pengiriman.opsi.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-md text-sm">
                    Kembali ke Daftar Pengiriman
                </a>
                
                <button id="printBtn" class="border border-green-500 text-green-600 hover:bg-green-50 px-6 py-3 rounded-md text-sm">
                    <i class="fas fa-print mr-2"></i> Cetak Resi
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('printBtn').addEventListener('click', function() {
        window.print();
    });
});
</script>
@endsection