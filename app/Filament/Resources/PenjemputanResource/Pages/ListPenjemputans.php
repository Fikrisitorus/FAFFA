<?php

namespace App\Filament\Resources\PenjemputanResource\Pages;

use App\Filament\Resources\PenjemputanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use App\Models\Penjemputan;

class ListPenjemputans extends ListRecords
{
    protected static string $resource = PenjemputanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // Enable polling (auto-refresh) every 10 seconds
    protected static ?string $pollingInterval = '10s';

    // Notifikasi popup jika ada penjemputan baru
    public function mount(): void
    {
        parent::mount();
        // Simpan jumlah penjemputan saat mount
        $this->oldCount = Penjemputan::count();
    }

    public function updated(): void
    {
        $newCount = Penjemputan::count();
        if (isset($this->oldCount) && $newCount > $this->oldCount) {
            Notification::make()
                ->title('Permintaan Penjemputan Baru!')
                ->body('Ada request penjemputan sampah baru dari kelompok nasabah.')
                ->success()
                ->send();
        }
        $this->oldCount = $newCount;
    }
}
