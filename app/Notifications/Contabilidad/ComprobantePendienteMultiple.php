<?php

namespace App\Notifications\Contabilidad;

use App\Entities\Compras\Compra;
use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Spatie\MediaLibrary\MediaStream;

class ComprobantePendienteMultiple extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $compra;
    public $compras;
    public $estatus_log;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $compras, Compra $compra, $estatus_log)
    {
        $this->user = $user;
        $this->compra = $compra;
        $this->compras = $compras;
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
        $mail = (new MailMessage)->markdown('vendor.notifications.emailMultiple', [
            'compra' => $this->compra,
            'compras' => $this->compras,
            'user' => $this->user,
            'estatus' => 'comprobante-pendiente',
            'complemento_estatus_log' => $this->estatus_log,
            'actionLines' => [
                'Por favor, continúa con el proceso ingresando los comprobantes de pago.',
            ]
        ]);
        $mail->subject('Contabilidad: Compras con comprobante de pago pendiente: #'.$this->compras->implode('no_compra', ' #'));
        $mail->greeting('Hola '.$this->user->nombre.',');
        $mail->introLines = [
            'Los documentos de las compras: #'.$this->compras->implode('no_compra', ' #').' han sido aprobados.',
        ];
        $mail->action('Iniciar sesión', route('login'));
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
