<?php

namespace App\Filament\Resources\PedidoResource\Pages;

use App\Filament\Resources\PedidoResource;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePedido extends CreateRecord
{
    protected static string $resource = PedidoResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Pedido creado')
            ->body('El pedido ha sido creado exitosamente.');
    }
    protected function handleRecordCreation(array $data): Pedido
    {
    
    
        $pedido = Pedido::create([
            'id_comprador' => $data['id_comprador'],
            'fecha_pedido' => $data['fecha_pedido'],
            'total' => $data['total'],
            'status' => $data['status'],
        ]);
    
        if (isset($data['productos']) && is_array($data['productos'])) {
            foreach ($data['productos'] as $producto) {
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto['id_producto'],
                    'cantidad' => $producto['cantidad'],
                ]);
            }
        }
    
        return $pedido;
    }

}
