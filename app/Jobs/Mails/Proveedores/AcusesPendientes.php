<?php

namespace App\Jobs\Mails\Proveedores;

use App\Notifications\Proveedores\Pendientes as NotificacionPendientes;
use App\Entities\Proveedores\Proveedor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AcusesPendientes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //factura
        //acuse-y-carta-porte
        //complemento
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $delay = 0;
        foreach (Proveedor::all() as $proveedor) {
            /*
             * Obtiene las compras del proveedor con acuse pendiente
             */
            $compras_acuse_pendiente =  $proveedor->compras()
                ->whereHas('estatusActual', function (Builder $query) {
                    $query->where('compras_estatus.clave', 'acuse-y-carta-porte');
                })->whereHas('estatus', function (Builder $query) {
                    $query->where('compras_estatus_log.estatus', 'pendiente');
                })->get();
            /*
             * Si tiene algún pendiente se notificará al proveedor
             */
            if($compras_acuse_pendiente->count() > 0) {
                $delay++;
                $proveedor->user->notify((new NotificacionPendientes(
                    $proveedor->user,
                    null,
                    $compras_acuse_pendiente,
                    null
                ))->delay(now()->addMinutes($delay)));
            }
        }
    }
}
