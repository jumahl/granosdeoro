<?php

namespace App\Filament\Resources\ProductoResource\Widgets;

use App\Models\DetallePedido;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ProductoDiario extends ChartWidget
{
    protected static ?string $heading = 'Estadísticas de Productos Vendidos Mensuales';

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

        $productosVendidosPorDia = DetallePedido::join('pedidos', 'detalle_pedidos.id_pedido', '=', 'pedidos.id')
            ->whereYear('pedidos.created_at', $year)
            ->whereMonth('pedidos.created_at', $month)
            ->join('productos', 'detalle_pedidos.id_producto', '=', 'productos.id')
            ->selectRaw('DAY(pedidos.created_at) as dia, productos.nombre, SUM(detalle_pedidos.cantidad) as total')
            ->groupBy('dia', 'productos.nombre')
            ->get()
            ->groupBy('nombre');

        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $labels = range(1, $daysInMonth);

        $datasets = [];
        foreach ($productosVendidosPorDia as $nombreProducto => $productos) {
            $dataset = $this->createDataset($nombreProducto, $daysInMonth);
            foreach ($productos as $producto) {
                $dataset['data'][$producto->dia - 1] = $producto->total;
            }
            $datasets[] = $dataset;
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

    private function createDataset(string $label, int $daysInMonth): array
    {
        return [
            'label' => $label,
            'data' => array_fill(0, $daysInMonth, 0),
            'backgroundColor' => $this->randomColor(),
            'borderColor' => $this->randomColor(),
            'borderWidth' => 1,
        ];
    }

    private function randomColor(): string
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}