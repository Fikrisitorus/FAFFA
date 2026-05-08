<?php

namespace App\Observers;

use App\Models\Penjemputan;
use App\Models\Notification;
use App\Models\LogAktivitas;

class PenjemputanObserver
{
    /**
     * Handle the Penjemputan "created" event.
     */
    public function created(Penjemputan $penjemputan): void
    {
        try {
            // Load relasi nasabah jika belum di-load
            if (!$penjemputan->relationLoaded('nasabah')) {
                $penjemputan->load('nasabah');
            }
            
            $nasabahName = $penjemputan->nasabah->nama ?? 'Nasabah';
            $tanggalFormat = $penjemputan->tanggal_penjemputan ? $penjemputan->tanggal_penjemputan->format('d/m/Y') : now()->format('d/m/Y');
            $tanggalWaktuFormat = $penjemputan->tanggal_penjemputan ? $penjemputan->tanggal_penjemputan->format('d/m/Y H:i') : now()->format('d/m/Y H:i');
            
            // Notifikasi untuk admin - ada penjemputan baru
            $adminUsers = \App\Models\User::role('admin')->get();
            foreach ($adminUsers as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'nasabah_id' => $penjemputan->nasabah_id,
                    'type' => Notification::TYPE_PENJEMPUTAN,
                    'title' => 'Penjemputan Baru',
                    'message' => "Penjemputan baru dari {$nasabahName} pada {$tanggalFormat}",
                    'data' => [
                        'penjemputan_id' => $penjemputan->id,
                        'status' => $penjemputan->status,
                    ],
                ]);
            }

            // Notifikasi untuk pengepul - ada order baru yang bisa diambil
            $pengepulUsers = \App\Models\User::role('pengepul')->get();
            foreach ($pengepulUsers as $pengepul) {
                Notification::create([
                    'user_id' => $pengepul->id,
                    'nasabah_id' => $penjemputan->nasabah_id,
                    'type' => Notification::TYPE_PENJEMPUTAN,
                    'title' => 'Order Penjemputan Baru',
                    'message' => "Ada order penjemputan baru dari {$nasabahName} pada {$tanggalWaktuFormat}",
                    'data' => [
                        'penjemputan_id' => $penjemputan->id,
                        'status' => $penjemputan->status,
                    ],
                ]);
            }

            // Log aktivitas
            LogAktivitas::create([
                'user_id' => $penjemputan->nasabah->user_id ?? null,
                'activity' => 'create_penjemputan',
                'description' => "Membuat penjemputan baru - {$nasabahName}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            \Log::error('PenjemputanObserver created error: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Penjemputan "updated" event.
     */
    public function updated(Penjemputan $penjemputan): void
    {
        try {
            // Jika status berubah
            if ($penjemputan->wasChanged('status')) {
                $oldStatus = $penjemputan->getOriginal('status');
                $newStatus = $penjemputan->status;

                // Notifikasi untuk nasabah/kelompok
                if ($penjemputan->nasabah && $penjemputan->nasabah->user_id) {
                    $statusMessages = [
                        'assigned' => 'Tim pengepul telah ditugaskan untuk penjemputan Anda',
                        'on_progress' => 'Tim pengepul sedang dalam perjalanan ke lokasi Anda',
                        'weight_verified' => 'Berat sampah telah diverifikasi oleh pengepul',
                        'completed' => 'Penjemputan telah selesai! Terima kasih telah berpartisipasi',
                        'cancelled' => 'Penjemputan telah dibatalkan',
                    ];

                    Notification::create([
                        'user_id' => $penjemputan->nasabah->user_id,
                        'nasabah_id' => $penjemputan->nasabah_id,
                        'type' => Notification::TYPE_PENJEMPUTAN,
                        'title' => 'Update Status Penjemputan',
                        'message' => $statusMessages[$newStatus] ?? "Status penjemputan Anda berubah menjadi {$newStatus}",
                        'data' => [
                            'penjemputan_id' => $penjemputan->id,
                            'old_status' => $oldStatus,
                            'new_status' => $newStatus,
                        ],
                    ]);
                }

                // Notifikasi untuk pengepul
                if ($penjemputan->pengepul_id) {
                    $statusMessages = [
                        'assigned' => 'Penjemputan telah ditugaskan kepada Anda',
                        'on_progress' => 'Penjemputan sedang dalam proses',
                        'weight_verified' => 'Berat sampah telah diverifikasi',
                        'completed' => 'Penjemputan telah selesai',
                        'cancelled' => 'Penjemputan telah dibatalkan',
                    ];

                    Notification::create([
                        'user_id' => $penjemputan->pengepul_id,
                        'nasabah_id' => $penjemputan->nasabah_id,
                        'type' => Notification::TYPE_PENJEMPUTAN,
                        'title' => 'Status Penjemputan Diperbarui',
                        'message' => $statusMessages[$newStatus] ?? "Status penjemputan berubah menjadi {$newStatus}",
                        'data' => [
                            'penjemputan_id' => $penjemputan->id,
                            'old_status' => $oldStatus,
                            'new_status' => $newStatus,
                        ],
                    ]);
                }
            }

            // Log aktivitas
            if ($penjemputan->wasChanged('status')) {
                LogAktivitas::create([
                    'user_id' => auth()->id(),
                    'activity' => 'update_penjemputan_status',
                    'description' => "Mengubah status penjemputan #{$penjemputan->id} dari {$oldStatus} ke {$newStatus}",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('PenjemputanObserver updated error: ' . $e->getMessage());
        }
    }
}

