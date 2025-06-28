@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    @include('layouts.pengiriman.navkirimmanual')

    <div class="bg-white rounded-md shadow-sm overflow-hidden">
        <div class="border-l-4 border-green-500 p-4">
            <h2 class="text-xl font-medium text-gray-800">Buat Pengiriman Baru</h2>
        </div>
        <div>
            <form action="{{ route('pengiriman.pengirim-penerima.store') }}" method="POST" id="pengirimForm">
                @csrf
                <input type="hidden" name="pengiriman_id" value="{{ $pengiriman->id }}">
                
                @include('layouts.pengiriman.navformmanual')
                
                <!-- Pengirim & Penerima Tab Content -->
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pengirim</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="nama_pengirim" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Pengirim<span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="nama_pengirim"
                                   name="nama_pengirim"
                                   value="{{ $pengiriman->pengirimPenerima->nama_pengirim ?? old('nama_pengirim') }}"
                                   placeholder="Masukkan Nama Pengirim"
                                   class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                   required>
                        </div>
                        
                        <div>
                            <label for="telepon_pengirim" class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor Telepon Pengirim<span class="text-red-500">*</span>
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 rounded-l-md border border-r-0 border-gray-300">
                                    +62
                                </span>
                                <input type="text"
                                       id="telepon_pengirim"
                                       name="telepon_pengirim"
                                       value="{{ $pengiriman->pengirimPenerima->telepon_pengirim ?? old('telepon_pengirim') }}"
                                       placeholder="Masukkan Nomor Telepon Pengirim"
                                       class="block w-full rounded-none rounded-r-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="email_pengirim" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Pengirim (Opsional)
                        </label>
                        <input type="email"
                               id="email_pengirim"
                               name="email_pengirim"
                               value="{{ $pengiriman->pengirimPenerima->email_pengirim ?? old('email_pengirim') }}"
                               placeholder="Masukkan Email Pengirim"
                               class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">
                    </div>
                    
                    <div class="mb-6">
                        <label for="alamat_pengirim" class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Pengirim<span class="text-red-500">*</span>
                        </label>
                        <textarea id="alamat_pengirim"
                                  name="alamat_pengirim"
                                  rows="3"
                                  placeholder="Masukkan Alamat Pengirim"
                                  class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                  required>{{ $pengiriman->pengirimPenerima->alamat_pengirim ?? old('alamat_pengirim') }}</textarea>
                    </div>
                    
                    <hr class="my-8">
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Penerima</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="nama_penerima" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Penerima<span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="nama_penerima"
                                   name="nama_penerima"
                                   value="{{ $pengiriman->pengirimPenerima->nama_penerima ?? old('nama_penerima') }}"
                                   placeholder="Masukkan Nama Penerima"
                                   class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                   required>
                        </div>
                        
                        <div>
                            <label for="telepon_penerima" class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor Telepon Penerima<span class="text-red-500">*</span>
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 rounded-l-md border border-r-0 border-gray-300">
                                    +62
                                </span>
                                <input type="text"
                                       id="telepon_penerima"
                                       name="telepon_penerima"
                                       value="{{ $pengiriman->pengirimPenerima->telepon_penerima ?? old('telepon_penerima') }}"
                                       placeholder="Masukkan Nomor Telepon Penerima"
                                       class="block w-full rounded-none rounded-r-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="email_penerima" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Penerima (Opsional)
                        </label>
                        <input type="email"
                               id="email_penerima"
                               name="email_penerima"
                               value="{{ $pengiriman->pengirimPenerima->email_penerima ?? old('email_penerima') }}"
                               placeholder="Masukkan Email Penerima"
                               class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">
                    </div>
                    
                    <div class="mb-6">
                        <label for="alamat_penerima" class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Penerima<span class="text-red-500">*</span>
                        </label>
                        <textarea id="alamat_penerima"
                                  name="alamat_penerima"
                                  rows="3"
                                  placeholder="Masukkan Alamat Penerima"
                                  class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                  required>{{ $pengiriman->pengirimPenerima->alamat_penerima ?? old('alamat_penerima') }}</textarea>
                    </div>
                    
                    <div class="flex justify-between mt-8">
                        <a href="{{ route('pengiriman.opsi.create', ['id' => $pengiriman->id]) }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md text-sm flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        
                        <div class="flex space-x-3">
                            <button type="button"
                                    id="simpan-draft-btn"
                                    class="border border-yellow-400 text-yellow-700 hover:bg-yellow-50 px-4 py-2 rounded-md text-sm">
                                Simpan Draft
                            </button>
                            
                            <button type="submit"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm flex items-center">
                                Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Submit as draft
    document.getElementById('simpan-draft-btn').addEventListener('click', function() {
        const form = document.getElementById('pengirimForm');
        form.action = '{{ route("pengiriman.simpan-draft") }}';
        form.submit();
    });
});
</script>
@endsection