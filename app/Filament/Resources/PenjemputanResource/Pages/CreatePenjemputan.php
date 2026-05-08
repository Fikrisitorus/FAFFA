<?php

namespace App\Filament\Resources\PenjemputanResource\Pages;

use App\Filament\Resources\PenjemputanResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class CreatePenjemputan extends CreateRecord
{
    protected static string $resource = PenjemputanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        
        // Auto-fill data berdasarkan role
        if ($user->hasRole('kelompok_nasabah')) {
            $data['kelompok_id'] = $user->kelompok_id;
            $data['nasabah_id'] = $user->nasabah?->id;
            $data['status'] = 'pending'; // Default status untuk kelompok
            
            // Set alamat otomatis dari kelompok
            $data['alamat_penjemputan'] = $user->kelompok?->lokasi ?? 'Lokasi belum diatur';
            
            // Set jadwal_penjemputan dari tanggal dan waktu yang dipilih
            if (isset($data['tanggal_penjemputan']) && isset($data['waktu_penjemputan'])) {
                $tanggal = $data['tanggal_penjemputan'];
                $waktu = $data['waktu_penjemputan'];
                $data['jadwal_penjemputan'] = $tanggal . ' ' . $waktu;
            }
            
            // Status tetap pending, akan di-assign oleh pengepul
            $data['status'] = 'pending';
        } else {
            // Untuk admin/pengepul
            $data['status'] = 'pending';
        }
        
        // Waktu selesai selalu null saat create
        $data['waktu_selesai'] = null;
        
        return $data;
    }

    protected function afterCreate(): void
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

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Berhasil')
            ->body('Penjemputan berhasil dibuat. Menunggu pengepul untuk mengambil order.')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}