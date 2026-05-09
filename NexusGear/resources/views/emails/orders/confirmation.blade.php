<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('email/orders/confirmation.title') }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #2D3748; line-height: 1.5;">
    <h1 style="color: #117864;">{{ __('email/orders/confirmation.heading') }}</h1>
    <p>{{ __('email/orders/confirmation.greeting', ['name' => $order->usuario->name, 'id' => $order->id]) }}</p>

    <p>
        <strong>{{ __('email/orders/confirmation.invoice_label') }}</strong> {{ $order->factura->numero_factura }}<br>
        <strong>{{ __('email/orders/confirmation.status_label') }}</strong> {{ $order->estado_label }}<br>
        <strong>{{ __('email/orders/confirmation.date_label') }}</strong> {{ $order->fecha_formateada }}
    </p>

    <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse;">
        <thead>
            <tr style="background: #f8fafc;">
                <th align="left">{{ __('email/orders/confirmation.col_product') }}</th>
                <th align="right">{{ __('email/orders/confirmation.col_qty') }}</th>
                <th align="right">{{ __('email/orders/confirmation.col_price') }}</th>
                <th align="right">{{ __('email/orders/confirmation.col_subtotal') }}</th>
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
        {{ __('email/orders/confirmation.summary_subtotal') }} <strong>{{ $order->factura->subtotal_formateado }}</strong><br>
        {{ __('email/orders/confirmation.summary_iva') }} <strong>{{ $order->factura->iva_formateado }}</strong><br>
        {{ __('email/orders/confirmation.summary_total') }} <strong>{{ $order->factura->total_formateado }}</strong>
    </p>
</body>
</html>
