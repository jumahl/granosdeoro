<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function generarFactura($pedidoId)
    {
        $pedido = Pedido::with('comprador', 'detallesPedidos.producto')->findOrFail($pedidoId);
        $pdf = Pdf::loadView('factura', ['pedido' => $pedido]);

        return $pdf->download('factura_'.$pedido->id.'.pdf');
    }
}
