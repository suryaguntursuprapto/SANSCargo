@extends('layouts.app')

@section('content')
<!-- Breadcrumb -->
<div class="flex items-center text-sm text-gray-600 mb-6">
    <a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a>
    <span class="mx-2">/</span>
    <a href="{{ route('pengiriman.index') }}" class="hover:text-primary">Pengiriman</a>
    <span class="mx-2">/</span>
    <span class="text-primary">Impor</span>
</div>

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">Buat Pengiriman</h1>
    <div class="h-1 w-16 bg-primary mt-2"></div>
</div>

<!-- Content -->
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-lg font-medium text-gray-800 mb-4">Impor Pengiriman</h2>
    
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

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data" id="importForm">
        @csrf
        
        <!-- File Upload Area -->
        <div class="border-2 border-dashed border-gray-200 rounded-lg p-8 mb-6 transition-all hover:border-primary" id="dropzone">
            <div class="text-center">
                <div class="mb-4 flex justify-center">
                    <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <p class="text-gray-500 mb-2">Seret dan jatuhkan file di sini atau</p>
                <label for="file-upload" class="text-primary hover:text-green-600 cursor-pointer font-medium">Pilih berkas</label>
                <input type="file" id="file-upload" name="file" class="hidden" accept=".doc,.docx,.pdf,.xls,.xlsx,.csv">
            </div>
        </div>
        
        <!-- File Format Info -->
        <div class="flex justify-between text-sm text-gray-500 mb-8">
            <div>Supported formats: doc, pdf, xls, xlsx, csv, etc.</div>
            <div>Ukuran Maksimum: 25MB</div>
        </div>
        
        <!-- Template Download Section with Fixed Route -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center">
                <div class="mr-4">
                    <i class="fas fa-file-download text-primary text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-800">Template Impor Pengiriman</h3>
                    <p class="text-xs text-gray-500">Unduh template untuk memudahkan proses impor data pengiriman</p>
                </div>
                <div class="ml-auto">
                    <a href="{{ route('import.download-template') }}" class="text-primary hover:text-green-600 text-sm">Unduh Template</a>
                </div>
            </div>
        </div>
        
        <!-- Instructions -->
        <div class="mb-8">
            <h3 class="text-sm font-medium text-gray-800 mb-2">Petunjuk Impor:</h3>
            <ul class="text-sm text-gray-600 list-disc pl-5 space-y-1">
                <li>Pastikan data yang diimpor sesuai dengan format template</li>
                <li>Gunakan format tanggal: DD/MM/YYYY</li>
                <li>Pastikan semua kolom wajib diisi dengan benar</li>
                <li>Ukuran file tidak melebihi 25MB</li>
            </ul>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('pengiriman.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" name="save_draft" value="1" class="px-6 py-2 border border-primary text-primary rounded-lg hover:bg-green-50">
                Simpan Draft
            </button>
            <button type="submit" id="submitButton" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-green-600">
                Kirim
            </button>
        </div>
    </form>
    
    <!-- Draft Files Section (will be shown if there are draft files) -->
    @if(isset($draftImports) && count($draftImports) > 0)
    <div class="mt-12 pt-8 border-t border-gray-200" id="draftFilesSection">
        <h2 class="text-lg font-medium text-gray-800 mb-4">File Draft</h2>
        <p class="text-sm text-gray-600 mb-4">Berikut adalah file yang telah disimpan sebagai draft. Anda dapat memproses file-file ini sekarang.</p>
        
        <div class="space-y-4">
            @foreach($draftImports as $draft)
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-medium text-gray-800">{{ $draft->original_filename }}</h3>
                        <div class="flex items-center text-sm text-gray-500 mt-1">
                            <span>{{ number_format($draft->file_size / 1024, 2) }} KB</span>
                            <span class="mx-2">•</span>
                            <span>{{ $draft->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    <div>
                        <form action="{{ route('import.process', $draft->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-green-600 text-sm">
                                Proses Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative">
        <button id="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h3 class="text-xl font-medium text-gray-900 mb-2">File Berhasil Dikirim!</h3>
            <p class="text-gray-600 mb-6" id="successMessage">File Anda telah berhasil diunggah dan sedang diproses.</p>
            
            <div class="flex justify-center">
                <a href="{{ route('import.index') }}" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-green-600">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file-upload');
        const importForm = document.getElementById('importForm');
        const successModal = document.getElementById('successModal');
        const closeModal = document.getElementById('closeModal');
        const successMessage = document.getElementById('successMessage');
        
        // Handle file selection via input
        fileInput.addEventListener('change', function(e) {
            handleFiles(this.files);
        });
        
        // Handle drag and drop
        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('border-primary');
            this.classList.remove('border-gray-200');
        });
        
        dropzone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('border-primary');
            this.classList.add('border-gray-200');
        });
        
        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('border-primary');
            this.classList.add('border-gray-200');
            
            const dt = e.dataTransfer;
            const files = dt.files;
            
            handleFiles(files);
        });
        
        // Handle uploaded files
        function handleFiles(files) {
            if (files.length === 0) return;
            
            const file = files[0];
            
            // Check file size (25MB max)
            if (file.size > 25 * 1024 * 1024) {
                alert('Ukuran file melebihi batas maksimum 25MB');
                fileInput.value = '';
                return;
            }
            
            // Check file extension
            const fileName = file.name;
            const fileExt = fileName.split('.').pop().toLowerCase();
            const allowedExts = ['doc', 'docx', 'pdf', 'xls', 'xlsx', 'csv'];
            
            if (!allowedExts.includes(fileExt)) {
                alert('Format file tidak didukung. Silakan unggah file dengan format: ' + allowedExts.join(', '));
                fileInput.value = '';
                return;
            }
            
            // Update UI to show selected file
            const fileInfo = document.createElement('div');
            fileInfo.className = 'mt-3 p-3 bg-gray-50 rounded flex items-center';
            fileInfo.innerHTML = `
                <i class="fas fa-file-alt text-primary mr-3"></i>
                <div class="flex-1">
                    <div class="text-sm font-medium">${fileName}</div>
                    <div class="text-xs text-gray-500">${formatFileSize(file.size)}</div>
                </div>
                <button type="button" class="text-gray-500 hover:text-red-500" id="remove-file">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Clear previous file info if any
            const previousFileInfo = dropzone.querySelector('.mt-3');
            if (previousFileInfo) {
                previousFileInfo.remove();
            }
            
            dropzone.querySelector('.text-center').classList.add('hidden');
            dropzone.appendChild(fileInfo);
            
            // Handle remove button
            document.getElementById('remove-file').addEventListener('click', function() {
                fileInput.value = '';
                dropzone.querySelector('.text-center').classList.remove('hidden');
                fileInfo.remove();
            });
        }
        
        // Format file size
        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            else return (bytes / 1048576).toFixed(1) + ' MB';
        }
        
        // Show success modal if needed (after form submission)
        @if(session('showSuccessModal'))
            successModal.classList.remove('hidden');
            successMessage.textContent = "{{ session('successMessage') ?? 'File Anda telah berhasil diunggah dan sedang diproses.' }}";
        @endif
        
        // Close modal when the close button is clicked
        if (closeModal) {
            closeModal.addEventListener('click', function() {
                successModal.classList.add('hidden');
            });
        }
        
        // Close modal when clicking outside the modal content
        successModal.addEventListener('click', function(e) {
            if (e.target === this) {
                successModal.classList.add('hidden');
            }
        });
    });
</script>
@endsection