<?php

namespace App\Filament\Resources\CompradorResource\Pages;

use App\Filament\Resources\CompradorResource;
use App\Models\Comprador;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateComprador extends CreateRecord
{
    // Especifica el recurso al que pertenece esta página
    protected static string $resource = CompradorResource::class;

    // Propiedades para los datos del comprador
    public $nombre;
    public $direccion;
    public $contacto;

    // Define la URL a la que se redirige después de crear un comprador
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Define la notificación que se muestra después de crear un comprador
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success() // Define el tipo de notificación como éxito
            ->title('Comprador creado') // Título de la notificación
            ->body('El comprador ha sido creado exitosamente.'); // Cuerpo de la notificación
    }

    // Método para crear un nuevo comprador
    public function create(bool $another = false): void
    {
        // Lógica para crear el comprador en la base de datos
        Comprador::create([
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'contacto' => $this->contacto,
        ]);

        // Redirigir a la URL de redirección
        redirect($this->getRedirectUrl());
    }
}