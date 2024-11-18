<?php

namespace App\Filament\Resources\PedidoResource\Pages;

use App\Filament\Resources\PedidoResource;
use App\Models\Pedido; // Importar la clase Pedido
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPedido extends EditRecord
{
    // Especifica el recurso al que pertenece esta página
    protected static string $resource = PedidoResource::class;

    // Propiedades para los datos del pedido
    public $id_comprador;
    public $fecha_pedido;
    public $total;
    public $status;

    // Define la URL a la que se redirige después de editar un pedido
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Define la notificación que se muestra después de editar un pedido
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success() // Define el tipo de notificación como éxito
            ->title('Pedido editado') // Título de la notificación
            ->body('El pedido ha sido editado correctamente.'); // Cuerpo de la notificación
    }

    // Método para guardar los cambios en un pedido
    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        // Lógica para actualizar el pedido en la base de datos
        $this->record->update([
            'id_comprador' => $this->id_comprador,
            'fecha_pedido' => $this->fecha_pedido,
            'total' => $this->total,
            'status' => $this->status,
        ]);

        // Enviar notificación de éxito si está habilitado
        if ($shouldSendSavedNotification) {
            //$this->notify('success', 'El pedido ha sido editado correctamente.');
        }

        // Redirigir a la URL de redirección si está habilitado
        if ($shouldRedirect) {
            redirect($this->getRedirectUrl());
        }
    }
}