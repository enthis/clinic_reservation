<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorNoteResource\Pages;
use App\Filament\Resources\DoctorNoteResource\RelationManagers;
use App\Models\DoctorNote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model; // Import Model
use Illuminate\Support\Facades\Auth; // Import Auth facade

class DoctorNoteResource extends Resource
{
    protected static ?string $model = DoctorNote::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('reservation_id')
                    ->relationship('reservation', 'id')
                    // Updated: Display reservation ID, customer name, and scheduled date
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Reservation #{$record->id} - {$record->user->name} ({$record->scheduled_date->format('M d, Y')})")
                    ->required()
                    ->searchable()
                    ->preload()
                    // Disable if the authenticated user is a doctor (cannot change reservation)
                    ->disabled(fn (): bool => Auth::user()->hasRole('doctor')),
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
                        $user = auth()->user(); // Use auth()->user() for consistency
                        if ($user->hasRole('doctor') && $user->doctor) {
                            return $user->doctor->id;
                        }
                        return null;
                    })
                    ->dehydrated(true), // Ensure the value is always sent to the database
                Forms\Components\Textarea::make('note_content')
                    ->required()
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('note_content')
            ->columns([
                Tables\Columns\TextColumn::make('reservation.id')
                    ->label('Reservation ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reservation.user.name') // Display Patient Name
                    ->label('Patient Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reservation.scheduled_date') // Display Reservation Date
                    ->label('Reservation Date')
                    ->date()
                    ->sortable(),
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
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('doctor_id')
                    ->relationship('doctor', 'name')
                    ->label('Filter by Doctor')
                    // Make filter visible only if the user is NOT a doctor, or if they have 'viewAnyDoctorNote' permission
                    ->visible(fn (): bool => !Auth::user()->hasRole('doctor') || Auth::user()->can('viewAnyDoctorNote')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (Model $record): bool => auth()->user()->can('editDoctorNote')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Model $record): bool => auth()->user()->can('deleteDoctorNote')),
                Tables\Actions\RestoreAction::make()
                    ->visible(fn (Model $record): bool => auth()->user()->can('editDoctorNote')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()->can('deleteDoctorNote')),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()->can('deleteDoctorNote')),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(fn (): bool => auth()->user()->can('editDoctorNote')),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
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
            'index' => Pages\ListDoctorNotes::route('/'),
            'create' => Pages\CreateDoctorNote::route('/create'),
            'edit' => Pages\EditDoctorNote::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // For doctors, only show notes created by them
        if (auth()->user()->hasRole('doctor') && !auth()->user()->can('viewAnyDoctorNote')) {
            return parent::getEloquentQuery()
                ->where('doctor_id', auth()->user()->doctor->id)
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]);
        }

        // For regular users, only show notes for their own reservations
        if (auth()->user()->hasRole('user') && !auth()->user()->can('viewAnyDoctorNote')) {
            return parent::getEloquentQuery()
                ->whereHas('reservation', fn ($query) => $query->where('user_id', auth()->id()))
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]);
        }

        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // Spatie Permission Integration
    public static function canViewAny(): bool
    {
        return auth()->user()->can('viewAnyDoctorNote');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('createDoctorNote');
    }

    public static function canEdit(Model $record): bool
    {
        // Doctors can edit their own notes
        if (auth()->user()->hasRole('doctor') && $record->doctor_id === auth()->user()->doctor->id) {
            return auth()->user()->can('editDoctorNote');
        }
        // Admins/Staff can edit any note
        return auth()->user()->can('editDoctorNote');
    }

    public static function canDelete(Model $record): bool
    {
        // Doctors can delete their own notes
        if (auth()->user()->hasRole('doctor') && $record->doctor_id === auth()->user()->doctor->id) {
            return auth()->user()->can('deleteDoctorNote');
        }
        // Admins/Staff can delete any note
        return auth()->user()->can('deleteDoctorNote');
    }

    public static function canForceDelete(Model $record): bool
    {
        return auth()->user()->can('deleteDoctorNote');
    }

    public static function canRestore(Model $record): bool
    {
        return auth()->user()->can('editDoctorNote');
    }
}

