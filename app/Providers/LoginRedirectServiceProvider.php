<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

class LoginRedirectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            $user = $event->user;
            
            // Redirect berdasarkan role setelah login
            if ($user->hasRole('pengepul')) {
                return redirect()->route('filament.admin.pages.dashboard-pengepul');
            } elseif ($user->hasRole('kelompok')) {
                return redirect()->route('filament.admin.pages.dashboard-kelompok');
            } elseif ($user->hasRole('admin')) {
                // Admin redirect ke dashboard admin khusus
                return redirect()->route('filament.admin.pages.dashboard-admin');
            }
        });
    }
}
