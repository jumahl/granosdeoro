<?php

namespace App\Filament\Resources\PedidoResource\Pages;

use App\Filament\Resources\PedidoResource;
use App\Models\DetallePedido;
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function handleRecordUpdate($record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // Calcular el total antes de guardar
        $total = 0;
        $producto = Producto::find($data['id_producto']);
        $cantidad = intval($data['cantidad'] ?? 0);
        $precio = floatval($producto->precio ?? 0);
        $total = $precio * $cantidad;
        $data['total'] = $total;

        $record->update($data);

        // Actualizar la relación en detalle_pedidos
        DetallePedido::where('id_pedido', $record->id)->delete();

        DetallePedido::create([
            'id_pedido' => $record->id,
            'id_producto' => $data['id_producto'],
        ]);

        return $record;
    }
}
