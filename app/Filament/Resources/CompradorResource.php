<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompradorResource\Pages;
use App\Models\Comprador;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompradorResource extends Resource
{
    protected static ?string $model = Comprador::class;
    protected static ?string $navigationGroup = 'Administrador';
    protected static ?string $navigationLabel = 'Compradores';
    
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-s-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')
                ->required()
                ->maxLength(60)
                ->label('Nombre')
                ->disabled(fn ($record) => $record !== null),
                TextInput::make('direccion')
                ->required()
                ->label('Dirección'),
                TextInput::make('contacto')
                ->required()
                ->label('Contacto'),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->sortable()->searchable()
                ->label('Nombre'),
                TextColumn::make('direccion')->sortable()
                ->label('Dirección'),
                TextColumn::make('contacto')->sortable()
                ->label('Contacto'),
                
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
            'index' => Pages\ListCompradors::route('/'),

        ];
    }
}
