<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCM Cargo - Solusi Pengiriman Terpercaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .hero-pattern { background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05' fill-rule='nonzero'%3E%3Ccircle cx='7' cy='7' r='7'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
        .animate-float { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-slide-up { animation: slideUp 0.8s ease-out forwards; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navigation -->
    <nav class="fixed w-full bg-white/95 backdrop-blur-sm shadow-sm z-50" x-data="{ open: false, scrolled: false }" 
         @scroll.window="scrolled = window.scrollY > 50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shipping-fast text-white text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">CCM Cargo</span>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#beranda" class="text-gray-700 hover:text-blue-600 transition duration-200">Beranda</a>
                    <a href="#layanan" class="text-gray-700 hover:text-blue-600 transition duration-200">Layanan</a>
                    <a href="#cara-kerja" class="text-gray-700 hover:text-blue-600 transition duration-200">Cara Kerja</a>
                    <a href="#testimoni" class="text-gray-700 hover:text-blue-600 transition duration-200">Testimoni</a>
                    <a href="#kontak" class="text-gray-700 hover:text-blue-600 transition duration-200">Kontak</a>
                </div>

                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/track" class="text-gray-700 hover:text-blue-600 transition duration-200">
                        <i class="fas fa-search mr-2"></i>Lacak Paket
                    </a>
                    <a href="/login" class="text-blue-600 hover:text-blue-800 transition duration-200">Masuk</a>
                    <a href="/register" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                        Daftar Sekarang
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="open" @click.away="open = false" class="md:hidden bg-white border-t">
                <div class="py-4 space-y-4">
                    <a href="#beranda" class="block text-gray-700 hover:text-blue-600">Beranda</a>
                    <a href="#layanan" class="block text-gray-700 hover:text-blue-600">Layanan</a>
                    <a href="#cara-kerja" class="block text-gray-700 hover:text-blue-600">Cara Kerja</a>
                    <a href="#testimoni" class="block text-gray-700 hover:text-blue-600">Testimoni</a>
                    <a href="#kontak" class="block text-gray-700 hover:text-blue-600">Kontak</a>
                    <div class="border-t pt-4 space-y-2">
                        <a href="/track" class="block text-gray-700 hover:text-blue-600">
                            <i class="fas fa-search mr-2"></i>Lacak Paket
                        </a>
                        <a href="/login" class="block text-blue-600 hover:text-blue-800">Masuk</a>
                        <a href="/register" class="block bg-blue-600 text-white px-4 py-2 rounded-lg text-center">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="relative min-h-screen gradient-bg hero-pattern flex items-center overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full animate-float"></div>
            <div class="absolute top-1/3 right-20 w-16 h-16 bg-white/10 rounded-full animate-float" style="animation-delay: -2s;"></div>
            <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-white/10 rounded-full animate-float" style="animation-delay: -4s;"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Content -->
                <div class="text-center lg:text-left animate-slide-up">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                        Solusi Pengiriman
                        <span class="text-yellow-300">Terpercaya</span>
                        untuk Bisnis Anda
                    </h1>
                    <p class="text-xl text-white/90 mb-8 leading-relaxed">
                        Kirimkan paket Anda dengan aman, cepat, dan mudah. CCM Cargo memberikan layanan pengiriman terbaik dengan teknologi modern dan jaringan nasional.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="/register" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-8 py-4 rounded-lg font-semibold text-lg transition duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-rocket mr-2"></i>
                            Mulai Kirim Sekarang
                        </a>
                        <a href="#cara-kerja" class="bg-white/20 hover:bg-white/30 text-white px-8 py-4 rounded-lg font-semibold text-lg transition duration-300 backdrop-blur-sm border border-white/20">
                            <i class="fas fa-play mr-2"></i>
                            Lihat Cara Kerja
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 mt-12 text-center lg:text-left">
                        <div class="text-white">
                            <div class="text-3xl font-bold text-yellow-300">50K+</div>
                            <div class="text-sm opacity-90">Paket Terkirim</div>
                        </div>
                        <div class="text-white">
                            <div class="text-3xl font-bold text-yellow-300">100+</div>
                            <div class="text-sm opacity-90">Kota Tujuan</div>
                        </div>
                        <div class="text-white">
                            <div class="text-3xl font-bold text-yellow-300">99.8%</div>
                            <div class="text-sm opacity-90">Tingkat Kepuasan</div>
                        </div>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="relative animate-slide-up" style="animation-delay: 0.3s;">
                    <div class="relative z-10">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                            <!-- Tracking Widget -->
                            <div class="bg-white rounded-xl p-6 shadow-xl">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                    <i class="fas fa-search text-blue-600 mr-2"></i>
                                    Lacak Paket Anda
                                </h3>
                                <div class="space-y-4">
                                    <input type="text" placeholder="Masukkan nomor resi..." 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                                        Lacak Sekarang
                                    </button>
                                </div>
                                <div class="mt-4 text-sm text-gray-600">
                                    <i class="fas fa-shield-alt text-green-500 mr-1"></i>
                                    Tracking real-time 24/7
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute -top-4 -right-4 w-24 h-24 bg-yellow-400/20 rounded-full"></div>
                    <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-blue-400/20 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Layanan Kami</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Kami menyediakan berbagai layanan pengiriman yang dapat disesuaikan dengan kebutuhan Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition duration-300 border border-gray-100 hover:-translate-y-2">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 transition duration-300">
                        <i class="fas fa-shipping-fast text-2xl text-blue-600 group-hover:text-white transition duration-300"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Pengiriman Regular</h3>
                    <p class="text-gray-600 mb-6">Layanan pengiriman standar dengan waktu 2-3 hari kerja ke seluruh Indonesia dengan harga terjangkau.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Asuransi gratis</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Tracking real-time</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Garansi aman</li>
                    </ul>
                </div>

                <!-- Service 2 -->
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition duration-300 border border-gray-100 hover:-translate-y-2">
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-yellow-500 transition duration-300">
                        <i class="fas fa-bolt text-2xl text-yellow-600 group-hover:text-white transition duration-300"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Pengiriman Express</h3>
                    <p class="text-gray-600 mb-6">Layanan pengiriman ekspres untuk kebutuhan mendesak dengan waktu 1 hari kerja.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Prioritas tinggi</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Same day delivery</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Customer service 24/7</li>
                    </ul>
                </div>

                <!-- Service 3 -->
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition duration-300 border border-gray-100 hover:-translate-y-2">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-green-600 transition duration-300">
                        <i class="fas fa-box text-2xl text-green-600 group-hover:text-white transition duration-300"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Cargo Besar</h3>
                    <p class="text-gray-600 mb-6">Layanan khusus untuk pengiriman barang dalam jumlah besar dan dimensi khusus.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Kapasitas besar</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Handling khusus</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Harga wholesale</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="cara-kerja" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Cara Kerja</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Proses pengiriman yang mudah dan dapat dipantau dalam 4 langkah sederhana
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="text-center group">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto group-hover:scale-110 transition duration-300 shadow-lg">
                            <i class="fas fa-edit text-2xl text-white"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-sm font-bold text-gray-900">1</div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Buat Pesanan</h3>
                    <p class="text-gray-600">Isi form pengiriman dengan data pengirim, penerima, dan detail barang yang akan dikirim.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center group">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto group-hover:scale-110 transition duration-300 shadow-lg">
                            <i class="fas fa-credit-card text-2xl text-white"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-sm font-bold text-gray-900">2</div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Bayar & Konfirmasi</h3>
                    <p class="text-gray-600">Lakukan pembayaran dengan berbagai metode yang tersedia dan konfirmasi pesanan Anda.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center group">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto group-hover:scale-110 transition duration-300 shadow-lg">
                            <i class="fas fa-truck text-2xl text-white"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-sm font-bold text-gray-900">3</div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Pickup & Kirim</h3>
                    <p class="text-gray-600">Tim kami akan menjemput paket Anda atau Anda dapat mengirim ke cabang terdekat.</p>
                </div>

                <!-- Step 4 -->
                <div class="text-center group">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto group-hover:scale-110 transition duration-300 shadow-lg">
                            <i class="fas fa-check-circle text-2xl text-white"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-sm font-bold text-gray-900">4</div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Terkirim</h3>
                    <p class="text-gray-600">Paket Anda akan sampai di tujuan tepat waktu dan Anda akan mendapat notifikasi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimoni" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Apa Kata Mereka</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Testimoni dari ribuan pelanggan yang telah mempercayakan pengiriman mereka kepada kami
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-8 shadow-lg border border-blue-100">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="ml-2 text-sm text-gray-600">5.0</span>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Pelayanan CCM Cargo sangat memuaskan. Paket sampai tepat waktu dan kondisi barang sangat baik. Akan terus menggunakan layanan ini."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold">AS</span>
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Andi Setiawan</div>
                            <div class="text-sm text-gray-600">Pemilik Toko Online</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-gradient-to-br from-green-50 to-white rounded-2xl p-8 shadow-lg border border-green-100">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="ml-2 text-sm text-gray-600">5.0</span>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Sistem tracking yang real-time sangat membantu. Saya bisa memantau paket dari awal hingga sampai tujuan. Customer service juga sangat responsif."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold">SP</span>
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Sari Pratiwi</div>
                            <div class="text-sm text-gray-600">Entrepreneur</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-gradient-to-br from-purple-50 to-white rounded-2xl p-8 shadow-lg border border-purple-100">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="ml-2 text-sm text-gray-600">5.0</span>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Harga yang kompetitif dengan kualitas layanan yang prima. Sudah 2 tahun menggunakan CCM Cargo dan tidak pernah kecewa."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold">RH</span>
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Rudi Hartono</div>
                            <div class="text-sm text-gray-600">Distributor</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Siap Memulai Pengiriman?</h2>
            <p class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
                Bergabunglah dengan ribuan pelanggan yang telah mempercayakan kebutuhan pengiriman mereka kepada CCM Cargo
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-8 py-4 rounded-lg font-semibold text-lg transition duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Gratis Sekarang
                </a>
                <a href="/track" class="bg-white/20 hover:bg-white/30 text-white px-8 py-4 rounded-lg font-semibold text-lg transition duration-300 backdrop-blur-sm border border-white/20">
                    <i class="fas fa-search mr-2"></i>
                    Lacak Paket Anda
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="kontak" class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shipping-fast text-white text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold">CCM Cargo</span>
                    </div>
                    <p class="text-gray-400 mb-6">
                        Solusi pengiriman terpercaya dengan teknologi modern dan jaringan luas di seluruh Indonesia.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition duration-200">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">Layanan</h3>
                    <ul class="space-y-4 text-gray-400">
                        <li><a href="#" class="hover:text-white transition duration-200">Pengiriman Regular</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Pengiriman Express</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Cargo Besar</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">International Shipping</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Same Day Delivery</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">Bantuan</h3>
                    <ul class="space-y-4 text-gray-400">
                        <li><a href="#" class="hover:text-white transition duration-200">Lacak Paket</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Cara Pengiriman</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Customer Service</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Klaim & Komplain</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">Kontak</h3>
                    <div class="space-y-4 text-gray-400">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt w-5 mr-3"></i>
                            <span>Jl. Malioboro No. 123<br>Yogyakarta 55213</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone w-5 mr-3"></i>
                            <span>+62 274 123 456</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope w-5 mr-3"></i>
                            <span>info@ccmcargo.com</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock w-5 mr-3"></i>
                            <span>24/7 Customer Support</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; 2024 CCM Cargo. All rights reserved. | Privacy Policy | Terms of Service</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="fixed bottom-6 right-6 w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg transition duration-300 transform hover:scale-110 z-40"
            style="display: none;" 
            onscroll="this.style.display = window.scrollY > 300 ? 'block' : 'none'">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script>
        // Show/hide back to top button
        window.addEventListener('scroll', function() {
            const button = document.querySelector('button[onclick]');
            if (window.scrollY > 300) {
                button.style.display = 'block';
            } else {
                button.style.display = 'none';
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

</body>
</html>