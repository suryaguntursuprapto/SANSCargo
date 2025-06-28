@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    @include('layouts.pengiriman.navkirimmanual')

    <div class="bg-white rounded-md shadow-sm overflow-hidden">
        <div class="border-l-4 border-green-500 p-4">
            <h2 class="text-xl font-medium text-gray-800">Buat Pengiriman Baru</h2>
        </div>
        <div>
            <form action="{{ route('pengiriman.pembayaran.store') }}" method="POST" id="pembayaranForm">
                @csrf
                <input type="hidden" name="pengiriman_id" value="{{ $pengiriman->id }}">
                
                @include('layouts.pengiriman.navformmanual')
                
                <!-- Informasi Pembayaran Tab Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="total_sub_biaya" class="block text-sm font-medium text-gray-700 mb-1">
                                Total Sub Biaya<span class="text-red-500">*</span>
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 rounded-l-md border border-r-0 border-gray-300">
                                    Rp.
                                </span>
                                <input type="text"
                                       id="total_sub_biaya"
                                       name="total_sub_biaya"
                                       value="{{ $additionalCost }}"
                                       class="block w-full rounded-none rounded-r-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 bg-gray-100"
                                       readonly
                                       required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Biaya tambahan (asuransi, packing tambahan)</p>
                        </div>
                        
                        <div>
                            <label for="total_biaya_pengiriman" class="block text-sm font-medium text-gray-700 mb-1">
                                Total Biaya Pengiriman
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 rounded-l-md border border-r-0 border-gray-300">
                                    Rp.
                                </span>
                                <input type="text"
                                       id="total_biaya_pengiriman"
                                       name="total_biaya_pengiriman"
                                       value="{{ $totalCost }}"
                                       class="block w-full rounded-none rounded-r-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 bg-gray-100"
                                       readonly
                                       required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Total biaya pengiriman (biaya dasar + biaya tambahan)</p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">
                            Metode Pembayaran<span class="text-red-500">*</span>
                        </label>
                        <select name="metode_pembayaran"
                                id="metode_pembayaran"
                                class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="Transfer Bank" {{ isset($pengiriman->informasiPembayaran) && $pengiriman->informasiPembayaran->metode_pembayaran == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="QRIS" {{ isset($pengiriman->informasiPembayaran) && $pengiriman->informasiPembayaran->metode_pembayaran == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            <option value="Cash On Delivery" {{ isset($pengiriman->informasiPembayaran) && $pengiriman->informasiPembayaran->metode_pembayaran == 'Cash On Delivery' ? 'selected' : '' }}>Cash On Delivery</option>
                            <option value="Kredit" {{ isset($pengiriman->informasiPembayaran) && $pengiriman->informasiPembayaran->metode_pembayaran == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                        </select>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-green-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    Detail biaya dihitung berdasarkan berat, dimensi, dan layanan yang dipilih.<br>
                                    Biaya dasar: Rp. {{ number_format($cost, 0, ',', '.') }}<br>
                                    @if($pengiriman->opsiPengiriman && $pengiriman->opsiPengiriman->asuransi)
                                        Asuransi (5%): Rp. {{ number_format($cost * 0.05, 0, ',', '.') }}<br>
                                    @endif
                                    @if($pengiriman->opsiPengiriman && $pengiriman->opsiPengiriman->packing_tambahan)
                                        Packing Tambahan: Rp. 25.000<br>
                                    @endif
                                    <strong>Total: Rp. {{ number_format($totalCost, 0, ',', '.') }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Update the buttons -->
                    <div class="flex justify-between mt-8">
                        <a href="{{ route('pengiriman.detail.create', ['id' => $pengiriman->id]) }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md text-sm flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        
                        <div class="flex space-x-3">
                            <button type="button"
                                    id="simpan-draft-btn"
                                    class="border border-yellow-400 text-yellow-700 hover:bg-yellow-50 px-4 py-2 rounded-md text-sm">
                                Simpan Draft
                            </button>
                            
                            <!-- Use a regular button outside the form -->
                            <a href="#" 
                            onclick="document.getElementById('pembayaranForm').submit(); return false;"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm flex items-center">
                                Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                            </a>
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
    // Formatting numbers for display
    function formatRupiah(angka) {
        let number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }
    
    // Format the currency fields on load
    document.getElementById('total_sub_biaya').value = formatRupiah({{ $additionalCost }});
    document.getElementById('total_biaya_pengiriman').value = formatRupiah({{ $totalCost }});
    
    // Submit as draft
    document.getElementById('simpan-draft-btn').addEventListener('click', function() {
        const form = document.getElementById('pembayaranForm');
        form.action = '{{ route("pengiriman.simpan-draft") }}';
        form.submit();
    });
});
</script>
@endsection