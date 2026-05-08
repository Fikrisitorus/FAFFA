<?php

namespace App\Filament\Resources\JadwalPengepulResource\Pages;

use App\Filament\Resources\JadwalPengepulResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateJadwalPengepul extends CreateRecord
{
    protected static string $resource = JadwalPengepulResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-set pengepul_id untuk pengepul
        if (Auth::user()->hasRole('pengepul')) {
            $data['pengepul_id'] = Auth::id();
        }

        // Set default values
        $data['kapasitas_terisi'] = 0;
        $data['status'] = 'available';

        return $data;
    }
}
