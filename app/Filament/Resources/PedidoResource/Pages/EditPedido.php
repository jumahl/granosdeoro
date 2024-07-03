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
    protected function getUpdatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Pedido Editado')
            ->body('El pedido ha sido editado correctamente.');
    }
protected function handleRecordUpdate($record, array $data): Pedido
{
    $record->update([
        'id_comprador' => $data['id_comprador'],
        'fecha_pedido' => $data['fecha_pedido'],
        'status' => $data['status'],
        'total' => $data['total'],
    ]);

    // Eliminar los detalles existentes
    $record->detallesPedidos()->delete();

    // Crear nuevos detalles
    if (isset($data['productos']) && is_array($data['productos'])) {
        foreach ($data['productos'] as $producto) {
            DetallePedido::create([
                'pedido_id' => $record->id,
                'producto_id' => $producto['id_producto'],
                'cantidad' => $producto['cantidad'],
            ]);
        }
    }

    return $record;
}

}


