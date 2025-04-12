<?php

namespace App\Notifications;

use App\Models\Facture;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $facture;
    protected $pdf;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, Facture $facture)
    {
        $this->order = $order;
        $this->facture = $facture;
        //$this->pdf = $pdf;
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
        $pdf = Pdf::loadView('factures.template', [
            'facture' => $this->facture,
            'order' => $this->order,
            'user' => $this->order->user,
            'items' => $this->order->items
        ]);

        $filename = 'facture-' . $this->facture->id . '.pdf';

        return (new MailMessage)
            ->subject('Votre commande #' . $this->order->id . ' a été expédiée')
            ->greeting('Bonjour ' . $notifiable->prenom . ' ' . $notifiable->nom . ',')
            ->line('Nous sommes heureux de vous informer que votre commande a été expédiée.')
            ->line('Vous trouverez ci-joint votre facture que vous pouvez télécharger et conserver sur votre téléphone.')
            ->action('Télécharger ma facture', route('order.facture.download', $this->order->id))
            ->line('Merci de faire confiance à BookStore !')
            ->attachData($pdf->output(), $filename, [
                'mime' => 'application/pdf',
            ]);
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
            'message' => 'Votre commande #' . $this->order->id . ' a été expédiée',
            'facture_num' => $this->facture->num_fac,
        ];
    }
}
