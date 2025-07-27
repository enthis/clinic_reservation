<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Doctor;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash; // For password hashing
use Illuminate\Database\Eloquent\Model; // Import Model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At')
                    ->nullable(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->maxLength(255),
                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->required(),
                // You might want to hide google_id in the form unless specifically needed
                // Forms\Components\TextInput::make('google_id')
                //     ->maxLength(255)
                //     ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
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
                Tables\Actions\Action::make('loginAsUser') // New action
                    ->label('Login as User')
                    ->icon('heroicon-o-arrow-right-start-on-rectangle')
                    ->color('info')
                    ->visible(function (Model $record): bool {
                        // Only show if the current authenticated user is an admin
                        // and they are not trying to log in as themselves
                        return auth()->user()->hasRole('admin') && auth()->id() !== $record->id;
                    })
                    ->action(function (User $record) {
                        // Store the current admin's ID in session for returning later
                        Session::put('admin_id_before_login_as', Auth::id());

                        // Log out the current admin
                        Auth::logout();
                        Session::invalidate();
                        Session::regenerateToken();

                        // Log in as the target user
                        Auth::login($record);
                        Session::regenerate(true);

                        // Generate a new API token for the impersonated user and store in session
                        $token = $record->createToken('impersonation-token')->plainTextToken;
                        Session::put('api_token', $token);

                        \Filament\Notifications\Notification::make()
                            ->title('Logged in as ' . $record->name)
                            ->success()
                            ->send();

                        // Redirect to the dashboard or a specific user-facing page
                        return redirect()->route('filament.admin.auth.login');
                    })
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
        return auth()->user()->can('viewAnyUser');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('createUser');
    }

    public static function canEdit(Model $record): bool // Corrected type hint
    {
        return auth()->user()->can('editUser');
    }

    public static function canDelete(Model $record): bool // Corrected type hint
    {
        return auth()->user()->can('deleteUser');
    }

    public static function canForceDelete(Model $record): bool // Corrected type hint
    {
        return auth()->user()->can('deleteUser'); // Often force delete is covered by general delete permission
    }

    public static function canRestore(Model $record): bool // Corrected type hint
    {
        return auth()->user()->can('editUser'); // Often restore is covered by edit permission
    }
}
