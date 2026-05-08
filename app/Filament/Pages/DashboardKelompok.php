<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use App\Models\Penjemputan;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardKelompok extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard Kelompok';
    protected static ?string $slug = 'dashboard-kelompok';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.dashboard-kelompok';

    public function getViewData(): array
    {
        $user = Auth::user();
        $kelompok = $user->kelompok;
        $nasabah = $user->nasabah;

        // Stats - Total saldo kelompok (semua nasabah dalam kelompok)
        $totalSaldoKelompok = $kelompok ? $kelompok->nasabah()->sum('saldo') : 0;
        $totalSaldoIndividual = $nasabah ? $nasabah->saldo : 0;
        $totalSampah = Transaksi::where('nasabah_id', $nasabah?->id)
            ->get()->sum(function($transaksi) {
                return $transaksi->berat;
            });
        $totalOrder = Penjemputan::where('kelompok_id', $kelompok?->id)
            ->count();

        // Stats untuk chart compact
        $completedOrders = Penjemputan::where('kelompok_id', $kelompok?->id)
            ->where('status', 'completed')
            ->count();
        $pendingOrders = Penjemputan::where('kelompok_id', $kelompok?->id)
            ->where('status', 'pending')
            ->count();

        // Data untuk chart sampah per bulan
        $chartSampahBulan = $this->getChartSampahBulan($nasabah?->id);
        
        // Data untuk chart completed orders per bulan
        $chartCompletedOrdersBulan = $this->getChartCompletedOrdersBulan($kelompok?->id);
        
        // Data untuk chart pending orders per bulan
        $chartPendingOrdersBulan = $this->getChartPendingOrdersBulan($kelompok?->id);

        // Penjemputan Aktif
        $penjemputanAktif = Penjemputan::where('kelompok_id', $kelompok?->id)
            ->whereIn('status', ['pending', 'assigned', 'on_progress'])
            ->with(['pengepul', 'sampahDetails.jenisSampah'])
            ->orderBy('tanggal_penjemputan', 'asc')
            ->get();

        // Riwayat Penjemputan
        $riwayatPenjemputan = Penjemputan::where('kelompok_id', $kelompok?->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->with(['pengepul', 'transaksi'])
            ->orderBy('tanggal_penjemputan', 'desc')
            ->limit(5)
            ->get();

        return [
            'totalSaldoKelompok' => $totalSaldoKelompok,
            'totalSaldoIndividual' => $totalSaldoIndividual,
            'totalSampah' => $totalSampah,
            'totalOrder' => $totalOrder,
            'completedOrders' => $completedOrders,
            'pendingOrders' => $pendingOrders,
            'penjemputanAktif' => $penjemputanAktif,
            'riwayatPenjemputan' => $riwayatPenjemputan,
            'kelompok' => $kelompok,
            'nasabah' => $nasabah,
            // Chart data
            'chartSampahBulan' => $chartSampahBulan,
            'chartCompletedOrdersBulan' => $chartCompletedOrdersBulan,
            'chartPendingOrdersBulan' => $chartPendingOrdersBulan,
        ];
    }

    /**
     * Data untuk chart sampah per bulan (6 bulan terakhir)
     */
    private function getChartSampahBulan($nasabahId): array
    {
        if (!$nasabahId) return ['labels' => [], 'data' => []];
        
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            $berat = Transaksi::where('nasabah_id', $nasabahId)
                ->whereYear('tanggal_transaksi', $date->year)
                ->whereMonth('tanggal_transaksi', $date->month)
                ->get()->sum(function($transaksi) {
                    return $transaksi->berat;
                });
            
            $data[] = $berat;
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    /**
     * Data untuk chart completed orders per bulan (6 bulan terakhir)
     */
    private function getChartCompletedOrdersBulan($kelompokId): array
    {
        if (!$kelompokId) return ['labels' => [], 'data' => []];
        
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            $count = Penjemputan::where('kelompok_id', $kelompokId)
                ->where('status', 'completed')
                ->whereYear('tanggal_penjemputan', $date->year)
                ->whereMonth('tanggal_penjemputan', $date->month)
                ->count();
            
            $data[] = $count;
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    /**
     * Data untuk chart pending orders per bulan (6 bulan terakhir)
     */
    private function getChartPendingOrdersBulan($kelompokId): array
    {
        if (!$kelompokId) return ['labels' => [], 'data' => []];
        
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            $count = Penjemputan::where('kelompok_id', $kelompokId)
                ->where('status', 'pending')
                ->whereYear('tanggal_penjemputan', $date->year)
                ->whereMonth('tanggal_penjemputan', $date->month)
                ->count();
            
            $data[] = $count;
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('kelompok_nasabah');
    }
}
