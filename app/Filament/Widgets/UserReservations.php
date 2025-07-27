<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserReservations extends BaseWidget
{
    protected static ?string $heading = 'Your Reservations';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query($this->getUserReservationsQuery())
            ->columns([
                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('scheduled_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_time')
                    ->label('Time'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary',
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->sortable(),
            ]);
    }

    protected function getUserReservationsQuery(): Builder
    {
        return Reservation::query()
            ->with(['doctor', 'service']) // eager load relationships
            ->where('user_id', Auth::id())
            ->latest('scheduled_date');
    }
}
