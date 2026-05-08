<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip redirect untuk halaman logout, login, atau route lainnya yang bukan /admin exact
        if (
            $request->is('admin/logout') ||
            $request->is('admin/login') ||
            $request->is('admin/*')
        ) {
            return $next($request);
        }

        // Hanya redirect jika user sudah login dan mengakses halaman /admin (exact)
        if (Auth::check() && $request->is('admin')) {
            $user = Auth::user();

            // Redirect berdasarkan role
            if ($user->hasRole('pengepul')) {
                return redirect()->route('filament.admin.pages.dashboard-pengepul');
            } elseif ($user->hasRole('kelompok')) {
                return redirect()->route('filament.admin.pages.dashboard-kelompok');
            } elseif ($user->hasRole('admin')) {
                // Admin redirect ke dashboard admin khusus
                return redirect()->route('filament.admin.pages.dashboard-admin');
            }
        }

        return $next($request);
    }
}
