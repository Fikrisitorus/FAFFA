<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use App\Models\Transaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Transaksi & Kas';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Transaksi';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nasabah_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('pengepul_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('penjemputan_id')
                    ->numeric(),
                Forms\Components\Select::make('jenis_sampah_id')
                    ->label('Jenis Sampah')
                    ->options(\App\Models\JenisSampah::pluck('nama', 'id'))
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('berat')
                    ->label('Berat (kg)')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->step(0.01),
                // Harga per kg display (dari relasi)
                Forms\Components\Placeholder::make('harga_per_kg_display')
                    ->label('Harga per kg')
                    ->content(function (callable $get) {
                        $jenisSampahId = $get('jenis_sampah_id');
                        if (!$jenisSampahId) return 'Pilih jenis sampah terlebih dahulu';
                        
                        $jenisSampah = \App\Models\JenisSampah::find($jenisSampahId);
                        if (!$jenisSampah) return 'Jenis sampah tidak ditemukan';
                        
                        return 'Rp ' . number_format((float)$jenisSampah->harga, 0, ',', '.');
                    })
                    ->reactive(),
                // Total harga display (dari perhitungan)
                Forms\Components\Placeholder::make('total_harga_display')
                    ->label('Total Harga')
                    ->content(function (callable $get) {
                        $berat = (float)($get('berat') ?: 0);
                        $jenisSampahId = $get('jenis_sampah_id');
                        
                        if (!$jenisSampahId || $berat <= 0) return 'Rp 0';
                        
                        $jenisSampah = \App\Models\JenisSampah::find($jenisSampahId);
                        if (!$jenisSampah) return 'Rp 0';
                        
                        $totalHarga = $berat * (float)$jenisSampah->harga;
                        return 'Rp ' . number_format($totalHarga, 0, ',', '.');
                    })
                    ->reactive(),
                Forms\Components\DateTimePicker::make('tanggal_transaksi')
                    ->required()
                    ->default(now()),
                Forms\Components\FileUpload::make('gambar')
                    ->label('Gambar Sampah')
                    ->image()
                    ->imageEditor()
                    ->directory('transaksi-images')
                    ->maxSize(5120)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('catatan')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nasabah.nama')
                    ->label('Nasabah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pengepul.name')
                    ->label('Pengepul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenisSampah.nama')
                    ->label('Jenis Sampah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('berat')
                    ->label('Berat (kg)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_per_kg')
                    ->label('Harga per kg')
                    ->getStateUsing(fn ($record) => $record->jenisSampah?->harga ?? 0)
                    ->money('IDR')
                    ->sortable()
                    ->description('Dari relasi jenisSampah'),
                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->getStateUsing(fn ($record) => $record->berat * ($record->jenisSampah?->harga ?? 0))
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_transaksi')
                    ->label('Tanggal Transaksi')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe_transaksi_label')
                    ->label('Tipe Transaksi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Nasabah' => 'success',
                        'Sistem (Sedekah)' => 'info',
                        'Split (Nasabah + Sistem)' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\ImageColumn::make('gambar_bukti_nasabah')
                    ->label('Bukti Nasabah')
                    ->circular()
                    ->width(50)
                    ->height(50),
                Tables\Columns\ImageColumn::make('gambar_bukti_sistem')
                    ->label('Bukti Sistem')
                    ->circular()
                    ->width(50)
                    ->height(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('nasabah')
                    ->label('Transaksi ke Nasabah')
                    ->placeholder('Semua')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
                Tables\Filters\TernaryFilter::make('sistem')
                    ->label('Transaksi ke Sistem')
                    ->placeholder('Semua')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        if ($user->hasRole('kelompok_nasabah')) {
            $query->whereHas('nasabah', function ($q) use ($user) {
                $q->where('kelompok_id', $user->kelompok_id);
            });
        } elseif ($user->hasRole('pengepul')) {
            $query->where('pengepul_id', $user->id);
        }
        return $query;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole(['admin', 'pengepul', 'kelompok_nasabah']);
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        // Hanya admin yang bisa create manual, pengepul input dari penjemputan
        return $user->hasRole('admin');
    }
    
    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user->hasRole('admin');
    }
    
    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user->hasRole('admin');
    }
}
