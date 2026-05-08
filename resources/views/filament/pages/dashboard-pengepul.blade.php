<x-filament-panels::page>
    
    <div class="space-y-6">
        <!-- Welcome Header -->
        <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Selamat Datang, {{ $pengepul->name }}! 👋</h1>
                        <p class="text-gray-600 dark:text-gray-400">Kelola penjemputan sampah dengan mudah dan efisien</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                            <x-heroicon-o-clipboard-document-list class="w-8 h-8 text-primary-600 dark:text-primary-400" />
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::card>

        <!-- Pending Orders - Dipindahkan ke bawah Selamat Datang -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center justify-between w-full">
                    <span>Order Tersedia</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-warning-500 rounded-full animate-pulse"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $orderPending->count() }} order menunggu</span>
                    </div>
                </div>
            </x-slot>
            
            @if($orderPending->count() > 0)
                <div class="space-y-4">
                    @foreach($orderPending as $penjemputan)
                        <x-filament::card class="border-l-4 border-warning-500">
                            <div class="p-3 sm:p-6">
                                <!-- Header dengan status dan tanggal -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <x-filament::badge color="warning" size="sm">
                                            PENDING
                                        </x-filament::badge>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($penjemputan->tanggal_penjemputan)->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($penjemputan->waktu_penjemputan)->format('H:i') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi utama -->
                                <div class="space-y-3 mb-4">
                                    <!-- Kelompok -->
                                    <div class="flex items-start space-x-3">
                                        <x-filament::icon
                                            icon="heroicon-o-user-group"
                                            class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0"
                                        />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">Kelompok</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $penjemputan->kelompok?->nama ?? 'Tidak Diketahui' }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Alamat -->
                                    <div class="flex items-start space-x-3">
                                        <x-filament::icon
                                            icon="heroicon-o-map-pin"
                                            class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0"
                                        />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">Alamat</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 break-words">{{ $penjemputan->alamat_penjemputan }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Catatan -->
                                    @if($penjemputan->catatan)
                                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                                            <div x-data="{ open: false }">
                                                <div class="flex items-start space-x-2">
                                                    <x-filament::icon icon="heroicon-o-document-text" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" />
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Catatan</p>
                                                        <p :class="open ? '' : 'max-h-8 overflow-hidden'" class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line leading-relaxed break-words">
                                                            {{ $penjemputan->catatan }}
                                                        </p>
                                                        @if(strlen($penjemputan->catatan) > 50)
                                                            <button type="button" @click="open = !open" class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 mt-1">
                                                                <span x-show="!open">Lihat selengkapnya</span>
                                                                <span x-show="open">Tutup</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Action buttons -->
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <form method="POST" action="{{ route('pengepul.ambil-order', $penjemputan->id) }}" class="w-full sm:flex-1">
                                        @csrf
                                        <x-filament::button 
                                            size="sm" 
                                            color="primary"
                                            type="submit"
                                            icon="heroicon-o-plus"
                                            class="w-full"
                                        >
                                            Ambil Order
                                        </x-filament::button>
                                    </form>
                                    
                                    <x-filament::button 
                                        size="sm" 
                                        color="gray"
                                        x-data
                                        x-on:click="$dispatch('open-modal', { id: 'detail-pending-{{ $penjemputan->id }}' })"
                                        class="w-full sm:flex-1"
                                    >
                                        Lihat Detail
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
                                    <form method="POST" action="{{ route('pengepul.ambil-order', $penjemputan->id) }}" class="inline">
                                        @csrf
                                        <x-filament::button 
                                            color="primary"
                                            type="submit"
                                        >
                                            <i class="fas fa-hand-paper mr-2"></i>
                                            Ambil Order
                                        </x-filament::button>
                                    </form>
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
                <div class="flex justify-center py-8">
                    <x-filament::card class="max-w-md w-full text-center">
                        <div class="p-6">
                            <div class="mx-auto mb-4 w-16 h-16 rounded-full flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                                <x-filament::icon icon="heroicon-o-clipboard-document-list" class="w-8 h-8 text-gray-500" />
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Tidak ada order pending</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Semua order sudah diambil atau belum ada order baru.</p>
                            <x-filament::button onclick="window.location.reload()" icon="heroicon-o-arrow-path">
                                Refresh
                            </x-filament::button>
                        </div>
                    </x-filament::card>
                </div>
            @endif
        </x-filament::section>

        <!-- Stats Overview (using Filament cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <x-filament::icon icon="heroicon-o-clipboard-document-list" class="w-6 h-6 text-primary-600 mr-3" />
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Penjemputan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPenjemputan }}</p>
                        </div>
                    </div>
                </div>
            </x-filament::card>

            <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <x-filament::icon icon="heroicon-o-calendar-days" class="w-6 h-6 text-success-600 mr-3" />
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Hari Ini</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $penjemputanHariIni }}</p>
                        </div>
                    </div>
                </div>
            </x-filament::card>

            <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <x-filament::icon icon="heroicon-o-currency-dollar" class="w-6 h-6 text-warning-600 mr-3" />
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Pembayaran</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </x-filament::card>

            <!-- Card Upload Bukti Transaksi -->
            <a href="{{ \App\Filament\Pages\UploadBuktiTransaksi::getUrl() }}" class="block">
                <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <x-filament::icon icon="heroicon-o-cloud-arrow-up" class="w-6 h-6 text-info-600 mr-3" />
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Upload Bukti Transaksi</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    @if($totalTransaksiTanpaBukti > 0)
                                        {{ $totalTransaksiTanpaBukti }}
                                    @else
                                        0
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if($totalTransaksiTanpaBukti > 0)
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-200">
                                    Pending
                                </span>
                            </div>
                        @endif
                    </div>
                </x-filament::card>
            </a>
        </div>

        <!-- Quick Actions -->
        <x-filament::section>
            <x-slot name="heading">
                Quick Actions
            </x-slot>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-filament::button
                    tag="a"
                    href="{{ route('filament.admin.pages.verifikasi-berat') }}"
                    size="lg"
                    color="warning"
                    icon="heroicon-o-scale"
                    class="h-20 justify-start"
                >
                    <div class="text-left">
                        <div class="font-semibold">Verifikasi Berat</div>
                        <div class="text-sm opacity-75">Timbang dan verifikasi sampah</div>
                    </div>
                </x-filament::button>
                
                <x-filament::button
                    tag="a"
                    href="{{ route('filament.admin.pages.pembayaran') }}"
                    size="lg"
                    color="success"
                    icon="heroicon-o-currency-dollar"
                    class="h-20 justify-start"
                >
                    <div class="text-left">
                        <div class="font-semibold">Pembayaran</div>
                        <div class="text-sm opacity-75">Proses pembayaran sampah</div>
                    </div>
                </x-filament::button>
            </div>
        </x-filament::section>

        <!-- Analytics Dashboard -->
        <x-filament::section>
            <x-slot name="heading">
                Analytics Dashboard
            </x-slot>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Total Orders Chart -->
                <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <x-filament::icon
                                    icon="heroicon-o-chart-bar"
                                    class="w-5 h-5 text-primary-600 mr-3"
                                />
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Total Orders</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">6 bulan terakhir</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $totalOrders }}</span>
                            </div>
                        </div>
                        <div class="h-32">
                            <canvas id="totalOrdersChart"></canvas>
                        </div>
                    </div>
                </x-filament::card>

                <!-- Completed Orders Chart -->
                <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <x-filament::icon
                                    icon="heroicon-o-check-circle"
                                    class="w-5 h-5 text-success-600 mr-3"
                                />
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Completed</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Selesai diproses</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-success-600 dark:text-success-400">{{ $completedOrders }}</span>
                            </div>
                        </div>
                        <div class="h-32">
                            <canvas id="completedOrdersChart"></canvas>
                        </div>
                    </div>
                </x-filament::card>

                <!-- Pending Orders Chart -->
                <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <x-filament::icon
                                    icon="heroicon-o-clock"
                                    class="w-5 h-5 text-warning-600 mr-3"
                                />
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Pending</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Menunggu proses</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-warning-600 dark:text-warning-400">{{ $pendingOrders }}</span>
                            </div>
                        </div>
                        <div class="h-32">
                            <canvas id="pendingOrdersChart"></canvas>
                        </div>
                    </div>
                </x-filament::card>
            </div>
        </x-filament::section>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Enhanced Charts JavaScript -->
    <script>
        // Data untuk chart dari backend
        const chartData = {
            labels: @json($chartPenjemputanBulan['labels'] ?? []),
            penjemputan: @json($chartPenjemputanBulan['data'] ?? []),
            completedOrders: @json($chartCompletedOrdersBulan['data'] ?? []),
            pendingOrders: @json($chartPendingOrdersBulan['data'] ?? [])
        };

        // Detect dark mode (Filament adds 'dark' class on <html>)
        const isDark = document.documentElement.classList.contains('dark');
        const colors = {
            text: isDark ? 'rgba(243, 244, 246, 0.9)' : 'rgba(55, 65, 81, 0.8)', // Lebih kontras di dark mode
            grid: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)', // Lebih terlihat di dark mode
            tooltipBg: isDark ? 'rgba(15, 23, 42, 0.95)' : 'rgba(255, 255, 255, 0.95)', // Lebih solid
            tooltipBorder: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
            tooltipTitle: isDark ? '#F9FAFB' : '#1F2937', // gray-800 di light mode
            tooltipBody: isDark ? '#E5E7EB' : '#374151', // gray-700 di light mode
        };

        // Common chart options
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: colors.tooltipBg,
                    titleColor: colors.tooltipTitle,
                    bodyColor: colors.tooltipBody,
                    borderColor: colors.tooltipBorder,
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: colors.grid,
                        drawBorder: false
                    },
                    ticks: {
                        color: colors.text,
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: colors.text,
                        font: {
                            size: 11
                        }
                    }
                }
            },
            elements: {
                point: {
                    radius: 4,
                    hoverRadius: 6,
                    borderWidth: 2
                },
                line: {
                    borderWidth: 3
                }
            }
        };

        // Total Orders Chart
        const ctxTotalOrders = document.getElementById('totalOrdersChart').getContext('2d');
        new Chart(ctxTotalOrders, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Total Orders',
                    data: chartData.penjemputan,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: 'white',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    tooltip: {
                        ...commonOptions.plugins.tooltip,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                return `Total Orders: ${context.parsed.y}`;
                            }
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
                    pointBackgroundColor: 'rgb(34, 197, 94)',
                    pointBorderColor: 'white',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    tooltip: {
                        ...commonOptions.plugins.tooltip,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                return `Completed: ${context.parsed.y}`;
                            }
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
                    pointBackgroundColor: 'rgb(251, 146, 60)',
                    pointBorderColor: 'white',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    tooltip: {
                        ...commonOptions.plugins.tooltip,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                return `Pending: ${context.parsed.y}`;
                            }
                        }
                    }
                }
            }
        });

        // Add smooth scroll behavior
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading animation to charts
            const charts = document.querySelectorAll('canvas');
            charts.forEach(canvas => {
                canvas.style.opacity = '0';
                canvas.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    canvas.style.transition = 'all 0.6s ease-out';
                    canvas.style.opacity = '1';
                    canvas.style.transform = 'translateY(0)';
                }, 200);
            });
        });
    </script>
</x-filament-panels::page>
