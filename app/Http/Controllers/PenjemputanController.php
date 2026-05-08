<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjemputan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjemputanController extends Controller
{
    /**
     * Ambil order (FCFS system)
     */
    public function ambilOrder($id)
    {
        try {
            DB::beginTransaction();

            $penjemputan = Penjemputan::where('status', 'pending')
                ->where('id', $id)
                ->lockForUpdate() // Prevent race condition
                ->first();

            if (!$penjemputan) {
                return redirect()->back()->with('error', 'Order tidak ditemukan atau sudah diambil.');
            }

            $user = Auth::user();

            if (!$user->hasRole('pengepul')) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses sebagai pengepul.');
            }

            // Update penjemputan
            $penjemputan->update([
                'pengepul_id' => $user->id,
                'status' => 'assigned',
                'waktu_diambil' => now(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Order berhasil diambil! Silakan lakukan penjemputan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil order: ' . $e->getMessage());
        }
    }

    /**
     * Mulai penjemputan
     */
    public function mulaiPenjemputan($id)
    {
        try {
            $penjemputan = Penjemputan::where('id', $id)
                ->where('pengepul_id', Auth::user()->id)
                ->where('status', 'assigned')
                ->first();

            if (!$penjemputan) {
                return redirect()->back()->with('error', 'Penjemputan tidak ditemukan.');
            }

            $penjemputan->update([
                'status' => 'on_progress',
                'waktu_mulai' => now(),
            ]);

            return redirect()->back()->with('success', 'Penjemputan dimulai!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Selesai penjemputan
     */
    public function selesaiPenjemputan($id)
    {
        try {
            $penjemputan = Penjemputan::where('id', $id)
                ->where('pengepul_id', Auth::user()->id)
                ->where('status', 'on_progress')
                ->first();

            if (!$penjemputan) {
                return redirect()->back()->with('error', 'Penjemputan tidak ditemukan.');
            }

            $penjemputan->update([
                'status' => 'completed',
                'waktu_selesai' => now(),
            ]);

            return redirect()->back()->with('success', 'Penjemputan selesai!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan penjemputan (untuk nasabah/kelompok)
     */
    public function batalPenjemputan($id)
    {
        try {
            $penjemputan = Penjemputan::where('id', $id)
                ->where('status', 'pending')
                ->first();

            if (!$penjemputan) {
                return redirect()->route('filament.admin.pages.dashboard-kelompok')->with('error', 'Penjemputan tidak ditemukan atau tidak dapat dibatalkan.');
            }

            // Cek apakah user yang login adalah pemilik penjemputan atau admin
            $user = Auth::user();
            $canCancel = false;

            // Jika user adalah admin, bisa batalkan semua
            if ($user->hasRole('admin')) {
                $canCancel = true;
            }
            // Jika user adalah nasabah, cek apakah dia pemilik penjemputan
            elseif ($user->hasRole('nasabah') || $user->hasRole('kelompok_nasabah')) {
                // Cek apakah user adalah nasabah yang membuat penjemputan
                if ($penjemputan->nasabah_id == $user->id || 
                    ($penjemputan->nasabah && $penjemputan->nasabah->kelompok_id == $user->kelompok_id)) {
                    $canCancel = true;
                }
            }

            if (!$canCancel) {
                return redirect()->route('filament.admin.pages.dashboard-kelompok')->with('error', 'Anda tidak memiliki izin untuk membatalkan penjemputan ini.');
            }

            // Update status menjadi cancelled
            $penjemputan->update([
                'status' => 'cancelled',
                'waktu_dibatalkan' => now(),
            ]);

            return redirect()->route('filament.admin.pages.dashboard-kelompok')->with('success', 'Penjemputan berhasil dibatalkan.');

        } catch (\Exception $e) {
            return redirect()->route('filament.admin.pages.dashboard-kelompok')->with('error', 'Terjadi kesalahan saat membatalkan penjemputan: ' . $e->getMessage());
        }
    }
}
