<?php

namespace App\Filament\Resources\PedidoResource\Widgets;

use App\Models\Pedido;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PedidoStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $pedidosEnProceso = Pedido::where('status', 'en proceso')->count();
        $pedidosEntregados = Pedido::where('status', 'entregado')->count();
        $pedidosCancelados = Pedido::where('status', 'cancelado')->count();

        return [
            Stat::make('Pedidos en Proceso', $pedidosEnProceso),
            Stat::make('Pedidos Entregados', $pedidosEntregados),
            Stat::make('Pedidos Cancelados', $pedidosCancelados),
        ];
    }
}
