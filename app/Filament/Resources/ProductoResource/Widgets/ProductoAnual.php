<?php

namespace App\Filament\Resources\ProductoResource\Widgets;

use App\Models\DetallePedido;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ProductoAnual extends ChartWidget
{
    protected static ?string $heading = 'Estadísticas de Productos Vendidos Anuales';

    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        $currentYear = Carbon::now()->year;
        $years = [];
        for ($i = 0; $i < 5; $i++) {
            $year = $currentYear - $i;
            $years[$year] = $year;
        }
        return $years;
    }

    protected function getData(): array
    {
        $selectedYear = $this->filter ?? Carbon::now()->year;

        $productosVendidosPorMes = DetallePedido::join('pedidos', 'detalle_pedidos.id_pedido', '=', 'pedidos.id')
            ->whereYear('pedidos.created_at', $selectedYear)
            ->join('productos', 'detalle_pedidos.id_producto', '=', 'productos.id')
            ->selectRaw('MONTH(pedidos.created_at) as mes, productos.nombre, SUM(detalle_pedidos.cantidad) as total')
            ->groupBy('mes', 'productos.nombre')
            ->get()
            ->groupBy('nombre');

        $labels = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        $datasets = [];
        foreach ($productosVendidosPorMes as $nombreProducto => $productos) {
            $dataset = $this->createDataset($nombreProducto, 12);
            foreach ($productos as $producto) {
                $dataset['data'][$producto->mes - 1] = $producto->total;
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

    private function createDataset(string $label, int $months): array
    {
        return [
            'label' => $label,
            'data' => array_fill(0, $months, 0),
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
