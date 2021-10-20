<?php

namespace App\Http\Controllers\Dashboard\Traits;

use App\Entities\Compras\Compra;
use App\Entities\Compras\CompraEstatus;
use App\Entities\Compras\CompraEstatusLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Cast\Double;

trait ValidaArchivos {
    private $rango = 1;

    public function setPendiente(Compra $compra) {
        $estatus_log = $compra->estatusLog()->where('estatus_id', $compra->estatusActual->id)->first();
        $estatus_log->estatus = "pendiente";
        $estatus_log->update();
    }

    public function limpiaArchivos(Compra $compra) {
        $estatus_final = CompraEstatus::where('clave', 'complemento')->first();
        $estatus_log = $compra->estatusLog()->where('estatus_id', $compra->estatusActual->id)->first();
        if(!($estatus_final->id == $estatus_log->estatus_id && $estatus_log->estatus == 'aprobado')) {
            switch ($compra->estatusActual->clave) {
                case "factura":
                    $estatus_log->clearMediaCollection('factura-pdf');
                    $estatus_log->clearMediaCollection('factura-xml');
                    break;
                case "acuse-y-carta-porte":
                    $estatus_log->clearMediaCollection('acuse');
                    $estatus_log->clearMediaCollection('carta');
                    break;
                case "pago":
                    $estatus_log->clearMediaCollection('comprobante');
                    break;
                case "complemento":
                    $estatus_log->clearMediaCollection('complemento-pdf');
                    $estatus_log->clearMediaCollection('complemento-xml');
                    break;
            }
            $estatus_log->estatus = "pendiente";
            $estatus_log->update();
        }
    }

    public function uploadArchivos(Compra $compra, Request $request) {
        $estatus_log = $compra->estatusLog()->where('estatus_id', $compra->estatusActual->id)->first();
        $response = [];
        switch ($compra->estatusActual->clave) {
            case "factura":
                if($request->hasFile('file-pdf')) {
                    $estatus_log->addMediaFromRequest('file-pdf')->toMediaCollection('factura-pdf');
                }
                if($request->hasFile('file-xml')) {
                    $estatus_log->addMediaFromRequest('file-xml')->toMediaCollection('factura-xml');
                }
                //Validar archivos
                $response = $this->validaFactura($compra, $estatus_log);
                break;
            case "acuse-y-carta-porte":
                if($request->hasFile('file-acuse')) {
                    $estatus_log->addMediaFromRequest('file-acuse')->toMediaCollection('acuse');
                }
                if($request->hasFile('file-carta')) {
                    $estatus_log->addMediaFromRequest('file-carta')->toMediaCollection('carta');
                }
                //Validar archivos
                $response = $this->validaAcuseCarta($compra, $estatus_log);
                break;
            case "pago":
                if($request->hasFile('file-comprobante')) {
                    $estatus_log->addMediaFromRequest('file-comprobante')->toMediaCollection('comprobante');
                }
                //Validar archivos
                $response = $this->validaComprobante($compra, $estatus_log);
                break;
            case "complemento":
                if($request->hasFile('file-pdf')) {
                    $estatus_log->addMediaFromRequest('file-pdf')->toMediaCollection('complemento-pdf');
                }
                if($request->hasFile('file-xml')) {
                    $estatus_log->addMediaFromRequest('file-xml')->toMediaCollection('complemento-xml');
                }
                //Validar archivos
                $response = $this->validaComplemento($compra, $estatus_log);
                break;
        }

        return $response;
    }

