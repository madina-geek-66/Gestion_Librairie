<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $oldStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, $oldStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
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
        $url = route('order.show', $this->order->id);

        return (new MailMessage)
            ->subject('Mise à jour de votre commande #' . $this->order->id)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Le statut de votre commande a été mis à jour.')
            ->line('Nouveau statut: ' . $this->order->statut)
            ->action('Voir ma commande', $url)
            ->line('Merci de votre confiance !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'Votre commande #' . $this->order->id . ' est maintenant ' . $this->order->statut . '.',
            'old_status' => $this->oldStatut,
            'new_status' => $this->order->statut,
        ];
    }
}
