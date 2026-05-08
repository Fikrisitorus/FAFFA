<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\Penjemputan;

class Pembayaran extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $title = 'Pembayaran';
    protected static ?string $slug = 'pembayaran';
    protected static ?string $navigationGroup = 'Operasional Pengepul';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.pembayaran';

    public function getViewData(): array
    {
        $user = Auth::user();
        $pengepulId = $user->id;

        // Ambil penjemputan yang perlu dibayar
        $penjemputanPembayaran = Penjemputan::where('pengepul_id', $pengepulId)
            ->where('status', 'weight_verified')
            ->with(['kelompok', 'sampahDetails.jenisSampah'])
            ->orderBy('tanggal_penjemputan', 'asc')
            ->get();

        return [
            'penjemputanPembayaran' => $penjemputanPembayaran,
            'pengepul' => $user,
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->hasRole('pengepul');
    }
}
