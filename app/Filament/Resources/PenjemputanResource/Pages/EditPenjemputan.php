<?php

namespace App\Filament\Resources\PenjemputanResource\Pages;

use App\Filament\Resources\PenjemputanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\JenisSampah;
use Filament\Notifications\Notification;

class EditPenjemputan extends EditRecord
{
    protected static string $resource = PenjemputanResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();
        
        // Validasi tanggal untuk kelompok nasabah
        if ($user->hasRole('kelompok_nasabah')) {
            if (isset($data['jadwal_penjemputan'])) {
                $jadwal = \Carbon\Carbon::parse($data['jadwal_penjemputan']);
                $sekarang = \Carbon\Carbon::now();
                
                // Jika tanggal yang dipilih adalah kemarin atau sebelumnya
                if ($jadwal->isPast()) {
                    throw new \Exception('Tidak bisa memilih tanggal kemarin atau hari sebelumnya. Silakan pilih tanggal hari ini atau masa depan.');
                }
            }
            
            // Jika kelompok mengubah sampah_details, berarti mereka menimbang sendiri
            if (isset($data['sampah_details']) && !empty($data['sampah_details'])) {
                // Cek apakah ada berat yang diinput
                $hasWeight = false;
                foreach ($data['sampah_details'] as $detail) {
                    if (isset($detail['berat_nasabah']) && $detail['berat_nasabah'] > 0) {
                        $hasWeight = true;
                        break;
                    }
                }
                
                if ($hasWeight) {
                    $data['self_weighted'] = true;
                    $data['weight_status'] = 'estimated'; // Berat dari estimasi kelompok
                }
            }
        }
        
        return $data;
    }

    protected function afterSave(): void
    {
        $user = Auth::user();
        
        // Jika kelompok nasabah dan ada sampah details, set self_weighted = true
        if ($user->hasRole('kelompok_nasabah')) {
            $penjemputan = $this->record;
            $sampahDetails = $penjemputan->sampahDetails;
            
            if ($sampahDetails->count() > 0) {
                // Cek apakah ada berat yang diinput
                $hasWeight = false;
                foreach ($sampahDetails as $detail) {
                    if ($detail->berat > 0) {
                        $hasWeight = true;
                        break;
                    }
                }
                
                if ($hasWeight) {
                    $penjemputan->update([
                        'self_weighted' => true,
                        'weight_status' => 'estimated'
                    ]);
                }
            }
        }
    }

    protected function getSavedNotification(): ?\Filament\Notifications\Notification
    {
        $user = Auth::user();
        $record = $this->record;
        
        // Notifikasi khusus untuk perubahan status
        if ($user->hasRole('pengepul') && $record->wasChanged('status')) {
            $statusMessages = [
                'assigned' => 'Penjemputan telah ditugaskan kepada Anda',
                'on_progress' => 'Penjemputan sedang dalam proses',
                'completed' => 'Penjemputan telah selesai dan transaksi telah dibuat',
                'cancelled' => 'Penjemputan telah dibatalkan'
            ];
            
            $message = $statusMessages[$record->status] ?? "Status penjemputan berubah menjadi {$record->status}";
            
            return \Filament\Notifications\Notification::make()
                ->title('Status Penjemputan Diperbarui')
                ->body($message)
                ->success()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->label('Lihat Detail')
                        ->url(static::getResource()::getUrl('edit', ['record' => $record]))
                        ->color('primary'),
                ]);
        }
        
        // Notifikasi untuk kelompok nasabah ketika status penjemputan mereka berubah
        if ($user->hasRole('kelompok_nasabah') && $record->wasChanged('status')) {
            $statusMessages = [
                'assigned' => 'Tim pengepul telah ditugaskan untuk penjemputan Anda',
                'on_progress' => 'Tim pengepul sedang dalam perjalanan ke lokasi Anda',
                'completed' => 'Penjemputan telah selesai! Terima kasih telah berpartisipasi',
                'cancelled' => 'Penjemputan telah dibatalkan'
            ];
            
            $message = $statusMessages[$record->status] ?? "Status penjemputan Anda berubah menjadi {$record->status}";
            
            return \Filament\Notifications\Notification::make()
                ->title('Update Penjemputan')
                ->body($message)
                ->success()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->label('Lihat Detail')
                        ->url(static::getResource()::getUrl('edit', ['record' => $record]))
                        ->color('primary'),
                ]);
        }
        
        return parent::getSavedNotification();
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\DeleteAction::make(),
        ];
        return $actions;
    }

    protected function getActions(): array
    {
        $actions = [];
        $user = Auth::user();
        $record = $this->record;
        
        // Tombol Selesaikan Penjemputan hanya untuk pengepul dan status belum completed
        if ($user && $user->hasRole('pengepul') && $record->status !== 'completed' && $record->pengepul_id === $user->id) {
            $actions[] = Actions\Action::make('selesaikan')
                ->label('Selesaikan Penjemputan')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->action(function () use ($record, $user) {
                    $record->status = 'completed';
                    $record->waktu_selesai = now();
                    $record->save();
                    // Buat transaksi otomatis (contoh: input berat, jenis_sampah, harga_per_kg dummy)
                    $jenisSampah = JenisSampah::first();
                    Transaksi::create([
                        'nasabah_id' => $record->nasabah_id,
                        'pengepul_id' => $user->id,
                        'penjemputan_id' => $record->id,
                        'jenis_sampah_id' => $jenisSampah?->id,
                        'berat' => 10, // Bisa diubah ke input form jika ingin
                        'tanggal_transaksi' => now(),
                        'catatan' => 'Transaksi otomatis dari penjemputan',
                    ]);
                    Notification::make()
                        ->title('Penjemputan diselesaikan!')
                        ->body('Transaksi otomatis telah dibuat.')
                        ->success()
                        ->send();
                });
        }
        return $actions;
    }
}
