<?php

namespace App\Livewire;

use App\Models\Penjemputan;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardPengepulStats extends Component
{
    public $stats = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Total semua penjemputan yang pernah ditugaskan ke pengepul ini
        $totalPenjemputan = Penjemputan::where('pengepul_id', $user->id)->count();
        
        // Penjemputan hari ini (berdasarkan jadwal_penjemputan)
        $hariIni = Penjemputan::where('pengepul_id', $user->id)
            ->whereDate('jadwal_penjemputan', $today)
            ->count();
        
        // Penjemputan yang selesai
        $selesai = Penjemputan::where('pengepul_id', $user->id)
            ->where('status', 'completed')
            ->count();
        
        // Penjemputan yang menunggu (assigned)
        $pending = Penjemputan::where('pengepul_id', $user->id)
            ->where('status', 'assigned')
            ->count();
        
        // Penjemputan yang sedang berlangsung (on_progress)
        $onProgress = Penjemputan::where('pengepul_id', $user->id)
            ->where('status', 'on_progress')
            ->count();
        
        $this->stats = [
            'total' => $totalPenjemputan,
            'hari_ini' => $hariIni,
            'selesai' => $selesai,
            'pending' => $pending,
            'on_progress' => $onProgress,
        ];
    }

    public function render()
    {
        return view('livewire.dashboard-pengepul-stats');
    }
}
