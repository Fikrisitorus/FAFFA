<div class="space-y-6">
    <!-- Detail Penjemputan Section -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detail Penjemputan</h3>
        
        @if($penjemputan->gambar)
        <div class="mb-4">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Gambar Sampah</p>
            <img src="{{ Storage::url($penjemputan->gambar) }}" alt="Gambar Sampah" class="w-full max-w-md rounded-lg shadow-sm">
        </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-3">
                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nasabah</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $penjemputan->nasabah->nama ?? 'N/A' }}</p>
                    </div>
                </div>
                
                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Jadwal Penjemputan</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $penjemputan->jadwal_penjemputan->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                            {{ ucfirst($penjemputan->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Alamat</p>
                        <p class="text-sm text-gray-900 dark:text-gray-300">{{ $penjemputan->alamat_penjemputan }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($penjemputan->sampahDetails->count() > 0)
        <!-- Detail Sampah Section -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
            <!-- Header -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Sampah Diurutkan</h4>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full">
                            {{ $penjemputan->sampahDetails->count() }} Jenis
                        </span>
                        <span class="px-2.5 py-0.5 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-medium rounded-full">
                            @php
                                $totalBerat = $penjemputan->sampahDetails->sum(function($detail) {
                                    return $detail->berat;
                                });
                            @endphp
                            {{ number_format($totalBerat, 2) }} kg
                        </span>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="w-2/5 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Jenis Sampah
                            </th>
                            <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Berat (kg)
                            </th>
                            @if(!auth()->user()?->hasRole('kelompok_nasabah'))
                            <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Harga/kg
                            </th>
                            <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Harga
                            </th>
                            @endif
                            <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Catatan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($penjemputan->sampahDetails as $detail)
                            <tr>
                                <td class="w-2/5 px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $detail->jenisSampah->nama }}
                                </td>
                                <td class="w-1/6 px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ number_format($detail->berat, 2) }}
                                </td>
                                @if(!auth()->user()?->hasRole('kelompok_nasabah'))
                                <td class="w-1/6 px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $detail->formatted_harga_per_kg }}
                                </td>
                                <td class="w-1/6 px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        {{ $detail->formatted_total_harga }}
                                    </span>
                                </td>
                                @endif
                                <td class="w-1/6 px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $detail->catatan ?: '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <td class="w-2/5 px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">Total</td>
                            <td class="w-1/6 px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                @php
                                $totalBerat = $penjemputan->sampahDetails->sum(function($detail) {
                                    return $detail->berat;
                                });
                            @endphp
                            {{ number_format($totalBerat, 2) }} kg
                            </td>
                            @if(!auth()->user()?->hasRole('kelompok_nasabah'))
                            <td class="w-1/6 px-6 py-4 text-sm text-gray-500 dark:text-gray-400">-</td>
                            <td class="w-1/6 px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                    @php
                                        $totalHarga = $penjemputan->sampahDetails->sum(function($detail) {
                                            return $detail->berat * ($detail->jenisSampah?->harga ?? 0);
                                        });
                                    @endphp
                                    Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                </span>
                            </td>
                            @endif
                            <td class="w-1/6 px-6 py-4 text-sm text-gray-500 dark:text-gray-400">-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Belum Ada Detail Sampah</h3>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                        Belum ada detail sampah yang diurutkan untuk penjemputan ini.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div> 