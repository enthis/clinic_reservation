<?php

namespace App\Filament\Resources\ReservationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model; // Import Model
use Illuminate\Support\Facades\Auth;

class DoctorNotesRelationManager extends RelationManager
{
    protected static string $relationship = 'doctorNotes'; // This must match the relationship method name in Reservation model

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('doctor_id')
                    ->relationship('doctor', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    // New logic: Disable if the authenticated user is a doctor
                    ->disabled(function (): bool {
                        return Auth::user()->hasRole('doctor');
                    })
                    // Optionally, set default to the logged-in doctor's ID if they are a doctor
                    ->default(function (): ?int {
                        $user = Auth::user();
                        if ($user->hasRole('doctor') && $user->doctor) {
                            return $user->doctor->id;
                        }
                        return null;
                    })
                    ->dehydrated(true),
                Forms\Components\Textarea::make('note_content')
                    ->required()
                    ->maxLength(65535),
            ])->columns(1); // Arrange fields in a single column
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('note_content')
            ->columns([
                Tables\Columns\TextColumn::make('doctor.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note_content')
                    ->limit(70)
                    ->wrap()
                    ->placeholder('No notes'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('doctor_id')
                    ->relationship('doctor', 'name')
                    ->label('Filter by Doctor'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn(): bool => auth()->user()->can('createDoctorNote')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn(Model $record): bool => auth()->user()->can('editDoctorNote')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(Model $record): bool => auth()->user()->can('deleteDoctorNote')),
                Tables\Actions\RestoreAction::make()
                    ->visible(fn(Model $record): bool => auth()->user()->can('editDoctorNote')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn(): bool => auth()->user()->can('deleteDoctorNote')),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(fn(): bool => auth()->user()->can('editDoctorNote')),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
