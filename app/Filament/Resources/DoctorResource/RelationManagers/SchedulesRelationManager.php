<?php

namespace App\Filament\Resources\DoctorResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model; // Import Model
use Illuminate\Support\Facades\Auth; // Import Auth facade

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('day_of_week')
                    ->label('Day of Week')
                    ->options([
                        0 => 'Sunday',
                        1 => 'Monday',
                        2 => 'Tuesday',
                        3 => 'Wednesday',
                        4 => 'Thursday',
                        5 => 'Friday',
                        6 => 'Saturday',
                    ])
                    ->required()
                    ->native(false)
                    // Disable if the authenticated user is a doctor
                    ->disabled(function (): bool {
                        return Auth::user()->hasRole('doctor');
                    }),
                Forms\Components\TimePicker::make('start_time')
                    ->required()
                    ->seconds(false)
                    ->displayFormat('H:i')
                    // Disable if the authenticated user is a doctor
                    ->disabled(function (): bool {
                        return Auth::user()->hasRole('doctor');
                    }),
                Forms\Components\TimePicker::make('end_time')
                    ->required()
                    ->seconds(false)
                    ->displayFormat('H:i')
                    ->afterOrEqual('start_time')
                    // Disable if the authenticated user is a doctor
                    ->disabled(function (): bool {
                        return Auth::user()->hasRole('doctor');
                    }),
                Forms\Components\Toggle::make('is_available')
                    ->label('Available for Booking')
                    ->default(true)
                    // Disable if the authenticated user is a doctor
                    ->disabled(function (): bool {
                        return Auth::user()->hasRole('doctor');
                    }),
                Forms\Components\Textarea::make('notes')
                    ->nullable()
                    ->maxLength(65535)
                    ->columnSpanFull()
                    // Disable if the authenticated user is a doctor
                    ->disabled(function (): bool {
                        return Auth::user()->hasRole('doctor');
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('day_of_week')
            ->columns([
                Tables\Columns\TextColumn::make('day_name') // Display day name using accessor
                    ->label('Day')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_available')
                    ->boolean()
                    ->label('Available'),
                Tables\Columns\TextColumn::make('notes')
                    ->limit(50)
                    ->wrap()
                    ->placeholder('No notes'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('day_of_week')
                    ->label('Filter by Day')
                    ->options([
                        0 => 'Sunday',
                        1 => 'Monday',
                        2 => 'Tuesday',
                        3 => 'Wednesday',
                        4 => 'Thursday',
                        5 => 'Friday',
                        6 => 'Saturday',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn (): bool => auth()->user()->can('createDoctorSchedule')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (Model $record): bool => auth()->user()->can('editDoctorSchedule')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Model $record): bool => auth()->user()->can('deleteDoctorSchedule')),
                Tables\Actions\RestoreAction::make()
                    ->visible(fn (Model $record): bool => auth()->user()->can('editDoctorSchedule')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()->can('deleteDoctorSchedule')),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(fn (): bool => auth()->user()->can('editDoctorSchedule')),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}

