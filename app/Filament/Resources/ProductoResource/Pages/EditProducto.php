<?php

namespace App\Filament\Resources\ProductoResource\Pages;

use App\Filament\Resources\ProductoResource;
use App\Models\Producto; // Importar la clase Producto
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProducto extends EditRecord
{
    protected static string $resource = ProductoResource::class;

    public $precio;
    public $cantidad_en_existencia;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Producto editado')
            ->body('El producto ha sido editado correctamente.');
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->record->update([
            'precio' => $this->precio,
            'cantidad_en_existencia' => $this->cantidad_en_existencia,
        ]);

        if ($shouldSendSavedNotification) {
            //$this->notify('success', 'El producto ha sido editado correctamente.');
        }
        
        if ($shouldRedirect) {
            redirect($this->getRedirectUrl());
        }
    }
}
