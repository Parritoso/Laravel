<?php

namespace App\Notifications;

use App\Models\Producto;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductAlertNotification extends Notification
{
    use Queueable;

    protected Producto $producto;
    protected string $tipo;
    protected array $detalles;

    public function __construct(Producto $producto, string $tipo, array $detalles = [])
    {
        $this->producto = $producto;
        $this->tipo = $tipo;
        $this->detalles = $detalles;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $asunto = match($this->tipo) {
            'precio'           => __('notifications.subjects.price'),
            'stock_bajo'       => __('notifications.subjects.stock_bajo'),
            'stock_agotado'    => __('notifications.subjects.stock_agotado'),
            'stock_disponible' => __('notifications.subjects.stock_disponible'),
        };

        return (new MailMessage)
            ->subject($asunto)
            ->greeting(__('notifications.greeting', ['name' => $notifiable->name]))
            ->line($this->obtenerMensajeTexto())
            ->action(__('notifications.view_product'), route('products.show', $this->producto));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'producto_id' => $this->producto->id,
            'tipo'        => $this->tipo,
            'mensaje'     => $this->obtenerMensajeTexto(),
            'url'         => route('products.show', $this->producto)
        ];
    }

    private function obtenerMensajeTexto(): string
    {
        return match($this->tipo) {
            'precio' => __('notifications.messages.price', [
                'product' => $this->producto->nombre,
                'price'   => number_format($this->detalles['nuevo'], 2, ',', '.')
            ]),
            'stock_bajo' => __('notifications.messages.stock_bajo', [
                'stock'   => $this->producto->stock,
                'product' => $this->producto->nombre
            ]),
            'stock_agotado' => __('notifications.messages.stock_agotado', [
                'product' => $this->producto->nombre
            ]),
            'stock_disponible' => __('notifications.messages.stock_disponible', [
                'product' => $this->producto->nombre,
                'stock'   => $this->producto->stock
            ]),
        };
    }
}
