<?php

namespace App\Jobs\Mails\Logistica;

use App\Entities\Compras\Compra;
use App\Entities\User;
use App\Notifications\Logistica\ValidacionesPendientes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Pendientes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /*
         * Obtiene las compras con validación pendiente y notifica a los usuarios de logística si hay alguna.
         */
        $compras_validacion_pendiente = Compra::whereHas('estatusActual', function (Builder $query) {
            $query->where('compras_estatus.clave', 'validacion');
        })->whereHas('estatus', function (Builder $query) {
            $query->where('compras_estatus_log.estatus', 'pendiente');
        })->get();
        if($compras_validacion_pendiente->count() > 0) {
            $delay = 0;
            foreach (User::role('logistica')->get() as $logistica) {
                $delay++;
                $logistica->notify((new ValidacionesPendientes($logistica, $compras_validacion_pendiente))->delay(now()->addMinutes($delay)));
            }
        }
    }
}
