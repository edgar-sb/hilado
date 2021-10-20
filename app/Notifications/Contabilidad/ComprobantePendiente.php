<?php

namespace App\Notifications\Contabilidad;

use App\Entities\Compras\Compra;
use App\Entities\Compras\CompraEstatus;
use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ComprobantePendiente extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $compra;
    public $estatus_log;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Compra $compra, $estatus_log)
    {
        $this->user = $user;
        $this->compra = $compra;
        $this->estatus_log = $estatus_log;
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
        $estatus_factura = CompraEstatus::where('clave', 'factura')->first();
        $estatus_factura_log = $this->compra->estatusLog()->where('estatus_id', $estatus_factura->id)->first();

        $mail = (new MailMessage)->markdown('vendor.notifications.email', [
            'compra' => $this->compra,
            'user' => $this->user,
            'estatus' => 'comprobante-pendiente',
            'factura_estatus_log' => $estatus_factura_log,
            'acuse_estatus_log' => $this->estatus_log,
            'actionLines' => [
                'Por favor, continúa con el proceso ingresando el comprobante del pago.',
            ]
        ]);
        $mail->subject('Contabilidad: Comprobante de pago pendiente OC: #'.$this->compra->no_compra);
        $mail->greeting('Hola '.$this->user->nombre.',');
        $mail->introLines = [
            'Los documentos de la compra: #'.$this->compra->no_pedido.' han sido aprobados.',
        ];
        $mail->action('Ir a la orden de compra', route('compras.edit', $this->compra));
        $mail->outroLines = ['Gracias por usar nuestra plataforma.'];
        $mail->salutation('Saludos, Hilados de Alta Calidad');

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
