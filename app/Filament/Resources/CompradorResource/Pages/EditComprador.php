<?php

namespace App\Filament\Resources\CompradorResource\Pages;

use App\Filament\Resources\CompradorResource;
use App\Models\Comprador; // Importar la clase Comprador
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditComprador extends EditRecord
{
    // Especifica el recurso al que pertenece esta página
    protected static string $resource = CompradorResource::class;

    // Propiedades para los datos del comprador
    public $nombre;
    public $direccion;
    public $contacto;

    // Define la URL a la que se redirige después de editar un comprador
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Define la notificación que se muestra después de editar un comprador
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success() // Define el tipo de notificación como éxito
            ->title('Comprador editado') // Título de la notificación
            ->body('El comprador ha sido editado correctamente.'); // Cuerpo de la notificación
    }

    // Método para guardar los cambios en un comprador
    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        // Lógica para actualizar el comprador en la base de datos
        $this->record->update([
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'contacto' => $this->contacto,
        ]);

        // Enviar notificación de éxito si está habilitado
        if ($shouldSendSavedNotification) {
            //$this->notify('success', 'El comprador ha sido editado correctamente.');
        }

        // Redirigir a la URL de redirección si está habilitado
        if ($shouldRedirect) {
            redirect($this->getRedirectUrl());
        }
    }
}