<div class="space-y-8">
    <!-- Hero Section - Clean & Simple -->
    <x-filament::section>
        <div class="text-center space-y-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-primary-100 dark:bg-primary-900/20 rounded-2xl">
                <svg class="w-10 h-10 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="space-y-4">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100">Informasi Kelompok Nasabah</h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto leading-relaxed">
                    Panduan lengkap dan mudah dipahami untuk pengelolaan sampah yang ramah lingkungan
                </p>
            </div>
        </div>
    </x-filament::section>

    <!-- Jenis Sampah Cards - Clean Grid -->
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-success-100 dark:bg-success-900/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Jenis Sampah yang Diterima</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pilih jenis sampah yang ingin Anda setor</p>
                </div>
            </div>
        </x-slot>
        
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <!-- Plastik Card -->
            <x-filament::card class="p-6 text-center hover:shadow-lg transition-shadow duration-300">
                <div class="w-16 h-16 bg-green-100 dark:bg-green-900/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Plastik</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">Botol, gelas plastik, kantong kresek</p>
                <div class="text-sm font-medium text-success-600 dark:text-success-400">Rp 2.000/kg</div>
            </x-filament::card>

            <!-- Kertas Card -->
            <x-filament::card class="p-6 text-center hover:shadow-lg transition-shadow duration-300">
                <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Kertas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">Koran, kardus, majalah</p>
                <div class="text-sm font-medium text-warning-600 dark:text-warning-400">Rp 1.500/kg</div>
            </x-filament::card>

            <!-- Logam Card -->
            <x-filament::card class="p-6 text-center hover:shadow-lg transition-shadow duration-300">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-900/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Logam</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">Kaleng, besi, alumunium</p>
                <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Rp 3.000/kg</div>
            </x-filament::card>

            <!-- Kaca Card -->
            <x-filament::card class="p-6 text-center hover:shadow-lg transition-shadow duration-300">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Kaca</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">Botol kaca, pecahan kaca</p>
                <div class="text-sm font-medium text-info-600 dark:text-info-400">Rp 1.000/kg</div>
            </x-filament::card>
        </div>
    </x-filament::section>

    <!-- Cara Setor Sampah - Clean Step Guide -->
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-info-100 dark:bg-info-900/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-info-600 dark:text-info-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Cara Setor Sampah</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ikuti langkah-langkah sederhana berikut</p>
                </div>
            </div>
        </x-slot>
        
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Left Column -->
            <div class="space-y-4">
                <x-filament::card class="p-5">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-success-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">1</div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Pilah Sampah</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Pilah sampah anorganik (plastik, kertas, logam, kaca) di rumah/kelompok Anda.</p>
                        </div>
                    </div>
                </x-filament::card>
                
                <x-filament::card class="p-5">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-success-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">2</div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Bersihkan Sampah</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Pastikan sampah sudah bersih dan kering untuk kualitas terbaik.</p>
                        </div>
                    </div>
                </x-filament::card>
                
                <x-filament::card class="p-5">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-success-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">3</div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Kemas Sampah</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Masukkan ke dalam wadah/karung sesuai jenisnya.</p>
                        </div>
                    </div>
                </x-filament::card>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-4">
                <x-filament::card class="p-5">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-success-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">4</div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Request Penjemputan</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Request penjemputan melalui menu "Request Penjemputan" di dashboard.</p>
                        </div>
                    </div>
                </x-filament::card>
                
                <x-filament::card class="p-5">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-success-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">5</div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Tunggu Pengepul</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Tunggu pengepul datang sesuai jadwal yang dipilih.</p>
                        </div>
                    </div>
                </x-filament::card>
            </div>
        </div>
    </x-filament::section>

    <!-- Jadwal Penjemputan - Clean Info -->
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Jadwal Penjemputan</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Fleksibel sesuai kebutuhan Anda</p>
                </div>
            </div>
        </x-slot>
        
        <x-filament::card class="p-6">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-warning-500 text-white rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Fleksibel & Sesuai Kebutuhan</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Penjemputan dilakukan sesuai permintaan kelompok. Silakan request jadwal melalui dashboard untuk mendapatkan layanan yang fleksibel dan sesuai kebutuhan Anda.</p>
                </div>
            </div>
        </x-filament::card>
    </x-filament::section>

    <!-- FAQ Section - Clean Accordion -->
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Pertanyaan Umum</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Klik untuk melihat jawaban</p>
                </div>
            </div>
        </x-slot>
        
        <div class="space-y-4">
            @foreach($faqItems as $index => $faq)
                <x-filament::card>
                    <button 
                        wire:click="toggleFaq({{ $index }})"
                        class="w-full p-5 text-left"
                    >
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-left">{{ $faq['question'] }}</h3>
                            <svg 
                                class="w-5 h-5 text-gray-500 transition-transform duration-200 {{ $faq['isOpen'] ? 'rotate-180' : '' }}"
                                fill="none" 
                                stroke="currentColor" 
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    
                    @if($faq['isOpen'])
                        <div class="px-5 pb-5 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-gray-600 dark:text-gray-400 mt-4 leading-relaxed">{{ $faq['answer'] }}</p>
                        </div>
                    @endif
                </x-filament::card>
            @endforeach
        </div>
    </x-filament::section>

    <!-- Call to Action - Clean & Clear -->
    <x-filament::section>
        <x-slot name="heading">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Siap Mulai Setor Sampah?</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 mt-3 leading-relaxed">Bergabunglah dengan sistem pengelolaan sampah yang ramah lingkungan</p>
            </div>
        </x-slot>
        
        <div class="text-center space-y-8">
            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <x-filament::button size="lg" color="primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                    Mulai Sekarang
                </x-filament::button>
                
                <x-filament::button size="lg" color="gray">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pelajari Lebih Lanjut
                </x-filament::button>
            </div>
            
            <!-- Clean Info Dots -->
            <div class="flex flex-wrap justify-center items-center space-x-8 text-sm text-gray-500 dark:text-gray-400">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-success-500 rounded-full"></div>
                    <span>100% Ramah Lingkungan</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-info-500 rounded-full"></div>
                    <span>Proses Mudah & Cepat</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-warning-500 rounded-full"></div>
                    <span>Keuntungan Maksimal</span>
                </div>
            </div>
        </div>
    </x-filament::section>
</div>