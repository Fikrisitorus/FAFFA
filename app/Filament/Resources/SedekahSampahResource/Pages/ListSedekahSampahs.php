<?php

namespace App\Filament\Resources\SedekahSampahResource\Pages;

use App\Filament\Resources\SedekahSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSedekahSampahs extends ListRecords
{
    protected static string $resource = SedekahSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
