<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Pedido $pedido)
    {
    }

    /**
     * Define el asunto del correo usando el número de factura generado para el pedido.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('email/orders/confirmation.subject', [
                'invoice' => $this->pedido->factura->numero_factura,
            ]),
        );
    }

    /**
     * Usa una vista Blade para poder traducir y maquetar el resumen de compra.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.confirmation',
            with: [
                'order' => $this->pedido,
            ],
        );
    }

    /**
     * De momento la confirmación no adjunta PDF; la factura se muestra desde el panel de pedidos.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
