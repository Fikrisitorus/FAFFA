<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Penjemputan;
use App\Models\Transaksi;
use App\Models\Nasabah;
use App\Models\Kelompok;
use App\Models\User;
use App\Models\SedekahSampah;
use Carbon\Carbon;

class AdminStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Stats untuk hari ini
        $penjemputanHariIni = Penjemputan::whereDate('tanggal_penjemputan', today())->count();
        $transaksiHariIni = Transaksi::whereDate('created_at', today())->count();
        $pendapatanHariIni = Transaksi::with('jenisSampah')
            ->whereDate('created_at', today())
            ->get()->sum(function($transaksi) {
                return $transaksi->berat * ($transaksi->jenisSampah?->harga ?? 0);
            });
        
        // Stats untuk bulan ini
        $penjemputanBulanIni = Penjemputan::whereMonth('tanggal_penjemputan', now()->month)
            ->whereYear('tanggal_penjemputan', now()->year)->count();
        $transaksiBulanIni = Transaksi::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $pendapatanBulanIni = Transaksi::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get()->sum(function($transaksi) {
                return $transaksi->berat * ($transaksi->jenisSampah?->harga ?? 0);
            });

        // Status penjemputan
        $penjemputanPending = Penjemputan::where('status', 'pending')->count();
        $penjemputanCompleted = Penjemputan::where('status', 'completed')->count();
        $penjemputanCancelled = Penjemputan::where('status', 'cancelled')->count();

        // Sedekah sampah bulan ini (hanya dari donate_all dan donate_partial)
        $sedekahBulanIni = Transaksi::where('sistem', true)
            ->whereHas('penjemputan', function($query) {
                $query->whereIn('payment_option', ['donate_all', 'donate_partial']);
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        // Total sumbangan ke sistem (hanya dari donate_all dan donate_partial)
        $totalSumbanganSistem = Transaksi::where('sistem', true)
            ->whereHas('penjemputan', function($query) {
                $query->whereIn('payment_option', ['donate_all', 'donate_partial']);
            })
            ->sum('total_harga');
        
        // Total pengeluaran admin
        $totalPengeluaranAdmin = \App\Models\Pengeluaran::where('status', \App\Models\Pengeluaran::STATUS_APPROVED)
            ->sum('jumlah');
        
        // Saldo sistem (pemasukan - pengeluaran)
        $saldoSistem = $totalSumbanganSistem - $totalPengeluaranAdmin;
        
        // Total saldo nasabah (dari transaksi nasabah)
        $totalSaldoNasabah = Transaksi::where('nasabah', true)
            ->whereHas('penjemputan', function($query) {
                $query->whereIn('payment_option', ['take_all', 'donate_partial']);
            })
            ->sum('total_harga');

        return [
            Stat::make('Penjemputan Hari Ini', $penjemputanHariIni)
                ->description('Total penjemputan hari ini')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary'),

            Stat::make('Transaksi Hari Ini', $transaksiHariIni)
                ->description('Total transaksi hari ini')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($pendapatanHariIni, 0, ',', '.'))
                ->description('Total pendapatan hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Penjemputan Pending', $penjemputanPending)
                ->description('Menunggu konfirmasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),

            Stat::make('Penjemputan Completed', $penjemputanCompleted)
                ->description('Sudah selesai')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Sedekah Bulan Ini', $sedekahBulanIni)
                ->description('Total sedekah sampah bulan ini')
                ->descriptionIcon('heroicon-m-heart')
                ->color('info'),

            Stat::make('Total Sumbangan Sistem', 'Rp ' . number_format($totalSumbanganSistem, 0, ',', '.'))
                ->description('Total sumbangan ke sistem')
                ->descriptionIcon('heroicon-m-heart')
                ->color('success'),

            Stat::make('Total Pengeluaran Admin', 'Rp ' . number_format($totalPengeluaranAdmin, 0, ',', '.'))
                ->description('Total pengeluaran admin')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('Saldo Sistem', 'Rp ' . number_format($saldoSistem, 0, ',', '.'))
                ->description('Saldo tersisa sistem')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($saldoSistem >= 0 ? 'success' : 'danger'),

            Stat::make('Total Saldo Nasabah', 'Rp ' . number_format($totalSaldoNasabah, 0, ',', '.'))
                ->description('Total saldo nasabah')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
