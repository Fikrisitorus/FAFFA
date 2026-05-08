<x-filament-panels::page>
    <div class="space-y-6">


        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Saldo Card -->
            <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Saldo Kelompok</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">Rp {{ number_format($totalSaldoKelompok, 0, ',', '.') }}</p>
                    </div>
                </div>
            </x-filament::card>

            <!-- Saldo Individual Card -->
            <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Saldo Individual</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">Rp {{ number_format($totalSaldoIndividual, 0, ',', '.') }}</p>
                    </div>
                </div>
            </x-filament::card>

            <!-- Sampah Card -->
            <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Sampah</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalSampah, 1) }} kg</p>
                    </div>
                </div>
            </x-filament::card>

            <!-- Order Card -->
            <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Order</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalOrder }}</p>
                    </div>
                </div>
            </x-filament::card>
        </div>

        <!-- Enhanced Stats with Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <!-- Total Sampah Chart -->
            <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Sampah</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($totalSampah, 1) }} kg</span>
                    </div>
                    <div class="h-32">
                        <canvas id="totalSampahChart"></canvas>
                    </div>
                </div>
            </x-filament::card>

            <!-- Completed Orders Chart -->
            <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Completed Orders</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $completedOrders ?? 0 }}</span>
                    </div>
                    <div class="h-32">
                        <canvas id="completedOrdersChart"></canvas>
                    </div>
                </div>
            </x-filament::card>

            <!-- Pending Orders Chart -->
            <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Pending Orders</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $pendingOrders ?? 0 }}</span>
                    </div>
                    <div class="h-32">
                        <canvas id="pendingOrdersChart"></canvas>
                    </div>
                </div>
            </x-filament::card>
        </div>

        <!-- Penjemputan Aktif -->
        <x-filament::section>
            <x-slot name="heading">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Penjemputan Aktif</h2>
            </x-slot>
            @if($penjemputanAktif->count() > 0)
                <div class="space-y-4">
                    @foreach($penjemputanAktif as $penjemputan)
                        <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md {{ $penjemputan->status === 'pending' ? 'border-yellow-200 bg-yellow-50 dark:bg-yellow-900/20' : ($penjemputan->status === 'assigned' ? 'border-blue-200 bg-blue-50 dark:bg-blue-900/20' : 'border-green-200 bg-green-50 dark:bg-green-900/20') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            {{ $penjemputan->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-400' : 
                                               ($penjemputan->status === 'assigned' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-400' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-400') }}">
                                            {{ ucfirst($penjemputan->status) }}
                                        </span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($penjemputan->tanggal_penjemputan)->format('d M Y') }} - 
                                            {{ \Carbon\Carbon::parse($penjemputan->waktu_penjemputan)->format('H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $penjemputan->alamat_penjemputan }}</p>
                                    @if($penjemputan->pengepul)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Pengepul: {{ $penjemputan->pengepul->name }}</p>
                                    @endif
                                    @if($penjemputan->catatan)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Catatan: {{ $penjemputan->catatan }}</p>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    @if($penjemputan->status === 'pending')
                                        <x-filament::button 
                                            size="sm" 
                                            color="danger"
                                            x-data=""
                                            x-on:click="$dispatch('open-modal', { id: 'batal-penjemputan-{{ $penjemputan->id }}' })"
                                        >
                                            Batalkan
                                        </x-filament::button>
                                    @endif
                                    <x-filament::button 
                                        size="sm" 
                                        color="gray"
                                        x-data=""
                                        x-on:click="$dispatch('open-modal', { id: 'detail-aktif-{{ $penjemputan->id }}' })"
                                    >
                                        Detail
                                    </x-filament::button>
                                </div>
                            </div>
                        </x-filament::card>

                        <!-- Modal Detail Penjemputan Aktif -->
                        <x-filament::modal id="detail-aktif-{{ $penjemputan->id }}" width="lg">
                            <x-slot name="header">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    Detail Penjemputan Aktif
                                </h3>
                            </x-slot>

                            <div class="space-y-4">
                                <!-- Informasi Dasar -->
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Informasi Penjemputan</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Status:</strong> 
                                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                    {{ $penjemputan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($penjemputan->status === 'assigned' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                                    {{ ucfirst($penjemputan->status) }}
                                                </span>
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjemputan->tanggal_penjemputan)->format('d M Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($penjemputan->waktu_penjemputan)->format('H:i') }}
                                            </p>
                                            @if($penjemputan->pengepul)
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    <strong>Pengepul:</strong> {{ $penjemputan->pengepul->name }}
                                                </p>
                                            @endif
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
                                        color="gray" 
                                        x-on:click="$dispatch('close-modal', { id: 'detail-aktif-{{ $penjemputan->id }}' })"
                                    >
                                        Tutup
                                    </x-filament::button>
                                    @if($penjemputan->status === 'pending')
                                        <x-filament::button 
                                            color="danger"
                                            x-on:click="$dispatch('open-modal', { id: 'batal-penjemputan-{{ $penjemputan->id }}' })"
                                        >
                                            Batalkan Penjemputan
                                        </x-filament::button>
                                    @endif
                                </div>
                            </x-slot>
                        </x-filament::modal>

                        <!-- Modal Konfirmasi Pembatalan -->
                        @if($penjemputan->status === 'pending')
                            <x-filament::modal id="batal-penjemputan-{{ $penjemputan->id }}" width="md">
                                <x-slot name="header">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        Konfirmasi Pembatalan Penjemputan
                                    </h3>
                                </x-slot>

                                <div class="space-y-4">
                                    <!-- Detail yang akan dibatalkan -->
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border-l-4 border-yellow-400">
                                        <h4 class="font-medium text-yellow-900 dark:text-yellow-100 mb-3">
                                            Detail yang akan dibatalkan:
                                        </h4>
                                        <div class="space-y-2 text-sm text-yellow-800 dark:text-yellow-200">
                                            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjemputan->tanggal_penjemputan)->format('d M Y') }}</p>
                                            <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($penjemputan->waktu_penjemputan)->format('H:i') }}</p>
                                            <p><strong>Alamat:</strong> {{ $penjemputan->alamat_penjemputan }}</p>
                                            @if($penjemputan->catatan)
                                                <p><strong>Catatan:</strong> {{ $penjemputan->catatan }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Warning -->
                                    <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border-l-4 border-red-400">
                                        <div class="flex items-start space-x-3">
                                            <svg class="w-6 h-6 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-red-900 dark:text-red-100">
                                                    ⚠️ Tindakan ini tidak dapat dibatalkan!
                                                </p>
                                                <p class="text-sm text-red-800 dark:text-red-200 mt-1">
                                                    Setelah dibatalkan, penjemputan akan dihapus dari sistem dan Anda perlu membuat ulang jika diperlukan.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <x-slot name="footer">
                                    <div class="flex justify-end space-x-2">
                                        <x-filament::button 
                                            color="gray" 
                                            x-on:click="$dispatch('close-modal', { id: 'batal-penjemputan-{{ $penjemputan->id }}' })"
                                        >
                                            Batal
                                        </x-filament::button>
                                        <form action="{{ route('filament.admin.penjemputan.batal', $penjemputan->id) }}" method="POST" class="inline" onsubmit="setTimeout(() => window.location.reload(), 1000)">
                                            @csrf
                                            <x-filament::button 
                                                type="submit" 
                                                color="danger"
                                            >
                                                Ya, Batalkan Penjemputan
                                            </x-filament::button>
                                        </form>
                                    </div>
                                </x-slot>
                            </x-filament::modal>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada penjemputan aktif</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai buat penjemputan baru untuk mengumpulkan sampah.</p>
                    <div class="mt-6">
                        <x-filament::button href="{{ route('filament.admin.resources.penjemputans.create') }}" color="primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Buat Penjemputan Baru
                        </x-filament::button>
                    </div>
                </div>
            @endif
        </x-filament::section>

        <!-- Riwayat Penjemputan -->
        <x-filament::section>
            <x-slot name="heading">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Riwayat Penjemputan</h2>
            </x-slot>
            @if($riwayatPenjemputan->count() > 0)
                <div class="space-y-4">
                    @foreach($riwayatPenjemputan as $penjemputan)
                        <x-filament::card class="border border-gray-200 dark:border-gray-700 shadow-md {{ $penjemputan->status === 'completed' ? 'border-green-200 bg-green-50 dark:bg-green-900/20' : 'border-red-200 bg-red-50 dark:bg-red-900/20' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            {{ $penjemputan->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-400' }}">
                                            {{ ucfirst($penjemputan->status) }}
                                        </span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($penjemputan->tanggal_penjemputan)->format('d M Y') }} - 
                                            {{ \Carbon\Carbon::parse($penjemputan->waktu_penjemputan)->format('H:i') }}
                                        </span>
                                    </div>
                                    @if($penjemputan->pengepul)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pengepul: {{ $penjemputan->pengepul->name }}</p>
                                    @endif
                                    @if($penjemputan->status === 'completed' && $penjemputan->transaksi->count() > 0)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Total: 
                                            @php
                                                $totalHarga = $penjemputan->transaksi->sum(function($transaksi) {
                                                    return $transaksi->berat * ($transaksi->jenisSampah?->harga ?? 0);
                                                });
                                            @endphp
                                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <x-filament::button 
                                        size="sm" 
                                        color="gray"
                                        x-data=""
                                        x-on:click="$dispatch('open-modal', { id: 'detail-riwayat-{{ $penjemputan->id }}' })"
                                    >
                                        Detail
                                    </x-filament::button>
                                    @if($penjemputan->status === 'completed')
                                        <x-filament::button 
                                            size="sm" 
                                            color="success"
                                            x-data=""
                                            x-on:click="$dispatch('open-modal', { id: 'ulangi-order-{{ $penjemputan->id }}' })"
                                        >
                                            Ulangi Order
                                        </x-filament::button>
                                    @endif
                                </div>
                            </div>
                        </x-filament::card>

                        <!-- Modal Detail Riwayat -->
                        <x-filament::modal id="detail-riwayat-{{ $penjemputan->id }}" width="lg">
                            <x-slot name="header">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    Detail Riwayat Penjemputan
                                </h3>
                            </x-slot>

                            <div class="space-y-4">
                                <!-- Informasi Dasar -->
                                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Informasi Penjemputan</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Status:</strong> 
                                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                    {{ $penjemputan->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($penjemputan->status) }}
                                                </span>
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjemputan->tanggal_penjemputan)->format('d M Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($penjemputan->waktu_penjemputan)->format('H:i') }}
                                            </p>
                                            @if($penjemputan->pengepul)
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    <strong>Pengepul:</strong> {{ $penjemputan->pengepul->name }}
                                                </p>
                                            @endif
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

                                <!-- Detail Transaksi -->
                                @if($penjemputan->status === 'completed' && $penjemputan->transaksi->count() > 0)
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Detail Transaksi</h4>
                                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <p class="text-sm font-medium text-green-900 dark:text-green-100">Total Pendapatan:</p>
                                                    <p class="text-lg font-bold text-green-900 dark:text-green-100">
                                                        @php
                                                            $totalHarga = $penjemputan->transaksi->sum(function($transaksi) {
                                                                return $transaksi->berat * ($transaksi->jenisSampah?->harga ?? 0);
                                                            });
                                                        @endphp
                                                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm font-medium text-green-900 dark:text-green-100">Status:</p>
                                                    <p class="text-lg font-bold text-green-900 dark:text-green-100">Selesai</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Detail Sampah -->
                                @if($penjemputan->sampahDetails && $penjemputan->sampahDetails->count() > 0)
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Detail Sampah</h4>
                                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                            @foreach($penjemputan->sampahDetails as $detail)
                                                <div class="flex justify-between items-center p-3 bg-white dark:bg-gray-700 rounded-lg mb-2">
                                                    <div>
                                                        <p class="font-medium text-gray-900 dark:text-white">
                                                            {{ $detail->jenisSampah?->nama ?? 'Tidak Diketahui' }}
                                                        </p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="font-bold text-gray-900 dark:text-white">
                                                            {{ $detail->berat }} kg
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
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
                                        color="gray" 
                                        x-on:click="$dispatch('close-modal', { id: 'detail-riwayat-{{ $penjemputan->id }}' })"
                                    >
                                        Tutup
                                    </x-filament::button>
                                    @if($penjemputan->status === 'completed')
                                        <x-filament::button 
                                            color="success"
                                            x-on:click="$dispatch('open-modal', { id: 'ulangi-order-{{ $penjemputan->id }}' })"
                                        >
                                            Ulangi Order
                                        </x-filament::button>
                                    @endif
                                </div>
                            </x-slot>
                        </x-filament::modal>

                        <!-- Modal Ulangi Order -->
                        @if($penjemputan->status === 'completed')
                            <x-filament::modal id="ulangi-order-{{ $penjemputan->id }}" width="md">
                                <x-slot name="header">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        Ulangi Order Penjemputan
                                    </h3>
                                </x-slot>

                                <div class="space-y-4">
                                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                        <p class="text-sm text-blue-900 dark:text-blue-100">
                                            Anda akan membuat order penjemputan baru dengan detail yang sama seperti order sebelumnya:
                                        </p>
                                        <ul class="mt-2 text-sm text-blue-800 dark:text-blue-200 space-y-1">
                                            <li>• Tanggal: {{ \Carbon\Carbon::parse($penjemputan->tanggal_penjemputan)->format('d M Y') }}</li>
                                            <li>• Waktu: {{ \Carbon\Carbon::parse($penjemputan->waktu_penjemputan)->format('H:i') }}</li>
                                            <li>• Alamat: {{ $penjemputan->alamat_penjemputan }}</li>
                                            @if($penjemputan->catatan)
                                                <li>• Catatan: {{ $penjemputan->catatan }}</li>
                                            @endif
                                        </ul>
                                    </div>
                                    
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                            <strong>Note:</strong> Anda bisa mengubah detail sampah dan informasi lainnya setelah order dibuat.
                                        </p>
                                    </div>
                                </div>

                                <x-slot name="footer">
                                    <div class="flex justify-end space-x-2">
                                        <x-filament::button 
                                            color="gray" 
                                            x-on:click="$dispatch('close-modal', { id: 'ulangi-order-{{ $penjemputan->id }}' })"
                                        >
                                            Batal
                                        </x-filament::button>
                                        <x-filament::button 
                                            color="success"
                                            href="{{ route('filament.admin.resources.penjemputans.create') }}?duplicate={{ $penjemputan->id }}"
                                        >
                                            Buat Order Baru
                                        </x-filament::button>
                                    </div>
                                </x-slot>
                            </x-filament::modal>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada riwayat penjemputan</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Riwayat penjemputan akan muncul di sini setelah selesai.</p>
                </div>
            @endif
        </x-filament::section>

        <!-- Quick Actions -->
        <x-filament::section>
            <x-slot name="heading">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Aksi Cepat</h2>
            </x-slot>
            <div class="flex flex-wrap gap-4">
                <x-filament::button href="{{ route('filament.admin.resources.penjemputans.create') }}" color="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Buat Penjemputan Baru
                </x-filament::button>
                <x-filament::button href="{{ route('filament.admin.resources.penjemputans.index') }}" color="gray">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Lihat Semua Penjemputan
                </x-filament::button>
            </div>
        </x-filament::section>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Charts JavaScript -->
    <script>
        // Data untuk chart dari backend
        const chartData = {
            // Data untuk chart compact
            labels: @json($chartSampahBulan['labels'] ?? []),
            sampah: @json($chartSampahBulan['data'] ?? []),
            completedOrders: @json($chartCompletedOrdersBulan['data'] ?? []),
            pendingOrders: @json($chartPendingOrdersBulan['data'] ?? [])
        };

        // Total Sampah Chart
        const ctxTotalSampah = document.getElementById('totalSampahChart').getContext('2d');
        new Chart(ctxTotalSampah, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Total Sampah',
                    data: chartData.sampah,
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
    </script>
</x-filament-panels::page>
