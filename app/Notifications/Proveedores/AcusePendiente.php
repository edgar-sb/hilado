<?php

namespace App\Notifications\Proveedores;

use App\Entities\Compras\Compra;
use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Spatie\MediaLibrary\MediaStream;

class AcusePendiente extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $compra;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Compra $compra)
    {
        $this->user = $user;
        $this->compra = $compra;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)->markdown('vendor.notifications.email', [
            'compra' => $this->compra,
            'user' => $this->user,
            'estatus' => 'acuse-pendiente',
            'actionLines' => [
                'Por favor, ingresa el acuse y carta porte de entrega de la orden de la compra en el sistema para continuar con el seguimiento de entrega y pago.',
            ]
        ]);
        $mail->subject('Proveedor: Acuse pendiente OC: #'.$this->compra->no_compra);
        $mail->greeting('Hola '.$this->user->nombre.',');
        $mail->introLines = [
            'La factura que ingresaste a la orden de compra: #'.$this->compra->no_pedido.' ha sido aprobada.',
        ];
        $mail->action('Ir a la orden de compra', route('compras.edit', $this->compra));
        $mail->outroLines = ['Gracias por usar nuestra plataforma.'];
        $mail->salutation('Saludos, Hilados de Alta Calidad');

        if(isset(optional($this->user->proveedor)->emails)) {
            $mail->cc($this->user->proveedor->emails);
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
