<?php

namespace App\Filament\Resources\ReservationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model; // Import Model
use Illuminate\Support\Str; // Import Str for unique ID generation

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments'; // This must match the relationship method name in Reservation model

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true, table: 'payments')
                    ->default(fn() => 'PAY-' . $this->ownerRecord->id  . '-' . Str::uuid()) // Auto-generate default order_id
                    ->disabledOn('edit') // Disable on edit
                    ->readOnly() // Make it read-only
                    ->dehydrated(fn(?string $state): bool => filled($state)), // Ensure it's dehydrated even if read-only
                Forms\Components\TextInput::make('gateway_transaction_id')
                    ->maxLength(255)
                    ->nullable()
                    ->disabled()
                    ->unique(ignoreRecord: true, table: 'payments'),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->stripCharacters(',')
                    ->dehydrateStateUsing(fn(string $state): float => (float) str_replace(',', '', $state))
                    ->step(100.0)
                    ->mask(RawJs::make('$money($input)'))
                    ->suffix('IDR')
                    ->default(function () {
                        $reservation = $this->ownerRecord;
                        $totalPaid = $reservation->payments()
                            ->whereIn('transaction_status', ['settlement', 'paid', 'capture'])
                            ->sum('amount');
                        return max(0, $reservation->payment_amount - $totalPaid); // Ensure it's not negative
                    }),
                Forms\Components\TextInput::make('currency')
                    ->required()
                    ->maxLength(3)
                    ->default('IDR'),
                Forms\Components\Select::make('payment_gateway')
                    ->options([
                        'midtrans' => 'Midtrans',
                        'cashier' => 'Cashier',
                        // 'qris_provider_a' => 'QRIS Provider A',
                        // Add more as needed
                    ])
                    ->default('cashier')
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('payment_method')
                    ->required()
                    ->default('cash')
                    ->options([
                        'cash' => 'Cash',
                        'credit_card' => 'Credit Card',
                    ]),
                Forms\Components\Select::make('transaction_status')
                    ->options([
                        'pending' => 'Pending',
                        'capture' => 'Capture',
                        'settlement' => 'Settlement',
                        'deny' => 'Deny',
                        'expire' => 'Expire',
                        'cancel' => 'Cancel',
                        'refund' => 'Refund',
                        'paid' => 'Paid', // Custom status for cashier/QRIS
                        'failed' => 'Failed', // Custom status for cashier/QRIS
                    ])
                    ->required()
                    ->default('paid')
                    ->native(false),
                Forms\Components\DateTimePicker::make('transaction_time')
                    ->default(now())
                    ->nullable(),
                Forms\Components\Textarea::make('raw_response')
                    ->nullable()
                    ->maxLength(65535),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_id')
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gateway_transaction_id')
                    ->searchable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_gateway')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('transaction_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending', 'challenge' => 'warning',
                        'capture', 'settlement', 'paid', 'authorize' => 'success',
                        'deny', 'expire', 'cancel', 'failed' => 'danger',
                        'refund', 'partial_refund' => 'gray',
                        default => 'info', // For 'unknown' or other statuses
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('payment_gateway')
                    ->options([
                        'midtrans' => 'Midtrans',
                        'cashier' => 'Cashier',
                        'qris_provider_a' => 'QRIS Provider A',
                    ]),
                Tables\Filters\SelectFilter::make('transaction_status')
                    ->options([
                        'pending' => 'Pending',
                        'capture' => 'Capture',
                        'settlement' => 'Settlement',
                        'deny' => 'Deny',
                        'expire' => 'Expire',
                        'cancel' => 'Cancel',
                        'refund' => 'Refund',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'challenge' => 'Challenge',
                        'authorize' => 'Authorize',
                        'partial_refund' => 'Partial Refund',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn(): bool => auth()->user()->can('createPayment')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn(Model $record): bool => auth()->user()->can('editPayment')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(Model $record): bool => auth()->user()->can('deletePayment')),
                Tables\Actions\RestoreAction::make()
                    ->visible(fn(Model $record): bool => auth()->user()->can('editPayment')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn(): bool => auth()->user()->can('deletePayment')),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(fn(): bool => auth()->user()->can('editPayment')),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        $user = auth()->user();
        if ($user->hasRole('doctor')) {
            return false;
        }
        return true;
        // Example: Show the relation manager only if the owner record's user_id matches the currently authenticated user's ID
    }
}
