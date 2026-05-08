<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjemputanResource\Pages;
use App\Filament\Resources\PenjemputanResource\RelationManagers;
use App\Models\Penjemputan;
use App\Models\JenisSampah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PenjemputanResource extends Resource
{
    protected static ?string $model = Penjemputan::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Operasional Pengepul';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Penjemputan';

    public static function form(Form $form): Form
    {
        $isKelompok = Auth::user()->hasRole('kelompok_nasabah');
        $isPengepul = Auth::user()->hasRole('pengepul');

        return $form
            ->schema([
                // Informasi Penjemputan untuk kelompok nasabah
                Forms\Components\Section::make('Informasi Penjemputan')
                    ->schema([
                        // Alamat otomatis dari kelompok
                        Forms\Components\Placeholder::make('alamat_otomatis')
                            ->label('Alamat Penjemputan')
                            ->content(fn () => Auth::user()->kelompok?->lokasi ?? 'Lokasi belum diatur')
                            ->helperText('Alamat mengikuti lokasi kelompok Anda')
                            ->columnSpanFull()
                            ->visible(fn () => $isKelompok),
                        
                        // Pilih tanggal penjemputan
                        Forms\Components\DatePicker::make('tanggal_penjemputan')
                            ->label('Tanggal Penjemputan')
                            ->required()
                            ->minDate(now()->toDateString())
                            ->helperText('Pilih tanggal penjemputan (minimal hari ini)')
                            ->visible(fn () => $isKelompok),
                        
                        // Pilih waktu penjemputan
                        Forms\Components\TimePicker::make('waktu_penjemputan')
                            ->label('Waktu Penjemputan')
                            ->required()
                            ->helperText('Pilih waktu penjemputan yang diinginkan')
                            ->visible(fn () => $isKelompok),
                        
                        // Tombol untuk melihat jadwal pengepul
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('lihat_jadwal_pengepul')
                                ->label('Lihat Jadwal Pengepul')
                                ->icon('heroicon-o-calendar')
                                ->color('info')
                                ->size('sm')
                                ->modalHeading('Jadwal Pengepul Tersedia')
                                ->modalDescription('Berikut adalah jadwal pengepul yang tersedia untuk penjemputan sampah')
                                ->modalContent(function () {
                                    $jadwalPengepul = \App\Models\JadwalPengepul::with(['pengepul'])
                                        ->orderBy('hari')
                                        ->orderBy('waktu_mulai')
                                        ->get();
                                    
                                    if ($jadwalPengepul->isEmpty()) {
                                        return view('filament.components.jadwal-pengepul-empty');
                                    }
                                    
                                    return view('filament.components.jadwal-pengepul-list', [
                                        'jadwalPengepul' => $jadwalPengepul
                                    ]);
                                })
                                ->modalSubmitAction(false)
                                ->modalCancelActionLabel('Tutup')
                                ->visible(fn () => $isKelompok),
                        ])
                        ->columnSpanFull()
                        ->visible(fn () => $isKelompok),
                    ])
                    ->columns(2)
                    ->visible(fn () => $isKelompok),

                // Informasi Penjemputan untuk admin/pengepul
                Forms\Components\Section::make('Informasi Penjemputan')
                    ->schema([
                        Forms\Components\FileUpload::make('gambar')
                            ->label('Foto Penjemputan')
                            ->image()
                            ->imageEditor()
                            ->maxSize(5120)
                            ->helperText('Upload foto sampah yang akan dijemput')
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('alamat_penjemputan')
                            ->label('Alamat Penjemputan')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('pengepul_id')
                            ->label('Pengepul')
                            ->relationship('pengepul', 'name')
                            ->searchable()
                            ->required()
                            ->visible(fn () => !$isPengepul),
                        
                        Forms\Components\DateTimePicker::make('jadwal_penjemputan')
                            ->label('Jadwal Penjemputan')
                            ->required()
                            ->minDate(now())
                            ->maxDate(now()->addMonths(3))
                            ->helperText('Pilih tanggal dan waktu penjemputan')
                            ->visible(fn () => !$isPengepul),
                    ])
                    ->columns(2)
                    ->visible(fn () => !$isKelompok),

                // Hidden fields untuk sistem otomatis
                Forms\Components\Hidden::make('status')
                    ->default('pending'),
                Forms\Components\Hidden::make('waktu_selesai')
                    ->default(null),

                // Detail Sampah - UNIFIED untuk semua role
                Forms\Components\Section::make('Detail Sampah')
                    ->schema([
                        Forms\Components\Repeater::make('sampah_details')
                            ->label('Detail Sampah')
                            ->relationship('sampahDetails')
                            ->schema([
                                Forms\Components\Select::make('jenis_sampah_id')
                                    ->label('Jenis Sampah')
                                    ->options(function (callable $get) {
                                        $allJenisSampah = JenisSampah::pluck('nama', 'id');
                                        $currentItems = $get('../../sampah_details') ?? [];
                                        $currentJenisId = $get('jenis_sampah_id');
                                        
                                        // Ambil ID jenis sampah yang sudah dipilih di item lain
                                        $selectedJenisSampahIds = [];
                                        foreach ($currentItems as $item) {
                                            if (isset($item['jenis_sampah_id']) && 
                                                $item['jenis_sampah_id'] && 
                                                $item['jenis_sampah_id'] != $currentJenisId) {
                                                $selectedJenisSampahIds[] = $item['jenis_sampah_id'];
                                            }
                                        }
                                        
                                        // Filter jenis sampah yang belum dipilih
                                        $availableJenisSampah = $allJenisSampah->filter(function ($nama, $id) use ($selectedJenisSampahIds) {
                                            return !in_array($id, $selectedJenisSampahIds);
                                        });
                                        
                                        return $availableJenisSampah;
                                    })
                                    ->required()
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            $jenisSampah = \App\Models\JenisSampah::find($state);
                                            if ($jenisSampah) {
                                                // Tidak perlu set harga_per_kg lagi karena akan diambil dari relasi
                                            }
                                        }
                                    }),
                                Forms\Components\TextInput::make('berat_nasabah')
                                    ->label('Berat Nasabah (kg)')
                                    ->numeric()
                                    ->default(0)
                                    ->step(0.01)
                                    ->minValue(0.01)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $berat = (float)($state ?: 0);
                                        $jenisSampahId = $get('jenis_sampah_id');
                                        if ($jenisSampahId && $berat > 0) {
                                            $jenisSampah = \App\Models\JenisSampah::find($jenisSampahId);
                                            if ($jenisSampah) {
                                                // Tidak perlu set harga_per_kg dan total_harga lagi karena akan diambil dari relasi
                                            }
                                        }
                                    }),
                                // Harga per kg display (hanya untuk admin/pengepul)
                                Forms\Components\Placeholder::make('harga_per_kg_display')
                                    ->label('Harga per kg')
                                    ->content(function (callable $get) {
                                        $jenisSampahId = $get('jenis_sampah_id');
                                        if (!$jenisSampahId) return 'Pilih jenis sampah terlebih dahulu';
                                        
                                        $jenisSampah = \App\Models\JenisSampah::find($jenisSampahId);
                                        if (!$jenisSampah) return 'Jenis sampah tidak ditemukan';
                                        
                                        return 'Rp ' . number_format((float)$jenisSampah->harga, 0, ',', '.');
                                    })
                                    ->reactive()
                                    ->visible(fn () => !$isKelompok),
                                // Total harga display (hanya untuk admin/pengepul)
                                Forms\Components\Placeholder::make('total_harga_display')
                                    ->label('Total Harga')
                                    ->content(function (callable $get) {
                                        $berat = $get('berat_nasabah') ?: 0;
                                        $jenisSampahId = $get('jenis_sampah_id');
                                        
                                        if (!$jenisSampahId) return 'Pilih jenis sampah terlebih dahulu';
                                        
                                        $jenisSampah = \App\Models\JenisSampah::find($jenisSampahId);
                                        if (!$jenisSampah) return 'Jenis sampah tidak ditemukan';
                                        
                                        $total = $berat * (float)$jenisSampah->harga;
                                        return 'Rp ' . number_format($total, 0, ',', '.');
                                    })
                                    ->reactive()
                                    ->visible(fn () => !$isKelompok),
                                Forms\Components\Textarea::make('catatan')
                                    ->label('Catatan')
                                    ->maxLength(255),
                            ])
                            ->columns($isKelompok ? 3 : 5)
                            ->defaultItems(1)
                            ->reorderable(false)
                            ->columnSpanFull()
                    ])
                    ->collapsible(),

                // Catatan - untuk semua role (di bawah)
                Forms\Components\Section::make('Catatan Tambahan')
                    ->schema([
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Penjemputan')
                            ->maxLength(500)
                            ->placeholder('Tambahkan catatan khusus jika diperlukan...')
                            ->helperText('Catatan tambahan untuk pengepul (opsional)')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                // Gambar untuk kelompok nasabah (di bawah)
                Forms\Components\Section::make('Foto Sampah')
                    ->schema([
                        Forms\Components\FileUpload::make('gambar')
                            ->label('Foto Sampah')
                            ->image()
                            ->imageEditor()
                            ->maxSize(5120)
                            ->helperText('Upload foto sampah yang akan dijemput (opsional)')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->visible(fn () => $isKelompok),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isKelompok = Auth::user()->hasRole('kelompok_nasabah');
        $isPengepul = Auth::user()->hasRole('pengepul');
        $isAdmin = Auth::user()->hasRole('admin');

        return $table
            ->columns([
                // Foto - untuk semua role (mobile: hidden)
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Foto')
                    ->circular()
                    ->size(40)
                    ->visible(fn () => !$isKelompok)
                    ->toggleable(isToggledHiddenByDefault: true),

                // Kolom untuk kelompok nasabah
                Tables\Columns\TextColumn::make('pengepul.name')
                    ->label('Pengepul')
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => $isKelompok)
                    ->default('Belum ditugaskan'),

                // Kolom untuk admin/pengepul (mobile: hidden)
                Tables\Columns\TextColumn::make('nasabah.nama')
                    ->label('Nasabah')
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => !$isKelompok)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('kelompok.nama')
                    ->label('Kelompok')
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => $isAdmin)
                    ->toggleable(isToggledHiddenByDefault: true),

                // Alamat - untuk semua role (mobile: hidden)
                Tables\Columns\TextColumn::make('alamat_penjemputan')
                    ->label('Alamat')
                    ->limit(20)
                    ->searchable()
                    ->visible(fn () => !$isKelompok)
                    ->toggleable(isToggledHiddenByDefault: true),

                // Jadwal - untuk semua role (mobile: priority)
                Tables\Columns\TextColumn::make('jadwal_penjemputan')
                    ->label('Jadwal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->searchable(),

                // Status - untuk semua role (mobile: priority)
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'assigned',
                        'primary' => 'on_progress',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'pending' => 'Menunggu',
                        'assigned' => 'Ditugaskan',
                        'on_progress' => 'Sedang Dijemput',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => ucfirst($state),
                    })
                    ->searchable(),

                // Total harga - hanya untuk admin/pengepul (mobile: hidden)
                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->visible(fn () => !$isKelompok)
                    ->toggleable(isToggledHiddenByDefault: true),

                // Waktu selesai - hanya untuk admin/pengepul (mobile: hidden)
                Tables\Columns\TextColumn::make('waktu_selesai')
                    ->label('Waktu Selesai')
                    ->dateTime('d/m/Y H:i')
                    ->visible(fn () => !$isKelompok)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'assigned' => 'Ditugaskan',
                        'on_progress' => 'Sedang Dijemput',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
                Tables\Filters\Filter::make('jadwal_penjemputan')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_dari')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('tanggal_sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['tanggal_dari'],
                                fn ($query) => $query->whereDate('jadwal_penjemputan', '>=', $data['tanggal_dari'])
                            )
                            ->when(
                                $data['tanggal_sampai'],
                                fn ($query) => $query->whereDate('jadwal_penjemputan', '<=', $data['tanggal_sampai'])
                            );
                    }),
            ])
            ->actions([
                // Edit action - sesuai role
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => 
                        $isAdmin || 
                        ($isKelompok && $record->status === 'pending') ||
                        ($isPengepul && $record->pengepul_id === Auth::user()->id)
                    ),
                
                // Delete action - hanya admin
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => $isAdmin),

                // View action untuk kelompok nasabah
                Tables\Actions\ViewAction::make()
                    ->visible(fn () => $isKelompok),
            ])
            ->bulkActions([
                // Bulk actions - hanya admin
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
                ->visible(fn () => $isAdmin),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListPenjemputans::route('/'),
            'create' => Pages\CreatePenjemputan::route('/create'),
            'edit' => Pages\EditPenjemputan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();
        
        if (!$user) {
            return $query->where('id', 0); // Tidak ada data jika tidak login
        }
        
        if ($user->hasRole('kelompok_nasabah')) {
            // Kelompok nasabah hanya lihat penjemputan milik kelompoknya
            $query->where('kelompok_id', $user->kelompok_id);
        } elseif ($user->hasRole('pengepul')) {
            // Pengepul: lihat semua pending + request miliknya sendiri
            $query->where(function ($q) use ($user) {
                $q->where('status', 'pending')
                  ->orWhere('pengepul_id', $user->id);
            });
        } elseif ($user->hasRole('admin')) {
            // Admin lihat semua data
            return $query;
        } else {
            // Role lain tidak bisa akses
            return $query->where('id', 0);
        }
        
        return $query;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->hasRole(['admin', 'pengepul', 'kelompok_nasabah']);
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasRole('kelompok_nasabah');
    }
    public static function canEdit(
        $record
    ): bool {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        if ($user->hasRole('kelompok_nasabah')) {
            // Kelompok nasabah hanya bisa edit penjemputan pending milik kelompoknya
            return $record->status === 'pending' && $record->kelompok_id === $user->kelompok_id;
        }
        
        if ($user->hasRole('pengepul')) {
            // Pengepul hanya bisa edit penjemputan yang dia ambil
            return $record->pengepul_id === $user->id;
        }
        
        if ($user->hasRole('admin')) {
            // Admin bisa edit semua
            return true;
        }
        
        return false;
    }
    public static function canDelete(
        $record
    ): bool {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        if ($user->hasRole('admin')) {
            // Admin bisa delete semua
            return true;
        }
        
        // Role lain tidak bisa delete
        return false;
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        return $user->hasRole(['admin', 'pengepul', 'kelompok_nasabah']);
    }

    public static function canView($record): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        if ($user->hasRole('admin')) {
            return true;
        }
        
        if ($user->hasRole('kelompok_nasabah')) {
            return $record->kelompok_id === $user->kelompok_id;
        }
        
        if ($user->hasRole('pengepul')) {
            return $record->status === 'pending' || $record->pengepul_id === $user->id;
        }
        
        return false;
    }



    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();
        
        // Jika pengepul mengubah status menjadi completed, set waktu_selesai
        if ($user->hasRole('pengepul') && isset($data['status']) && $data['status'] === 'completed') {
            $data['waktu_selesai'] = now();
        }
        
        return $data;
    }
    

}
