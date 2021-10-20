<?php

namespace App\Notifications\Proveedores;

use App\Entities\Compras\Compra;
use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AcuseAprobadoParcial extends Notification implements ShouldQueue
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
        $mail = (new MailMessage)->markdown('vendor.notifications.email', [
            'compra' => $this->compra,
            'user' => $this->user,
            'estatus' => 'acuse-aprobado-parcial',
            'acuse_estatus_log' => $this->estatus_log,
            'actionLines' => [
                'Por favor, ingresa en la plataforma y carga el siguiente acuse y carta porte.',
            ]
        ]);
        $mail->subject('Proveedor: Acuse y carta porte aprobados parcialmente OC: #'.$this->compra->no_compra);
        $mail->greeting('Hola '.$this->user->nombre.',');
        $mail->introLines = [
            'El acuse y carta porte que ingresaste a la orden de compra: #'.$this->compra->no_pedido.' han sido aprobados.',
            'Comentarios: '.$this->compra->parcial_comentarios,
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
