<?php

namespace App\Filament\Resources\JadwalPengepulResource\Pages;

use App\Filament\Resources\JadwalPengepulResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalPengepuls extends ListRecords
{
    protected static string $resource = JadwalPengepulResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
