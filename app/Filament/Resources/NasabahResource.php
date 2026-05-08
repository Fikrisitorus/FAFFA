<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NasabahResource\Pages;
use App\Models\Nasabah;
use App\Models\Kelompok;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NasabahResource extends Resource
{
    protected static ?string $model = Nasabah::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Nasabah';
    protected static ?string $pluralModelLabel = 'Nasabah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User Akun (Opsional)')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->placeholder('Pilih User Akun (opsional)')
                    ->nullable(),

                Forms\Components\TextInput::make('kode_nasabah')
                    ->label('Kode Nasabah')
                    ->maxLength(255)
                    ->default(fn () => 'NSB' . str_pad(Nasabah::max('id') + 1, 4, '0', STR_PAD_LEFT))
                    ->disabledOn('edit'),

                Forms\Components\TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255)
                    ->nullable(),

                Forms\Components\TextInput::make('phone')
                    ->label('No. Telepon')
                    ->tel()
                    ->required()
                    ->maxLength(20),

                Forms\Components\Textarea::make('address')
                    ->label('Alamat')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('kelompok_id')
                    ->label('Kelompok')
                    ->options(Kelompok::pluck('nama', 'id'))
                    ->searchable()
                    ->nullable(),

                Forms\Components\DatePicker::make('tanggal_bergabung')
                    ->label('Tanggal Bergabung')
                    ->required()
                    ->default(now()),

                Forms\Components\TextInput::make('saldo')
                    ->label('Saldo')
                    ->numeric()
                    ->default(0.00)
                    ->disabled(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif?')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_nasabah')
                    ->label('Kode')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('No. Telepon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelompok.nama')
                    ->label('Kelompok')
                    ->default('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_bergabung')
                    ->label('Tanggal Bergabung')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('saldo')
                    ->label('Saldo')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelompok_id')
                    ->label('Filter Kelompok')
                    ->options(Kelompok::pluck('nama', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNasabahs::route('/'),
            'create' => Pages\CreateNasabah::route('/create'),
            'edit' => Pages\EditNasabah::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
