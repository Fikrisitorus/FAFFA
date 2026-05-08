<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                \App\Filament\Pages\DashboardAdmin::class,
                \App\Filament\Pages\LaporanAdmin::class,
                \App\Filament\Pages\DashboardPengepul::class,
                \App\Filament\Pages\DashboardKelompok::class,
                \App\Filament\Pages\LaporanKelompok::class,
                \App\Filament\Pages\UploadBuktiTransaksi::class,
                \App\Filament\Pages\VerifikasiTransaksiSistem::class,
                \App\Filament\Pages\VerifikasiTransaksiNasabah::class,
                \App\Filament\Pages\VerifikasiTransaksiNasabahUser::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\Widgets\AdminStatsOverview::class,
                \App\Filament\Widgets\AdminPenjemputanChart::class,
                \App\Filament\Widgets\AdminPendapatanChart::class,
                \App\Filament\Widgets\AdminLogAktivitasTable::class,
                \App\Filament\Widgets\AdminUpcomingPickupsWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\RedirectBasedOnRole::class,
            ]);
    }
}
