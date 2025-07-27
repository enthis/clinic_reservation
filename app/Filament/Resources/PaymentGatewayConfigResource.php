<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentGatewayConfigResource\Pages;
use App\Filament\Resources\PaymentGatewayConfigResource\RelationManagers;
use App\Models\PaymentGatewayConfig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Crypt; // For encryption/decryption
use Illuminate\Database\Eloquent\Model; // Import Model

class PaymentGatewayConfigResource extends Resource
{
    protected static ?string $model = PaymentGatewayConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Settings & Permissions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('gateway_name')
                    ->required()
                    ->maxLength(255)
                    ->helperText('e.g., midtrans, qris_provider_a'),
                Forms\Components\Select::make('mode')
                    ->options([
                        'sandbox' => 'Sandbox',
                        'production' => 'Production',
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\TextInput::make('config_key')
                    ->required()
                    ->maxLength(255)
                    ->helperText('e.g., server_key, client_key, merchant_id, callback_url'),
                Forms\Components\TextInput::make('config_value')
                    ->required()
                    ->maxLength(65535)
                    ->password() // Treat as password for sensitive keys
                    ->dehydrateStateUsing(function (?string $state, Forms\Get $get) {
                        if (filled($state) && $get('is_encrypted')) {
                            return Crypt::encryptString($state);
                        }
                        return $state;
                    })
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->revealable()
                    ->label('Configuration Value'),
                Forms\Components\Toggle::make('is_encrypted')
                    ->label('Is Encrypted?')
                    ->helperText('Toggle if the config value should be encrypted in the database.')
                    ->default(false),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gateway_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('config_key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('config_value')
                    ->label('Value (Decrypted if Encrypted)')
                    ->formatStateUsing(function (string $state, PaymentGatewayConfig $record) {
                        return $record->is_encrypted ? Crypt::decryptString($state) : $state;
                    })
                    ->wrap()
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_encrypted')
                    ->boolean(),
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
                Tables\Filters\SelectFilter::make('gateway_name')
                    ->options([
                        'midtrans' => 'Midtrans',
                        'qris_provider_a' => 'QRIS Provider A',
                    ]),
                Tables\Filters\SelectFilter::make('mode')
                    ->options([
                        'sandbox' => 'Sandbox',
                        'production' => 'Production',
                    ]),
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
            'index' => Pages\ListPaymentGatewayConfigs::route('/'),
            'create' => Pages\CreatePaymentGatewayConfig::route('/create'),
            'edit' => Pages\EditPaymentGatewayConfig::route('/{record}/edit'),
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
        return auth()->user()->can('viewAnyPaymentGatewayConfig');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('createPaymentGatewayConfig');
    }

    public static function canEdit(Model $record): bool // Corrected type hint
    {
        return auth()->user()->can('editPaymentGatewayConfig');
    }

    public static function canDelete(Model $record): bool // Corrected type hint
    {
        return auth()->user()->can('deletePaymentGatewayConfig');
    }

    public static function canForceDelete(Model $record): bool // Corrected type hint
    {
        return auth()->user()->can('deletePaymentGatewayConfig');
    }

    public static function canRestore(Model $record): bool // Corrected type hint
    {
        return auth()->user()->can('editPaymentGatewayConfig');
    }
}
