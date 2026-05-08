<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\SedekahSampah;
use App\Models\Penjemputan;
use App\Models\Pengeluaran;
use App\Models\Artikel;
use App\Observers\TransaksiObserver;
use App\Observers\SedekahSampahObserver;
use App\Observers\PenjemputanObserver;
use App\Observers\PengeluaranObserver;
use App\Observers\ArtikelObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind custom LogoutResponse untuk redirect ke welcome page
        $this->app->bind(
            \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class,
            \App\Http\Responses\LogoutResponse::class
        );
    }

    public function boot(): void
    {
        // Implicitly grant "Super Admin" role all permissions
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        Transaksi::observe(TransaksiObserver::class);
        SedekahSampah::observe(SedekahSampahObserver::class);
        Penjemputan::observe(PenjemputanObserver::class);
        Pengeluaran::observe(PengeluaranObserver::class);
        Artikel::observe(ArtikelObserver::class);

        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}