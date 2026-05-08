<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\Penjemputan;

class VerifikasiBerat extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $navigationLabel = 'Verifikasi Berat';
    protected static ?string $title = 'Verifikasi Berat';
    protected static ?string $slug = 'verifikasi-berat';
    protected static ?string $navigationGroup = 'Operasional Pengepul';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.verifikasi-berat';

    public function getViewData(): array
    {
        $user = Auth::user();
        $pengepulId = $user->id;

        // Ambil penjemputan yang perlu diverifikasi berat
        $penjemputanVerifikasi = Penjemputan::where('pengepul_id', $pengepulId)
            ->whereIn('status', ['assigned', 'on_progress'])
            ->with(['kelompok', 'sampahDetails.jenisSampah'])
            ->orderBy('tanggal_penjemputan', 'asc')
            ->get();

        return [
            'penjemputanPerluVerifikasi' => $penjemputanVerifikasi,
            'pengepul' => $user,
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->hasRole('pengepul');
    }
}
