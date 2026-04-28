<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Confirmación de pedido</title>
</head>
<body style="font-family: Arial, sans-serif; color: #2D3748; line-height: 1.5;">
    <h1 style="color: #117864;">Pedido confirmado</h1>
    <p>Hola {{ $order->usuario->name }}, hemos recibido tu pedido #{{ $order->id }}.</p>

    <p>
        <strong>Factura:</strong> {{ $order->factura->numero_factura }}<br>
        <strong>Estado:</strong> {{ $order->estado_label }}<br>
        <strong>Fecha:</strong> {{ $order->fecha_formateada }}
    </p>

    <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse;">
        <thead>
            <tr style="background: #f8fafc;">
                <th align="left">Producto</th>
                <th align="right">Cantidad</th>
                <th align="right">Precio</th>
                <th align="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->lineas as $line)
                <tr>
                    <td>{{ $line->producto->nombre }}</td>
                    <td align="right">{{ $line->cantidad }}</td>
                    <td align="right">{{ $line->precio_unitario_formateado }}</td>
                    <td align="right">{{ $line->subtotal_formateado }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p align="right">
        Subtotal: <strong>{{ $order->factura->subtotal_formateado }}</strong><br>
        IVA: <strong>{{ $order->factura->iva_formateado }}</strong><br>
        Total: <strong>{{ $order->factura->total_formateado }}</strong>
    </p>
</body>
</html>
