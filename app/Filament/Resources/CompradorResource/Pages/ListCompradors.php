<?php

namespace App\Filament\Resources\CompradorResource\Pages;

use App\Filament\Resources\CompradorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompradors extends ListRecords
{
    protected static string $resource = CompradorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Agregar Comprador'),
        ];
    }
}
