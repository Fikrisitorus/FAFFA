<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaksi;
use Carbon\Carbon;

class AdminPendapatanChart extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan per Bulan (6 Bulan Terakhir)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $pendapatan = Transaksi::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->with('jenisSampah')
                ->get()
                ->sum(function($transaksi) {
                    return $transaksi->berat * ($transaksi->jenisSampah?->harga ?? 0);
                });
            
            // Ensure integer type and no null values
            $data[] = (int) ($pendapatan ?? 0);
            $labels[] = $date->format('M Y');
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'borderColor' => 'rgb(245, 158, 11)',
                    'tension' => 0.1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
