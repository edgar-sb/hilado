<?php

namespace App\Notifications\Proveedores;

use App\Entities\Compras\Compra;
use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Pendientes extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $compras_factura;
    public $compras_acuse;
    public $compras_complemento;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $compras_factura, $compras_acuse, $compras_complemento)
    {
        $this->user = $user;
        $this->compras_factura = $compras_factura;
        $this->compras_acuse = $compras_acuse;
        $this->compras_complemento = $compras_complemento;
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
            ->subject('Proveedor: Pendientes')
            ->greeting('Hola '.$this->user->nombre.',')
            ->line('Tienes acciones pendientes en tus compras asignadas.');
        if (isset($this->compras_factura) && $this->compras_factura->count() > 0) {
            $mail = $mail->line('Compras con factura pendiente.');
            foreach ($this->compras_factura as $compra) {
                $mail = $mail->line(' * Número de compra: #'.$compra->no_pedido.'.');
            }
        }
        if (isset($this->compras_acuse) && $this->compras_acuse->count() > 0) {
            $mail = $mail->line('Compras con acuse y carta porte pendientes.');
            foreach ($this->compras_acuse as $compra) {
                $mail = $mail->line(' * Número de compra: #'.$compra->no_pedido.'.');
            }
        }
        if (isset($this->compras_complemento) && $this->compras_complemento->count() > 0) {
            $mail = $mail->line('Compras con complemento de pago pendiente.');
            foreach ($this->compras_complemento as $compra) {
                $mail = $mail->line(' * Número de compra: #'.$compra->no_pedido.'.');
            }
        }
        $mail = $mail->line('Por favor, ingresa en la plataforma para continuar con el proceso de cada compra.')
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
