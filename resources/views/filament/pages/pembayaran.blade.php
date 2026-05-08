<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pembayaran Sampah</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Proses pembayaran untuk sampah yang sudah diverifikasi</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Penjemputan yang Perlu Dibayar -->
        @if($penjemputanPembayaran->count() > 0)
            <div class="space-y-4">
                @foreach($penjemputanPembayaran as $penjemputan)
                    <x-filament::card class="border-green-200 bg-green-50 dark:bg-green-900/20">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-400">
                                        Siap Dibayar
                                    </span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($penjemputan->tanggal_penjemputan)->format('d M Y') }} - 
                                        {{ \Carbon\Carbon::parse($penjemputan->waktu_penjemputan)->format('H:i') }}
                                    </span>
                                </div>
                                
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <strong>Kelompok:</strong> {{ $penjemputan->kelompok?->nama ?? 'Tidak Diketahui' }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <strong>Alamat:</strong> {{ $penjemputan->alamat_penjemputan }}
                                    </p>
                                    @if($penjemputan->catatan)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            <strong>Catatan:</strong> {{ $penjemputan->catatan }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Detail Sampah yang Sudah Diverifikasi -->
                                @if($penjemputan->sampahDetails && $penjemputan->sampahDetails->count() > 0)
                                    <div class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Data Sampah Terverifikasi:</h4>
                                        <div class="bg-white dark:bg-gray-700 rounded-lg p-3 space-y-2">
                                            @php
                                                $totalBeratFinal = 0;
                                                $totalHargaFinal = 0;
                                            @endphp
                                            
                                            @foreach($penjemputan->sampahDetails as $detail)
                                                @php
                                                    $berat = $detail->berat ?? 0;
                                                    $hargaPerKg = (float)($detail->jenisSampah?->harga ?? 0);
                                                    $totalBeratFinal += $berat;
                                                    $totalHargaFinal += $berat * $hargaPerKg;
                                                @endphp
                                                
                                                <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-600 rounded">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $detail->jenisSampah?->nama ?? 'Tidak Diketahui' }}
                                                        </p>
                                                        @if($hargaPerKg > 0)
                                                            <p class="text-xs text-gray-500">
                                                                Rp {{ number_format($hargaPerKg, 0, ',', '.') }}/kg
                                                            </p>
                                                        @endif
                                                        @if($detail->catatan)
                                                            <p class="text-xs text-gray-500 italic">
                                                                Catatan: {{ $detail->catatan }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                                                            {{ $detail->berat }} kg
                                                        </p>
                                                        @if($hargaPerKg > 0)
                                                            <p class="text-xs text-green-600 font-medium">
                                                                Rp {{ number_format($berat * $hargaPerKg, 0, ',', '.') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                            <!-- Total Final -->
                                            <div class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border-l-4 border-green-400">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <p class="text-xs font-medium text-green-900 dark:text-green-100">Total Berat Final:</p>
                                                        <p class="text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($totalBeratFinal, 1) }} kg</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-xs font-medium text-green-900 dark:text-green-100">Total Pembayaran:</p>
                                                        <p class="text-lg font-bold text-green-900 dark:text-green-100">
                                                            @if($totalHargaFinal > 0)
                                                                Rp {{ number_format($totalHargaFinal, 0, ',', '.') }}
                                                            @else
                                                                Belum dihitung
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="ml-4">
                                <x-filament::button 
                                    size="sm" 
                                    color="success"
                                    tag="a"
                                    href="{{ route('pengepul.pembayaran', $penjemputan->id) }}"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Proses Pembayaran
                                </x-filament::button>
                            </div>
                        </div>
                    </x-filament::card>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada pembayaran yang perlu diproses</h3>
                <p class="text-gray-500 dark:text-gray-400">Semua penjemputan sudah dibayar atau belum ada penjemputan dengan status "Berat Terverifikasi".</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
