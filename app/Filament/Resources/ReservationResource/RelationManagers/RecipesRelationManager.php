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

class RecipesRelationManager extends RelationManager
{
    protected static string $relationship = 'recipes'; // This must match the relationship method name in Reservation model

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('prescription_item_id')
                    ->relationship('prescriptionItem', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('dose')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('frequency_to_consume')
                    ->label('Frequency to Consume')
                    ->options([
                        '1 time a day' => '1 time a day',
                        '2 times a day' => '2 times a day',
                        '3 times a day' => '3 times a day',
                        'Every 4 hours' => 'Every 4 hours',
                        'Every 6 hours' => 'Every 6 hours',
                        'Once a week' => 'Once a week',
                        'Other' => 'Other (specify below)', // Option for custom input
                    ])
                    ->nullable()
                    ->native(false)
                    ->live() // Make it live to react to changes for conditional field
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                        // If "Other" is not selected, clear the custom frequency field
                        if ($get('frequency_to_consume') !== 'Other') {
                            $set('custom_frequency', null);
                        }
                    }),
                Forms\Components\TextInput::make('custom_frequency')
                    ->label('Custom Frequency')
                    ->nullable()
                    ->maxLength(255)
                    ->visible(fn(Forms\Get $get): bool => $get('frequency_to_consume') === 'Other'), // Only visible if 'Other' is selected
                Forms\Components\Textarea::make('notes')
                    ->nullable()
                    ->maxLength(65535),
            ])->columns(1); // Arrange fields in a single column
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('dose')
            ->columns([
                Tables\Columns\TextColumn::make('prescriptionItem.name')
                    ->label('Item')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dose')
                    ->searchable(),
                Tables\Columns\TextColumn::make('frequency_to_consume')
                    ->label('Frequency')
                    ->formatStateUsing(function (string $state, Model $record) {
                        // Display custom frequency if 'Other' was chosen and stored as such
                        $predefinedFrequencies = [
                            '1 time a day',
                            '2 times a day',
                            '3 times a day',
                            'Every 4 hours',
                            'Every 6 hours',
                            'Once a week'
                        ];
                        if (!in_array($state, $predefinedFrequencies)) {
                            return $state; // It's a custom value, display it directly
                        }
                        return $state; // It's a predefined value
                    })
                    ->searchable()
                    ->placeholder('N/A'),
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
                Tables\Filters\SelectFilter::make('prescription_item_id')
                    ->relationship('prescriptionItem', 'name')
                    ->label('Filter by Item'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn(): bool => auth()->user()->can('createRecipe') )
                    ->mutateFormDataUsing(function (array $data): array {
                        // Store custom_frequency in frequency_to_consume if 'Other' was selected
                        if ($data['frequency_to_consume'] === 'Other' && isset($data['custom_frequency'])) {
                            $data['frequency_to_consume'] = $data['custom_frequency'];
                        }
                        unset($data['custom_frequency']); // Remove temporary field
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn(Model $record): bool => auth()->user()->can('editRecipe'))
                    // This hook modifies data from the record *before* it's loaded into the form for editing.
                    ->mutateRecordDataUsing(function (array $data): array {
                        $predefinedFrequencies = [
                            '1 time a day',
                            '2 times a day',
                            '3 times a day',
                            'Every 4 hours',
                            'Every 6 hours',
                            'Once a week'
                        ];
                        // If the stored frequency is not one of the predefined ones,
                        // set the form's frequency_to_consume to 'Other' and populate custom_frequency.
                        if (isset($data['frequency_to_consume']) && !in_array($data['frequency_to_consume'], $predefinedFrequencies)) {
                            $data['custom_frequency'] = $data['frequency_to_consume'];
                            $data['frequency_to_consume'] = 'Other';
                        }
                        return $data;
                    })
                    // This hook modifies data *from* the form *before* it's saved to the database.
                    ->mutateFormDataUsing(function (array $data): array {
                        // If 'Other' was selected in the dropdown, use the value from custom_frequency.
                        if (isset($data['frequency_to_consume']) && $data['frequency_to_consume'] === 'Other' && isset($data['custom_frequency'])) {
                            $data['frequency_to_consume'] = $data['custom_frequency'];
                        }
                        // Unset the temporary custom_frequency field before saving to the database.
                        unset($data['custom_frequency']);
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(Model $record): bool => auth()->user()->can('deleteRecipe')),
                Tables\Actions\RestoreAction::make()
                    ->visible(fn(Model $record): bool => auth()->user()->can('editRecipe')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn(): bool => auth()->user()->can('deleteRecipe')),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(fn(): bool => auth()->user()->can('editRecipe')),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
