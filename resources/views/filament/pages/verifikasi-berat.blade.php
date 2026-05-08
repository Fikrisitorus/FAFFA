    <x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Verifikasi Berat Sampah</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Timbang dan verifikasi sampah yang sudah diambil</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Penjemputan yang Perlu Diverifikasi -->
        @if($penjemputanPerluVerifikasi->count() > 0)
            <div class="space-y-4">
                @foreach($penjemputanPerluVerifikasi as $penjemputan)
                    <x-filament::card class="border-orange-200 bg-orange-50 dark:bg-orange-900/20">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-400">
                                        Perlu Verifikasi
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

                                <!-- Detail Sampah yang Sudah Diinput Kelompok -->
                                @if($penjemputan->sampahDetails && $penjemputan->sampahDetails->count() > 0)
                                    <div class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Data Sampah dari Kelompok:</h4>
                                        <div class="bg-white dark:bg-gray-700 rounded-lg p-3 space-y-2">
                                            @php
                                                $totalBeratKelompok = 0;
                                                $estimasiHargaKelompok = 0;
                                            @endphp
                                            
                                            @foreach($penjemputan->sampahDetails as $detail)
                                                @php
                                                    $berat = $detail->berat ?? 0;
                                                    $hargaPerKg = (float)($detail->jenisSampah?->harga ?? 0);
                                                    $totalBeratKelompok += $berat;
                                                    $estimasiHargaKelompok += $berat * $hargaPerKg;
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
                                            
                                            <!-- Total Kelompok -->
                                            <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border-l-4 border-blue-400">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <p class="text-xs font-medium text-blue-900 dark:text-blue-100">Total Berat Kelompok:</p>
                                                        <p class="text-sm font-bold text-blue-900 dark:text-blue-100">{{ number_format($totalBeratKelompok, 1) }} kg</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-xs font-medium text-blue-900 dark:text-blue-100">Estimasi Harga:</p>
                                                        <p class="text-sm font-bold text-blue-900 dark:text-blue-100">
                                                            @if($estimasiHargaKelompok > 0)
                                                                Rp {{ number_format($estimasiHargaKelompok, 0, ',', '.') }}
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
                                    color="primary"
                                    tag="a"
                                    href="{{ route('pengepul.verifikasi-berat', $penjemputan->id) }}"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                                    </svg>
                                    Verifikasi Berat
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada penjemputan yang perlu diverifikasi</h3>
                <p class="text-gray-500 dark:text-gray-400">Semua penjemputan sudah diverifikasi atau belum ada penjemputan dengan status "Sedang Diproses".</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
