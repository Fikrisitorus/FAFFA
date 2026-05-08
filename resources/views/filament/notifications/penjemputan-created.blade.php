<div class="space-y-3">
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800">Detail Jadwal Penjemputan</h4>
                <div class="mt-2 text-sm text-blue-700 space-y-1">
                    <div class="flex justify-between">
                        <span class="font-medium">Tanggal & Waktu:</span>
                        <span>{{ $penjemputan->jadwal_penjemputan->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Lokasi:</span>
                        <span>{{ $penjemputan->alamat_penjemputan }}</span>
                        @if($user->hasRole('kelompok_nasabah'))
                            <span class="text-xs text-blue-600">(Lokasi Kelompok)</span>
                        @endif
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Status:</span>
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                            Menunggu Penjemputan
                        </span>
                    </div>
                    @if($penjemputan->catatan)
                        <div class="flex justify-between">
                            <span class="font-medium">Catatan:</span>
                            <span>{{ $penjemputan->catatan }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-green-800">Apa Selanjutnya?</h4>
                <div class="mt-2 text-sm text-green-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Tim pengepul akan menghubungi Anda sebelum penjemputan</li>
                        <li>Pastikan sampah sudah dipisahkan sesuai jenisnya</li>
                        <li>Anda dapat melacak status penjemputan di halaman ini</li>
                        <li>Notifikasi akan dikirim saat penjemputan selesai</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if($user->kelompok)
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-purple-800">Informasi Kelompok</h4>
                    <div class="mt-2 text-sm text-purple-700">
                        <div class="flex justify-between">
                            <span class="font-medium">Kelompok:</span>
                            <span>{{ $user->kelompok->nama }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Lokasi:</span>
                            <span>{{ $user->kelompok->lokasi }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-gray-800">Estimasi Waktu</h4>
                <div class="mt-2 text-sm text-gray-700">
                    <p>Penjemputan biasanya dilakukan dalam 1-2 hari kerja setelah jadwal yang ditentukan.</p>
                </div>
            </div>
        </div>
    </div>
</div> 