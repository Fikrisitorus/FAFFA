<?php

namespace App\Filament\Resources\JadwalPengepulResource\Pages;

use App\Filament\Resources\JadwalPengepulResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalPengepul extends EditRecord
{
    protected static string $resource = JadwalPengepulResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
