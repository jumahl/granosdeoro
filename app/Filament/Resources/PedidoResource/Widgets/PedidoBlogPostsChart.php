<?php

namespace App\Filament\Resources\PedidoResource\Widgets;

use App\Models\Pedido;
use Filament\Widgets\ChartWidget;

class PedidoBlogPostsChart extends ChartWidget
{
    protected static ?string $heading = 'Estadísticas de Pedidos';

    protected function getData(): array
    {
        $pedidosPorMes = Pedido::selectRaw('MONTH(fecha_pedido) as mes, status, COUNT(*) as total')
            ->groupBy('mes', 'status')
            ->get()
            ->groupBy('status');

        $labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $data = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'En Proceso',
                    'data' => array_fill(0, 12, 0),
                    'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Entregado',
                    'data' => array_fill(0, 12, 0),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Cancelado',
                    'data' => array_fill(0, 12, 0),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];

        foreach ($pedidosPorMes as $status => $pedidos) {
            foreach ($pedidos as $pedido) {
                $data['datasets'][$status == 'en proceso' ? 0 : ($status == 'entregado' ? 1 : 2)]['data'][$pedido->mes - 1] = $pedido->total;
            }
        }

        return $data;
    }

    protected function getType(): string
    {
        return 'bar' ;
    }
}
