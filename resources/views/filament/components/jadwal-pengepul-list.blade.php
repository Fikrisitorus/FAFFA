<div class="space-y-4">
    <!-- Header Info -->
    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border-l-4 border-blue-400">
        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">
            📅 Jadwal Pengepul Tersedia
        </h4>
        <p class="text-sm text-blue-800 dark:text-blue-200">
            Berikut adalah jadwal pengepul yang tersedia untuk penjemputan sampah. 
            Meskipun ada jadwal, sistem tetap menggunakan FCFS (First Come, First Served).
        </p>
    </div>

    <!-- List Jadwal -->
    <div class="space-y-3">
        @foreach($jadwalPengepul as $jadwal)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <!-- Header Jadwal -->
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ ucfirst($jadwal->hari) }}
                                </span>
                            </div>
                            <span class="text-gray-400 dark:text-gray-500">•</span>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                                </span>
                            </div>
                        </div>

                        <!-- Info Pengepul -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Pengepul:</strong> 
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $jadwal->pengepul?->name ?? 'Tidak Diketahui' }}
                                    </span>
                                </p>
                                @if($jadwal->pengepul?->phone)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <strong>Telepon:</strong> 
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            {{ $jadwal->pengepul->phone }}
                                        </span>
                                    </p>
                                @endif
                            </div>
                            <div>
                                @if($jadwal->lokasi)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <strong>Lokasi:</strong> 
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            {{ $jadwal->lokasi }}
                                        </span>
                                    </p>
                                @endif
                                @if($jadwal->catatan)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <strong>Catatan:</strong> 
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            {{ $jadwal->catatan }}
                                        </span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="ml-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-400">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Tersedia
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Footer Info -->
    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border-l-4 border-yellow-400">
        <h4 class="text-sm font-medium text-yellow-900 dark:text-yellow-100 mb-2">
            ℹ️ Informasi Penting
        </h4>
        <div class="text-sm text-yellow-800 dark:text-yellow-200 space-y-1">
            <p>• Jadwal ini hanya untuk informasi, tidak menjamin pengepul tertentu akan datang</p>
            <p>• Sistem tetap menggunakan FCFS - siapa cepat dia dapat</p>
            <p>• Pengepul akan mendapat notifikasi real-time saat ada order baru</p>
            <p>• Anda bisa membuat permintaan penjemputan kapan saja</p>
        </div>
    </div>
</div>
