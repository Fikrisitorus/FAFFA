<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelompokResource\Pages;
use App\Models\Kelompok;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class KelompokResource extends Resource
{
    protected static ?string $model = Kelompok::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?string $navigationLabel = 'Kelompok';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama')->required()->label('Nama Kelompok'),
            Forms\Components\TextInput::make('kode')->required()->unique(ignoreRecord: true)->label('Kode Kelompok'),
            Forms\Components\Textarea::make('deskripsi')->label('Deskripsi'),
            Forms\Components\TextInput::make('koordinator')->label('Koordinator'),
            Forms\Components\TextInput::make('lokasi')->label('Lokasi'),

            Forms\Components\Repeater::make('jadwal_rutin')
                ->schema([
                    Forms\Components\TextInput::make('hari')->label('Hari')->required(),
                    Forms\Components\TextInput::make('jam')->label('Jam')->required(),
                ])
                ->label('Jadwal Rutin')
                ->collapsible()
                ->default([]),


            Forms\Components\Toggle::make('is_active')->label('Aktif?')->default(true),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nama')->searchable()->label('Kelompok'),
            Tables\Columns\TextColumn::make('kode')->sortable(),
            Tables\Columns\TextColumn::make('koordinator'),
            Tables\Columns\TextColumn::make('jadwal_rutin')
                ->label('Jadwal')
                ->formatStateUsing(function ($state) {
                    if (!is_array($state)) {
                        return '-';
                    }
                    return collect($state)
                        ->map(fn ($item) => "{$item['hari']} ({$item['jam']})")
                        ->implode(', ');
                }),

            Tables\Columns\TextColumn::make('total_nasabah')->label('Jumlah Nasabah'),
            Tables\Columns\TextColumn::make('total_saldo')->label('Total Saldo')->money('IDR'),
            Tables\Columns\IconColumn::make('is_active')->boolean()->label('Aktif'),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelompoks::route('/'),
            'create' => Pages\CreateKelompok::route('/create'),
            'edit' => Pages\EditKelompok::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
