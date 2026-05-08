<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Models\LogAktivitas;
use Illuminate\Database\Eloquent\Builder;

class AdminLogAktivitasTable extends BaseWidget
{
    protected static ?string $heading = 'Log Aktivitas Terbaru';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                LogAktivitas::query()
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('activity')
                    ->label('Aktivitas')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('model_type')
                    ->label('Model')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'App\\Models\\Penjemputan' => 'warning',
                        'App\\Models\\Transaksi' => 'success',
                        'App\\Models\\Nasabah' => 'info',
                        'App\\Models\\Kelompok' => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
