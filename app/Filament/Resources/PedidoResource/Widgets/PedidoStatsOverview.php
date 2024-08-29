<?php

namespace App\Filament\Resources\PedidoResource\Widgets;

use App\Models\Pedido;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class PedidoStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();

        $pedidosEnProceso = Pedido::whereDate('fecha_pedido', $today)
            ->where('status', 'en proceso')
            ->count();

        $pedidosEntregados = Pedido::whereDate('fecha_pedido', $today)
            ->where('status', 'entregado')
            ->count();

        $pedidosCancelados = Pedido::whereDate('fecha_pedido', $today)
            ->where('status', 'cancelado')
            ->count();

        return [
            Stat::make('Pedidos en Proceso', $pedidosEnProceso)
                ->description('Hoy')
                ->color('warning'),
            Stat::make('Pedidos Entregados', $pedidosEntregados)
                ->description('Hoy')
                ->color('success'),
            Stat::make('Pedidos Cancelados', $pedidosCancelados)
                ->description('Hoy')
                ->color('danger'),
        ];
    }
}