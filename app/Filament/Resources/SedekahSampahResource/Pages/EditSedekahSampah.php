<?php

namespace App\Filament\Resources\SedekahSampahResource\Pages;

use App\Filament\Resources\SedekahSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSedekahSampah extends EditRecord
{
    protected static string $resource = SedekahSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
