<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Models\Doctor;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Reservation Details')
                    ->description('Basic information about the reservation.')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->disabled(function (): ?int {
                                $user = auth()->user();
                                if ($user->hasRole('doctor')) {
                                    return true;
                                }
                                return false;
                            })
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('doctor_id')
                            ->relationship('doctor', 'name')
                            ->required()
                            ->disabled(function (): ?int {
                                $user = auth()->user();
                                if ($user->hasRole('doctor')) {
                                    return true;
                                }
                                return false;
                            })
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('service_id')
                            ->relationship('service', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])->columns(3), // Arrange these three fields in 3 columns

                Forms\Components\Section::make('Schedule Information')
                    ->description('Details about the selected time slot.')
                    ->schema([
                        Forms\Components\Select::make('schedule_id')
                            ->relationship('schedule', 'day_of_week')
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->day_name} ({$record->start_time} - {$record->end_time})")
                            ->required()
                            ->disabled(function (): ?int {
                                $user = auth()->user();
                                if ($user->hasRole('doctor')) {
                                    return true;
                                }
                                return false;
                            })
                            ->searchable()
                            ->preload(),
                        Forms\Components\DatePicker::make('scheduled_date')
                            ->required()
                            ->disabled(function (): ?int {
                                $user = auth()->user();
                                if ($user->hasRole('doctor')) {
                                    return true;
                                }
                                return false;
                            })
                            ->native(false),
                        Forms\Components\TimePicker::make('scheduled_time')
                            ->required()
                            ->disabled(function (): ?int {
                                $user = auth()->user();
                                if ($user->hasRole('doctor')) {
                                    return true;
                                }
                                return false;
                            })
                            ->seconds(false)
                            ->displayFormat('H:i'),
                    ])->columns(3), // Arrange these three fields in 3 columns

                Forms\Components\Section::make('Status & Payment')
                    ->description('Manage reservation and payment statuses.')
                    ->visible(function (): ?int {
                        $user = auth()->user();
                        if ($user->hasRole('doctor')) {
                            return false;
                        }
                        return true;
                    })
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending')
                            ->native(false),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->required()
                            ->default('pending')
                            ->native(false),
                        Forms\Components\TextInput::make('payment_amount')
                            ->required()
                            ->numeric()
                            ->stripCharacters(',')
                            ->dehydrateStateUsing(fn(string $state): float => (float) str_replace(',', '', $state))
                            ->step(100.0)
                            ->mask(RawJs::make('$money($input)'))
                            ->suffix('IDR'),
                        Forms\Components\Select::make('approved_by')
                            ->relationship('approver', 'name')
                            ->label('Approved By (Staff)')
                            ->disabled()
                            ->nullable()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('completed_by')
                            ->relationship('completer', 'name')
                            ->label('Completed By (Staff)')
                            ->disabled()
                            ->nullable()
                            ->searchable()
                            ->preload(),
                    ])->columns(2), // Arrange these fields in 2 columns
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('doctor.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_time')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'info',
                        'cancelled' => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('approver.name')
                    ->label('Approved By')
                    ->placeholder('N/A')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('completer.name')
                    ->label('Completed By')
                    ->placeholder('N/A')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('doctor_id')
                    ->relationship('doctor', 'name')
                    ->label('Filter by Doctor')
                    ->options(function (): array {
                        $user = auth()->user();
                        // If the logged-in user is a doctor, only show their own doctor in the filter
                        if ($user->hasRole('doctor') && $user->doctor) {
                            return [$user->doctor->id => $user->doctor->name];
                        }
                        // Otherwise (admin/staff), show all doctors
                        return Doctor::pluck('name', 'id')->toArray();
                    })
                    ->default(function (): ?int {
                        $user = auth()->user();
                        // Set default filter to current doctor's ID if logged in as a doctor
                        if ($user->hasRole('doctor') && $user->doctor) {
                            return $user->doctor->id;
                        }
                        return null;
                    }),
                Tables\Filters\SelectFilter::make('service_id')
                    ->relationship('service', 'name')
                    ->label('Filter by Service'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                // Custom actions for staff/doctor
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Reservation $record): bool => $record->status === 'pending' && auth()->user()->can('approveReservations'))
                    ->action(function (Reservation $record) {
                        $record->update(['status' => 'approved', 'approved_by' => auth()->id()]);
                        \Filament\Notifications\Notification::make()
                            ->title('Reservation Approved')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('info')
                    ->visible(fn(Reservation $record): bool => $record->status === 'approved' && auth()->user()->can('completeReservations'))
                    ->action(function (Reservation $record) {
                        $record->recalculatePaymentAmount();
                        $record->refresh();
                        // Check if total payments paid match payment_amount
                        $totalPaid = $record->payments()
                            ->whereIn('transaction_status', ['settlement', 'paid', 'capture'])
                            ->sum('amount');

                        if ($totalPaid < $record->payment_amount) {
                            \Filament\Notifications\Notification::make()
                                ->title('Cannot Complete Reservation')
                                ->body('Total paid amount (Rp' . number_format($totalPaid, 0, ',', '.') . ') does not match reservation amount (Rp' . number_format($record->payment_amount, 0, ',', '.') . ').')
                                ->danger()
                                ->send();
                            return; // Stop the action
                        }

                        $record->update(['status' => 'completed', 'completed_by' => auth()->id()]);
                        \Filament\Notifications\Notification::make()
                            ->title('Reservation Completed')
                            ->success()
                            ->send();
                    }),
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
            RelationManagers\PaymentsRelationManager::class, // Link to payments
            RelationManagers\RecipesRelationManager::class, // Link to recipes
            RelationManagers\DoctorNotesRelationManager::class, // Link to doctor notes
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // For doctors, only show reservations they handle
        if (auth()->user()->hasRole('doctor') && !auth()->user()->can('viewAnyReservation')) {
            return parent::getEloquentQuery()
                ->where('doctor_id', auth()->user()->doctor->id)
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]);
        }

        // For regular users, only show their own reservations
        if (auth()->user()->hasRole('user') && !auth()->user()->can('viewAnyReservation')) {
            return parent::getEloquentQuery()
                ->where('user_id', auth()->id())
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
        // Admins and Staff can view any. Doctors/Users might have restricted view.
        return auth()->user()->can('viewAnyReservation') || auth()->user()->can('viewReservation');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('createReservation');
    }

    public static function canEdit(Model $record): bool
    {
        // Staff can edit any. Doctors can edit their own. Users can edit their own (e.g., cancel)
        return auth()->user()->can('editReservation') ||
            (auth()->user()->hasRole('doctor') && $record->doctor_id === auth()->user()->doctor->id) ||
            (auth()->user()->hasRole('user') && $record->user_id === auth()->id());
    }

    public static function canDelete(Model $record): bool
    {
        // Only admins and staff can delete reservations
        return auth()->user()->can('deleteReservation');
    }

    public static function canForceDelete(Model $record): bool
    {
        return auth()->user()->can('deleteReservation');
    }

    public static function canRestore(Model $record): bool
    {
        return auth()->user()->can('editReservation');
    }
}
