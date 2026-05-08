<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

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
        
        // Fallback ke dashboard default
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
