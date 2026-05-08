<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Models\Penjemputan;
use Illuminate\Database\Eloquent\Builder;

class AdminRecentActivityWidget extends BaseWidget
{
    protected static ?string $heading = 'Jadwal Penjemputan Mendatang';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Penjemputan::query()
                    ->where('status', 'pending')
                    ->where('tanggal_penjemputan', '>=', now())
                    ->orderBy('tanggal_penjemputan', 'asc')
                    ->limit(8)
            )
            ->columns([
                TextColumn::make('nasabah.nama')
                    ->label('Nasabah')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kelompok.nama')
                    ->label('Kelompok')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_penjemputan')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('waktu_penjemputan')
                    ->label('Waktu')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
