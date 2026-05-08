<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Penjemputan;
use App\Models\Transaksi;
use Carbon\Carbon;

class AdminPenjemputanChart extends ChartWidget
{
    protected static ?string $heading = 'Penjemputan per Bulan (6 Bulan Terakhir)';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Penjemputan::whereMonth('tanggal_penjemputan', $date->month)
                ->whereYear('tanggal_penjemputan', $date->year)
                ->count();
            
            $data[] = (int) ($count ?? 0);
            $labels[] = $date->format('M Y');
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Penjemputan',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
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
