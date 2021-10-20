<?php

namespace App\Notifications\Contabilidad;

use App\Entities\Compras\Compra;
use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Spatie\MediaLibrary\MediaStream;

class ComplementoPago extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $compra;
    public $estatus_log;
    public $path;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
     public function __construct(User $user, Compra $compra, $estatus_log, $path)
     {
         $this->user = $user;
         $this->compra = $compra;
         $this->estatus_log = $estatus_log;
         $this->path = $path;
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
             'estatus' => 'comprobante-pendiente',
             'complemento_estatus_log' => $this->estatus_log,
         ]);
         $mail->subject('Contabilidad: Compra finalizada OC: #'.$this->compra->no_compra);
         $mail->greeting('Hola '.$this->user->nombre.',');
         $mail->introLines = [
             'Los documentos de la compra: #'.$this->compra->no_pedido.' han sido aprobados.',
         ];
         $mail->action('Ir a la orden de compra', route('compras.edit', $this->compra));
         $mail->outroLines = ['Gracias por usar nuestra plataforma.'];
         $mail->salutation('Saludos, Hilados de Alta Calidad');
         $mail->attach(storage_path('app/public/temp/'.$this->path));

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
