<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transparansi Donasi - Bank Sampah</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
</head>
<body class="bg-[#f8fbfa]">
<div class="min-h-screen flex flex-col">
    <header class="bg-white shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <span class="font-bold text-lg text-[#0e1a13] tracking-tight">Bank Sampah</span>
            </a>
            <a href="{{ url('/') }}" class="text-sm text-[#51946c] hover:text-[#38e07b] font-medium">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </header>

    <main class="flex-1">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-2xl font-bold text-[#0e1a13] mb-2">Transparansi Donasi</h1>
            <p class="text-sm text-gray-600 mb-6">
                Halaman ini menampilkan rincian lengkap dana donasi yang masuk ke sistem dan seluruh pengeluaran yang telah disetujui.
            </p>

            <!-- Ringkasan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">Total Donasi Masuk</p>
                    <p class="text-2xl font-bold text-[#0e1a13]">
                        Rp {{ number_format($totalDonasiMasuk ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">Total Pengeluaran</p>
                    <p class="text-2xl font-bold text-[#0e1a13]">
                        Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">Saldo Donasi Saat Ini</p>
                    <p class="text-2xl font-bold text-[#0e1a13]">
                        Rp {{ number_format($saldoDonasi ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Donasi Masuk -->
                <div>
                    <h2 class="text-lg font-semibold text-[#0e1a13] mb-3">Daftar Donasi Masuk ke Sistem</h2>
                    @if(isset($donasiMasuk) && $donasiMasuk->count() > 0)
                        <div class="bg-white rounded-lg border border-gray-200 divide-y max-h-[550px] overflow-y-auto">
                            @foreach($donasiMasuk as $donasi)
                                <div class="p-4 text-sm">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-medium text-[#0e1a13]">
                                            Penjemputan #{{ $donasi->penjemputan_id }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ optional($donasi->tanggal_transaksi)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-1">
                                        Pengepul:
                                        <span class="font-medium text-[#0e1a13]">
                                            {{ $donasi->pengepul?->name ?? '-' }}
                                        </span>
                                    </p>
                                    <p class="font-semibold text-[#0e1a13]">
                                        Rp {{ number_format($donasi->total_harga ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-lg border border-gray-200 p-6 text-center text-sm text-gray-500">
                            Belum ada data donasi masuk.
                        </div>
                    @endif
                </div>

                <!-- Pengeluaran -->
                <div>
                    <h2 class="text-lg font-semibold text-[#0e1a13] mb-3">Daftar Pengeluaran Donasi</h2>
                    @if(isset($semuaPengeluaran) && $semuaPengeluaran->count() > 0)
                        <div class="bg-white rounded-lg border border-gray-200 divide-y max-h-[550px] overflow-y-auto">
                            @foreach($semuaPengeluaran as $pengeluaran)
                                <div class="p-4 text-sm">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 text-[11px] font-medium rounded-full bg-[#e8f2ec] text-[#51946c]">
                                                {{ $pengeluaran->kategori_label }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($pengeluaran->tanggal_pengeluaran)->format('d M Y') }}
                                            </span>
                                        </div>
                                        <span class="font-semibold text-[#0e1a13]">
                                            Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <p class="font-medium text-[#0e1a13]">
                                        {{ $pengeluaran->nama_pengeluaran }}
                                    </p>
                                    @if($pengeluaran->penerima_pengeluaran)
                                        <p class="text-xs text-gray-500 mt-1">
                                            Penerima: {{ $pengeluaran->penerima_pengeluaran }}
                                        </p>
                                    @endif
                                    @if($pengeluaran->approvedBy)
                                        <p class="text-xs text-gray-500 mt-1">
                                            Disetujui oleh: {{ $pengeluaran->approvedBy->name }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-lg border border-gray-200 p-6 text-center text-sm text-gray-500">
                            Belum ada data pengeluaran.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>


