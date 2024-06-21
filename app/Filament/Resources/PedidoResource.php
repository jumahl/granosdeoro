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
                ->required()
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
Select::make('id_producto')
    ->label('Producto')
    ->options(Producto::all()->pluck('nombre', 'id'))
    ->reactive()
    ->required()
    ->afterStateUpdated(function ($state, callable $get, callable $set) {
        $producto = Producto::find($state);
        $cantidad = $get('cantidad');
        if ($producto && $cantidad) {
            $set('total', $producto->precio * $cantidad);
        } else {
            $set('total', 0);
        }
    }),

                    TextInput::make('cantidad')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(function (callable $get) {
                            $producto = Producto::find($get('id_producto'));
                            return $producto ? $producto->cantidad_en_existencia : null;
                        })
                        ->label('Cantidad')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, callable $get) {
                            $producto = Producto::find($get('id_producto'));
                            $cantidad = $get('cantidad');
                            if ($producto && $cantidad) {
                                $set('total', $producto->precio * $cantidad);
                            } else {
                                $set('total', 0);
                            }
                        }),
                    TextInput::make('total')
                        ->label('Total')
                        ->disabled()
                        ->dehydrateStateUsing(fn ($state) => $state ? number_format($state, 2, '.', '') : 0),
                ]);
        }

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('comprador.nombre')->label('Comprador')->sortable()->searchable(),
            TextColumn::make('fecha_pedido')->label('Fecha del Pedido')->dateTime('d/m/Y')->sortable(),
            TextColumn::make('detallesPedidos.id_producto')->label('Producto')->getStateUsing(function ($record) {
                return optional($record->detallesPedidos->first()->producto)->nombre;
            })->sortable()->searchable(),
            TextColumn::make('cantidad')->label('Cantidad')->sortable(),
            TextColumn::make('total')->label('Total del Producto')->sortable(),
        ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
