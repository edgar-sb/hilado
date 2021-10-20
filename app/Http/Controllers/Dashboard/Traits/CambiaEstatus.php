<?php

namespace App\Http\Controllers\Dashboard\Traits;


use App\Entities\Compras\Compra;
use App\Entities\Compras\CompraEstatus;
use App\Entities\User;
use App\Http\Requests\Dashboard\CompraRequest;
use App\Notifications\Contabilidad\ComprobantePendiente;
use App\Notifications\Contabilidad\ComplementoPago;
use App\Notifications\Contabilidad\ComprobantePendienteMultiple;
use App\Notifications\Contabilidad\FacturaAprobada;
use App\Notifications\Logistica\ValidacionPendiente;
use App\Notifications\Proveedores\AcuseAprobado;
use App\Notifications\Proveedores\AcuseAprobadoParcial;
use App\Notifications\Proveedores\AcusePendiente;
use App\Notifications\Proveedores\Accesos;
use App\Notifications\Proveedores\AcuseRechazado;
use App\Notifications\Proveedores\ComplementoPendiente;
use App\Notifications\Proveedores\CompraFinalizada;
use App\Notifications\Proveedores\FacturaPendiente;
use App\SAE\Models\Esquema;
use App\SAE\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait CambiaEstatus {
    private function syncManiobras(Compra $compra, Request $request = null) {
        if (isset($request)) {
            if($request->filled('maniobras') && $request->input('maniobras') > 0) {
                $compra->maniobras = $request->input('maniobras') * 100;
                //obtiene maniobra si se agregó desde el SAE
                $maniobras_sae = $compra->productos()->where('clave', ' MANIOBRAS')->first();
                if(isset($maniobras_sae)) {
                    //Se define el costo de las maniobras (por si se cambia)
                    if($compra->maniobras != $maniobras_sae->precio) { //Si si se cambió el precio de las maniobras se vuelve a calcular
                        $maniobras_sae->precio = $compra->maniobras;
                        $maniobras_sae->impuesto = $compra->maniobras * ($maniobras_sae->iva / 10000);
                        $maniobras_sae->importe = $compra->maniobras + ($compra->maniobras * ($maniobras_sae->iva / 10000));
                        $maniobras_sae->update();
                    }
                } else {
                    $esquema = Esquema::where('CVE_ESQIMPU', 1)->first();
                    $producto = Producto::where('CVE_ART', ' MANIOBRAS')->first();
                    if(isset($esquema) && isset($producto)) {
                        $compra->productos()->create([
                            'portal' => true,
                            'cantidad' => 1,
                            'clave' => $producto->CVE_ART,
                            'descripcion' => $producto->DESC,
                            'descuento' => 0,
                            'precio' => $compra->maniobras,
                            'impuesto' => $compra->maniobras * ($esquema->IMPUESTO4 / 100),
                            'importe' => $compra->maniobras + ($compra->maniobras * ($esquema->IMPUESTO4 / 100)),
                            'esquema' => $esquema->DESCRIPESQ,
                            'iva' => $esquema->IMPUESTO4 * 100,
                            'ret_iva' => $esquema->IMPUESTO2 * 100,
                        ]);
                    } else {
                        abort(404);
                    }
                }
            } else {
                $compra->maniobras = null;
                //Elimina producto Maniobras
                optional($compra->productos()->where('clave', ' MANIOBRAS'))->delete();
            }

            $compra->monto = $compra->productos()->select(DB::raw('SUM(precio * cantidad) as precio'))->first()->precio;
            $compra->impuesto = $compra->productos()->select(DB::raw('SUM(impuesto) as impuesto'))->first()->impuesto;
            $compra->importe = $compra->productos()->select(DB::raw('SUM(importe) as importe'))->first()->importe;

        } else {
            $maniobras_sae = $compra->productos()->where('clave', ' MANIOBRAS')->first();
            if(isset($maniobras_sae)) {
                $compra->maniobras = $maniobras_sae->precio;
            }
        }

        $compra->update();

        return $compra;
    }

    private function syncEstadia(Compra $compra, Request $request = null) {
        if (isset($request)) {
            if ($request->filled('estadia') && $request->input('estadia') > 0) {
                $compra->estadia = $request->input('estadia') * 100;
                //obtiene estadía si se agregó desde el SAE
                $estadia_sae = $compra->productos()->where('clave', 'Estadía')->first();
                if (isset($estadia_sae)) {
                    //Se define el costo de la estadía (por si se cambia)
                    if ($compra->estadia != $estadia_sae->precio) { //Si si se cambió el precio de la estadía se vuelve a calcular
                        $estadia_sae->precio = $compra->maniobras;
                        $estadia_sae->impuesto = $compra->maniobras * ($estadia_sae->iva / 10000);
                        $estadia_sae->importe = $compra->maniobras + ($compra->maniobras * ($estadia_sae->iva / 10000));
                        $estadia_sae->update();
                    }
                } else {
                    $esquema = Esquema::where('CVE_ESQIMPU', 1)->first();
                    $producto = Producto::where('CVE_ART', 'Estadía')->first();
                    if (isset($esquema) && isset($producto)) {
                        $compra->productos()->create([
                            'portal' => true,
                            'cantidad' => 1,
                            'clave' => $producto->CVE_ART,
                            'descripcion' => $producto->DESC,
                            'descuento' => 0,
                            'precio' => $compra->estadia,
                            'impuesto' => $compra->estadia * ($esquema->IMPUESTO4 / 100),
                            'importe' => $compra->estadia + ($compra->estadia * ($esquema->IMPUESTO4 / 100)),
                            'esquema' => $esquema->DESCRIPESQ,
                            'iva' => $esquema->IMPUESTO4 * 100,
                            'ret_iva' => $esquema->IMPUESTO2 * 100,
                        ]);
                    } else {
                        abort(404);
                    }
                }
            } else {
                $compra->estadia = null;
                //Elimina producto Maniobras
                optional($compra->productos()->where('clave', 'Estadía'))->delete();
            }

            $compra->monto = $compra->productos()->select(DB::raw('SUM(precio * cantidad) as precio'))->first()->precio;
            $compra->impuesto = $compra->productos()->select(DB::raw('SUM(impuesto) as impuesto'))->first()->impuesto;
            $compra->importe = $compra->productos()->select(DB::raw('SUM(importe) as importe'))->first()->importe;

        } else {
            $estadia_sae = $compra->productos()->where('clave', 'Estadía')->first();
            if(isset($estadia_sae)) {
                $compra->estadia = $estadia_sae->precio;
            }
        }

        $compra->update();

        return $compra;
    }


    public function guardaCompra(CompraRequest $request) {
        $orden_compra = $this->SAERepository->ordenesCompras()->whereRaw("TRIM(CVE_DOC) LIKE '".$request->input('cve_doc')."'")
            ->with(['proveedor', 'productos.producto'])
            ->first();
        if (isset($orden_compra)) {
            $password = Str::random(8);
            $user = User::create([
                'nombre'=> $orden_compra->proveedor->NOMBRE,
                'password' => bcrypt($password),
                'email' => $request->input('email'),
            ]);
            $user->assignRole('proveedor');
            $proveedor = $user->proveedor()->create([
                'razon_social' => $orden_compra->proveedor->NOMBRE,
                'rfc' => $orden_compra->proveedor->RFC,
                'dias_credito' => $orden_compra->proveedor->DIASCRED,
            ]);
            $estatus = CompraEstatus::orderBy('orden', 'asc')->first();
            $compra = $proveedor->compras()->create([
                'estatus_id' => $estatus->siguiente_estatus->id,
                'no_pedido' => $orden_compra->CVE_DOC,
                'pedido_sae' => $orden_compra->pedido_sae,
                'fecha_envio' => $orden_compra->FECHA_DOC,
                'fecha_flete' => $orden_compra->FECHA_REC,
                'monto' => $orden_compra->monto_int,
                'impuesto' => $orden_compra->impuesto_int,
                'importe' => $orden_compra->importe_int,
                'direccion' => $orden_compra->OBS_COND, //TODO: Revisar este campo
                'almacen' => $orden_compra->almacen? $orden_compra->almacen->DESCR : null,
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
            $compra = $this->syncManiobras($compra, $request);
            $compra = $this->syncEstadia($compra, $request);
            $user->notify(new Accesos($user, $password));
            $user->notify((new FacturaPendiente($user, $compra))->delay(now()->addMinutes(1)));
        } else {
            abort(404);
        }
    }

    public function updateCompra(Compra $compra, Request $request, $enviarMail = true) {
        $delay = 0;
        $estatus = CompraEstatus::where('clave', $request->input('estatus'))->first();
        if (!isset($estatus)) {
            $estatus = CompraEstatus::findOrfail($request->input('estatus'));
        }
        $estatus_log = $compra->estatusLog()->where('estatus_id', $estatus->id)->first();
        $response = null;
        switch ($estatus->clave) {
            case "venta":
                /*$compra = $this->syncManiobrasEstadia($request, $compra);
                $compra->estatus()->updateExistingPivot($estatus, ['estatus' => 'aprobado', 'user_id' => auth()->user()->id]);
                $delay++;
                $compra->proveedor->user->notify((new FacturaPendiente($compra->proveedor->user, $compra))->delay(now()->addMinutes($delay)));*/
                break;
            case "factura":
                if ($estatus_log->estatus == "aprobado") {
                    $compra_sae = $this->SAERepository->storeCompra($compra->no_pedido, $compra);
                    if(isset($compra_sae)) {
                        $this->SAERepository->updateOrden($compra_sae);
                        $compra->no_compra = $compra_sae->CVE_DOC;
                        $compra->update();
                    }
                    //Envío de mails
                    if($enviarMail) {
                        $delay++;
                        $compra->proveedor->user->notify((new AcusePendiente($compra->proveedor->user, $compra))->delay(now()->addMinutes($delay)));
                        $path = $this->createFacturaZip($estatus_log);
                        if(isset($path)) {
                            foreach (User::role('contabilidad')->get() as $contabilidad) {
                                $delay++;
                                $contabilidad->notify((new FacturaAprobada($contabilidad, $compra, $estatus_log, $path))->delay(now()->addMinutes($delay)));
                                ;
                            }
                        } else {
                            $response = ['error' => 'No se pudo enviar el correo electrónico correctamente.'];
                        }
                    }
                } else {
                    $response = ['error' => 'No se puede aprobar la factura, ya que no se han subido las evidencias o las que se subieron no son correctas'];
                }
                break;
            case "acuse-y-carta-porte":
                if ($estatus_log->estatus == "aprobado") {
                    //Envío de mails
                    if($enviarMail) {
                        $path = $this->createAcuseZip($estatus_log);
                        if (isset($path)) {
                            foreach (User::role('logistica')->get() as $logistica) {
                                $delay++;
                                $logistica->notify((new ValidacionPendiente($logistica, $compra, $path))->delay(now()->addMinutes($delay)));;
                            }
                        } else {
                            $response = ['error' => 'No se pudo enviar el correo electrónico correctamente.'];
                        }
                    }
                } else {
                    $response = ['error' => 'No se puede aprobar el acuse y la carta porte, ya que no se han subido las evidencias o las que se subieron no son correctas'];
                }
                break;
            case "validacion":
                if ($request->filled('rechazar') && $request->input('rechazar') == "true") {
                    $estatus_log->comentarios = $request->input('comentarios');
                    $estatus_log->estatus = "rechazado";
                    $estatus_log->update();
                    $estatus = CompraEstatus::where('clave', 'acuse-y-carta-porte')->first();
                    $compra->fill([
                        'estatus_id' => $estatus->id,
                    ]);
                    $compra->update();
                    $compra->estatus()->attach($estatus, ['user_id' => auth()->user()->id]);
                    $response = ['success' => "Se ha rechazado el acuse y la carta porte correctamente"];

                    //Envío de mails
                    if($enviarMail) {
                        $delay++;
                        $compra->proveedor->user->notify((new AcuseRechazado($compra->proveedor->user, $compra, $estatus_log))->delay(now()->addMinutes($delay)));
                    }

                } else {
                    if ($request->filled('parcial') && $request->input('parcial') == "true") {
                        if ($request->filled('comentarios_parcial')) {
                            $estatus_log->comentarios = $request->input('comentarios_parcial');
                        }
                        $estatus_log->estatus = "aprobado-parcial";
                        $estatus_log->update();
                        $estatus = CompraEstatus::where('clave', 'acuse-y-carta-porte')->first();
                        $compra->fill([
                            'estatus_id' => $estatus->id,
                        ]);
                        $compra->update();
                        $compra->estatus()->attach($estatus, ['user_id' => auth()->user()->id]);
                        $response = ['success' => "Se ha aprobado parcialmente el acuse y la carta porte correctamente"];

                        //Envío de mails
                        if($enviarMail) {
                            $delay++;
                            $compra->proveedor->user->notify((new AcuseAprobadoParcial($compra->proveedor->user, $compra, $estatus_log))->delay(now()->addMinutes($delay)));
                        }
                    } else {
                        $estatus_log->estatus = "aprobado";
                        $estatus_log->update();
                        //Envío de mails
                        if($enviarMail) {
                            $delay++;
                            $compra->proveedor->user->notify((new AcuseAprobado($compra->proveedor->user, $compra))->delay(now()->addMinutes($delay)));

                            // Si está agrupada mandar mail hasta que todos los acuses estén aprobados
                            if(!is_null($compra->agrupacion())) {
                                $compras_agrupadas = $compra->agrupacion()->compras();
                                $enviar = true;
                                foreach ($compras_agrupadas as $compra_agrupada) {
                                    if ($compra_agrupada->progreso('validacion') != 'complete') {
                                        $enviar = false;
                                    }
                                }
                                if ($enviar) {
                                    foreach (User::role('contabilidad')->get() as $contabilidad) {
                                        $delay++;
                                        $contabilidad->notify((new ComprobantePendienteMultiple($contabilidad, $compras_agrupadas, $compra, $estatus_log))->delay(now()->addMinutes($delay)));
                                    }
                                }
                            } else {
                                foreach (User::role('contabilidad')->get() as $contabilidad) {
                                    $delay++;
                                    $contabilidad->notify((new ComprobantePendiente($contabilidad, $compra, $estatus_log))->delay(now()->addMinutes($delay)));
                                }
                            }

                        }
                    }
                }
                break;
            case "pago":
                if ($estatus_log->estatus == "aprobado") {
                    $compra->fill([
                        'fecha_pago' => $request->input('fecha_pago')
                    ]);

                    //Envío de mails
                    if($enviarMail) {
                        $delay++;
                        $compra->proveedor->user->notify((new ComplementoPendiente($compra->proveedor->user, $compra))->delay(now()->addMinutes($delay)));
                    }
                } else {
                    $response = ['error' => 'No se puede aprobar el comprobante del pago, ya que no se han subido las evidencias o las que se subieron no son correctas'];
                }
                break;
            case "complemento":
                if ($estatus_log->estatus == "aprobado") {
                    //Envío de mails
                    if($enviarMail) {
                        $delay++;
                        $compra->proveedor->user->notify((new CompraFinalizada($compra->proveedor->user, $compra))->delay(now()->addMinutes($delay)));;
                        $path = $this->createComplementoZip($estatus_log);
                        if (isset($path)) {
                            foreach (User::role('contabilidad')->get() as $contabilidad) {
                                $delay++;
                                $contabilidad->notify((new ComplementoPago($contabilidad, $compra, $estatus_log, $path))->delay(now()->addMinutes($delay)));;
                            }
                        } else {
                            $response = ['error' => 'No se pudo enviar el correo electrónico correctamente.'];
                        }
                    }
                } else {
                    $response = ['error' => 'No se puede aprobar el complemento del pago, ya que no se han subido las evidencias o las que se subieron no son correctas'];
                }
                break;
        }
        if ($response == null) {
            if (isset($estatus->siguiente_estatus)) {
                $compra->fill([
                    'estatus_id' => $estatus->siguiente_estatus->id,
                ]);
            }
            $compra->update();
            if (isset($estatus->siguiente_estatus)) {
                $compra->estatus()->attach($estatus->siguiente_estatus, ['user_id' => auth()->user()->id]);
            }
        }

        return $response;
    }
}
