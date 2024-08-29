<?php

namespace App\Filament\Resources\CompradorResource\Pages;

use App\Filament\Resources\CompradorResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditComprador extends EditRecord
{
    protected static string $resource = CompradorResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Comprador editado')
            ->body('El comprador ha sido editado correctamente.');
    }
}
