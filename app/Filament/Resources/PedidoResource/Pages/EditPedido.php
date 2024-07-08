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

    protected function handleRecordUpdate($record, array $data): Pedido
    {
        $record->update([
            'id_comprador' => $data['id_comprador'],
            'fecha_pedido' => $data['fecha_pedido'],
            'total' => $data['total'],
            'status' => $data['status'],
        ]);
    
        if (isset($data['productos'])) {
            $existingIds = [];
            foreach ($data['productos'] as $producto) {
                $detalle = DetallePedido::updateOrCreate(
                    [
                        'id_pedido' => $record->id,
                        'id_producto' => $producto['id_producto'],
                    ],
                    [
                        'cantidad' => $producto['cantidad'],
                    ]
                );
                $existingIds[] = $producto['id_producto'];
            }
    
            // Eliminar detalles que ya no existen
            DetallePedido::where('id_pedido', $record->id)
                ->whereNotIn('id_producto', $existingIds)
                ->delete();
        }
    
        return $record->fresh();
    }
    
    
}

