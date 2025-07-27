<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrescriptionItemResource\Pages;
use App\Filament\Resources\PrescriptionItemResource\RelationManagers;
use App\Models\PrescriptionItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrescriptionItemResource extends Resource
{
    protected static ?string $model = PrescriptionItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->maxLength(65535),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->stripCharacters(',')
                    ->dehydrateStateUsing(fn(string $state): float => (float) str_replace(',', '', $state))
                    ->step(100.0)
                    ->mask(RawJs::make('$money($input)'))
                    ->suffix('IDR'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPrescriptionItems::route('/'),
            'create' => Pages\CreatePrescriptionItem::route('/create'),
            'edit' => Pages\EditPrescriptionItem::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // Spatie Permission Integration
    public static function canViewAny(): bool
    {
        return auth()->user()->can('viewAnyPrescriptionItem');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('createPrescriptionItem');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('editPrescriptionItem');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('deletePrescriptionItem');
    }

    public static function canForceDelete(Model $record): bool
    {
        return auth()->user()->can('deletePrescriptionItem');
    }

    public static function canRestore(Model $record): bool
    {
        return auth()->user()->can('editPrescriptionItem');
    }
}
