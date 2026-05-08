<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class InformasiKelompokNasabah extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static string $view = 'filament.pages.informasi-kelompok-nasabah';
    protected static ?string $navigationLabel = 'Informasi';
    protected static ?string $navigationGroup = 'Kelompok Nasabah';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('kelompok_nasabah');
    }
} 