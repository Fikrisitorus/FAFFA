<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SedekahSampahResource\Pages;
use App\Models\SedekahSampah;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SedekahSampahResource extends \Filament\Resources\Resource
{
    protected static ?string $model = SedekahSampah::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationGroup = 'Transaksi & Kas';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Sedekah Sampah';
    protected static ?string $pluralModelLabel = 'Sedekah Sampah';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Sedekah')
                    ->columns(2)
                    ->schema([
                        Select::make('nasabah_id')
                            ->label('Nasabah')
                            ->relationship('nasabah', 'nama')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Boleh dikosongkan jika sedekah tidak terkait nasabah tertentu.'),

                        Select::make('kelompok_id')
                            ->label('Kelompok')
                            ->relationship('kelompok', 'nama')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Opsional. Pilih jika sedekah berasal dari kelompok.'),

                        // Select::make('transaksi_id')
                        //     ->label('Transaksi Terkait')
                        //     ->relationship('transaksi', 'kode_transaksi')
                        //     ->searchable()
                        //     ->preload()
                        //     ->nullable()
                        //     ->helperText('Opsional. Hubungkan ke transaksi sumber dana.'),

                        TextInput::make('jumlah_sedekah')
                            ->label('Jumlah Sedekah (Rp)')
                            ->numeric()
                            ->required()
                            ->rule('gte:0'),

                        TextInput::make('persentase')
                            ->label('Persentase (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->nullable()
                            ->helperText('Persentase dari nilai transaksi (jika relevan). Boleh kosong.'),

                        DatePicker::make('tanggal_sedekah')
                            ->label('Tanggal Sedekah')
                            ->required()
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->closeOnDateSelection(),
                    ]),

                Section::make('Periode Rekap')
                    ->columns(3)
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Select::make('bulan_sedekah')
                            ->label('Bulan')
                            ->options(self::getMonthOptions())
                            ->nullable(),
                        TextInput::make('tahun_sedekah')
                            ->label('Tahun')
                            ->numeric()
                            ->minLength(4)
                            ->maxLength(4)
                            ->nullable(),
                        Select::make('status')
                            ->label('Status')
                            ->options(SedekahSampah::getStatusOptions())
                            ->default(SedekahSampah::STATUS_PENDING)
                            ->required(),
                    ]),

                Section::make('Keterangan')
                    ->schema([
                        Textarea::make('keterangan')
                            ->label('Catatan / Keterangan')
                            ->rows(3)
                            ->nullable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nasabah.nama')
                    ->label('Nasabah')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('kelompok.nama')
                    ->label('Kelompok')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                // TextColumn::make('transaksi.kode_transaksi')
                //     ->label('Transaksi')
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true)
                //     ->searchable(),

                TextColumn::make('formatted_jumlah_sedekah')
                    ->label('Jumlah Sedekah')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('jumlah_sedekah', $direction);
                    }),

                TextColumn::make('persentase')
                    ->label('%')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state !== null ? rtrim(rtrim(number_format($state, 2, ',', '.'), '0'), ',') . '%' : '-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('tanggal_sedekah')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => SedekahSampah::STATUS_PENDING,
                        'success' => SedekahSampah::STATUS_APPROVED,
                        'primary' => SedekahSampah::STATUS_USED,
                    ])
                    ->formatStateUsing(fn ($state) => SedekahSampah::getStatusOptions()[$state] ?? $state),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(SedekahSampah::getStatusOptions()),

                SelectFilter::make('bulan_sedekah')
                    ->label('Bulan')
                    ->options(self::getMonthOptions()),

                Filter::make('tanggal_range')
                    ->form([
                        Forms\Components\DatePicker::make('dari'),
                        Forms\Components\DatePicker::make('sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari'] ?? null, fn ($q, $date) => $q->whereDate('tanggal_sedekah', '>=', $date))
                            ->when($data['sampai'] ?? null, fn ($q, $date) => $q->whereDate('tanggal_sedekah', '<=', $date));
                    }),

                SelectFilter::make('nasabah_id')
                    ->label('Nasabah')
                    ->relationship('nasabah', 'nama')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('kelompok_id')
                    ->label('Kelompok')
                    ->relationship('kelompok', 'nama')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Setujui (Approve)')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->action(function (array $records): void {
                            SedekahSampah::whereIn('id', $records)->update(['status' => SedekahSampah::STATUS_APPROVED]);
                            $this->notify('success', 'Sedekah berhasil disetujui.');
                        }),
                    Tables\Actions\BulkAction::make('markUsed')
                        ->label('Tandai Sudah Digunakan')
                        ->icon('heroicon-o-currency-dollar')
                        ->requiresConfirmation()
                        ->action(function (array $records): void {
                            SedekahSampah::whereIn('id', $records)->update(['status' => SedekahSampah::STATUS_USED]);
                            $this->notify('success', 'Sedekah berhasil ditandai sudah digunakan.');
                        }),
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
            'index' => Pages\ListSedekahSampahs::route('/'),
            'create' => Pages\CreateSedekahSampah::route('/create'),
            'edit' => Pages\EditSedekahSampah::route('/{record}/edit'),
        ];
    }

    public static function getMonthOptions(): array
    {
        return [
            '1'  => 'Januari',
            '2'  => 'Februari',
            '3'  => 'Maret',
            '4'  => 'April',
            '5'  => 'Mei',
            '6'  => 'Juni',
            '7'  => 'Juli',
            '8'  => 'Agustus',
            '9'  => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
    }
}

/* ---------------------------------------------------------------------
 |  PAGES (nested namespace)
 * -------------------------------------------------------------------- */

namespace App\Filament\Resources\SedekahSampahResource\Pages;

use App\Filament\Resources\SedekahSampahResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;

class ListSedekahSampahs extends ListRecords
{
    protected static string $resource = SedekahSampahResource::class;
}

class CreateSedekahSampah extends CreateRecord
{
    protected static string $resource = SedekahSampahResource::class;
}

class EditSedekahSampah extends EditRecord
{
    protected static string $resource = SedekahSampahResource::class;
}
