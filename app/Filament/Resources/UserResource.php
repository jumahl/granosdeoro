<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Usuarios del sistema';
    protected static ?string $navigationIcon = 'heroicon-s-user-group';
    protected static ?string $navigationGroup = 'Administrador';
    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->label('Correo electrónico')
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                ->password() 
                ->label('Contraseña')
                ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                ->minLength(5)
                ->same('password_confirmation')
                ->dehydrated(fn ($state)=> filled($state))
                ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                TextInput::make('password_confirmation')
                ->password()
                ->label('Confirmar contraseña')
                ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord),

                Select::make('roles')
                ->multiple()
                ->label('Roles')
                ->relationship('roles', 'name')->preload(),
                Select::make('permissions')
                ->multiple()
                ->relationship('permissions', 'name')->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->sortable()
                ->searchable()
                ->label('Nombre'),
                TextColumn::make('email')
                ->sortable()
                ->searchable()
                ->label('Correo electrónico'),
                TextColumn::make('roles.name')
                ->badge()
                ->label('Rol'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

}
