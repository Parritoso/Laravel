@extends('layouts.email')

@section('title', __('email/orders/confirmation.title'))

@section('content')
    <h2 style="margin-top: 0; margin-bottom: 12px; color: #117864; font-size: 24px; font-weight: 800; line-height: 1.3;">
        {{ __('email/orders/confirmation.heading') }}
    </h2>
    
    <p style="color: #2D3748; font-size: 15px; line-height: 1.6; margin-bottom: 24px;">
        {{ __('email/orders/confirmation.greeting', ['name' => $order->usuario->name, 'id' => $order->id]) }}
    </p>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; border: 1px solid rgba(45, 55, 72, 0.08); border-radius: 0.75rem; margin-bottom: 30px;">
        <tr>
            <td style="padding: 20px; font-size: 14px; color: #2D3748; line-height: 1.7;">
                <strong>{{ __('email/orders/confirmation.invoice_label') }}</strong> <span style="color: #64748b;">{{ $order->factura->numero_factura }}</span><br>
                <strong>{{ __('email/orders/confirmation.status_label') }}</strong> <span style="color: #64748b;">{{ $order->estado_label }}</span><br>
                <strong>{{ __('email/orders/confirmation.date_label') }}</strong> <span style="color: #64748b;">{{ $order->fecha_formateada }}</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellpadding="10" cellspacing="0" border="0" style="border-collapse: collapse; font-size: 14px; margin-bottom: 30px;">
        <thead>
            <tr style="background-color: #f8fafc; border-bottom: 2px solid rgba(45, 55, 72, 0.08);">
                <th align="left" style="color: #2D3748; font-weight: 600; padding: 12px 10px;">{{ __('email/orders/confirmation.col_product') }}</th>
                <th align="right" style="color: #2D3748; font-weight: 600; padding: 12px 10px;">{{ __('email/orders/confirmation.col_qty') }}</th>
                <th align="right" style="color: #2D3748; font-weight: 600; padding: 12px 10px;">{{ __('email/orders/confirmation.col_price') }}</th>
                <th align="right" style="color: #2D3748; font-weight: 600; padding: 12px 10px;">{{ __('email/orders/confirmation.col_subtotal') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->lineas as $line)
                <tr style="border-bottom: 1px solid rgba(45, 55, 72, 0.05);">
                    <td style="padding: 12px 10px; color: #2D3748; font-weight: 600;">{{ $line->producto->nombre }}</td>
                    <td align="right" style="padding: 12px 10px; color: #64748b;">{{ $line->cantidad }}</td>
                    <td align="right" style="padding: 12px 10px; color: #64748b;">{{ $line->precio_unitario_formateado }}</td>
                    <td align="right" style="padding: 12px 10px; color: #2D3748; font-weight: 600;">{{ $line->subtotal_formateado }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="right" style="font-size: 14px; color: #64748b; line-height: 1.8; padding-right: 10px;">
                {{ __('email/orders/confirmation.summary_subtotal') }} <strong style="color: #2D3748;">{{ $order->factura->subtotal_formateado }}</strong><br>
                {{ __('email/orders/confirmation.summary_iva') }} <strong style="color: #2D3748;">{{ $order->factura->iva_formateado }}</strong><br>
                <span style="font-size: 16px; font-weight: 800; color: #117864;">
                    {{ __('email/orders/confirmation.summary_total') }} <span style="font-size: 20px;">{{ $order->factura->total_formateado }}</span>
                </span>
            </td>
        </tr>
    </table>
@endsection