<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisSampahResource\Pages;
use App\Filament\Resources\JenisSampahResource\RelationManagers;
use App\Models\JenisSampah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class JenisSampahResource extends Resource
{
    protected static ?string $model = JenisSampah::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Data Sampah';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Jenis Sampah';
    protected static ?string $pluralModelLabel = 'Jenis';

    public static function canCreate(): bool
    {
        if (!Auth::check())
            return false;
        $isKelompok = Auth::user()->hasRole('kelompok_nasabah');
        $isPengepul = Auth::user()->hasRole('pengepul');
        return !$isKelompok && !$isPengepul;
    }

    public static function canEdit($record): bool
    {
        if (!Auth::check())
            return false;
        $isKelompok = Auth::user()->hasRole('kelompok_nasabah');
        $isPengepul = Auth::user()->hasRole('pengepul');
        return !$isKelompok && !$isPengepul;
    }

    public static function canDelete($record): bool
    {
        if (!Auth::check())
            return false;
        $isKelompok = Auth::user()->hasRole('kelompok_nasabah');
        $isPengepul = Auth::user()->hasRole('pengepul');
        return !$isKelompok && !$isPengepul;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kategori')
                    ->required(),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('satuan')
                    ->required(),
                Forms\Components\TextInput::make('harga')
                    ->label('Harga per kg')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->step(0.01),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isKelompok = false;
        $isPengepul = false;

        if (Auth::check()) {
            $isKelompok = Auth::user()->hasRole('kelompok_nasabah');
            $isPengepul = Auth::user()->hasRole('pengepul');
        }

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori'),
                Tables\Columns\TextColumn::make('satuan'),
                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga per kg')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn() => !$isKelompok && !$isPengepul),
                Tables\Actions\ViewAction::make()
                    ->visible(fn() => $isKelompok || $isPengepul),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => !$isKelompok && !$isPengepul),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJenisSampahs::route('/'),
            'create' => Pages\CreateJenisSampah::route('/create'),
            'edit' => Pages\EditJenisSampah::route('/{record}/edit'),
        ];
    }
}
