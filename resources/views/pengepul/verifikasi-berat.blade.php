<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Berat Sampah - Pengepul</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                        <h1 class="text-2xl font-bold text-gray-900">Verifikasi Berat Sampah</h1>
                    </div>
                    <div class="text-sm text-gray-500">
                        Pengepul: {{ Auth::user()->name }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto p-6">
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
                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                        Order #{{ $penjemputan->id }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Kelompok</p>
                        <p class="font-medium text-gray-900">{{ $penjemputan->kelompok->nama ?? 'Tidak Diketahui' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tanggal Penjemputan</p>
                        <p class="font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($penjemputan->tanggal_penjemputan)->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Waktu</p>
                        <p class="font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($penjemputan->waktu_penjemputan)->format('H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Alamat</p>
                        <p class="font-medium text-gray-900">{{ $penjemputan->alamat_penjemputan }}</p>
                    </div>
                </div>

                @if($penjemputan->estimasi_berat > 0)
                    <div class="mt-4 p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-yellow-800 font-medium">Estimasi Berat dari Kelompok</p>
                                <p class="text-yellow-700 text-lg font-bold">{{ $penjemputan->estimasi_berat }} kg</p>
                                <p class="text-yellow-600 text-sm mt-1">
                                    Kelompok sudah menimbang sendiri - mohon verifikasi dengan timbangan yang akurat
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($penjemputan->catatan)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Catatan Kelompok</p>
                        <p class="text-gray-900">{{ $penjemputan->catatan }}</p>
                    </div>
                @endif
            </div>

            <!-- Form Verifikasi Berat -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Verifikasi Berat Sampah</h2>
                
                <form action="{{ route('pengepul.proses-verifikasi-berat', $penjemputan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Verifikasi Berat -->
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-blue-900 mb-4">
                                <i class="fas fa-weight-hanging mr-2"></i>
                                Verifikasi Berat & Jenis Sampah
                            </h3>
                            
                            <div class="space-y-4">
                                <p class="text-sm text-blue-600 mb-4">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Kelompok bisa punya multiple jenis sampah. Input semua jenis yang ada dengan berat masing-masing.
                                </p>
                                
                                <!-- Info Data Kelompok -->
                                @if($sampahDetailsKelompok->count() > 0)
                                    <div class="mb-4 p-4 bg-green-50 rounded-lg border-l-4 border-green-400">
                                        <div class="flex items-start">
                                            <i class="fas fa-info-circle text-green-600 mt-1 mr-3"></i>
                                            <div>
                                                <p class="text-green-800 font-medium">Data dari Kelompok (Pre-filled)</p>
                                                <p class="text-green-700 text-sm mt-1">
                                                    Berikut adalah data yang sudah diinput kelompok. Anda bisa mengubah berat jika ada perbedaan saat verifikasi.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Container untuk multiple jenis sampah -->
                                <div id="jenis-sampah-container" class="space-y-4">
                                    @if($sampahDetailsKelompok->count() > 0)
                                        <!-- Pre-fill dengan data dari kelompok -->
                                        @foreach($sampahDetailsKelompok as $index => $detail)
                                            <div class="jenis-sampah-item space-y-4 p-4 bg-white rounded-lg border border-blue-200">
                                                <!-- Mobile-friendly layout -->
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <!-- Jenis Sampah -->
                                                    <div class="sm:col-span-2">
                                                        <label class="block text-sm font-medium text-blue-900 mb-2">
                                                            Jenis Sampah
                                                        </label>
                                                        <div class="w-full border-blue-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-700 text-base">
                                                            {{ $detail->jenisSampah?->nama ?? 'Tidak Diketahui' }}
                                                            @if($detail->jenisSampah?->harga)
                                                                <span class="text-sm text-gray-500 block">
                                                                    Rp {{ number_format((float)($detail->jenisSampah->harga), 0, ',', '.') }}/kg
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <input type="hidden" name="jenis_sampah_id[]" value="{{ $detail->jenis_sampah_id }}">
                                                    </div>
                                                    
                                                    <!-- Berat Kelompok -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-blue-900 mb-2">
                                                            Berat Kelompok
                                                        </label>
                                                        <div class="w-full border-blue-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-700 text-base">
                                                            {{ $detail->berat_nasabah ?? 0 }} kg
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Berat Verifikasi -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-blue-900 mb-2">
                                                            Berat Verifikasi (kg) *
                                                        </label>
                                                        <input type="number" step="0.1" name="berat_verifikasi[]" 
                                                               class="w-full border-blue-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 berat-verifikasi-input text-base" 
                                                               required min="0.1" placeholder="0.0" 
                                                               value="{{ $detail->berat_nasabah ?? 0 }}">
                                                    </div>
                                                </div>
                                                
                                                <!-- Catatan -->
                                                <div>
                                                    <label class="block text-sm font-medium text-blue-900 mb-2">
                                                        Catatan
                                                    </label>
                                                    <textarea name="catatan[]" rows="3" 
                                                              class="w-full border-blue-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base"
                                                              placeholder="Catatan khusus...">{{ $detail->catatan ?? '' }}</textarea>
                                                </div>
                                                
                                                <!-- Gambar -->
                                                <div>
                                                    <label class="block text-sm font-medium text-blue-900 mb-2">
                                                        Gambar
                                                    </label>
                                                    <input type="file" name="gambar[]" 
                                                           class="w-full border-blue-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                                           accept="image/*">
                                                </div>
                                                
                                                <!-- Hapus Button -->
                                                <div class="flex justify-end">
                                                    <button type="button" onclick="hapusJenisSampah(this)" 
                                                            class="px-4 py-2 text-red-600 border border-red-300 rounded-lg hover:bg-red-50 focus:ring-2 focus:ring-red-500 focus:border-red-500 hapus-btn flex items-center justify-center text-base"
                                                            {{ $sampahDetailsKelompok->count() == 1 ? 'style=display:none;' : '' }}>
                                                        <i class="fas fa-trash mr-2"></i>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Item pertama (default) jika tidak ada data kelompok -->
                                        <div class="jenis-sampah-item space-y-4 p-4 bg-white rounded-lg border border-blue-200">
                                            <!-- Mobile-friendly layout -->
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <!-- Jenis Sampah -->
                                                <div class="sm:col-span-2">
                                                    <label class="block text-sm font-medium text-blue-900 mb-2">
                                                        Jenis Sampah *
                                                    </label>
                                                    <select name="jenis_sampah_id[]" 
                                                            class="w-full border-blue-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 jenis-sampah-select text-base" 
                                                            required>
                                                        <option value="">Pilih jenis sampah</option>
                                                        @foreach($jenisSampah as $jenis)
                                                            <option value="{{ $jenis->id }}" 
                                                                    data-harga="{{ (float)($jenis->harga ?? 0) }}">
                                                                {{ $jenis->nama }} - Rp {{ number_format((float)($jenis->harga ?? 0), 0, ',', '.') }}/kg
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <!-- Berat Kelompok -->
                                                <div>
                                                    <label class="block text-sm font-medium text-blue-900 mb-2">
                                                        Berat Kelompok
                                                    </label>
                                                    <div class="w-full border-blue-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-500 text-base">
                                                        Tidak ada data
                                                    </div>
                                                </div>
                                                
                                                <!-- Berat Verifikasi -->
                                                <div>
                                                    <label class="block text-sm font-medium text-blue-900 mb-2">
                                                        Berat Verifikasi (kg) *
                                                    </label>
                                                    <input type="number" step="0.1" name="berat_verifikasi[]" 
                                                           class="w-full border-blue-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 berat-verifikasi-input text-base" 
                                                           required min="0.1" placeholder="0.0">
                                                </div>
                                            </div>
                                            
                                            <!-- Catatan -->
                                            <div>
                                                <label class="block text-sm font-medium text-blue-900 mb-2">
                                                    Catatan
                                                </label>
                                                <textarea name="catatan[]" rows="3" 
                                                          class="w-full border-blue-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base"
                                                          placeholder="Catatan khusus..."></textarea>
                                            </div>
                                            
                                            <!-- Gambar -->
                                            <div>
                                                <label class="block text-sm font-medium text-blue-900 mb-2">
                                                    Gambar
                                                </label>
                                                <input type="file" name="gambar[]" 
                                                       class="w-full border-blue-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                                       accept="image/*">
                                            </div>
                                            
                                            <!-- Hapus Button -->
                                            <div class="flex justify-end">
                                                <button type="button" onclick="hapusJenisSampah(this)" 
                                                        class="px-4 py-2 text-red-600 border border-red-300 rounded-lg hover:bg-red-50 focus:ring-2 focus:ring-red-500 focus:border-red-500 hapus-btn flex items-center justify-center text-base"
                                                        style="display: none;">
                                                    <i class="fas fa-trash mr-2"></i>
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Tombol tambah jenis sampah -->
                                <button type="button" onclick="tambahJenisSampah()" 
                                        class="w-full px-4 py-3 text-blue-600 border-2 border-dashed border-blue-300 rounded-lg hover:bg-blue-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    + Tambah Jenis Sampah
                                </button>
                                
                                <!-- Total berat real-time -->
                                <div class="mt-4 p-4 bg-blue-100 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="text-blue-900 font-medium">Total Berat:</span>
                                        <span class="text-blue-900 font-bold text-lg" id="total-berat">0.0 kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('filament.admin.pages.dashboard-pengepul') }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit" id="submitBtn"
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            <i class="fas fa-check mr-2"></i>
                            Verifikasi Berat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk multiple jenis sampah -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kalkulasi berat real-time untuk multiple jenis sampah
            function updateKalkulasi() {
                const items = document.querySelectorAll('.jenis-sampah-item');
                let totalBerat = 0;
                
                items.forEach((item, index) => {
                    const beratVerifikasiInput = item.querySelector('.berat-verifikasi-input');
                    
                    if (beratVerifikasiInput && beratVerifikasiInput.value) {
                        const berat = parseFloat(beratVerifikasiInput.value) || 0;
                        if (berat > 0) {
                            totalBerat += berat;
                        }
                    }
                });
                
                // Update display
                document.getElementById('total-berat').textContent = totalBerat.toFixed(1) + ' kg';
            }
            
            // Update options untuk mencegah duplikasi jenis sampah
            // Sistem ini akan menyembunyikan opsi yang sudah dipilih di item lain
            function updateSelectOptions() {
                const allSelects = document.querySelectorAll('.jenis-sampah-select');
                const allSelectedValues = [];
                
                // Kumpulkan semua nilai yang sudah dipilih (termasuk dari hidden input)
                allSelects.forEach(select => {
                    if (select.value) {
                        allSelectedValues.push(select.value);
                    }
                });
                
                // Juga kumpulkan dari hidden input (jenis sampah dari kelompok)
                const hiddenInputs = document.querySelectorAll('input[name="jenis_sampah_id[]"]');
                hiddenInputs.forEach(input => {
                    if (input.value) {
                        allSelectedValues.push(input.value);
                    }
                });
                
                // Update setiap select untuk menyembunyikan opsi yang sudah dipilih
                allSelects.forEach(select => {
                    const options = select.querySelectorAll('option');
                    options.forEach(option => {
                        if (option.value && option.value !== select.value) {
                            // Sembunyikan jika sudah dipilih di select lain atau dari kelompok
                            if (allSelectedValues.includes(option.value)) {
                                option.style.display = 'none';
                                option.disabled = true;
                            } else {
                                option.style.display = 'block';
                                option.disabled = false;
                            }
                        }
                    });
                });
            }
            
            // Hitung total berat saat halaman load (untuk data pre-filled)
            updateKalkulasi();
            updateSelectOptions();
            
            // Event listeners untuk semua input
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('berat-verifikasi-input')) {
                    updateKalkulasi();
                }
                if (e.target.classList.contains('jenis-sampah-select')) {
                    updateSelectOptions();
                }
            });
            
            // Validasi form sebelum submit
            document.querySelector('form').addEventListener('submit', function(e) {
                console.log('Form submit triggered');
                
                // Debug file upload
                const fileInputs = document.querySelectorAll('input[type="file"]');
                console.log('File inputs found:', fileInputs.length);
                console.log('All file inputs:', fileInputs);
                fileInputs.forEach((input, index) => {
                    console.log(`File input ${index}:`, input.files.length, 'files');
                    console.log(`File input ${index} name:`, input.name);
                    console.log(`File input ${index} value:`, input.value);
                    if (input.files.length > 0) {
                        console.log(`File ${index}:`, input.files[0].name, input.files[0].size, 'bytes');
                        console.log(`File ${index} type:`, input.files[0].type);
                    }
                });
                
                const items = document.querySelectorAll('.jenis-sampah-item');
                let isValid = true;
                let totalBerat = 0;
                let selectedJenis = [];
                
                items.forEach((item, index) => {
                    const jenisSelect = item.querySelector('.jenis-sampah-select');
                    const beratVerifikasiInput = item.querySelector('.berat-verifikasi-input');
                    
                    console.log(`Item ${index}:`, {
                        jenisSelect: jenisSelect,
                        beratVerifikasiInput: beratVerifikasiInput,
                        jenisValue: jenisSelect ? jenisSelect.value : 'N/A',
                        beratValue: beratVerifikasiInput ? beratVerifikasiInput.value : 'N/A'
                    });
                    
                    // Untuk item yang sudah ada (dari kelompok), jenis_sampah_id sudah fixed
                    if (jenisSelect) {
                        // Item baru dengan dropdown
                        if (jenisSelect.value && beratVerifikasiInput.value) {
                            const berat = parseFloat(beratVerifikasiInput.value) || 0;
                            if (berat <= 0) {
                                isValid = false;
                                alert(`Berat verifikasi untuk jenis sampah ${index + 1} harus lebih dari 0 kg!`);
                                beratVerifikasiInput.focus();
                                return;
                            }
                            
                            // Cek duplikasi jenis sampah
                            if (selectedJenis.includes(jenisSelect.value)) {
                                isValid = false;
                                alert(`Jenis sampah "${jenisSelect.options[jenisSelect.selectedIndex].text.split(' - ')[0]}" sudah dipilih sebelumnya!`);
                                jenisSelect.focus();
                                return;
                            }
                            
                            selectedJenis.push(jenisSelect.value);
                            totalBerat += berat;
                        }
                    } else {
                        // Item dari kelompok (jenis_sampah_id sudah fixed via hidden input)
                        if (beratVerifikasiInput.value) {
                            const berat = parseFloat(beratVerifikasiInput.value) || 0;
                            if (berat <= 0) {
                                isValid = false;
                                alert(`Berat verifikasi untuk jenis sampah ${index + 1} harus lebih dari 0 kg!`);
                                beratVerifikasiInput.focus();
                                return;
                            }
                            totalBerat += berat;
                        }
                    }
                });
                
                console.log('Total berat calculated:', totalBerat);
                console.log('Selected jenis:', selectedJenis);
                
                // Debug: Show all berat inputs
                const beratInputs = document.querySelectorAll('.berat-verifikasi-input');
                console.log('Berat inputs found:', beratInputs.length);
                beratInputs.forEach((input, index) => {
                    console.log(`Berat input ${index}:`, input.value, 'parsed:', parseFloat(input.value) || 0);
                });
                
                if (totalBerat <= 0) {
                    e.preventDefault();
                    alert('Total berat verifikasi harus lebih dari 0 kg! Silakan masukkan berat untuk minimal satu jenis sampah.');
                    return false;
                }
                
                console.log('Form validation passed, submitting...');
                console.log('Form action:', this.action);
                console.log('Form method:', this.method);
                console.log('Form enctype:', this.enctype);
                
                // Show loading state
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                }
                
                // Allow form to submit normally
                // Don't prevent default
            });
        });
        
        // Fungsi global untuk update kalkulasi
        function updateKalkulasi() {
            const items = document.querySelectorAll('.jenis-sampah-item');
            let totalBerat = 0;
            
            items.forEach((item, index) => {
                const beratVerifikasiInput = item.querySelector('.berat-verifikasi-input');
                
                if (beratVerifikasiInput && beratVerifikasiInput.value) {
                    const berat = parseFloat(beratVerifikasiInput.value) || 0;
                    if (berat > 0) {
                        totalBerat += berat;
                    }
                }
            });
            
            // Update display
            document.getElementById('total-berat').textContent = totalBerat.toFixed(1) + ' kg';
        }
        
        // Fungsi global untuk update select options
        function updateSelectOptions() {
            const allSelects = document.querySelectorAll('.jenis-sampah-select');
            const allSelectedValues = [];
            
            // Kumpulkan semua nilai yang sudah dipilih (termasuk dari hidden input)
            allSelects.forEach(select => {
                if (select.value) {
                    allSelectedValues.push(select.value);
                }
            });
            
            // Juga kumpulkan dari hidden input (jenis sampah dari kelompok)
            const hiddenInputs = document.querySelectorAll('input[name="jenis_sampah_id[]"]');
            hiddenInputs.forEach(input => {
                if (input.value) {
                    allSelectedValues.push(input.value);
                }
            });
            
            // Update setiap select untuk menyembunyikan opsi yang sudah dipilih
            allSelects.forEach(select => {
                const options = select.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value && option.value !== select.value) {
                        // Sembunyikan jika sudah dipilih di select lain atau dari kelompok
                        if (allSelectedValues.includes(option.value)) {
                            option.style.display = 'none';
                            option.disabled = true;
                        } else {
                            option.style.display = 'block';
                            option.disabled = false;
                        }
                    }
                });
            });
        }
        
        // Fungsi untuk menambah jenis sampah baru
        function tambahJenisSampah() {
            const container = document.getElementById('jenis-sampah-container');
            const newItem = document.createElement('div');
            newItem.className = 'jenis-sampah-item space-y-4 p-4 bg-white rounded-lg border border-blue-200';
            
            newItem.innerHTML = `
                <!-- Mobile-friendly layout -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Jenis Sampah -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-blue-900 mb-2">
                            Jenis Sampah *
                        </label>
                        <select name="jenis_sampah_id[]" 
                                class="w-full border-blue-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 jenis-sampah-select text-base" 
                                required>
                            <option value="">Pilih jenis sampah</option>
                            @foreach($jenisSampah as $jenis)
                                <option value="{{ $jenis->id }}" 
                                        data-harga="{{ (float)($jenis->harga ?? 0) }}">
                                    {{ $jenis->nama }} - Rp {{ number_format((float)($jenis->harga ?? 0), 0, ',', '.') }}/kg
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Berat Kelompok -->
                    <div>
                        <label class="block text-sm font-medium text-blue-900 mb-2">
                            Berat Kelompok
                        </label>
                        <div class="w-full border-blue-300 rounded-lg px-3 py-3 bg-gray-50 text-gray-500 text-base">
                            Tidak ada data
                        </div>
                    </div>
                    
                    <!-- Berat Verifikasi -->
                    <div>
                        <label class="block text-sm font-medium text-blue-900 mb-2">
                            Berat Verifikasi (kg) *
                        </label>
                        <input type="number" step="0.1" name="berat_verifikasi[]" 
                               class="w-full border-blue-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 berat-verifikasi-input text-base" 
                               required min="0.1" placeholder="0.0">
                    </div>
                </div>
                
                <!-- Catatan -->
                <div>
                    <label class="block text-sm font-medium text-blue-900 mb-2">
                        Catatan
                    </label>
                    <textarea name="catatan[]" rows="3" 
                              class="w-full border-blue-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base"
                              placeholder="Catatan khusus..."></textarea>
                </div>
                
                <!-- Gambar -->
                <div>
                    <label class="block text-sm font-medium text-blue-900 mb-2">
                        Gambar
                    </label>
                    <input type="file" name="gambar[]" 
                           class="w-full border-blue-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                           accept="image/*">
                </div>
                
                <!-- Hapus Button -->
                <div class="flex justify-end">
                    <button type="button" onclick="hapusJenisSampah(this)" 
                            class="px-4 py-2 text-red-600 border border-red-300 rounded-lg hover:bg-red-50 focus:ring-2 focus:ring-red-500 focus:border-red-500 hapus-btn flex items-center justify-center text-base">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus
                    </button>
                </div>
            `;
            
            container.appendChild(newItem);
            updateHapusButtons();
            updateSelectOptions();
        }
        
        // Fungsi untuk menghapus jenis sampah
        function hapusJenisSampah(button) {
            const item = button.closest('.jenis-sampah-item');
            item.remove();
            updateHapusButtons();
            updateKalkulasi();
            updateSelectOptions(); // Update options setelah menghapus item
        }
        
        // Fungsi untuk update tombol hapus
        function updateHapusButtons() {
            const items = document.querySelectorAll('.jenis-sampah-item');
            const hapusButtons = document.querySelectorAll('.hapus-btn');
            
            hapusButtons.forEach((btn, index) => {
                if (items.length === 1) {
                    btn.style.display = 'none'; // Sembunyikan jika hanya 1 item
                } else {
                    btn.style.display = 'block'; // Tampilkan jika lebih dari 1 item
                }
            });
        }
        
        // Update tombol hapus saat halaman load
        document.addEventListener('DOMContentLoaded', function() {
            updateHapusButtons();
            
            // Add file input change listener
            document.addEventListener('change', function(e) {
                if (e.target.type === 'file') {
                    console.log('File selected:', e.target.files.length, 'files');
                    if (e.target.files.length > 0) {
                        console.log('File details:', e.target.files[0].name, e.target.files[0].size, 'bytes');
                        
                        // Add visual feedback
                        const fileInput = e.target;
                        const parentDiv = fileInput.closest('div');
                        let feedback = parentDiv.querySelector('.file-feedback');
                        
                        if (!feedback) {
                            feedback = document.createElement('div');
                            feedback.className = 'file-feedback text-sm text-green-600 mt-1';
                            parentDiv.appendChild(feedback);
                        }
                        
                        feedback.textContent = `File dipilih: ${e.target.files[0].name} (${(e.target.files[0].size / 1024).toFixed(1)} KB)`;
                    }
                }
            });
            
            // Add submit button click listener
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    console.log('Submit button clicked');
                    console.log('Form data before submit:');
                    
                    const form = document.querySelector('form');
                    const formData = new FormData(form);
                    
                    for (let [key, value] of formData.entries()) {
                        console.log(key, value);
                    }
                });
            }
        });
    </script>
</body>
</html>
