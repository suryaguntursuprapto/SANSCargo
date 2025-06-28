<!-- review.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-md shadow-sm overflow-hidden">
        <div class="border-l-4 border-green-500 p-4">
            <h2 class="text-xl font-medium text-gray-800">Review Pengiriman</h2>
        </div>
        
        <div class="p-6">
            <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            Pengiriman Anda berhasil dibuat! Kami sedang memproses permintaan Anda.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Display all the shipping details here -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Detail Pengiriman</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p><strong>Dari:</strong> {{ $pengiriman->asal }}</p>
                        <p><strong>Ke:</strong> {{ $pengiriman->tujuan }}</p>
                        <p><strong>Alamat Detail:</strong> {{ $pengiriman->detail_alamat }}</p>
                        <p><strong>Jenis Layanan:</strong> {{ $pengiriman->opsiPengiriman->jenis_layanan }}</p>
                        <p><strong>Tipe Pengiriman:</strong> {{ $pengiriman->opsiPengiriman->tipe_pengiriman }}</p>
                        <p><strong>Asuransi:</strong> {{ $pengiriman->opsiPengiriman->asuransi ? 'Ya' : 'Tidak' }}</p>
                        <p><strong>Packing Tambahan:</strong> {{ $pengiriman->opsiPengiriman->packing_tambahan ? 'Ya' : 'Tidak' }}</p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Informasi Pengirim & Penerima</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p><strong>Pengirim:</strong> {{ $pengiriman->pengirimPenerima->nama_pengirim }}</p>
                        <p><strong>Telepon Pengirim:</strong> +62{{ $pengiriman->pengirimPenerima->telepon_pengirim }}</p>
                        <p><strong>Penerima:</strong> {{ $pengiriman->pengirimPenerima->nama_penerima }}</p>
                        <p><strong>Telepon Penerima:</strong> +62{{ $pengiriman->pengirimPenerima->telepon_penerima }}</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Daftar Barang</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat (Kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dimensi (PxLxT cm)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pengiriman->barangPengiriman as $barang)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $barang->nama_barang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $barang->jenis_barang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $barang->berat_barang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $barang->panjang_barang }} x {{ $barang->lebar_barang }} x {{ $barang->tinggi_barang }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Informasi Pembayaran</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p><strong>Metode Pembayaran:</strong> {{ $pengiriman->informasiPembayaran->metode_pembayaran }}</p>
                        <p><strong>Total Biaya:</strong> Rp. {{ number_format($pengiriman->informasiPembayaran->total_biaya_pengiriman, 0, ',', '.') }}</p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Catatan</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p>{{ $pengiriman->catatan ?: 'Tidak ada catatan' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <a href="{{ route('pengiriman.index') }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md text-sm flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>
                
                <form action="{{ route('pengiriman.request') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pengiriman_id" value="{{ $pengiriman->id }}">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">
                        Konfirmasi dan Proses
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection