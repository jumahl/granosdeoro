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
    
        $errores = [];
    
        if (isset($data['detallesPedidos']) && is_array($data['detallesPedidos'])) {
            foreach ($data['detallesPedidos'] as $detalle) {
                $productoModel = Producto::find($detalle['id_producto']);
                if ($productoModel) {
                    $cantidadReducida = $productoModel->reducirCantidad($detalle['cantidad']);
                    if ($cantidadReducida) {
                        DetallePedido::create([
                            'id_pedido' => $pedido->id,
                            'id_producto' => $detalle['id_producto'],
                            'cantidad' => $detalle['cantidad'],
                        ]);
                    } else {
                        $errores[] = "No hay suficiente cantidad en existencia para el producto: {$productoModel->nombre}. Cantidad actual: {$productoModel->cantidad_en_existencia}, Cantidad solicitada: {$detalle['cantidad']}";
                    }
                } else {
                    $errores[] = "Producto no encontrado: ID {$detalle['id_producto']}";
                }
            }
        }
    
        if (!empty($errores)) {
            // Si hay errores, eliminamos el pedido creado y lanzamos una excepción
            $pedido->delete();
            throw new \Exception(implode("\n", $errores));
        }
    
        return $pedido;
    }
}
