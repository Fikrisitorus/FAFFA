<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran - Pengepul</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        console.log('[blade] snap.js requested');
        console.log('Client Key:', '{{ config('midtrans.client_key') }}');
        
        // Wait for snap to load
        window.addEventListener('load', function() {
            console.log('Page loaded, checking Snap availability...');
            console.log('typeof snap:', typeof snap);
            console.log('window.snap:', window.snap);
            
            if (typeof snap !== 'undefined') {
                console.log('✅ Snap is available');
            } else {
                console.log('❌ Snap is NOT available - retrying...');
                // Retry loading snap
                setTimeout(function() {
                    console.log('Retrying snap load...');
                    console.log('typeof snap after retry:', typeof snap);
                }, 2000);
            }
        });
    </script>
    <!-- Disable payment.js karena kita handle langsung di blade -->
    <!-- <script src="{{ asset('js/payment.js') }}"></script> -->
    <script>console.log('[blade] payment.js disabled - using direct implementation');</script>
    <script>
        // Debug Midtrans configuration
        console.log('Midtrans Client Key:', '{{ config('midtrans.client_key') }}');
        console.log('Midtrans Snap Token:', '{{ $snapToken ?? 'Not available' }}');
        
        // Debug Snap availability
        window.addEventListener('load', function() {
            console.log('Page loaded, checking Snap availability...');
            console.log('typeof snap:', typeof snap);
            console.log('window.snap:', window.snap);
            
            if (typeof snap !== 'undefined') {
                console.log('✅ Snap is available');
            } else {
                console.log('❌ Snap is NOT available');
            }
        });
    </script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('filament.admin.pages.dashboard-pengepul') }}" 
                           class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <h1 class="text-2xl font-bold text-gray-900">Pembayaran</h1>
                    </div>
                    <div class="text-sm text-gray-500">
                        Pengepul: {{ Auth::user()->name }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto p-4 sm:p-6">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Info Order -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Informasi Order</h2>
                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                        Berat Terverifikasi
                    </span>
                </div>
                
                <div class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Kelompok</p>
                        <p class="font-medium text-gray-900 text-base">{{ $penjemputan->kelompok->nama ?? 'Tidak Diketahui' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tanggal Penjemputan</p>
                        <p class="font-medium text-gray-900 text-base">
                            {{ \Carbon\Carbon::parse($penjemputan->tanggal_penjemputan)->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Berat Final</p>
                        <p class="font-medium text-gray-900 text-base">{{ $penjemputan->berat_final }} kg</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Perbedaan Berat</p>
                        <p class="font-medium text-gray-900 text-base">
                            @if($penjemputan->berat_difference)
                                @if($penjemputan->berat_difference > 0)
                                    <span class="text-green-600">+{{ $penjemputan->berat_difference }} kg</span>
                                @else
                                    <span class="text-red-600">{{ $penjemputan->berat_difference }} kg</span>
                                @endif
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detail Sampah yang Sudah Diverifikasi -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Detail Sampah Terverifikasi</h2>
                
                <div class="space-y-3">
                    @foreach($penjemputan->sampahDetails as $detail)
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 bg-gray-50 rounded-lg border space-y-2 sm:space-y-0">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900 text-base">{{ $detail->jenisSampah->nama }}</span>
                                <span class="text-sm text-gray-600 ml-2">{{ $detail->berat }} kg</span>
                            </div>
                            <div class="text-left sm:text-right">
                                @php
                                    $hargaPerKg = (float)($detail->jenisSampah->harga ?? 0);
                                    $subtotal = (float)$detail->berat * $hargaPerKg;
                                @endphp
                                <span class="text-sm text-gray-600">Rp {{ number_format($hargaPerKg, 0, ',', '.') }}/kg</span>
                                <div class="font-bold text-gray-900 text-base">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Total Summary -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-medium text-gray-900">Total Pembayaran:</span>
                        <span class="text-3xl font-bold text-green-600">
                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Konfirmasi Pembayaran -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Konfirmasi Pembayaran</h2>
                
                <div class="space-y-6">
                    <!-- Total Pembayaran -->
                    <div class="bg-green-50 p-6 rounded-lg border-l-4 border-green-400">
                        <h3 class="text-lg font-medium text-green-900 mb-4">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            Pembayaran ke Nasabah
                        </h3>
                        
                        <div class="text-center">
                            <p class="text-3xl font-bold text-green-600" id="nasabah-payment-amount">
                                Rp {{ number_format($totalHarga, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-green-700 mt-2">
                                Jumlah yang akan ditambahkan ke saldo nasabah
                            </p>
                        </div>
                        
                        <!-- Info Total Harga -->
                        <div class="mt-4 p-3 bg-gray-100 rounded-md">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Harga Sampah:</span>
                                <span class="font-medium">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm mt-1">
                                <span class="text-gray-600">Donasi:</span>
                                <span class="font-medium text-red-600" id="donation-info">Rp 0</span>
                            </div>
                        </div>
                        
                        <!-- Info Midtrans -->
                        <div class="mt-3 p-3 bg-blue-50 rounded-md border border-blue-200">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                                <div class="text-sm text-blue-700">
                                    <p class="font-medium">Metode Pembayaran Tersedia:</p>
                                    <ul class="list-disc list-inside mt-1 space-y-1">
                                        <li><strong>QRIS</strong> - Scan QR dengan e-wallet (GoPay, ShopeePay, dll)</li>
                                        <li><strong>Virtual Account</strong> - Transfer ke rekening bank (BCA, BNI, BRI, Mandiri, Permata)</li>
                                        <li><strong>E-wallet</strong> - GoPay, ShopeePay</li>
                                    </ul>
                                    <p class="mt-2 font-medium">Catatan:</p>
                                    <p>Midtrans akan memproses total harga sampah (Rp {{ number_format($totalHarga, 0, ',', '.') }}), kemudian sistem akan membaginya sesuai opsi yang dipilih.</p>
                                    <p class="mt-1 text-xs text-blue-600">
                                        <i class="fas fa-clock mr-1"></i>
                                        Virtual Account berlaku 24 jam setelah transaksi dibuat
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Info Donate All -->
                        <div class="mt-3 p-3 bg-green-50 rounded-md border border-green-200" id="donate-all-info" style="display: none;">
                            <div class="flex items-start">
                                <i class="fas fa-heart text-green-500 mt-1 mr-2"></i>
                                <div class="text-sm text-green-700">
                                    <p class="font-medium">Sumbangkan Semua - Tidak Perlu Pembayaran!</p>
                                    <p>Karena Anda memilih untuk menyumbangkan semua sampah, tidak perlu pembayaran melalui Midtrans. Sampah akan langsung masuk ke sistem sebagai sedekah.</p>
                                    <p class="mt-1 text-xs text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Proses akan otomatis selesai tanpa popup pembayaran
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal Konfirmasi Donate All -->
                        <div id="confirm-donate-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: none;">
                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                <div class="mt-3 text-center">
                                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                        <i class="fas fa-heart text-green-600 text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mt-4">Konfirmasi Sumbangan</h3>
                                    <div class="mt-2 px-7 py-3">
                                        <p class="text-sm text-gray-500">
                                            Apakah Anda yakin ingin menyumbangkan semua sampah senilai 
                                            <span class="font-semibold text-green-600" id="confirm-total-amount">Rp 0</span> 
                                            ke sistem?
                                        </p>
                                        <p class="text-xs text-red-500 mt-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Tindakan ini tidak dapat dibatalkan
                                        </p>
                                    </div>
                                    <div class="items-center px-4 py-3">
                                        <button id="confirm-donate-btn" 
                                                class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md w-20 mr-2 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                                            Ya
                                        </button>
                                        <button id="cancel-donate-btn" 
                                                class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-20 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detail Sampah -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-list mr-2"></i>
                            Detail Sampah
                        </h3>
                        
                        <div class="space-y-3">
                            @foreach($penjemputan->sampahDetails as $detail)
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $detail->jenisSampah->nama }}</p>
                                        <p class="text-sm text-gray-600">{{ $detail->berat }} kg</p>
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $hargaPerKg = (float)($detail->jenisSampah->harga ?? 0);
                                            $subtotal = (float)$detail->berat * $hargaPerKg;
                                        @endphp
                                        <p class="font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-600">@ Rp {{ number_format($hargaPerKg, 0, ',', '.') }}/kg</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Opsi Pembayaran -->
                <div class="bg-blue-50 p-6 rounded-lg border-l-4 border-blue-400">
                    <h3 class="text-lg font-medium text-blue-900 mb-4">
                        <i class="fas fa-hand-holding-heart mr-2"></i>
                        Opsi Pembayaran
                    </h3>
                    
                    <form id="payment-options-form">
                        <div class="space-y-4">
                            <!-- Opsi 1: Diambil Semuanya -->
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="radio" name="payment_option" value="take_all" class="mt-1 text-blue-600 focus:ring-blue-500" checked>
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">Diambil Semuanya</div>
                                    <div class="text-sm text-gray-600">Semua pembayaran akan masuk ke saldo nasabah</div>
                                    <div class="text-sm text-green-600 font-medium mt-1">
                                        Nasabah: <span id="nasabah-preview-take-all">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span> | Donasi: <span id="donation-preview-take-all">Rp 0</span>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Opsi 2: Disumbang Semuanya -->
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="radio" name="payment_option" value="donate_all" class="mt-1 text-blue-600 focus:ring-blue-500">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">Disumbang Semuanya</div>
                                    <div class="text-sm text-gray-600">Semua pembayaran akan disumbang ke sistem</div>
                                    <div class="text-sm text-red-600 font-medium mt-1">
                                        Nasabah: <span id="nasabah-preview-donate-all">Rp 0</span> | Donasi: <span id="donation-preview-donate-all">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Opsi 3: Disumbang Sebagian -->
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="radio" name="payment_option" value="donate_partial" class="mt-1 text-blue-600 focus:ring-blue-500">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">Disumbang Sebagian</div>
                                    <div class="text-sm text-gray-600">Tentukan berapa yang akan disumbang</div>
                                    
                                    <div class="mt-3 space-y-3" id="partial-options" style="display: none;">
                                        <!-- Input Manual -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Donasi (Rp)</label>
                                            <input type="number" 
                                                   id="donation_amount" 
                                                   name="donation_amount" 
                                                   min="0" 
                                                   max="{{ $totalHarga }}" 
                                                   step="1000"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="Masukkan jumlah donasi">
                                        </div>
                                        
                                        <!-- Preset Persentase -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Atau pilih persentase:</label>
                                            <div class="grid grid-cols-2 gap-2">
                                                <button type="button" class="preset-btn px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50" data-percent="25">25%</button>
                                                <button type="button" class="preset-btn px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50" data-percent="50">50%</button>
                                                <button type="button" class="preset-btn px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50" data-percent="75">75%</button>
                                                <button type="button" class="preset-btn px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50" data-percent="90">90%</button>
                                            </div>
                                        </div>
                                        
                                        <!-- Preview Pembagian -->
                                        <div class="bg-gray-100 p-3 rounded-md">
                                            <div class="text-sm">
                                                <div class="flex justify-between">
                                                    <span>Nasabah:</span>
                                                    <span id="nasabah-preview" class="font-medium text-green-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Donasi:</span>
                                                    <span id="donation-preview" class="font-medium text-red-600">Rp 0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </form>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('filament.admin.pages.dashboard-pengepul') }}" 
                       class="w-full sm:w-auto px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors text-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <form method="POST" action="{{ route('pengepul.proses-pembayaran', $penjemputan->id) }}" id="payment-form">
                        @csrf
                        <input type="hidden" name="payment_option" id="hidden_payment_option" value="take_all">
                        <input type="hidden" name="donation_amount" id="hidden_donation_amount" value="0">
                        
                        <button type="button" 
                                id="pay-button"
                                data-token=""
                                data-success="{{ route('pengepul.payment-success', $penjemputan->id) }}"
                                data-pending="{{ route('pengepul.payment-pending', $penjemputan->id) }}"
                                data-error="{{ route('pengepul.payment-error', $penjemputan->id) }}"
                                onclick="handlePaymentAction()"
                                class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors text-center text-base">
                            <i class="fas fa-credit-card mr-2"></i>
                            <span id="button-text">Bayar Sekarang</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Opsi Pembayaran -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalHarga = {{ $totalHarga }};
            const paymentOptions = document.querySelectorAll('input[name="payment_option"]');
            const partialOptions = document.getElementById('partial-options');
            const donationAmountInput = document.getElementById('donation_amount');
            const presetButtons = document.querySelectorAll('.preset-btn');
            const nasabahPreview = document.getElementById('nasabah-preview');
            const donationPreview = document.getElementById('donation-preview');
            
            // Fungsi untuk update preview
            function updatePreview() {
                const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
                let nasabahAmount = 0;
                let donationAmount = 0;
                
                if (selectedOption === 'take_all') {
                    nasabahAmount = totalHarga;
                    donationAmount = 0;
                } else if (selectedOption === 'donate_all') {
                    nasabahAmount = 0;
                    donationAmount = totalHarga;
                } else if (selectedOption === 'donate_partial') {
                    donationAmount = parseFloat(donationAmountInput.value) || 0;
                    nasabahAmount = totalHarga - donationAmount;
                }
                
                // Update preview di opsi pembayaran
                nasabahPreview.textContent = 'Rp ' + nasabahAmount.toLocaleString('id-ID');
                donationPreview.textContent = 'Rp ' + donationAmount.toLocaleString('id-ID');
                
                // Update preview di setiap opsi
                document.getElementById('nasabah-preview-take-all').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
                document.getElementById('donation-preview-take-all').textContent = 'Rp 0';
                
                document.getElementById('nasabah-preview-donate-all').textContent = 'Rp 0';
                document.getElementById('donation-preview-donate-all').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
                
                // Update tampilan pembayaran ke nasabah
                document.getElementById('nasabah-payment-amount').textContent = 'Rp ' + nasabahAmount.toLocaleString('id-ID');
                document.getElementById('donation-info').textContent = 'Rp ' + donationAmount.toLocaleString('id-ID');
                
                // Update info Midtrans
                const midtransInfo = document.querySelector('.bg-blue-50 p:last-child');
                if (midtransInfo) {
                    midtransInfo.textContent = `Midtrans akan memproses total harga sampah (Rp ${totalHarga.toLocaleString('id-ID')}), kemudian sistem akan membaginya sesuai opsi yang dipilih.`;
                }
                
                // Show/hide donate-all info
                const donateAllInfo = document.getElementById('donate-all-info');
                const midtransInfoBox = document.querySelector('.bg-blue-50');
                const buttonText = document.getElementById('button-text');
                
                if (selectedOption === 'donate_all') {
                    donateAllInfo.style.display = 'block';
                    midtransInfoBox.style.display = 'none';
                    buttonText.textContent = 'Sumbangkan Sekarang';
                } else {
                    donateAllInfo.style.display = 'none';
                    midtransInfoBox.style.display = 'block';
                    buttonText.textContent = 'Bayar Sekarang';
                }
                
                // Update warning box
                const warningInfo = document.querySelector('.bg-yellow-50 p:last-child');
                if (warningInfo) {
                    warningInfo.textContent = `Midtrans popup akan menampilkan total harga sampah (Rp ${totalHarga.toLocaleString('id-ID')}). Ini normal karena sistem akan membaginya sesuai opsi yang dipilih setelah pembayaran berhasil.`;
                }
            }
            
            // Event listener untuk radio buttons
            paymentOptions.forEach(option => {
                option.addEventListener('change', function() {
                    if (this.value === 'donate_partial') {
                        partialOptions.style.display = 'block';
                        donationAmountInput.focus();
                    } else {
                        partialOptions.style.display = 'none';
                    }
                    updatePreview();
                });
            });
            
            // Event listener untuk input donasi
            donationAmountInput.addEventListener('input', function() {
                const value = parseFloat(this.value) || 0;
                if (value > totalHarga) {
                    this.value = totalHarga;
                }
                updatePreview();
            });
            
            // Event listener untuk preset buttons
            presetButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const percent = parseInt(this.dataset.percent);
                    const donationAmount = Math.floor((totalHarga * percent) / 100);
                    donationAmountInput.value = donationAmount;
                    updatePreview();
                });
            });
            
            // Update preview saat halaman load
            updatePreview();
            
            // Debug: Log untuk memastikan JavaScript berjalan
            console.log('Payment options JavaScript loaded successfully');
            console.log('Total harga:', totalHarga);
            console.log('Default option:', document.querySelector('input[name="payment_option"]:checked').value);
            
            // Test: Pastikan updatePreview() berjalan
            setTimeout(() => {
                const nasabahAmount = document.getElementById('nasabah-payment-amount').textContent;
                console.log('Nasabah payment amount after load:', nasabahAmount);
            }, 100);
            
            // Flag to prevent multiple popup calls
            let isPopupOpen = false;
            
            // Fungsi untuk handle payment action dengan konfirmasi
            window.handlePaymentAction = function() {
                const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
                
                if (selectedOption === 'donate_all') {
                    // Tampilkan modal konfirmasi untuk donate_all
                    const totalHarga = {{ $totalHarga }};
                    document.getElementById('confirm-total-amount').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
                    document.getElementById('confirm-donate-modal').style.display = 'block';
                } else {
                    // Langsung proses untuk take_all dan donate_partial
                    getSnapTokenAndPay();
                }
            };
            
            // Event listeners untuk modal konfirmasi
            document.getElementById('confirm-donate-btn').addEventListener('click', function() {
                document.getElementById('confirm-donate-modal').style.display = 'none';
                // Untuk donate_all, langsung submit form tanpa Midtrans
                if (document.querySelector('input[name="payment_option"]:checked').value === 'donate_all') {
                    submitDonateAllForm();
                } else {
                    getSnapTokenAndPay();
                }
            });
            
            document.getElementById('cancel-donate-btn').addEventListener('click', function() {
                document.getElementById('confirm-donate-modal').style.display = 'none';
            });
            
            // Close modal when clicking outside
            document.getElementById('confirm-donate-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });
            
            // Fungsi untuk submit form donate_all tanpa Midtrans
            window.submitDonateAllForm = function() {
                const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
                const donationAmount = parseFloat(donationAmountInput.value) || 0;
                
                // Show loading
                const payButton = document.getElementById('pay-button');
                const originalText = payButton.innerHTML;
                payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyumbangkan...';
                payButton.disabled = true;
                
                // Update hidden fields
                document.getElementById('hidden_payment_option').value = selectedOption;
                document.getElementById('hidden_donation_amount').value = donationAmount;
                
                console.log('Submitting donate_all form directly without Midtrans...');
                
                // Submit form langsung
                document.getElementById('payment-form').submit();
            };
            
            // Fungsi untuk mendapatkan snap token dan membuka Midtrans popup
            window.getSnapTokenAndPay = function() {
                const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
                const donationAmount = parseFloat(donationAmountInput.value) || 0;
                
                // Show loading
                const payButton = document.getElementById('pay-button');
                const originalText = payButton.innerHTML;
                
                // Update button text based on payment option
                if (selectedOption === 'donate_all') {
                    payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyumbangkan...';
                } else {
                    payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                }
                payButton.disabled = true;
                
                // Kirim request ke server untuk mendapatkan snap token
                console.log('Sending request to get snap token...');
                fetch('{{ route("pengepul.get-snap-token", $penjemputan->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        payment_option: selectedOption,
                        donation_amount: donationAmount
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        // Update button dengan snap token
                        const payButton = document.getElementById('pay-button');
                        payButton.setAttribute('data-token', data.snap_token);
                        
                        // Update hidden fields
                        document.getElementById('hidden_payment_option').value = selectedOption;
                        document.getElementById('hidden_donation_amount').value = donationAmount;
                        
                        console.log('Snap token set to button:', data.snap_token);
                        
                        // Buka Midtrans popup langsung
                        if (window.snap) {
                            console.log('Opening Midtrans popup with token:', data.snap_token);
                            
                            // Set flag to prevent multiple calls
                            isPopupOpen = true;
                            
                            window.snap.pay(data.snap_token, {
                                onSuccess: function (result) {
                                    console.log('Payment success:', result);
                                    isPopupOpen = false;
                                    // Submit form untuk menyimpan opsi pembayaran
                                    document.getElementById('payment-form').submit();
                                },
                                onPending: function (result) {
                                    console.log('Payment pending:', result);
                                    isPopupOpen = false;
                                    window.location.href = '{{ route("pengepul.payment-pending", $penjemputan->id) }}';
                                },
                                onError: function (result) {
                                    console.log('Payment error:', result);
                                    console.error('Payment failed details:', result);
                                    isPopupOpen = false;
                                    alert('Pembayaran gagal: ' + (result.status_message || 'Unknown error'));
                                    window.location.href = '{{ route("pengepul.payment-error", $penjemputan->id) }}';
                                },
                                onClose: function () {
                                    console.log('Payment popup closed by user');
                                    isPopupOpen = false;
                                    // Restore button
                                    payButton.disabled = false;
                                    payButton.classList.remove('opacity-70', 'cursor-not-allowed');
                                    payButton.innerHTML = originalText;
                                }
                            });
                        } else {
                            console.error('Midtrans Snap not available');
                            alert('Midtrans tidak tersedia. Silakan refresh halaman dan pastikan koneksi internet stabil.');
                        }
                    } else {
                        console.error('Payment creation failed:', data);
                        alert('Gagal membuat pembayaran: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Network error:', error);
                    alert('Terjadi kesalahan saat memproses pembayaran. Cek koneksi internet dan coba lagi.');
                })
                .finally(() => {
                    // Restore button
                    payButton.innerHTML = originalText;
                    payButton.disabled = false;
                });
            };
            
            // Sync data ke hidden fields untuk form submission
            function syncToHiddenFields() {
                const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
                const donationAmount = parseFloat(donationAmountInput.value) || 0;
                
                document.getElementById('hidden_payment_option').value = selectedOption;
                document.getElementById('hidden_donation_amount').value = donationAmount;
            }
            
            // Update hidden fields saat ada perubahan
            paymentOptions.forEach(option => {
                option.addEventListener('change', syncToHiddenFields);
            });
            
            donationAmountInput.addEventListener('input', syncToHiddenFields);
            
            // Sync saat halaman load
            syncToHiddenFields();
        });
    </script>

</body>
</html>
