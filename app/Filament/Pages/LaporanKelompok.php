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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class LaporanKelompok extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Kelompok';
    protected static ?string $title = 'Laporan Kelompok';
    protected static ?string $slug = 'laporan-kelompok';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.laporan-kelompok';

    public ?array $data = [];
    public ?array $report = null;

    public function mount(): void
    {
        // Set kelompok_id ke kelompok user yang login
        $user = auth()->user();
        $kelompokId = $user->kelompok ? $user->kelompok->id : null;
        
        $this->form->fill([
            'periode' => 'bulan_ini',
            'tanggal_mulai' => now()->startOfMonth(),
            'tanggal_selesai' => now()->endOfMonth(),
            'kelompok_id' => $kelompokId,
            'jenis_sampah_id' => null,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filter Laporan')
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
                            ->reactive()
                            ->required(),

                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->visible(fn ($get) => $get('periode') === 'custom')
                            ->required(fn ($get) => $get('periode') === 'custom'),

                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->visible(fn ($get) => $get('periode') === 'custom')
                            ->required(fn ($get) => $get('periode') === 'custom'),

                        Select::make('kelompok_id')
                            ->label('Kelompok')
                            ->options(function() {
                                $user = auth()->user();
                                if ($user->kelompok) {
                                    return [$user->kelompok->id => $user->kelompok->nama];
                                }
                                return [];
                            })
                            ->disabled()
                            ->dehydrated(),

                        // Filter jenis sampah dihilangkan agar laporan mencakup semua jenis
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }


    public function generateReport(): void
    {
        $reportData = $this->getReportData();
        
        // Simpan ke state agar bisa dirender langsung di Blade
        $this->report = $reportData;
        
        // Show notification
        Notification::make()
            ->title('Laporan berhasil di-generate!')
            ->success()
            ->send();
    }

    public function exportExcel(): void
    {
        // Logic untuk export Excel akan diimplementasikan
        $this->dispatch('export-excel');
    }

    public function exportPDF(): void
    {
        // Logic untuk export PDF akan diimplementasikan
        $this->dispatch('export-pdf');
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

        // Query data berdasarkan filter (hanya untuk kelompok user)
        $user = auth()->user();
        $kelompokId = $user->kelompok ? $user->kelompok->id : null;
        
        $penjemputanQuery = Penjemputan::query()
            ->whereBetween('tanggal_penjemputan', [$startDate, $endDate])
            ->where('kelompok_id', $kelompokId);
            
        $transaksiQuery = Transaksi::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('penjemputan', function($q) use ($kelompokId) {
                $q->where('kelompok_id', $kelompokId);
            });

        // Tidak ada filter berdasarkan jenis sampah agar laporan mencakup semuanya

        // Query penggunaan dana berdasarkan periode
        $penggunaanDanaQuery = PenggunaanDana::query()
            ->whereBetween('tanggal_penggunaan', [$startDate, $endDate]);

        // Ambil data
        $penjemputan = $penjemputanQuery->with(['nasabah:id,nama'])->get();
        $transaksi = $transaksiQuery->with(['jenisSampah:id,harga,nama', 'penjemputan.nasabah:id,nama'])->get();
        $penggunaanDana = $penggunaanDanaQuery->with('createdBy:id,name')->get();

        // Hitung statistik
        $totalPenjemputan = $penjemputan->count();
        $totalTransaksi = $transaksi->count();
        $totalBerat = $transaksi->sum('berat');
        $totalPendapatan = $transaksi->sum(function($t) {
            return $t->berat * ($t->jenisSampah?->harga ?? 0);
        });
        
        // Statistik transaksi berdasarkan tujuan
        $transaksiNasabah = $transaksi->where('nasabah', true);
        $transaksiSistem = $transaksi->where('sistem', true);
        
        $totalPendapatanNasabah = $transaksiNasabah->sum(function($t) {
            return $t->berat * ($t->jenisSampah?->harga ?? 0);
        });
        
        $totalPendapatanSistem = $transaksiSistem->sum(function($t) {
            return $t->berat * ($t->jenisSampah?->harga ?? 0);
        });
        
        // Statistik penggunaan dana
        $totalPengeluaran = $penggunaanDana->sum('jumlah_pengeluaran');
        $jumlahPenggunaanDana = $penggunaanDana->count();

        // Data per kelompok (flatten, JSON-safe)
        $dataPerKelompok = $penjemputan
            ->groupBy(fn ($p) => $p->kelompok->nama ?? 'Unknown')
            ->map(function($group) {
                return [
                    'nama' => $group->first()->kelompok->nama ?? 'Unknown',
                    'penjemputan' => $group->count(),
                    // Jika relasi transaksi tidak tersedia, set 0
                    'transaksi' => 0,
                ];
            })
            ->values()
            ->all();

        // Data per jenis sampah dihilangkan sesuai permintaan

        // Data per kategori penggunaan dana
        $dataPerKategori = $penggunaanDana->groupBy('kategori')->map(function($group) {
            return [
                'kategori' => $group->first()->kategori,
                'total_pengeluaran' => $group->sum('jumlah_pengeluaran'),
                'jumlah_transaksi' => $group->count(),
            ];
        })->values()->all();

        // Detail (flatten, JSON-safe, limited fields)
        $penjemputanDetail = $penjemputan->map(function ($p) {
            return [
                'nasabah' => ['nama' => $p->nasabah->nama ?? null],
                'tanggal_penjemputan' => optional($p->tanggal_penjemputan)->toDateTimeString() ?? (string) $p->tanggal_penjemputan,
                'status' => $p->status,
            ];
        })->values()->all();

        $transaksiDetail = $transaksi->map(function ($t) {
            return [
                'penjemputan' => [
                    'nasabah' => ['nama' => optional(optional($t->penjemputan)->nasabah)->nama],
                ],
                'berat' => (float) $t->berat,
                'jenis_sampah' => [
                    'harga' => (float) ($t->jenisSampah->harga ?? 0),
                ],
            ];
        })->values()->all();

        $penggunaanDanaDetail = $penggunaanDana->map(function ($p) {
            return [
                'tanggal_penggunaan' => $p->tanggal_penggunaan->format('Y-m-d'),
                'kategori' => $p->kategori,
                'deskripsi' => $p->deskripsi,
                'jumlah_pengeluaran' => (float) $p->jumlah_pengeluaran,
                'created_by' => ['name' => $p->createdBy->name ?? 'Unknown'],
            ];
        })->values()->all();

        return [
            'periode' => $periode,
            'tanggal_mulai' => $startDate->format('d M Y'),
            'tanggal_selesai' => $endDate->format('d M Y'),
            'total_penjemputan' => $totalPenjemputan,
            'total_transaksi' => $totalTransaksi,
            'total_berat' => $totalBerat,
            'total_pendapatan' => $totalPendapatan,
            'total_pendapatan_nasabah' => $totalPendapatanNasabah,
            'total_pendapatan_sistem' => $totalPendapatanSistem,
            'total_pengeluaran' => $totalPengeluaran,
            'jumlah_penggunaan_dana' => $jumlahPenggunaanDana,
            'data_per_kelompok' => $dataPerKelompok,
            'data_per_kategori' => $dataPerKategori,
            'penjemputan_detail' => $penjemputanDetail,
            'transaksi_detail' => $transaksiDetail,
            'penggunaan_dana_detail' => $penggunaanDanaDetail,
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('kelompok');
    }
}
