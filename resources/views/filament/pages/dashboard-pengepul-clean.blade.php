<x-filament-panels::page>
    <div class="space-y-6">

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Penjemputan Card -->
            <x-filament::card>
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Penjemputan</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalPenjemputan }}</p>
                    </div>
                </div>
            </x-filament::card>

            <!-- Penjemputan Hari Ini Card -->
            <x-filament::card>
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Hari Ini</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $penjemputanHariIni }}</p>
                    </div>
                </div>
            </x-filament::card>

            <!-- Total Pendapatan Card -->
            <x-filament::card>
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pendapatan</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </x-filament::card>
        </div>

        <!-- Shortcuts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Shortcut Verifikasi Berat -->
            <a href="{{ route('filament.admin.pages.verifikasi-berat') }}" class="block">
                <x-filament::card class="cursor-pointer hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">Verifikasi Berat</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Timbang dan verifikasi sampah</p>
                            </div>
                        </div>
                        <div class="text-orange-600 dark:text-orange-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </x-filament::card>
            </a>

            <!-- Shortcut Pembayaran -->
            <a href="{{ route('filament.admin.pages.pembayaran') }}" class="block">
                <x-filament::card class="cursor-pointer hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">Pembayaran</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Proses pembayaran sampah</p>
                            </div>
                        </div>
                        <div class="text-green-600 dark:text-green-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </x-filament::card>
            </a>
        </div>

        <!-- Order Pending -->
        <x-filament::section id="order-pending">
            <x-slot name="heading">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Order Pending</h2>
            </x-slot>
            
            @if($penjemputanPending->count() > 0)
                <div class="space-y-4">
                    @foreach($penjemputanPending as $penjemputan)
                        <x-filament::card class="border-yellow-200 bg-yellow-50 dark:bg-yellow-900/20">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-400">
                                            Pending
                                        </span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($penjemputan->tanggal_penjemputan)->format('d M Y') }} - 
                                            {{ \Carbon\Carbon::parse($penjemputan->waktu_penjemputan)->format('H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        <strong>Kelompok:</strong> {{ $penjemputan->kelompok?->nama ?? 'Tidak Diketahui' }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <strong>Alamat:</strong> {{ $penjemputan->alamat_penjemputan }}
                                    </p>
                                    @if($penjemputan->catatan)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Catatan: {{ $penjemputan->catatan }}</p>
                                    @endif
                                </div>
                                
                                <div class="ml-4">
                                    <x-filament::button 
                                        size="sm" 
                                        color="primary"
                                        tag="a"
                                        href="{{ route('pengepul.ambil-order', $penjemputan->id) }}"
                                    >
                                        <i class="fas fa-hand-paper mr-2"></i>
                                        Ambil Order
                                    </x-filament::button>
                                </div>
                            </div>
                        </x-filament::card>

                        <!-- Modal Detail Order Pending -->
                        <x-filament::modal id="detail-pending-{{ $penjemputan->id }}" width="lg">
                            <x-slot name="header">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    Detail Order Pending
                                </h3>
                            </x-slot>

                            <div class="space-y-4">
                                <!-- Informasi Dasar -->
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Informasi Penjemputan</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Kelompok:</strong> {{ $penjemputan->kelompok?->nama ?? 'Tidak Diketahui' }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjemputan->tanggal_penjemputan)->format('d M Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($penjemputan->waktu_penjemputan)->format('H:i') }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Status:</strong> <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                                        <strong>Alamat:</strong> {{ $penjemputan->alamat_penjemputan }}
                                    </p>
                                    @if($penjemputan->catatan)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                            <strong>Catatan:</strong> {{ $penjemputan->catatan }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Detail Sampah -->
                                @if($penjemputan->sampahDetails && $penjemputan->sampahDetails->count() > 0)
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Detail Sampah</h4>
                                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                            @php
                                                $totalBerat = 0;
                                                $estimasiHarga = 0;
                                            @endphp
                                            
                                            @foreach($penjemputan->sampahDetails as $detail)
                                                @php
                                                    $berat = $detail->berat ?? 0;
                                                    $hargaPerKg = (float)($detail->jenisSampah?->harga ?? 0);
                                                    $totalBerat += $berat;
                                                    $estimasiHarga += $berat * $hargaPerKg;
                                                @endphp
                                                
                                                <div class="flex justify-between items-center p-3 bg-white dark:bg-gray-700 rounded-lg mb-2">
                                                    <div>
                                                        <p class="font-medium text-gray-900 dark:text-white">
                                                            {{ $detail->jenisSampah?->nama ?? 'Tidak Diketahui' }}
                                                        </p>
                                                        @if($hargaPerKg > 0)
                                                            <p class="text-sm text-gray-500">
                                                                Rp {{ number_format($hargaPerKg, 0, ',', '.') }}/kg
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="font-bold text-gray-900 dark:text-white">
                                                            {{ $detail->berat }} kg
                                                        </p>
                                                        @if($hargaPerKg > 0)
                                                            <p class="text-sm text-green-600 font-medium">
                                                                Rp {{ number_format($berat * $hargaPerKg, 0, ',', '.') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                            <!-- Total Summary -->
                                            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border-l-4 border-blue-400">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <p class="text-sm font-medium text-blue-900 dark:text-blue-100">Total Berat:</p>
                                                        <p class="text-lg font-bold text-blue-900 dark:text-blue-100">{{ number_format($totalBerat, 1) }} kg</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-sm font-medium text-blue-900 dark:text-blue-100">Estimasi Harga:</p>
                                                        <p class="text-lg font-bold text-blue-900 dark:text-blue-100">
                                                            @if($estimasiHarga > 0)
                                                                Rp {{ number_format($estimasiHarga, 0, ',', '.') }}
                                                            @else
                                                                Belum dihitung
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Detail Sampah</h4>
                                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                            <p class="text-gray-600 dark:text-gray-400">Detail sampah belum diisi</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <x-slot name="footer">
                                <div class="flex justify-end space-x-2">
                                    <x-filament::button 
                                        color="primary"
                                        tag="a"
                                        href="{{ route('pengepul.ambil-order', $penjemputan->id) }}"
                                    >
                                        <i class="fas fa-hand-paper mr-2"></i>
                                        Ambil Order
                                    </x-filament::button>
                                    <x-filament::button 
                                        color="gray" 
                                        x-on:click="$dispatch('close-modal', { id: 'detail-pending-{{ $penjemputan->id }}' })"
                                    >
                                        Tutup
                                    </x-filament::button>
                                </div>
                            </x-slot>
                        </x-filament::modal>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada order pending</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Semua order sudah diambil atau belum ada order baru.</p>
                </div>
            @endif
        </x-filament::section>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Charts JavaScript -->
    <script>
        // Data untuk chart dari backend
        const chartData = {
            // Data untuk chart compact
            labels: @json($chartPenjemputanBulan['labels'] ?? []),
            penjemputan: @json($chartPenjemputanBulan['data'] ?? []),
            completedOrders: @json($chartCompletedOrdersBulan['data'] ?? []),
            pendingOrders: @json($chartPendingOrdersBulan['data'] ?? [])
        };

        // Penjemputan Chart
        const ctxPenjemputan = document.getElementById('penjemputanChart').getContext('2d');
        new Chart(ctxPenjemputan, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Penjemputan',
                    data: chartData.penjemputan,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                }
            }
        });

        // Completed Orders Chart
        const ctxCompletedOrders = document.getElementById('completedOrdersChart').getContext('2d');
        new Chart(ctxCompletedOrders, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Completed Orders',
                    data: chartData.completedOrders,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                }
            }
        });

        // Pending Orders Chart
        const ctxPendingOrders = document.getElementById('pendingOrdersChart').getContext('2d');
        new Chart(ctxPendingOrders, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Pending Orders',
                    data: chartData.pendingOrders,
                    borderColor: 'rgb(251, 146, 60)',
                    backgroundColor: 'rgba(251, 146, 60, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                }
            }
        });

        // Chart keempat dihapus untuk menghemat ruang
    </script>
</x-filament-panels::page>
