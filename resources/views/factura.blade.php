<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Granos de Oro</title>
    <style>
        /* Agrega aquí tus estilos para la factura */
    </style>
</head>
<body>
    <h1>Factura del Pedido #{{ $pedido->id }}</h1>
    <p>Fecha: {{ $pedido->fecha_pedido }}</p>
    <p>Comprador: {{ $pedido->comprador->nombre }}</p>
    <p>Dirección: {{ $pedido->comprador->direccion }}</p>
    <p>Contacto: {{ $pedido->comprador->contacto }}</p>

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

    <h2>Total del Pedido: {{ number_format($pedido->total, 2) }}</h2>
</body>
</html>
