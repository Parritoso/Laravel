<?php

namespace App\Notifications;

use App\Models\Producto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductAlertNotification extends Notification
{
    use Queueable;

    protected $producto;
    protected $tipo; // 'precio' o 'stock'
    protected $detalles;

    /**
     * Create a new notification instance.
     */
    public function __construct(Producto $producto, string $tipo, array $detalles = [])
    {
        $this->producto = $producto;
        $this->tipo = $tipo;
        $this->detalles = $detalles;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
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

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
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
