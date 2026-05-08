<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationLabel = 'Pengaturan Umum';
    protected static ?string $pluralModelLabel = 'Pengaturan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('key')
                ->label('Nama Pengaturan')
                ->required()
                ->unique(ignoreRecord: true)
                ->disabled(fn ($context) => $context === 'edit'),

            Forms\Components\TextInput::make('group')
                ->label('Grup Pengaturan')
                ->nullable()
                ->helperText('Kategori pengaturan seperti umum, notifikasi, sedekah, dll.'),

            Forms\Components\Textarea::make('value')
                ->label('Isi Pengaturan')
                ->rows(4)
                ->required(),

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')
                ->rows(3)
                ->nullable()
                ->helperText('Penjelasan singkat tentang fungsi pengaturan ini.'),

            Forms\Components\Select::make('type')
                ->label('Tipe Data')
                ->options([
                    'string' => 'Teks Pendek',
                    'number' => 'Angka',
                    'boolean' => 'Ya/Tidak',
                    'json' => 'Data Terstruktur (JSON)',
                    'text' => 'Teks Panjang',
                ])
                ->required(),

            Forms\Components\Toggle::make('is_public')
                ->label('Tampilkan ke Publik?')
                ->helperText('Jika aktif, pengaturan ini dapat dilihat oleh semua pengguna.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('key')->label('Nama Pengaturan')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('group')->label('Grup')->sortable(),
            Tables\Columns\TextColumn::make('value')->label('Kategori')->limit(50)->searchable(),
            Tables\Columns\TextColumn::make('description')->label('Deskripsi')->limit(80),
            Tables\Columns\IconColumn::make('is_public')
                ->label('Tampil ke Publik')
                ->boolean()
                ->sortable(),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
