<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use App\Models\Penjemputan;
use App\Models\Transaksi;
use App\Models\Nasabah;
use App\Models\Kelompok;
use App\Models\User;
use App\Models\SedekahSampah;
use App\Models\Kas;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardAdmin extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Dashboard Admin';
    protected static ?string $title = 'Dashboard Admin - Monitoring Sistem';
    protected static ?string $slug = 'dashboard-admin';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.dashboard-admin';

    protected function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\AdminStatsOverview::class,
            \App\Filament\Widgets\AdminPenjemputanChart::class,
            \App\Filament\Widgets\AdminPendapatanChart::class,
            \App\Filament\Widgets\AdminLogAktivitasTable::class,
            \App\Filament\Widgets\AdminUpcomingPickupsWidget::class,
        ];
    }

    public function getViewData(): array
    {
        // Stats Overview
        $totalNasabah = Nasabah::count();
        $totalKelompok = Kelompok::count();
        $totalPenjemputan = Penjemputan::count();
        $totalTransaksi = Transaksi::count();
        
        // Total Sumbangan ke Sistem (hanya dari donate_all dan donate_partial)
        $totalSumbanganSistem = Transaksi::where('sistem', true)
            ->whereHas('penjemputan', function($query) {
                $query->whereIn('payment_option', ['donate_all', 'donate_partial']);
            })
            ->sum('total_harga');
        
        // Total Pengeluaran Admin
        $totalPengeluaranAdmin = \App\Models\Pengeluaran::where('status', \App\Models\Pengeluaran::STATUS_APPROVED)
            ->sum('jumlah');
        
        // Saldo Sistem (Pemasukan - Pengeluaran)
        $saldoSistem = $totalSumbanganSistem - $totalPengeluaranAdmin;
        
        // Total Saldo Nasabah (dari transaksi nasabah)
        $totalSaldoNasabah = Transaksi::where('nasabah', true)
            ->whereHas('penjemputan', function($query) {
                $query->whereIn('payment_option', ['take_all', 'donate_partial']);
            })
            ->sum('total_harga');
        
        // Stats Hari Ini
        $penjemputanHariIni = Penjemputan::whereDate('tanggal_penjemputan', today())->count();
        $transaksiHariIni = Transaksi::whereDate('created_at', today())->count();
        $pendapatanHariIni = Transaksi::with('jenisSampah')
            ->whereDate('created_at', today())
            ->get()->sum(function($transaksi) {
                return $transaksi->berat * ($transaksi->jenisSampah?->harga ?? 0);
            });
        
        // Stats Bulan Ini
        $penjemputanBulanIni = Penjemputan::whereMonth('tanggal_penjemputan', now()->month)
            ->whereYear('tanggal_penjemputan', now()->year)->count();
        $transaksiBulanIni = Transaksi::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $pendapatanBulanIni = Transaksi::with('jenisSampah')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get()->sum(function($transaksi) {
                return $transaksi->berat * ($transaksi->jenisSampah?->harga ?? 0);
            });

        // Status Penjemputan
        $penjemputanPending = Penjemputan::where('status', 'pending')->count();
        $penjemputanCompleted = Penjemputan::where('status', 'completed')->count();
        $penjemputanCancelled = Penjemputan::where('status', 'cancelled')->count();

        // Data Chart - Penjemputan per bulan (6 bulan terakhir)
        $chartPenjemputanBulan = $this->getChartPenjemputanBulan();
        
        // Data Chart - Transaksi per bulan (6 bulan terakhir)
        $chartTransaksiBulan = $this->getChartTransaksiBulan();
        
        // Data Chart - Pendapatan per bulan (6 bulan terakhir)
        $chartPendapatanBulan = $this->getChartPendapatanBulan();

        // Data Chart - Status Penjemputan
        $chartStatusPenjemputan = [
            'labels' => ['Pending', 'Completed', 'Cancelled'],
            'data' => [$penjemputanPending, $penjemputanCompleted, $penjemputanCancelled],
            'colors' => ['#f59e0b', '#10b981', '#ef4444']
        ];

        // Top Kelompok berdasarkan jumlah nasabah
        $topKelompok = Kelompok::withCount('nasabah')
            ->orderBy('nasabah_count', 'desc')
            ->limit(5)
            ->get();

        // Top Nasabah berdasarkan total sampah - Optimized
        $topNasabah = Nasabah::select('nasabah.*')
            ->selectSub(
                Transaksi::selectRaw('SUM(berat)')
                    ->whereColumn('nasabah_id', 'nasabah.id'),
                'total_berat'
            )
            ->orderBy('total_berat', 'desc')
            ->limit(5)
            ->get();

        // Penjemputan Terbaru - dengan eager loading
        $penjemputanTerbaru = Penjemputan::with(['kelompok', 'pengepul', 'nasabah'])
            ->latest()
            ->limit(10)
            ->get();

        // Transaksi Terbaru - dengan eager loading
        $transaksiTerbaru = Transaksi::with(['nasabah', 'jenisSampah', 'penjemputan.pengepul', 'pengepul'])
            ->latest()
            ->limit(10)
            ->get();

        // Sedekah Sampah Terbaru (hanya dari donate_all dan donate_partial) - dengan eager loading
        $sedekahTerbaru = Transaksi::where('sistem', true)
            ->whereHas('penjemputan', function($query) {
                $query->whereIn('payment_option', ['donate_all', 'donate_partial']);
            })
            ->with(['nasabah', 'jenisSampah', 'penjemputan.kelompok'])
            ->latest()
            ->limit(5)
            ->get();

        // Log Aktivitas Terbaru - dengan eager loading
        $logAktivitasTerbaru = \App\Models\LogAktivitas::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return [
            // Stats Overview
            'totalNasabah' => $totalNasabah,
            'totalKelompok' => $totalKelompok,
            'totalPenjemputan' => $totalPenjemputan,
            'totalTransaksi' => $totalTransaksi,
            'totalSumbanganSistem' => $totalSumbanganSistem,
            'totalPengeluaranAdmin' => $totalPengeluaranAdmin,
            'saldoSistem' => $saldoSistem,
            'totalSaldoNasabah' => $totalSaldoNasabah,
            
            // Stats Hari Ini
            'penjemputanHariIni' => $penjemputanHariIni,
            'transaksiHariIni' => $transaksiHariIni,
            'pendapatanHariIni' => $pendapatanHariIni,
            
            // Stats Bulan Ini
            'penjemputanBulanIni' => $penjemputanBulanIni,
            'transaksiBulanIni' => $transaksiBulanIni,
            'pendapatanBulanIni' => $pendapatanBulanIni,
            
            // Status Penjemputan
            'penjemputanPending' => $penjemputanPending,
            'penjemputanCompleted' => $penjemputanCompleted,
            'penjemputanCancelled' => $penjemputanCancelled,
            
            // Charts
            'chartPenjemputanBulan' => $chartPenjemputanBulan,
            'chartTransaksiBulan' => $chartTransaksiBulan,
            'chartPendapatanBulan' => $chartPendapatanBulan,
            'chartStatusPenjemputan' => $chartStatusPenjemputan,
            
            // Top Data
            'topKelompok' => $topKelompok,
            'topNasabah' => $topNasabah,
            
            // Data Terbaru
            'penjemputanTerbaru' => $penjemputanTerbaru,
            'transaksiTerbaru' => $transaksiTerbaru,
            'sedekahTerbaru' => $sedekahTerbaru,
            'logAktivitasTerbaru' => $logAktivitasTerbaru,
        ];
    }

    private function getChartPenjemputanBulan()
    {
        // Optimized: Single query untuk semua data
        $chartData = Penjemputan::selectRaw('
                DATE_FORMAT(tanggal_penjemputan, "%b %Y") as month_year,
                COUNT(*) as count
            ')
            ->where('tanggal_penjemputan', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->get()
            ->keyBy('month_year');

        $data = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthYear = $date->format('M Y');
            
            $data[] = $chartData->get($monthYear)->count ?? 0;
            $labels[] = $monthYear;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    private function getChartTransaksiBulan()
    {
        // Optimized: Single query untuk semua data
        $chartData = Transaksi::selectRaw('
                DATE_FORMAT(created_at, "%b %Y") as month_year,
                COUNT(*) as count
            ')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->get()
            ->keyBy('month_year');

        $data = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthYear = $date->format('M Y');
            
            $data[] = $chartData->get($monthYear)->count ?? 0;
            $labels[] = $monthYear;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    private function getChartPendapatanBulan()
    {
        // Optimized: Single query dengan JOIN
        $chartData = Transaksi::selectRaw('
                DATE_FORMAT(transaksi.created_at, "%b %Y") as month_year,
                SUM(transaksi.berat * jenis_sampah.harga) as total_pendapatan
            ')
            ->join('jenis_sampah', 'transaksi.jenis_sampah_id', '=', 'jenis_sampah.id')
            ->where('transaksi.created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->get()
            ->keyBy('month_year');

        $data = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthYear = $date->format('M Y');
            
            $data[] = (float) ($chartData->get($monthYear)->total_pendapatan ?? 0);
            $labels[] = $monthYear;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
