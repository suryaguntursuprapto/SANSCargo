@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    @include('layouts.pengiriman.navkirimmanual')

    <div class="bg-white rounded-md shadow-sm overflow-hidden">
        <div class="border-l-4 border-green-500 p-4">
            <h2 class="text-xl font-medium text-gray-800">Buat Pengiriman Baru</h2>
        </div>
        <div>
            <form action="{{ route('pengiriman.catatan.store') }}" method="POST" id="catatanForm">
                @csrf
                <input type="hidden" name="pengiriman_id" value="{{ $pengiriman->id }}">
                
                @include('layouts.pengiriman.navformmanual')
                
                <!-- Catatan Tab Content -->
                <div class="p-6">
                    <div class="mb-6">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan
                        </label>
                        <textarea id="catatan"
                                  name="catatan"
                                  rows="6"
                                  placeholder="Masukkan catatan tambahan jika ada..."
                                  class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">{{ $pengiriman->catatan ?? old('catatan') }}</textarea>
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi & Pengiriman</h3>
                        
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="setuju"
                                       value="1"
                                       class="h-4 w-4 text-green-500 focus:ring-green-400"
                                       required>
                                <span class="ml-2">Saya telah membaca dan menyetujui syarat & ketentuan</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-between mt-8">
                        <a href="{{ route('pengiriman.pembayaran.create', ['id' => $pengiriman->id]) }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md text-sm flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        
                        <div class="flex space-x-3">
                            <button type="button"
                                    id="batal-btn"
                                    class="border border-red-500 text-red-700 hover:bg-red-50 px-4 py-2 rounded-md text-sm">
                                Batal
                            </button>
                            
                            <button type="button"
                                    id="simpan-draft-btn"
                                    class="border border-yellow-400 text-yellow-700 hover:bg-yellow-50 px-4 py-2 rounded-md text-sm">
                                Simpan Draft
                            </button>
                            
                            <button type="button"
                                    id="request-btn"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">
                                Request
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
            <!-- Hidden form for request -->
            <form id="requestForm" action="{{ route('pengiriman.request') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="pengiriman_id" value="{{ $pengiriman->id }}">
            </form>
            
            <!-- Hidden form for cancel -->
            <form id="cancelForm" action="{{ route('pengiriman.cancel') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="pengiriman_id" value="{{ $pengiriman->id }}">
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal for Cancel -->
<div id="cancelModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Batal!
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Semua data yang Anda masukkan tidak akan disimpan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button id="confirmCancel" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
                <button id="cancelCancel" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Lanjut
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Submit as draft
    document.getElementById('simpan-draft-btn').addEventListener('click', function() {
        const form = document.getElementById('catatanForm');
        form.action = '{{ route("pengiriman.simpan-draft") }}';
        form.submit();
    });
    
    // Submit as request
    document.getElementById('request-btn').addEventListener('click', function() {
        // Check if terms are accepted
        const termsAccepted = document.querySelector('input[name="setuju"]').checked;
        
        if (termsAccepted) {
            // First save the notes
            const form = document.getElementById('catatanForm');
            form.submit();
            
            // Then submit the request form
            setTimeout(function() {
                document.getElementById('requestForm').submit();
            }, 500);
        } else {
            alert('Anda harus menyetujui syarat dan ketentuan terlebih dahulu.');
        }
    });
    
    // Cancel button handling
    document.getElementById('batal-btn').addEventListener('click', function() {
        document.getElementById('cancelModal').classList.remove('hidden');
    });
    
    // Modal cancel button
    document.getElementById('cancelCancel').addEventListener('click', function() {
        document.getElementById('cancelModal').classList.add('hidden');
    });
    
    // Modal confirm cancel
    document.getElementById('confirmCancel').addEventListener('click', function() {
        document.getElementById('cancelForm').submit();
    });
});
</script>
@endsection