    public function uploadArchivosMultiple(Request $request) {
        $estatus = CompraEstatus::where('clave', $request->input('estatus'))->first();
        $compras = Compra::whereIn('id', $request->input('compras_ids'))->get();
        $response = [];
        $total = array_sum($compras->pluck('importe')->toArray())/100;
        switch ($estatus->clave) {
            case "factura":
                foreach ($compras as $compra) {
                    $estatus_log = $compra->estatusLog()->where('estatus_id', $estatus->id)->first();
                    if(isset($estatus_log)) {
                        if($request->hasFile('file-pdf')) {
                            $estatus_log->addMediaFromRequest('file-pdf')->preservingOriginal()->toMediaCollection('factura-pdf');
                        }
                        if($request->hasFile('file-xml')) {
                            $estatus_log->addMediaFromRequest('file-xml')->preservingOriginal()->toMediaCollection('factura-xml');
                        } //Validar archivos
                        $response = $this->validaFactura($compra, $estatus_log, $total);
                    }
                }
                break;
            case "pago":
                foreach ($compras as $compra) {
                    $estatus_log = $compra->estatusLog()->where('estatus_id', $estatus->id)->first();
                    if(isset($estatus_log)) {
                        if($request->hasFile('file-comprobante')) {
                            $estatus_log->addMediaFromRequest('file-comprobante')->preservingOriginal()->toMediaCollection('comprobante');
                        }
                        //Validar archivos
                        $response = $this->validaComprobante($compra, $estatus_log);
                    }
                }
                break;
            case "complemento":
                foreach ($compras as $compra) {
                    $estatus_log = $compra->estatusLog()->where('estatus_id', $estatus->id)->first();
                    if(isset($estatus_log)) {
                        if($request->hasFile('file-pdf')) {
                            $estatus_log->addMediaFromRequest('file-pdf')->preservingOriginal()->toMediaCollection('complemento-pdf');
                        }
                        if($request->hasFile('file-xml')) {
                            $estatus_log->addMediaFromRequest('file-xml')->preservingOriginal()->toMediaCollection('complemento-xml');
                        }
                        //Validar archivos
                        $response = $this->validaComplemento($compra, $estatus_log, $total);
                    }
                }
                break;

        }
        return $response;
    }

