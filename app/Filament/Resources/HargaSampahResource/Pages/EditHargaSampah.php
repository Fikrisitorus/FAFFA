<?php

namespace App\Filament\Resources\HargaSampahResource\Pages;

use App\Filament\Resources\HargaSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHargaSampah extends EditRecord
{
    protected static string $resource = HargaSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
