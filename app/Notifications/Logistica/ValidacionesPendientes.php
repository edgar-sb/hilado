<?php

namespace App\Notifications\Logistica;

use App\Entities\Compras\Compra;
use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ValidacionesPendientes extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $compras;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $compras)
    {
        $this->user = $user;
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
        $mail = (new MailMessage)
            ->subject('Logística: Validaciones pendientes')
            ->greeting('Hola '.$this->user->nombre.',')
            ->line('Tienes validaciones pendientes.');
        foreach ($this->compras as $compra) {
            $mail = $mail->line(' * El proveedor '.$compra->proveedor->nombre.' ha cargado las evidencias de entrega de la orden de la compra: #'.$compra->no_pedido.'.');
        }
        $mail = $mail->line('Por favor, ingresa en la plataforma para continuar con la validación de los archivos.')
            ->action('Iniciar sesión', route('login'))
            ->line('Gracias por usar nuestra plataforma.')
            ->salutation('Saludos, Hilados de Alta Calidad');
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
