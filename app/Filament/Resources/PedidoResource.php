<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidoResource\Pages;
use App\Filament\Resources\PedidoResource\RelationManagers;
use App\Models\Comprador;
use App\Models\Pedido;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Select::make('id_comprador')
                ->label('Comprador')
                ->relationship('comprador', 'nombre')
                ->searchable()
                ->preload()
                ->createOptionForm([
                    TextInput::make('nombre')
                        ->required()
                        ->maxLength(100),
                    TextInput::make('direccion'),
                    TextInput::make('contacto'),
                ]),
            DatePicker::make('fecha_pedido')
                ->required(),
            Repeater::make('detallesPedidos')
                ->relationship()
                ->schema([
                    Select::make('id_producto')
                        ->label('Producto')
                        ->options(Producto::all()->pluck('nombre', 'id'))
                        ->reactive()
                        ->required(),
                    TextInput::make('cantidad')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(function (callable $get) {
                            $producto = Producto::find($get('id_producto'));
                            return $producto ? $producto->cantidad_en_existencia : null;
                        })
                        ->reactive()
                        ->required()
                        ->afterStateUpdated(function (callable $set, callable $get, $state) {
                            $producto = Producto::find($get('id_producto'));
                            $cantidad = intval($get('cantidad'));
                            $precio = $producto ? floatval($producto->precio) : 0;
                            $total = $precio * $cantidad;
                            $set('total', $total);

                        }),
                    TextInput::make('total')
                        ->label('Total')
                        ->disabled()
                        ->dehydrateStateUsing(fn ($state) => $state ? number_format($state, 2, '.', '') : 0),
                ])
                ->required()
                ->addable(false)
                ->deletable(false),
        ]);
}




public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('comprador.nombre')->label('Comprador')->sortable()->searchable(),
            TextColumn::make('fecha_pedido')->label('Fecha del Pedido')->dateTime('d/m/Y')->sortable(),
            TextColumn::make('detallesPedidos.producto.nombre')->label('Producto')->sortable(),
            TextColumn::make('detallesPedidos.cantidad')->label('Cantidad')->sortable(),
            TextColumn::make('detallesPedidos.total')->label('Total del Producto')->sortable(),
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
            'index' => Pages\ListPedidos::route('/'),
            'create' => Pages\CreatePedido::route('/create'),
            'edit' => Pages\EditPedido::route('/{record}/edit'),
        ];
    }
}
