<?php

namespace App\Filament\Resources\PedidoResource\Pages;

use App\Filament\Resources\PedidoResource;
use App\Models\DetallePedido;
use App\Models\Producto;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePedido extends CreateRecord
{
    protected static string $resource = PedidoResource::class;
    

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Calcular el total antes de guardar
        $total = 0;
        $producto = Producto::find($data['id_producto']);
        $cantidad = intval($data['cantidad'] ?? 0);
        $precio = floatval($producto->precio ?? 0);
        $total = $precio * $cantidad;
        $data['total'] = $total;

        $pedido = static::getModel()::create($data);
        DetallePedido::create([
            'id_pedido' => $pedido->id,
            'id_producto' => $data['id_producto'],
        ]);

        return $pedido;
    }
}
