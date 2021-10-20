<?php

namespace App\Notifications\Proveedores;

use App\Entities\Compras\Compra;
use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CompraFinalizadaMultiple extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $compra;
    public $compras;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $compras, Compra $compra)
    {
        $this->user = $user;
        $this->compra = $compra;
        $this->compras = $compras;
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
        $mail = (new MailMessage)->markdown('vendor.notifications.emailMultiple', [
            'compra' => $this->compra,
            'compras' => $this->compras,
            'user' => $this->user,
            'estatus' => 'compra-finalizada',
        ]);
        $mail->subject('Proveedor: Complemento de pago aprobado de las compras: #'.$this->compras->implode('no_compra', ' #'));
        $mail->greeting('Hola '.$this->user->nombre.',');
        $mail->introLines = [
            'EL complemento de pago que ingresaste ha sido aprobado.',
            'Las compras: #'.$this->compras->implode('no_compra', ' #').' se ha finalizado correctamente.',
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