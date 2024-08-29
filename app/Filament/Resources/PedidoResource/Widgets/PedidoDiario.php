<?php

namespace App\Filament\Resources\PedidoResource\Widgets;

use App\Models\Pedido;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Widgets\ChartWidget;

class PedidoDiarioChart extends ChartWidget
{
    protected static ?string $heading = 'Estadísticas de Pedidos Mensuales';
    
    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $date = $currentMonth->copy()->subMonths($i);
            $months[$date->format('Y-m')] = $date->format('F Y');
        }
        return $months;
    }

    protected function getData(): array
    {
        $selectedMonth = $this->filter ?? Carbon::now()->format('Y-m');
        [$year, $month] = explode('-', $selectedMonth);

        $pedidosPorDia = Pedido::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->selectRaw('DAY(created_at) as dia, status, COUNT(*) as total')
            ->groupBy('dia', 'status')
            ->get()
            ->groupBy('status');

        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $labels = range(1, $daysInMonth);

        $datasets = [
            $this->createDataset('En Proceso', 'rgba(255, 206, 86, 0.2)', 'rgba(255, 206, 86, 1)', $daysInMonth),
            $this->createDataset('Entregado', 'rgba(31, 188, 31, 0.2)', 'rgba(31, 188, 31, 1)', $daysInMonth),
            $this->createDataset('Cancelado', 'rgba(255, 99, 132, 0.2)', 'rgba(255, 99, 132, 1)', $daysInMonth),
        ];

        foreach ($pedidosPorDia as $status => $pedidos) {
            $index = $this->getStatusIndex($status);
            foreach ($pedidos as $pedido) {
                $datasets[$index]['data'][$pedido->dia - 1] = $pedido->total;
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

    private function createDataset(string $label, string $backgroundColor, string $borderColor, int $daysInMonth): array
    {
        return [
            'label' => $label,
            'data' => array_fill(0, $daysInMonth, 0),
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