<?php

namespace App\Jobs\Proveedores;

use App\Entities\Compras\Compra;
use App\Notifications\Proveedores\AcusePendiente;
use App\Notifications\Proveedores\Bloqueado;
use App\Notifications\Proveedores\ComplementoPendiente;
use App\Notifications\Proveedores\FacturaPendiente;
use App\Notifications\Proveedores\Pendientes as NotificacionPendientes;
use App\SAE\Models\Proveedor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Bloquear implements ShouldQueue
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
        try {
            DB::beginTransaction();
            $delay = 0;
            foreach (\App\Entities\Proveedores\Proveedor::all() as $proveedor) {
                /*
                 * Obtiene las compras del proveedor con acuse pendiente
                 */
                $compras_acuse_pendiente = $proveedor->compras()
                    ->whereHas('estatusActual', function (Builder $query) {
                        $query->where('compras_estatus.clave', 'acuse-y-carta-porte');
                    })->whereHas('estatus', function (Builder $query) {
                        $query->where('compras_estatus_log.estatus', 'pendiente');
                    })->get();

                $compras_acuse_pendiente = $compras_acuse_pendiente->filter(function ($compra, $key) {
                    return (!Str::contains($compra->dias_vencer_factura, '-') && $compra->dias_vencer_factura != "Factura no aprobada");
                });

                foreach ($compras_acuse_pendiente as $compra) {
                    $compra->bloqueado = true;
                    $compra->update();
                }

                /*
                 * Obtiene las compras del proveedor con complemento de pago pendiente
                 */
                $compras_complemento_pendiente = $proveedor->compras()
                    ->whereHas('estatusActual', function (Builder $query) {
                        $query->where('compras_estatus.clave', 'complemento');
                    })->whereHas('estatus', function (Builder $query) {
                        $query->where('compras_estatus_log.estatus', 'pendiente');
                    })->get();

                $compras_complemento_pendiente = $compras_complemento_pendiente->filter(function ($compra, $key) {
                    return (!Str::contains($compra->dias_vencer_factura,'-') && $compra->dias_vencer_factura != "Factura no aprobada") || (!Str::contains($compra->dias_vencer_pago, '-') && $compra->dias_vencer_pago != "Pago no realizado");
                });

                foreach ($compras_complemento_pendiente as $compra) {
                    $compra->bloqueado = true;
                    $compra->update();
                }
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::alert($ex->getMessage());
        }
    }
}
