<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompradorResource\Pages;
use App\Filament\Resources\CompradorResource\RelationManagers;
use App\Models\Comprador;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompradorResource extends Resource
{
    protected static ?string $model = Comprador::class;
    protected static ?string $navigationGroup = 'Administrador';
    protected static ?string $navigationLabel = 'Compradores';
    
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')
                ->required()
                ->maxLength(100),
                TextInput::make('direccion'),
                TextInput::make('contacto'),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->sortable()->searchable(),
                TextColumn::make('direccion')->sortable(),
                TextColumn::make('contacto')->sortable(),
                
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
            'index' => Pages\ListCompradors::route('/'),
            'create' => Pages\CreateComprador::route('/create'),
            'edit' => Pages\EditComprador::route('/{record}/edit'),
        ];
    }
}
