<?php

namespace App\Filament\Resources\PedidoResource\Widgets;

use App\Models\Pedido;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class PedidoBlogPostsChart extends ChartWidget
{
    protected static ?string $heading = 'Estadísticas de Pedidos';
    
    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        $currentYear = Carbon::now()->year;
        $years = range($currentYear - 1, $currentYear);
        return array_combine($years, $years);
    }

    protected function getData(): array
    {
        $selectedYear = $this->filter ?? Carbon::now()->year;

        $pedidosPorMes = Pedido::selectRaw('MONTH(fecha_pedido) as mes, status, COUNT(*) as total')
            ->whereYear('fecha_pedido', $selectedYear)
            ->groupBy('mes', 'status')
            ->get()
            ->groupBy('status');

        $labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $datasets = [
            $this->createDataset('En Proceso', 'rgba(255, 206, 86, 0.2)', 'rgba(255, 206, 86, 1)'),
            $this->createDataset('Entregado', 'rgba(54, 162, 235, 0.2)', 'rgba(54, 162, 235, 1)'),
            $this->createDataset('Cancelado', 'rgba(255, 99, 132, 0.2)', 'rgba(255, 99, 132, 1)'),
        ];

        foreach ($pedidosPorMes as $status => $pedidos) {
            $index = $this->getStatusIndex($status);
            foreach ($pedidos as $pedido) {
                $datasets[$index]['data'][$pedido->mes - 1] = $pedido->total;
            }
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function createDataset(string $label, string $backgroundColor, string $borderColor): array
    {
        return [
            'label' => $label,
            'data' => array_fill(0, 12, 0),
            'backgroundColor' => $backgroundColor,
            'borderColor' => $borderColor,
            'borderWidth' => 1,
        ];
    }

    private function getStatusIndex(string $status): int
    {
        return match($status) {
            'en proceso' => 0,
            'entregado' => 1,
            'cancelado' => 2,
            default => 0,
        };
    }
}