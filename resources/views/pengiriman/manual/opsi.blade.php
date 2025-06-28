{{-- resources/views/pengiriman/manual/opsi.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    @include('layouts.pengiriman.navkirimmanual')
    
    <div class="bg-white rounded-md shadow-sm overflow-hidden">
        <div class="border-l-4 border-green-500 p-4">
            <h2 class="text-xl font-medium text-gray-800">Buat Pengiriman Baru</h2>
        </div>
        
        <div>
            <form action="{{ route('pengiriman.opsi.store') }}" method="POST" id="opsiForm">
                @csrf
                @if(isset($pengiriman))
                    <input type="hidden" name="pengiriman_id" value="{{ $pengiriman->id }}">
                @endif
                
                @include('layouts.pengiriman.navformmanual')
                
                <!-- Opsi Pengiriman Tab Content -->
                <div class="p-6">
                    <!-- Tipe Pengiriman -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tipe Pengiriman<span class="text-red-500">*</span>
                        </label>
                        <div class="flex space-x-6">
                            <label class="flex items-center">
                                <input type="radio"
                                       name="tipe_pengiriman"
                                       value="Dijemput"
                                       class="h-4 w-4 text-green-500 focus:ring-green-400"
                                       {{ isset($pengiriman->opsiPengiriman) && $pengiriman->opsiPengiriman->tipe_pengiriman == 'Dijemput' ? 'checked' : '' }}
                                       {{ old('tipe_pengiriman') == 'Dijemput' ? 'checked' : '' }}
                                       required>
                                <span class="ml-2">Dijemput</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio"
                                       name="tipe_pengiriman"
                                       value="Diantar"
                                       class="h-4 w-4 text-green-500 focus:ring-green-400"
                                       {{ isset($pengiriman->opsiPengiriman) && $pengiriman->opsiPengiriman->tipe_pengiriman == 'Diantar' ? 'checked' : '' }}
                                       {{ old('tipe_pengiriman') == 'Diantar' ? 'checked' : '' }}
                                       required>
                                <span class="ml-2">Diantar</span>
                            </label>
                        </div>
                        @error('tipe_pengiriman')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Layanan -->
                    <div class="mb-6">
                        <label for="jenis_layanan" class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Layanan<span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_layanan"
                                id="jenis_layanan"
                                class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 @error('jenis_layanan') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Jenis Layanan</option>
                            <option value="Regular" 
                                {{ isset($pengiriman->opsiPengiriman) && $pengiriman->opsiPengiriman->jenis_layanan == 'Regular' ? 'selected' : '' }}
                                {{ old('jenis_layanan') == 'Regular' ? 'selected' : '' }}>
                                Regular (3-5 hari)
                            </option>
                            <option value="Express" 
                                {{ isset($pengiriman->opsiPengiriman) && $pengiriman->opsiPengiriman->jenis_layanan == 'Express' ? 'selected' : '' }}
                                {{ old('jenis_layanan') == 'Express' ? 'selected' : '' }}>
                                Express (2-3 hari)
                            </option>
                            <option value="Same Day" 
                                {{ isset($pengiriman->opsiPengiriman) && $pengiriman->opsiPengiriman->jenis_layanan == 'Same Day' ? 'selected' : '' }}
                                {{ old('jenis_layanan') == 'Same Day' ? 'selected' : '' }}>
                                Same Day (1 hari)
                            </option>
                        </select>
                        @error('jenis_layanan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Layanan Tambahan -->
                    <div class="mb-6">
                        <h3 class="block text-sm font-medium text-gray-700 mb-2">Layanan Tambahan</h3>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="asuransi"
                                       value="1"
                                       class="h-4 w-4 text-green-500 focus:ring-green-400"
                                       {{ isset($pengiriman->opsiPengiriman) && $pengiriman->opsiPengiriman->asuransi ? 'checked' : '' }}
                                       {{ old('asuransi') ? 'checked' : '' }}>
                                <span class="ml-2">Asuransi Pengiriman</span>
                                <span class="ml-2 text-sm text-gray-500">(+5% dari nilai barang)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="packing_tambahan"
                                       value="1"
                                       class="h-4 w-4 text-green-500 focus:ring-green-400"
                                       {{ isset($pengiriman->opsiPengiriman) && $pengiriman->opsiPengiriman->packing_tambahan ? 'checked' : '' }}
                                       {{ old('packing_tambahan') ? 'checked' : '' }}>
                                <span class="ml-2">Packing Tambahan</span>
                                <span class="ml-2 text-sm text-gray-500">(Rp 10.000)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Branch Selection -->
                    <div class="mb-6">
                        <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Branch Pengiriman<span class="text-red-500">*</span>
                        </label>
                        <select name="branch_id"
                                id="branch_id"
                                class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 @error('branch_id') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Branch</option>
                            @forelse($branches as $branch)
                                <option value="{{ $branch->id }}" 
                                    {{ isset($pengiriman->opsiPengiriman) && $pengiriman->opsiPengiriman->branch_id == $branch->id ? 'selected' : '' }}
                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}
                                    data-kota="{{ $branch->kota }}"
                                    data-telepon="{{ $branch->telepon }}">
                                    {{ $branch->nama_branch }} - {{ $branch->kota }}
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada branch tersedia</option>
                            @endforelse
                        </select>
                        @error('branch_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Branch Info Display -->
                        <div id="branch-info" class="mt-2 p-3 bg-gray-50 rounded-md hidden">
                            <div class="text-sm text-gray-600">
                                <div class="flex items-center space-x-4">
                                    <span id="branch-kota" class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span></span>
                                    </span>
                                    <span id="branch-telepon" class="flex items-center">
                                        <i class="fas fa-phone mr-1"></i>
                                        <span></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-between mt-8">
                        <a href="{{ route('pengiriman.index') }}" 
                           class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md text-sm flex items-center transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        
                        <div class="flex space-x-3">
                            <button type="button"
                                    id="simpan-draft-btn"
                                    class="border border-yellow-400 text-yellow-700 hover:bg-yellow-50 px-4 py-2 rounded-md text-sm transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Draft
                            </button>
                            <button type="submit"
                                    id="submit-btn"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm flex items-center transition-colors">
                                <span id="submit-text">Selanjutnya</span>
                                <i class="fas fa-arrow-right ml-2" id="submit-icon"></i>
                                <div class="hidden ml-2" id="submit-spinner">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
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
    console.log('Opsi form loaded');
    
    // Branch selection handler
    const branchSelect = document.getElementById('branch_id');
    const branchInfo = document.getElementById('branch-info');
    const branchKota = document.getElementById('branch-kota').querySelector('span');
    const branchTelepon = document.getElementById('branch-telepon').querySelector('span');
    
    branchSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const kota = selectedOption.getAttribute('data-kota');
            const telepon = selectedOption.getAttribute('data-telepon');
            
            branchKota.textContent = kota;
            branchTelepon.textContent = telepon;
            branchInfo.classList.remove('hidden');
        } else {
            branchInfo.classList.add('hidden');
        }
    });
    
    // Trigger change event if there's a pre-selected value
    if (branchSelect.value) {
        branchSelect.dispatchEvent(new Event('change'));
    }
    
    // Save as draft functionality
    document.getElementById('simpan-draft-btn').addEventListener('click', function() {
        if (confirm('Simpan sebagai draft? Anda dapat melanjutkan nanti.')) {
            const form = document.getElementById('opsiForm');
            
            // Remove required attributes temporarily for draft
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                field.removeAttribute('required');
            });
            
            // Change form action to save draft route
            form.action = '{{ route("pengiriman.opsi.store") }}?draft=1';
            form.submit();
        }
    });
    
    // Form submission with loading state
    const form = document.getElementById('opsiForm');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitIcon = document.getElementById('submit-icon');
    const submitSpinner = document.getElementById('submit-spinner');
    
    form.addEventListener('submit', function(e) {
        console.log('Form submitted');
        console.log('Form action:', this.action);
        console.log('Form method:', this.method);
        
        // Show loading state
        submitBtn.disabled = true;
        submitText.textContent = 'Menyimpan...';
        submitIcon.classList.add('hidden');
        submitSpinner.classList.remove('hidden');
        
        // Check if all required fields are filled when not saving as draft
        if (this.action.indexOf('draft') === -1) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    console.log('Invalid field:', field.name);
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                
                // Reset button state
                submitBtn.disabled = false;
                submitText.textContent = 'Selanjutnya';
                submitIcon.classList.remove('hidden');
                submitSpinner.classList.add('hidden');
                
                alert('Mohon lengkapi semua field yang wajib diisi.');
                return false;
            }
        }
        
        // Log form data for debugging
        const formData = new FormData(this);
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
    });
    
    // Service selection info
    const jenisLayananSelect = document.getElementById('jenis_layanan');
    jenisLayananSelect.addEventListener('change', function() {
        console.log('Selected service:', this.value);
    });
    
    // Auto-select user's branch if available
    @if(Auth::user() && Auth::user()->branch)
        const userBranch = '{{ Auth::user()->branch }}';
        const options = branchSelect.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.textContent.includes(userBranch)) {
                option.selected = true;
                branchSelect.dispatchEvent(new Event('change'));
            }
        });
    @endif
    
    // Reset form button state on page load (in case of back button)
    submitBtn.disabled = false;
    submitText.textContent = 'Selanjutnya';
    submitIcon.classList.remove('hidden');
    submitSpinner.classList.add('hidden');
});
</script>
@endsection