<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidoResource\Pages;
use App\Filament\Resources\PedidoResource\RelationManagers;
use App\Models\Comprador;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Columns\Layout\Split;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 1;

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
                Repeater::make('detallesPedidos')
                    ->relationship()
                    ->schema([
                        Select::make('id_producto')
                            ->relationship('producto', 'nombre')
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
                            ->label('Cantidad')
                            ->required()
                            ->reactive(),
                    ])
                    ->minItems(1)
                    ->label('Productos')
                    ->columns(2)
                    
                    ->deletable(false)
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        $detallesPedidos = $get('detallesPedidos') ?? [];
                        $total = 0;
                        foreach ($detallesPedidos as $index => $detallePedido) {
                            if (isset($detallePedido['id_producto']) && isset($detallePedido['cantidad'])) {
                                $productoInfo = Producto::find($detallePedido['id_producto']);
                                if ($productoInfo) {
                                    $cantidad = (float) $detallePedido['cantidad'];
                                    $precio = (float) $productoInfo->precio;
                                    $total += $precio * $cantidad;
                                }
                            }
                        }
                        $set('total', number_format($total, 2, '.', ''));
                    }),
                TextInput::make('total')
                    ->label('Total Pedido')
                    ->readonly()
                    ->required()
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        $component->state(number_format($state, 2, '.', ''));
                    }),
                Select::make('status')
                    ->label('Estado del Pedido')
                    ->options([
                        'en proceso' => 'En Proceso',
                        'entregado' => 'Entregado',
                        'cancelado' => 'Cancelado',
                    ])
                    ->default('en proceso')
                    ->required(),
            ]);
    }

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('id')->label('No.Pedido')->searchable(),
            TextColumn::make('comprador.nombre')->label('Comprador')->searchable(),
            TextColumn::make('comprador.contacto')->label('Contacto'),
            TextColumn::make('fecha_pedido')->label('Fecha del Pedido')->dateTime('d/m/y')->sortable(),
            TextColumn::make('detallesPedidos.producto.nombre')->label('Producto'),
            TextColumn::make('detallespedidos.cantidad')->label('Cantidad'),
            TextColumn::make('total')->label('Total'),
            TextColumn::make('status')->label('Estado')->searchable()
            ->badge()
            ->color(fn (Pedido $record) => match ($record->status) {
                'en proceso' => 'warning',
                'entregado' => 'success',
                'cancelado' => 'danger',
            }),
        ])
        

            ->filters([
                SelectFilter::make('status')
                ->label('Estado del Pedido')
                ->options([
                    'en proceso' => 'En Proceso',
                    'entregado' => 'Entregado',
                    'cancelado' => 'Cancelado',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('generarFactura')
                ->label('Bill')
                ->color('gray')
                ->url(fn (Pedido $record) => route('factura.generar', $record->id))
                ->icon('heroicon-o-document-arrow-down'),
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
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
