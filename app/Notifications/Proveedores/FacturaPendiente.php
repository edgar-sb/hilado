<?php

namespace App\Notifications\Proveedores;

use App\Entities\Compras\Compra;
use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FacturaPendiente extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $compra;
    public $rol;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Compra $compra)
    {
        $this->user = $user;
        $this->compra = $compra;
        $this->rol = 'proveedor';
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
            'estatus' => 'factura-pendiente',
            'actionLines' => [
                'Por favor, ingresa la factura de compra en el sistema para continuar con el seguimiento de entrega y pago.',
                'Recuerda que el plazo de pago corre desde que se sube la factura.',
            ]
        ]);
        $mail->subject('Proveedor: Nueva orden Asignada ODC: #'.$this->compra->no_pedido);
        $mail->greeting('Hola '.$this->user->nombre.',');
        $mail->introLines = [
            'Tienes una nueva orden de compra en la plataforma Hilados de Alta Calidad.',
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
