<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                Verifikasi Pembayaran Nasabah
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                Validasi seluruh transaksi pembayaran nasabah sebelum dana dicatat sebagai pengeluaran resmi kepada kelompok nasabah.
            </p>
        </div>

        <!-- Table Transaksi Grouped -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Daftar Pembayaran Nasabah ({{ count($this->transaksiList) }} penjemputan)
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-12">
                                No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Penjemputan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total Pembayaran
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Bukti
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($this->transaksiList as $index => $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" onclick="toggleDetails('details-{{ $row['penjemputan_id'] }}')">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        #{{ $row['penjemputan_id'] }}
                                        <span class="ml-2 text-xs text-gray-500">({{ count($row['items']) }} item)</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $row['tanggal'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($row['total_pembayaran'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($row['any_bukti'])
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            Belum Ada
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($row['status'] == 99)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Ditolak
                                        </span>
                                    @elseif($row['verified_admin'])
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            ✓ Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($row['status'] == 99)
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Ditolak
                                            </span>
                                            @if(!empty($row['alasan_penolakan']))
                                                <span class="text-xs text-red-600 dark:text-red-400">
                                                    Alasan: {{ $row['alasan_penolakan'] }}
                                                </span>
                                            @endif
                                        </div>
                                    @elseif($row['verified_admin'])
                                        <span class="inline-flex items-center text-green-600 dark:text-green-400 font-medium">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Selesai
                                        </span>
                                    @else
                                        <div onclick="event.stopPropagation()">
                                            <button 
                                                type="button"
                                                wire:click="openVerifyModal({{ $row['penjemputan_id'] }})"
                                                wire:loading.attr="disabled"
                                                style="background-color: rgb(22, 163, 74) !important; opacity: 1 !important; border: none !important;"
                                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-700 transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2 dark:focus:ring-offset-gray-800 cursor-pointer disabled:opacity-50" 
                                                title="Verifikasi Pembayaran">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Verifikasi
                                            </button>
                                        </div>
                                        @if(isset($row['bukti_pembayaran']) && $row['bukti_pembayaran'])
                                            <a href="{{ asset('storage/' . $row['bukti_pembayaran']) }}" target="_blank" 
                                               class="ml-2 mt-2 inline-flex items-center px-2.5 py-1 text-xs font-medium border border-blue-500 text-blue-600 rounded-md hover:bg-blue-50 hover:border-blue-600 dark:border-blue-400 dark:text-blue-300 dark:hover:bg-blue-900/30">
                                                Lihat Bukti
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            <!-- Detail Row -->
                            <tr id="details-{{ $row['penjemputan_id'] }}" class="hidden bg-gray-50 dark:bg-gray-900">
                                <td colspan="7" class="px-6 py-4">
                                    <div class="space-y-3">
                                        @if($row['status'] == 99 && !empty($row['alasan_penolakan']))
                                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3 mb-3">
                                                <div class="flex items-start gap-2">
                                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-red-800 dark:text-red-300">Pembayaran Ditolak</p>
                                                        <p class="mt-1 text-sm text-red-700 dark:text-red-400">
                                                            <strong>Alasan Penolakan:</strong> {{ $row['alasan_penolakan'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Detail Pembayaran:</h4>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full text-sm">
                                                <thead>
                                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                                        <th class="px-3 py-2 text-left text-xs text-gray-500 dark:text-gray-400">Jenis Sampah</th>
                                                        <th class="px-3 py-2 text-left text-xs text-gray-500 dark:text-gray-400">Berat (Kg)</th>
                                                        <th class="px-3 py-2 text-left text-xs text-gray-500 dark:text-gray-400">Harga/Kg</th>
                                                        <th class="px-3 py-2 text-left text-xs text-gray-500 dark:text-gray-400">Total</th>
                                                        <th class="px-3 py-2 text-left text-xs text-gray-500 dark:text-gray-400">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($row['items'] as $item)
                                                        <tr class="border-b border-gray-100 dark:border-gray-800">
                                                            <td class="px-3 py-2 text-gray-900 dark:text-white">{{ $item->jenisSampah->nama ?? '-' }}</td>
                                                            <td class="px-3 py-2 text-gray-900 dark:text-white">{{ number_format($item->berat, 2) }}</td>
                                                            <td class="px-3 py-2 text-gray-900 dark:text-white">Rp {{ number_format($item->jenisSampah->harga ?? 0, 0, ',', '.') }}</td>
                                                            <td class="px-3 py-2 font-medium text-gray-900 dark:text-white">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                                            <td class="px-3 py-2">
                                                                @if($item->verified_by_admin)
                                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                                        ✓
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                                        Pending
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                                            Tidak ada pembayaran nasabah yang perlu diverifikasi
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-filament-actions::modals />

    <script>
        function toggleDetails(id) {
            const element = document.getElementById(id);
            if (element) {
                element.classList.toggle('hidden');
            }
        }
    </script>
</x-filament-panels::page>
