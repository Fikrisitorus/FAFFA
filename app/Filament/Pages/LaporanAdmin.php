<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use App\Models\Penjemputan;
use App\Models\Transaksi;
use App\Models\Nasabah;
use App\Models\Kelompok;
use App\Models\JenisSampah;
use App\Models\PenggunaanDana;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;

class LaporanAdmin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationLabel = 'Laporan Admin';
    protected static ?string $title = 'Laporan Admin - Financial Overview';
    protected static ?string $slug = 'laporan-admin';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.laporan-admin';

    public ?array $data = [];
    public ?array $report = null;

    public function mount(): void
    {
        $this->form->fill([
            'periode' => 'bulan_ini',
            'tanggal_mulai' => now()->startOfMonth(),
            'tanggal_selesai' => now()->endOfMonth(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filter Periode')
                    ->schema([
                        Select::make('periode')
                            ->label('Periode')
                            ->options([
                                'hari_ini' => 'Hari Ini',
                                'minggu_ini' => 'Minggu Ini',
                                'bulan_ini' => 'Bulan Ini',
                                'tahun_ini' => 'Tahun Ini',
                                'custom' => 'Custom',
                            ])
                            ->default('bulan_ini')
                            ->reactive(),

                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->visible(fn ($get) => $get('periode') === 'custom')
                            ->required(fn ($get) => $get('periode') === 'custom'),

                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->visible(fn ($get) => $get('periode') === 'custom')
                            ->required(fn ($get) => $get('periode') === 'custom'),
                    ])
                    ->columns(3),
            ])
            ->statePath('data');
    }

    public function generateReport(): void
    {
        $this->report = $this->getReportData();
        
        Notification::make()
            ->title('Laporan berhasil di-generate!')
            ->success()
            ->send();
    }

    public function getReportData(): array
    {
        $data = $this->form->getState();
        $periode = $data['periode'];
        
        // Tentukan tanggal berdasarkan periode
        switch ($periode) {
            case 'hari_ini':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'minggu_ini':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'bulan_ini':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'tahun_ini':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            case 'custom':
                $startDate = Carbon::parse($data['tanggal_mulai'])->startOfDay();
                $endDate = Carbon::parse($data['tanggal_selesai'])->endOfDay();
                break;
        }

        // PEMASUKAN (Income)
        $pemasukan = $this->getPemasukanData($startDate, $endDate);
        
        // PENGELUARAN (Expense)
        $pengeluaran = $this->getPengeluaranData($startDate, $endDate);
        
        // SUMMARY
        $totalPemasukan = $pemasukan->sum('amount');
        $totalPengeluaran = $pengeluaran->sum('amount');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return [
            'periode' => $periode,
            'tanggal_mulai' => $startDate->format('d M Y'),
            'tanggal_selesai' => $endDate->format('d M Y'),
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
        ];
    }

    private function getPemasukanData($startDate, $endDate)
    {
        $pemasukan = collect();

        // 1. Sumbangan dari donate_all
        $donateAll = Transaksi::where('sistem', true)
            ->whereHas('penjemputan', function($query) {
                $query->where('payment_option', 'donate_all');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['penjemputan.kelompok', 'jenisSampah'])
            ->get()
            ->groupBy('penjemputan_id')
            ->map(function($transactions, $penjemputanId) {
                $penjemputan = $transactions->first()->penjemputan;
                return [
                    'type' => 'Sumbangan Penuh',
                    'description' => "Sumbangan dari {$penjemputan->kelompok->nama}",
                    'amount' => $transactions->sum('total_harga'),
                    'date' => $penjemputan->created_at,
                    'details' => $transactions->map(function($t) {
                        // harga_per_kg diambil dari relasi jenisSampah->harga
                        $hargaPerKg = $t->jenisSampah?->harga ?? 0;
                        return "{$t->jenisSampah->nama}: {$t->berat} kg × Rp " . number_format($hargaPerKg, 0, ',', '.') . " = Rp " . number_format($t->total_harga, 0, ',', '.');
                    })->toArray()
                ];
            });

        // 2. Sumbangan dari donate_partial
        $donatePartial = Transaksi::where('sistem', true)
            ->whereHas('penjemputan', function($query) {
                $query->where('payment_option', 'donate_partial');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['penjemputan.kelompok', 'jenisSampah'])
            ->get()
            ->groupBy('penjemputan_id')
            ->map(function($transactions, $penjemputanId) {
                $penjemputan = $transactions->first()->penjemputan;
                return [
                    'type' => 'Sumbangan Sebagian',
                    'description' => "Sumbangan dari {$penjemputan->kelompok->nama}",
                    'amount' => $transactions->sum('total_harga'),
                    'date' => $penjemputan->created_at,
                    'details' => $transactions->map(function($t) {
                        // harga_per_kg diambil dari relasi jenisSampah->harga
                        $hargaPerKg = $t->jenisSampah?->harga ?? 0;
                        return "{$t->jenisSampah->nama}: {$t->berat} kg × Rp " . number_format($hargaPerKg, 0, ',', '.') . " = Rp " . number_format($t->total_harga, 0, ',', '.');
                    })->toArray()
                ];
            });

        // 3. Sedekah Sampah (jika ada)
        $sedekahSampah = \App\Models\SedekahSampah::whereBetween('created_at', [$startDate, $endDate])
            ->with(['nasabah.kelompok'])
            ->get()
            ->map(function($sedekah) {
                return [
                    'type' => 'Sedekah Sampah',
                    'description' => "Sedekah dari {$sedekah->nasabah->kelompok->nama}",
                    'amount' => $sedekah->jumlah_sedekah,
                    'date' => $sedekah->created_at,
                    'details' => ["Jumlah: Rp " . number_format($sedekah->jumlah_sedekah, 0, ',', '.')]
                ];
            });

        // Gabungkan semua pemasukan
        $pemasukan = $pemasukan->merge($donateAll)->merge($donatePartial)->merge($sedekahSampah);
        
        return $pemasukan->sortByDesc('date');
    }

    private function getPengeluaranData($startDate, $endDate)
    {
        $pengeluaran = collect();

        // 1. Pengeluaran Admin (Baru)
        $pengeluaranAdmin = Pengeluaran::whereBetween('tanggal_pengeluaran', [$startDate, $endDate])
            ->where('status', Pengeluaran::STATUS_APPROVED)
            ->get()
            ->map(function($pengeluaran) {
            return [
                    'type' => 'Pengeluaran Admin',
                    'description' => $pengeluaran->nama_pengeluaran,
                    'amount' => $pengeluaran->jumlah,
                    'date' => $pengeluaran->tanggal_pengeluaran,
                    'details' => [
                        "Kategori: {$pengeluaran->kategori_label}",
                        "Lokasi: {$pengeluaran->lokasi_label}",
                        "Penerima: " . ($pengeluaran->penerima_pengeluaran ?: 'Tidak disebutkan'),
                        "Jumlah: Rp " . number_format($pengeluaran->jumlah, 0, ',', '.')
                    ],
                    'pengeluaran_id' => $pengeluaran->id, // Untuk edit/delete
                ];
            });

        // 2. Penggunaan Dana (Lama)
        $penggunaanDana = PenggunaanDana::whereBetween('tanggal_penggunaan', [$startDate, $endDate])
            ->get()
            ->map(function($penggunaan) {
            return [
                    'type' => 'Penggunaan Dana',
                    'description' => $penggunaan->keterangan,
                    'amount' => $penggunaan->jumlah_penggunaan,
                    'date' => $penggunaan->tanggal_penggunaan,
                    'details' => ["Kategori: {$penggunaan->kategori}", "Jumlah: Rp " . number_format($penggunaan->jumlah_penggunaan, 0, ',', '.')]
                ];
            });

        // 3. Kas Keluar (jika ada)
        $kasKeluar = \App\Models\Kas::where('tipe', 'keluar')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->map(function($kas) {
            return [
                    'type' => 'Kas Keluar',
                    'description' => $kas->deskripsi,
                    'amount' => $kas->jumlah,
                    'date' => $kas->tanggal,
                    'details' => ["Jumlah: Rp " . number_format($kas->jumlah, 0, ',', '.')]
                ];
            });

        // Gabungkan semua pengeluaran
        $pengeluaran = $pengeluaran->merge($pengeluaranAdmin)->merge($penggunaanDana)->merge($kasKeluar);
        
        return $pengeluaran->sortByDesc('date');
    }

    // Actions untuk CRUD Pengeluaran
    protected function getHeaderActions(): array
    {
            return [
            Action::make('tambahPengeluaran')
                ->label('Tambah Pengeluaran')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\Select::make('kategori')
                        ->label('Kategori')
                        ->options(Pengeluaran::getKategoriOptions())
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('nama_pengeluaran')
                        ->label('Nama Pengeluaran')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->rows(2),
                    \Filament\Forms\Components\Textarea::make('catatan_pengeluaran')
                        ->label('Catatan Pengeluaran')
                        ->rows(2)
                        ->required()
                        ->helperText('Wajib diisi untuk audit trail'),
                    \Filament\Forms\Components\TextInput::make('penerima_pengeluaran')
                        ->label('Penerima Pengeluaran')
                        ->placeholder('Nama penerima atau vendor'),
                    \Filament\Forms\Components\Select::make('lokasi_pengeluaran')
                        ->label('Lokasi Pengeluaran')
                        ->options(Pengeluaran::getLokasiOptions())
                        ->default(Pengeluaran::LOKASI_LAINNYA),
                    \Filament\Forms\Components\TextInput::make('jumlah')
                        ->label('Jumlah')
                        ->numeric()
                        ->required()
                        ->prefix('Rp')
                        ->step(0.01),
                    \Filament\Forms\Components\DatePicker::make('tanggal_pengeluaran')
                        ->label('Tanggal Pengeluaran')
                        ->required()
                        ->default(now()),
                ])
                ->action(function (array $data): void {
                    // Validasi saldo
                    $totalPemasukan = Transaksi::where('sistem', true)
                        ->whereHas('penjemputan', function($query) {
                            $query->whereIn('payment_option', ['donate_all', 'donate_partial']);
                        })
                        ->sum('total_harga');
                    
                    $totalPengeluaran = Pengeluaran::where('status', Pengeluaran::STATUS_APPROVED)->sum('jumlah');
                    $saldoSistem = $totalPemasukan - $totalPengeluaran;
                    
                    if ($saldoSistem < $data['jumlah']) {
                        Notification::make()
                            ->title('Saldo tidak mencukupi!')
                            ->body("Saldo sistem: Rp " . number_format($saldoSistem, 0, ',', '.') . " | Pengeluaran: Rp " . number_format($data['jumlah'], 0, ',', '.'))
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    // Buat pengeluaran
                    $pengeluaran = Pengeluaran::create([
                        ...$data,
                        'status' => Pengeluaran::STATUS_APPROVED,
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);
                    
                    Notification::make()
                        ->title('Pengeluaran berhasil ditambahkan!')
                        ->success()
                        ->send();
                }),
        ];
    }

    // Method untuk edit pengeluaran
    public function editPengeluaran($pengeluaranId)
    {
        $pengeluaran = Pengeluaran::find($pengeluaranId);
        
        if (!$pengeluaran) {
            Notification::make()
                ->title('Pengeluaran tidak ditemukan!')
                ->danger()
                ->send();
            return;
        }

        // Buka modal edit (bisa menggunakan Filament modal atau redirect ke form)
        $this->dispatch('open-edit-modal', [
            'pengeluaran' => $pengeluaran->toArray()
        ]);
    }

    // Method untuk delete pengeluaran
    public function deletePengeluaran($pengeluaranId)
    {
        $pengeluaran = Pengeluaran::find($pengeluaranId);
        
        if (!$pengeluaran) {
            Notification::make()
                ->title('Pengeluaran tidak ditemukan!')
                ->danger()
                ->send();
            return;
        }

        $pengeluaran->delete();
        
        Notification::make()
            ->title('Pengeluaran berhasil dihapus!')
            ->success()
            ->send();
            
        // Refresh report
        $this->report = $this->getReportData();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
