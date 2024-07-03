<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Filament\Resources\ProductoResource\RelationManagers;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;
    protected static ?string $navigationGroup = 'Administrador';

    protected static ?string $navigationIcon = 'heroicon-s-plus-circle';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            TextInput::make('nombre')
                ->required()
                ->maxLength(20)
                ->disabled(fn ($record) => $record !== null),
            Textarea::make('descripcion')
                ->required()
                ->disabled(fn ($record) => $record !== null)
                ->maxLength(60),
            TextInput::make('precio')
                ->required()
                ->numeric(),
            TextInput::make('cantidad_en_existencia')
                ->required()
                ->numeric()
                ->minValue(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('nombre')->sortable()->searchable(),
                TextColumn::make('descripcion')->sortable(),
                TextColumn::make('precio')->sortable(),
                TextColumn::make('cantidad_en_existencia')->sortable(),
                
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
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}
