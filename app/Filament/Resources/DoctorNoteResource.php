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

class DoctorNoteResource extends Resource
{
    protected static ?string $model = DoctorNote::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('reservation_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('prescription_item_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('dose')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reservation_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prescription_item_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dose')
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDoctorNotes::route('/'),
            'create' => Pages\CreateDoctorNote::route('/create'),
            'edit' => Pages\EditDoctorNote::route('/{record}/edit'),
        ];
    }
}
