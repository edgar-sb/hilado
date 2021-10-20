<?php

namespace App\Notifications\Logistica;

use App\Entities\Compras\Compra;
use App\Entities\Compras\CompraEstatus;
use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ValidacionPendiente extends Notification implements ShouldQueue
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
    public function __construct(User $user, Compra $compra, $path)
    {
        $this->user = $user;
        $this->compra = $compra;
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
        $estatus_factura = CompraEstatus::where('clave', 'factura')->first();
        $estatus_factura_log = $this->compra->estatusLog()->where('estatus_id', $estatus_factura->id)->first();

        $mail = (new MailMessage)->markdown('vendor.notifications.email', [
            'compra' => $this->compra,
            'user' => $this->user,
            'estatus' => 'validacion-pendiente',
            'factura_estatus_log' => $estatus_factura_log,
            'actionLines' => [
                'Por favor, ingresa en la plataforma para continuar con la validación de los archivos.',
            ]
        ]);
        $mail->subject('Logística: Validación pendiente OC: #'.$this->compra->no_compra);
        $mail->greeting('Hola '.$this->user->nombre.',');
        $mail->introLines = [
            'El proveedor '.$this->compra->proveedor->nombre.' ha cargado las evidencias de entrega de la orden de la compra: #'.$this->compra->no_pedido.'.',
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
