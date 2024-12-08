<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Granos de Oro - Factura</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #4a4a4a;
            border-bottom: 2px solid #4a4a4a;
            padding-bottom: 10px;
        }
        h2 {
            color: #666;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total {
            font-weight: bold;
            font-size: 1.2em;
            margin-top: 20px;
            text-align: right;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h1>Granos de Oro</h1>
    <h2>No. Factura #{{ $pedido->id }}</h2>
    
    <div class="info">
    <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
    <p><strong>Comprador:</strong> {{ $pedido->comprador->nombre }}</p>
    <p><strong>Dirección:</strong> {{ $pedido->comprador->direccion }}</p>
    <p><strong>Fecha del pedido:</strong> {{ $pedido->fecha_pedido }}</p>
    <p><strong>Contacto:</strong> {{ $pedido->comprador->contacto }}</p>
</div>

    <h2>Detalles del Pedido</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->detallesPedidos as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ number_format($detalle->producto->precio, 2) }}</td>
                    <td>{{ number_format($detalle->producto->precio * $detalle->cantidad, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total del Pedido: ${{ number_format($pedido->total, 2) }}
    </div>
</body>
</html>