<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalPengepulResource\Pages;
use App\Models\JadwalPengepul;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class JadwalPengepulResource extends Resource
{
    protected static ?string $model = JadwalPengepul::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Jadwal Pengepul';

    protected static ?string $navigationGroup = 'Operasional Pengepul';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Jadwal Pengepul';

    protected static ?string $pluralModelLabel = 'Jadwal Pengepul';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pengepul_id')
                    ->label('Pengepul')
                    ->relationship('pengepul', 'name')
                    ->disabled(fn () => Auth::user()->hasRole('pengepul'))
                    ->default(fn () => Auth::user()->hasRole('pengepul') ? Auth::id() : null)
                    ->required()
                    ->dehydrated(),

                Forms\Components\Select::make('hari')
                    ->label('Hari')
                    ->options([
                        'senin' => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu' => 'Rabu',
                        'kamis' => 'Kamis',
                        'jumat' => 'Jumat',
                        'sabtu' => 'Sabtu',
                        'minggu' => 'Minggu',
                    ])
                    ->required(),

                Forms\Components\TimePicker::make('waktu_mulai')
                    ->label('Waktu Mulai')
                    ->required()
                    ->seconds(false)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('waktu_selesai', null);
                    }),

                Forms\Components\TimePicker::make('waktu_selesai')
                    ->label('Waktu Selesai')
                    ->required()
                    ->seconds(false)
                    ->after('waktu_mulai')
                    ->helperText('Waktu selesai harus setelah waktu mulai')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $waktuMulai = $get('waktu_mulai');
                        if ($waktuMulai && $state) {
                            $mulai = \Carbon\Carbon::parse($waktuMulai);
                            $selesai = \Carbon\Carbon::parse($state);
                            if ($selesai->lte($mulai)) {
                                $set('waktu_selesai', null);
                                // Tampilkan notifikasi error
                                \Filament\Notifications\Notification::make()
                                    ->title('Error')
                                    ->body('Waktu selesai harus setelah waktu mulai.')
                                    ->danger()
                                    ->send();
                            }
                        }
                    }),

                // Forms\Components\TextInput::make('lokasi')
                //     ->label('Lokasi')
                //     ->required()
                //     ->maxLength(255),

                // Forms\Components\TextInput::make('kapasitas_maksimal')
                //     ->label('Kapasitas Maksimal')
                //     ->numeric()
                //     ->default(10)
                //     ->minValue(1)
                //     ->required(),

                Forms\Components\Textarea::make('catatan')
                    ->label('Catatan')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengepul.name')
                    ->label('Pengepul')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('hari')
                    ->label('Hari')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->label('Waktu Mulai')
                    ->time()
                    ->sortable(),

                Tables\Columns\TextColumn::make('waktu_selesai')
                    ->label('Waktu Selesai')
                    ->time()
                    ->sortable(),

                Tables\Columns\TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->searchable()
                    ->sortable(),

                // Tables\Columns\TextColumn::make('kapasitas_terisi')
                //     ->label('Terisi')
                //     ->numeric()
                //     ->sortable(),

                // Tables\Columns\TextColumn::make('kapasitas_maksimal')
                //     ->label('Maksimal')
                //     ->numeric()
                //     ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'available',
                        'warning' => 'full',
                        'info' => 'completed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'available' => 'Tersedia',
                        'full' => 'Penuh',
                        'completed' => 'Selesai',
                        default => ucfirst($state),
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('hari')
                    ->label('Hari')
                    ->options([
                        'senin' => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu' => 'Rabu',
                        'kamis' => 'Kamis',
                        'jumat' => 'Jumat',
                        'sabtu' => 'Sabtu',
                        'minggu' => 'Minggu',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'full' => 'Penuh',
                        'completed' => 'Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListJadwalPengepul::route('/'),
            'create' => Pages\CreateJadwalPengepul::route('/create'),
            'edit' => Pages\EditJadwalPengepul::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasAnyRole(['admin', 'pengepul']);
    }
}
