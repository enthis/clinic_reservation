<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecipeResource\Pages;
use App\Filament\Resources\RecipeResource\RelationManagers;
use App\Models\Recipe;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecipeResource extends Resource
{
    protected static ?string $model = Recipe::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('reservation_id')
                    ->relationship('reservation', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Reservation #{$record->id} - {$record->user->name} ({$record->scheduled_date->format('M d, Y')})")
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('prescription_item_id')
                    ->relationship('prescriptionItem', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('dose')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->nullable()
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reservation.id')
                    ->label('Reservation ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reservation.user.name')
                    ->label('Patient Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prescriptionItem.name')
                    ->label('Item')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dose')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->limit(50)
                    ->wrap(),
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
                Tables\Filters\SelectFilter::make('reservation_id')
                    ->relationship('reservation', 'id')
                    ->label('Filter by Reservation'),
                Tables\Filters\SelectFilter::make('prescription_item_id')
                    ->relationship('prescriptionItem', 'name')
                    ->label('Filter by Item'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListRecipes::route('/'),
            'create' => Pages\CreateRecipe::route('/create'),
            'edit' => Pages\EditRecipe::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Doctors can only see/manage recipes for their own reservations
        if (auth()->user()->hasRole('doctor') && !auth()->user()->can('viewAnyRecipe')) {
            return parent::getEloquentQuery()
                ->whereHas('reservation', fn ($query) => $query->where('doctor_id', auth()->user()->doctor->id))
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]);
        }
        // Users can only see their own recipes
        if (auth()->user()->hasRole('user') && !auth()->user()->can('viewAnyRecipe')) {
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
        return auth()->user()->can('viewAnyRecipe');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('createRecipe');
    }

    public static function canEdit(Model $record): bool
    {
        // Doctors can edit recipes for their own reservations
        if (auth()->user()->hasRole('doctor') && $record->reservation->doctor_id === auth()->user()->doctor->id) {
            return auth()->user()->can('editRecipe');
        }
        // Admins/Staff can edit any recipe
        return auth()->user()->can('editRecipe');
    }

    public static function canDelete(Model $record): bool
    {
        // Doctors can delete recipes for their own reservations
        if (auth()->user()->hasRole('doctor') && $record->reservation->doctor_id === auth()->user()->doctor->id) {
            return auth()->user()->can('deleteRecipe');
        }
        // Admins/Staff can delete any recipe
        return auth()->user()->can('deleteRecipe');
    }

    public static function canForceDelete(Model $record): bool
    {
        return auth()->user()->can('deleteRecipe');
    }

    public static function canRestore(Model $record): bool
    {
        return auth()->user()->can('editRecipe');
    }
}

