<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use App\Models\Penjemputan;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardPengepul extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard Pengepul';
    protected static ?string $slug = 'dashboard-pengepul';
    protected static ?int $navigationSort = 1; 

    protected static string $view = 'filament.pages.dashboard-pengepul';

    public function getViewData(): array
    {
        $user = Auth::user();
        
        // User dengan role pengepul langsung menjadi pengepul
        $pengepulId = $user->id;

        // Stats
        $totalPenjemputan = Penjemputan::where('pengepul_id', $pengepulId)
            ->count();
        $penjemputanHariIni = Penjemputan::where('pengepul_id', $pengepulId)
            ->whereDate('tanggal_penjemputan', today())
            ->count();
        // Pengepul tidak punya pendapatan, mereka membayar nasabah
        $totalPembayaran = Transaksi::where('nasabah', true)
            ->whereHas('penjemputan', function($query) use ($pengepulId) {
                $query->where('pengepul_id', $pengepulId);
            })
            ->sum('total_harga');


        // Stats untuk chart compact
        $totalOrders = Penjemputan::where('pengepul_id', $pengepulId)->count();
        $completedOrders = Penjemputan::where('pengepul_id', $pengepulId)
            ->where('status', 'completed')
            ->count();
        $pendingOrders = Penjemputan::where('status', 'pending')->count();

        // Data untuk chart completed orders per bulan
        $completedOrdersData = $this->getChartCompletedOrdersBulan($pengepulId);

        // Data untuk chart pending orders per bulan
        $pendingOrdersData = $this->getChartPendingOrdersBulan();

        // Order Pending (yang belum diambil) - FCFS
        $orderPending = Penjemputan::where('status', 'pending')
            ->with(['kelompok', 'sampahDetails.jenisSampah'])
            ->orderBy('tanggal_penjemputan', 'asc')
            ->orderBy('waktu_penjemputan', 'asc')
            ->get();

        // Statistik untuk Upload Bukti Transaksi
        // Hitung berdasarkan penjemputan_id yang belum ada bukti (sesuai dengan halaman Upload Bukti Transaksi)
        // Transaksi nasabah yang belum ada bukti
        $transaksiNasabah = Transaksi::where('nasabah', true)
            ->whereNotNull('penjemputan_id') // Pastikan ada penjemputan_id
            ->where(function ($q) use ($pengepulId) {
                $q->whereHas('penjemputan', function($qq) use ($pengepulId) {
                    $qq->where('pengepul_id', $pengepulId);
                })->orWhere('pengepul_id', $pengepulId);
            })
            ->with('penjemputan')
            ->get();
            
        // Group by penjemputan_id dan cek apakah ada yang belum ada bukti
        $penjemputanTanpaBuktiNasabah = $transaksiNasabah
            ->groupBy('penjemputan_id')
            ->filter(function ($items) {
                // Cek apakah semua transaksi dalam grup ini belum ada bukti
                return $items->every(function ($t) {
                    return empty($t->gambar_bukti_nasabah) && empty($t->bukti_pembayaran);
                });
            })
            ->count();
            
        // Transaksi sistem yang belum ada bukti
        $transaksiSistem = Transaksi::where('sistem', true)
            ->where('nasabah', false)
            ->whereNotNull('penjemputan_id') // Pastikan ada penjemputan_id
            ->where(function ($q) use ($pengepulId) {
                $q->whereHas('penjemputan', function($qq) use ($pengepulId) {
                    $qq->where('pengepul_id', $pengepulId);
                })->orWhere('pengepul_id', $pengepulId);
            })
            ->with('penjemputan')
            ->get();
            
        // Group by penjemputan_id dan cek apakah ada yang belum ada bukti
        $penjemputanTanpaBuktiSistem = $transaksiSistem
            ->groupBy('penjemputan_id')
            ->filter(function ($items) {
                // Cek apakah semua transaksi dalam grup ini belum ada bukti
                return $items->every(function ($t) {
                    return empty($t->gambar_bukti_sistem) && empty($t->bukti_pembayaran);
                });
            })
            ->count();
            
        $totalTransaksiTanpaBukti = $penjemputanTanpaBuktiNasabah + $penjemputanTanpaBuktiSistem;

        return [
            'totalPenjemputan' => $totalPenjemputan,
            'penjemputanHariIni' => $penjemputanHariIni,
            'totalPembayaran' => $totalPembayaran,
            'totalOrders' => $totalOrders,
            'completedOrders' => $completedOrders,
            'pendingOrders' => $pendingOrders,
            'orderPending' => $orderPending,
            'pengepul' => $user, // User langsung sebagai pengepul
            'totalTransaksiTanpaBukti' => $totalTransaksiTanpaBukti,
            'penjemputanTanpaBuktiNasabah' => $penjemputanTanpaBuktiNasabah,
            'penjemputanTanpaBuktiSistem' => $penjemputanTanpaBuktiSistem,
            // Chart data
            'chartPenjemputanBulan' => $this->getChartPenjemputanBulan($pengepulId),
            'chartCompletedOrdersBulan' => $this->getChartCompletedOrdersBulan($pengepulId),
            'chartPendingOrdersBulan' => $this->getChartPendingOrdersBulan(),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('pengepul');
    }
    private function getChartPenjemputanBulan($pengepulId): array
    {
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            $count = Penjemputan::where('pengepul_id', $pengepulId)
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
     * Data untuk chart completed orders per bulan (6 bulan terakhir)
     */
    private function getChartCompletedOrdersBulan($pengepulId): array
    {
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            $count = Penjemputan::where('pengepul_id', $pengepulId)
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
    private function getChartPendingOrdersBulan(): array
    {
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            $count = Penjemputan::where('status', 'pending')
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

} 