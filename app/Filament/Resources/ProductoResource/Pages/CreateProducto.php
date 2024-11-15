<?php

namespace App\Filament\Resources\ProductoResource\Pages;

use App\Filament\Resources\ProductoResource;
use App\Models\Producto;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateProducto extends CreateRecord
{
    protected static string $resource = ProductoResource::class;

    public $nombre;
    public $descripcion;
    public $precio;
    public $cantidad_en_existencia;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Producto creado')
            ->body('El producto ha sido creado exitosamente.');
    }

    public function create(bool $another = false): void
    {
        // Lógica para crear el producto en la base de datos
        Producto::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'cantidad_en_existencia' => $this->cantidad_en_existencia,
        ]);

       // $this->emit('refresh');
       // $this->notify('success', 'El producto ha sido creado exitosamente.');
        redirect($this->getRedirectUrl());
    }
}
