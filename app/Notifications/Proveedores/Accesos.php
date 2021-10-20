<?php

namespace App\Notifications\Proveedores;

use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Accesos extends Notification implements ShouldQueue
{
    use Queueable;


    public $user;
    public $password;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
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
            ->subject('Proveedor: Credenciales de acceso a la plataforma')
            ->greeting('Hola '.$this->user->nombre.',')
            ->line('Has sido registrado en la plataforma Hilados')
            ->line('Tus credenciales de acceso son:')
            ->line('Correo electrónico: '.$this->user->email)
            ->line('Contraseña: '.$this->password)
            ->action('Iniciar sesión', route('login'))
            ->line('Gracias por usar nuestra plataforma.')
            ->salutation('Saludos, Hilados de Alta Calidad');

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
