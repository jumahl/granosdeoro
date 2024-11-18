<?php

namespace App\Filament\Resources\PedidoResource\Pages;

use App\Filament\Resources\PedidoResource;
use App\Models\Pedido;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePedido extends CreateRecord
{
    // Especifica el recurso al que pertenece esta página
    protected static string $resource = PedidoResource::class;

    // Propiedades para los datos del pedido
    public $id_comprador;
    public $producto;
    public $cantidad;

    // Define la URL a la que se redirige después de crear un pedido
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Define la notificación que se muestra después de crear un pedido
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success() // Define el tipo de notificación como éxito
            ->title('Pedido creado') // Título de la notificación
            ->body('El pedido ha sido creado exitosamente.'); // Cuerpo de la notificación
    }
}