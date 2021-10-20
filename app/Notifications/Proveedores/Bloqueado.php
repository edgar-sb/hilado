<?php

namespace App\Notifications\Proveedores;

use App\Entities\Compras\Compra;
use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Bloqueado extends Notification implements ShouldQueue
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
            'estatus' => 'bloqueado',
        ]);
        $mail->subject('Proveedor: Cuenta bloqueada');
        $mail->greeting('Hola '.$this->user->nombre.',');
        $mail->introLines = [
            'Tu cuenta de la plataforma Hilados ha sido bloqueada.',
            'El acuse y carta porte de la orden de compra: #'.$this->compra->no_pedido.' no fueron ingresados durante el periodo de vigencia.',
            'Por favor, comunicate con nosotros.',
        ];

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
