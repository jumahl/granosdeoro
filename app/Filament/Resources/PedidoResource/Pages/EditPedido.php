<?php

namespace App\Filament\Resources\PedidoResource\Pages;

use App\Filament\Resources\PedidoResource;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPedido extends EditRecord
{
    protected static string $resource = PedidoResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Pedido editado')
            ->body('El Pedido ha sido editado correctamente.');
    }

    protected function fillForm(): void
{
    $this->callHook('beforeFill');

    $data = $this->record->attributesToArray();
    
    // Cargar explícitamente los detalles del pedido
    $data['detallesPedidos'] = $this->record->detallesPedidos()
        ->with('producto')
        ->get()
        ->map(function ($detalle) {
            return [
                'id_producto' => $detalle->id_producto,
                'cantidad' => $detalle->cantidad,
                'precio_unitario' => $detalle->precio_unitario,
            ];
        })
        ->toArray();

    $this->form->fill($data);

    $this->callHook('afterFill');
}


    protected function handleRecordUpdate($record, array $data): Pedido
    {
        $record->update([
            'id_comprador' => $data['id_comprador'],
            'fecha_pedido' => $data['fecha_pedido'],
            'total' => $data['total'],
            'status' => $data['status'],
        ]);
    
        if (isset($data['detallesPedidos'])) {
            $existingIds = [];
            foreach ($data['detallesPedidos'] as $detalle) {
                $producto = Producto::find($detalle['id_producto']);
                $detallePedido = DetallePedido::updateOrCreate(
                    [
                        'id_pedido' => $record->id,
                        'id_producto' => $detalle['id_producto'],
                    ],
                    [
                        'cantidad' => $detalle['cantidad'],
                        'precio_unitario' => $producto->precio
                    ]
                );
                $existingIds[] = $detalle['id_producto'];
            }
    
            DetallePedido::where('id_pedido', $record->id)
                ->whereNotIn('id_producto', $existingIds)
                ->delete();
        }
    
        return $record->fresh();
    }
    
    
}