    public function validaFactura(Compra $compra, $estatus_log, $importe = null) {
        if(is_null($importe)) {
            $importe = $compra->importe/100;
        }
        $total = 0;
        $uuid = null;
        $fecha_factura = null;
        $no_factura = null;
        if ($estatus_log->getMedia('factura-xml')->count() < 1) {
            $this->setPendiente($compra);
            return [
                'error'=> 'Las evidencias de la factura han sido rechazadas.',
                'msg' => 'No se ha subido ningún archivo XML.'
            ];
        }
        if ($estatus_log->getMedia('factura-pdf')->count() < 1) {
            $this->setPendiente($compra);
            return [
                'error'=> 'Las evidencias de la factura han sido rechazadas.',
                'msg' => 'No se ha subido ningún archivo PDF.'
            ];
        }
        foreach ($estatus_log->getMedia('factura-xml') as $factura_xml) {
            $xml = simplexml_load_file($factura_xml->getPath());
            $ns = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('c', $ns['cfdi']);
            $xml->registerXPathNamespace('t', $ns['tfd']);
            foreach ($xml->xpath('//c:Comprobante') as $cfdiComprobante){
                $total += floatval($cfdiComprobante['Total']);
                if(isset($no_factura)) {
                    $no_factura = $no_factura.','.$cfdiComprobante['Folio'];
                } else {
                    $no_factura = $cfdiComprobante['Folio'];
                }
            }
            foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
                if(isset($uuid)) {
                    $uuid = $uuid.','.$tfd['UUID'];
                } else {
                    $uuid = $tfd['UUID'];
                }
                if(is_null($fecha_factura)) {
                    $fecha_factura = Carbon::parse($tfd['FechaTimbrado'])->startOfDay();
                }
            }
        }
        if(($importe - $this->rango) > $total || ($importe + $this->rango) < $total) {
            $this->setPendiente($compra);
            return [
                'error'=> 'Las evidencias de la factura han sido rechazadas.',
                'msg' => 'Los archivos XML no concuerdan con el total de la compra.'
            ];
        }
        if ($estatus_log->getMedia('factura-pdf')->count() != $estatus_log->getMedia('factura-xml')->count()){
            $this->setPendiente($compra);
            return [
                'error'=> 'Las evidencias de la factura han sido rechazadas.',
                'msg' => 'El número de archivos XML y PDF no concuerdan.'
            ];
        }
        $compra->estatus()->updateExistingPivot($compra->estatusActual, ['estatus' => 'aprobado', 'user_id' => auth()->user()->id]);
        $compra->factura = $uuid;
        $compra->no_factura = $no_factura;
        $compra->fecha_factura = $fecha_factura;
        $compra->update();
        return [
            'success'=> 'Las evidencias de la factura han sido aceptadas.',
            'msg' => 'Los archivos XML concuerdan con el total de la compra y se agregó correctamente el PDF.'
        ];
    }

    public function validaAcuseCarta(Compra $compra, $estatus_log) {
        if ($estatus_log->getMedia('acuse')->count() < 1) {
            $this->setPendiente($compra);
            return [
                'error'=> 'Las evidencias de entrega han sido rechazadas.',
                'msg' => 'No se ha subido el acuse.'
            ];
        }
        $compra->estatus()->updateExistingPivot($compra->estatusActual, ['estatus' => 'aprobado', 'user_id' => auth()->user()->id]);
        return [
            'success'=> 'Las evidencias de entrega han sido aceptadas.',
            'msg' =>'Las evidencias se han almacenado correctamente.'
        ];
    }

    public function validaComprobante(Compra $compra, $estatus_log) {
        if ($estatus_log->getMedia('comprobante')->count() < 1) {
            $this->setPendiente($compra);
            return [
                'error'=> 'Las evidencias del pago han sido rechazadas.',
                'msg' => 'No se ha subido el comprobante del pago.'
            ];
        }
        $compra->estatus()->updateExistingPivot($compra->estatusActual, ['estatus' => 'aprobado', 'user_id' => auth()->user()->id]);
        return [
            'success'=> 'Las evidencias del pago han sido aceptadas.',
            'msg' => 'El comprobante de pago se ha almacenado correctamente.'
        ];
    }

    public function validaComplemento(Compra $compra, $estatus_log, $importe = null) {
        if(is_null($importe)) {
            $importe = $compra->importe/100;
        }
        $total = 0;
        $fecha_pago = null;
        if ($estatus_log->getMedia('complemento-xml')->count() < 1) {
            $this->setPendiente($compra);
            return [
                'error'=> 'Las evidencias del complemento del pago han sido rechazadas.',
                'msg' => 'No se ha subido ningún archivo XML.'
            ];
        }
        if ($estatus_log->getMedia('complemento-pdf')->count() < 1) {
            $this->setPendiente($compra);
            return [
                'error'=> 'Las evidencias del complemento del pago han sido rechazadas.',
                'msg' => 'No se ha subido ningún archivo PDF.'
            ];
        }
        foreach ($estatus_log->getMedia('complemento-xml') as $factura_xml) {
            $xml = simplexml_load_file($factura_xml->getPath());
            $ns = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('c', $ns['cfdi']);
            $xml->registerXPathNamespace('t', $ns['tfd']);
            $xml->registerXPathNamespace('p', $ns['pago10']);
            foreach ($xml->xpath('//c:Comprobante//c:Complemento//p:Pagos//p:Pago') as $pago10Pago){
                $total += floatval($pago10Pago['Monto']);
                $fecha_pago = Carbon::parse($pago10Pago['FechaPago'])->startOfDay();
            }
        }
        if(($importe - $this->rango) > $total || ($importe + $this->rango) < $total) {
            $this->setPendiente($compra);
            return [
                'error'=> 'Las evidencias del complemento del pago han sido rechazadas.',
                'msg' => 'Los archivos XML no concuerdan con el total de la compra.'
            ];
        }
        if(!Carbon::parse($compra->fecha_pago)->eq($fecha_pago)) {
            $this->setPendiente($compra);
            return [
                'error'=> 'Las evidencias del complemento del pago han sido rechazadas.',
                'msg' => 'Los archivos XML no concuerdan con la fecha de pago de la compra.'
            ];
        }
        if ($estatus_log->getMedia('complemento-pdf')->count() != $estatus_log->getMedia('complemento-xml')->count()){
            $this->setPendiente($compra);
            return [
                'error'=> 'Las evidencias del complemento del pago han sido rechazadas.',
                'msg' => 'El número de archivos XML y PDF no concuerdan.'
            ];
        }
        $compra->estatus()->updateExistingPivot($compra->estatusActual, ['estatus' => 'aprobado', 'user_id' => auth()->user()->id]);
        return [
            'success'=> 'Las evidencias del complemento del pago han sido aceptadas.',
            'msg' => 'Se agregó correctamente el PDF, Los archivos XML concuerdan con el total de la compra y su fecha de pago.'
        ];
    }
}
