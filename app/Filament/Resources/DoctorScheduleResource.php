<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorScheduleResource\Pages;
use App\Filament\Resources\DoctorScheduleResource\RelationManagers;
use App\Models\DoctorSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model; // Import Model

class DoctorScheduleResource extends Resource
{
    protected static ?string $model = DoctorSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('doctor_id')
                    ->relationship('doctor', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('day_of_week') // Changed from DatePicker to Select
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
                    ->native(false),
                Forms\Components\TimePicker::make('start_time')
                    ->required()
                    ->seconds(false)
                    ->displayFormat('H:i'),
                Forms\Components\TimePicker::make('end_time')
                    ->required()
                    ->seconds(false)
                    ->displayFormat('H:i')
                    ->afterOrEqual('start_time'),
                Forms\Components\Toggle::make('is_available')
                    ->label('Available for Booking')
                    ->default(true),
                Forms\Components\Textarea::make('notes') // Added notes field
                    ->nullable()
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('doctor.name')
                    ->searchable()
                    ->sortable(),
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
                Tables\Columns\TextColumn::make('notes') // Display notes
                    ->limit(50)
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
                    ->label('Filter by Doctor'),
                Tables\Filters\SelectFilter::make('day_of_week') // Filter by day of week
                    ->options([
                        0 => 'Sunday',
                        1 => 'Monday',
                        2 => 'Tuesday',
                        3 => 'Wednesday',
                        4 => 'Thursday',
                        5 => 'Friday',
                        6 => 'Saturday',
                    ])
                    ->label('Filter by Day'),
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
            'index' => Pages\ListDoctorSchedules::route('/'),
            'create' => Pages\CreateDoctorSchedule::route('/create'),
            'edit' => Pages\EditDoctorSchedule::route('/{record}/edit'),
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
        return auth()->user()->can('viewAnyDoctorSchedule');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('createDoctorSchedule');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('editDoctorSchedule');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('deleteDoctorSchedule');
    }

    public static function canForceDelete(Model $record): bool
    {
        return auth()->user()->can('deleteDoctorSchedule');
    }

    public static function canRestore(Model $record): bool
    {
        return auth()->user()->can('editDoctorSchedule');
    }
}

