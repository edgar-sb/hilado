<?php

namespace App\Http\Controllers\Dashboard\Traits;


use App\Entities\Compras\CompraEstatus;
use App\Entities\Proveedores\Proveedor;
use App\Notifications\Proveedores\FacturaPendiente;
use App\SAE\Models\OrdenCompra;
use App\SAE\Models\Proveedor as SAE_Proveedor;
use App\SAE\SAERepository;
use Illuminate\Http\Request;

trait SincronizaSAE {

    public function getODCVinculados() {
        $total_ordenes_compras = OrdenCompra::MesActual()
            ->whereNull('FECHA_CANCELA')
            ->count();
        $provedores_claves = $this->SAERepository->proveedores()
            ->whereIn('RFC', Proveedor::pluck('rfc')->toArray())->pluck('CLAVE')->toArray();
        $ordenes_compras = OrdenCompra::MesActual()
            ->whereIn('CVE_CLPV', $provedores_claves)
            ->whereNull('FECHA_CANCELA')
            ->get();

        return ['total' => $total_ordenes_compras, 'ordenes' => $ordenes_compras];
    }
    public function getODCNoVinculados() {
        $provedores_claves = $this->SAERepository->proveedores()
            ->whereNotIn('RFC', Proveedor::pluck('rfc')->toArray())->pluck('CLAVE')->toArray();
        $ordenes_compras = OrdenCompra::MesActual()
            ->whereIn('CVE_CLPV', $provedores_claves)
            ->whereNull('FECHA_CANCELA')
            ->get();

        return $ordenes_compras;
    }

    public function select_ordenes_compras($ordenes_compras) {
        $select_ordenes_compras = [];
        foreach ($ordenes_compras as $orden_compra) {
            $select_ordenes_compras[$orden_compra->CVE_DOC] = $orden_compra->CVE_DOC." - ".$orden_compra->proveedor->RFC." - ".$orden_compra->fecha_format;
        }

        return $select_ordenes_compras;
    }

    public function sincroniza() {
        $ordenes_sincronizadas = 0;
        $ordenes_repetidas = 0;
        $response = $this->getODCVinculados();
        $ordenes_compras = $response['ordenes'];
        $delay = 0;
        foreach ($ordenes_compras as $orden_compra) {
            $proveedor = Proveedor::where('rfc', $orden_compra->proveedor->RFC)->first();
            if (isset($proveedor)) {
                if (!in_array($orden_compra->CVE_DOC, $proveedor->compras()->pluck('no_pedido')->toArray())) {
                    $estatus = CompraEstatus::orderBy('orden', 'asc')->first();
                    $compra = $proveedor->compras()->create([
                        'estatus_id' => $estatus->siguiente_estatus->id,
                        'no_pedido' => $orden_compra->CVE_DOC,
                        'pedido_sae' => $orden_compra->pedido_sae,
                        'fecha_envio' => $orden_compra->FECHA_DOC,
                        'fecha_flete' => $orden_compra->FECHA_REC,
                        'almacen' => $orden_compra->almacen? $orden_compra->almacen->DESCR : null,
                        'monto' => $orden_compra->monto_int,
                        'impuesto' => $orden_compra->impuesto_int,
                        'importe' => $orden_compra->importe_int,
                        'direccion' => $orden_compra->OBS_COND,
                    ]);
                    $compra->estatus()->attach($estatus, ['estatus' => 'aprobado', 'user_id' => auth()->user()->id]);
                    $compra->estatus()->attach($estatus->siguiente_estatus, ['user_id' => auth()->user()->id]);
                    foreach ($orden_compra->productos as $producto) {
                        $compra->productos()->create([
                            'cantidad' => $producto->CANT,
                            'clave' => $producto->CVE_ART,
                            'descripcion' => optional($producto->producto)->DESC,
                            'descuento' => $producto->descuento_int,
                            'precio' => $producto->precio_int,
                            'impuesto' => $producto->impuesto_int,
                            'importe' => $producto->importe_int,
                            'observaciones' => optional($producto->observacion)->STR_OBS,
                            'esquema' => optional($producto->esquema)->DESCRIPESQ,
                            'iva' => $producto->iva_int,
                            'ret_iva' => $producto->ret_iva_int,
                        ]);
                    }
                    $compra = $this->syncManiobras($compra);
                    $compra = $this->syncEstadia($compra);
                    $user = $proveedor->user;
                    $delay++;
                    $user->notify((new FacturaPendiente($user, $compra))->delay(now()->addMinutes($delay)));
                    $ordenes_sincronizadas++;
                } else {
                    $ordenes_repetidas++;
                }
            }
        }

        return ['total' => $response['total'], 'sincronizadas' => $ordenes_sincronizadas, 'repetidas' => $ordenes_repetidas];
    }
}
