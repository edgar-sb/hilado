<?php

namespace App\Notifications\Proveedores;

use App\Entities\Compras\Compra;
use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ComplementoPendienteMultiple extends Notification implements ShouldQueue
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
            'estatus' => 'complemento-pendiente',
            'actionLines' => [
                'Por favor confirma el pago y carga en el sistema el complemento de pago correspondiente.',
            ],
            'note' => 'AVISO: Es motivo de suspensión de pago en caso de no subir complementos pendientes.'
        ]);
        $mail->subject('Proveedor: Complemento de pago pendiente de las compras: #'.$this->compras->implode('no_compra', ' #'));
        $mail->greeting('Hola '.$this->user->nombre.',');
        $mail->introLines = [
            'Se ha realizado el pago de las compras: #'.$this->compras->implode('no_compra', ' #'),
        ];
        $mail->action('Iniciar sesión', route('login'));
        $mail->outroLines = [
            'Gracias por usar nuestra plataforma.'
        ];
        $mail->salutation('Saludos, Hilados de Alta Calidad.');

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
