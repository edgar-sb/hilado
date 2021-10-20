<?php

namespace App\SAE;


use App\SAE\Models\CompraProducto;
use App\SAE\Models\Esquema;
use App\SAE\Models\Folio;
use App\SAE\Models\OrdenCompra;
use App\SAE\Models\Pago;
use App\SAE\Models\Producto;
use App\SAE\Models\Proveedor;
use App\SAE\Models\Compra;
use App\Entities\Compras\Compra as PortalCompra;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SAERepository
{
    public function ordenesCompras() {
        return OrdenCompra::MesActual();
    }

    public function proveedores() {
        return Proveedor::RFC();
    }

    public function storeCompra($cve_doc, PortalCompra $portal_compra) {
        $orden_compra = $this->ordenesCompras()->whereRaw("CVE_DOC LIKE '".$cve_doc."'")->first();
        $compra = null;
        if(isset($orden_compra)) {
            $ultima_compra = Compra::orderBy(DB::raw("CAST(TRIM(CVE_DOC) AS int)"), 'desc')->limit(1)->first();
            $compra = Compra::where('DOC_ANT', $orden_compra->CVE_DOC)->limit(1)->first();
            if(!isset($compra)) {
                if(isset($ultima_compra)) {
                    $folio = intval(trim($ultima_compra->CVE_DOC)) + 1;
                } else {
                    $folio = 1;
                }
                $registro_folio = Folio::where('TIP_DOC', 'c')->first();
                if (isset($registro_folio)) {
                    $registro_folio->ULT_DOC = $folio;
                    $registro_folio->FECH_ULT_DOC = now();
                    $registro_folio->update();
                } else {
                    abort(404);
                }
                $clave_doc = str_pad($folio, 10, "0", STR_PAD_LEFT);
                $clave_doc = str_pad($clave_doc, 20, " ", STR_PAD_LEFT);
                $compra = new Compra([
                    'TIP_DOC' => 'c',
                    'CVE_DOC' => $clave_doc,
                    'CVE_CLPV' => $orden_compra->CVE_CLPV,
                    'STATUS' => $orden_compra->STATUS,
                    'SU_REFER' => $orden_compra->SU_REFER,
                    'FECHA_DOC' => Carbon::now()->startOfDay(),
                    'FECHA_REC' => Carbon::now()->startOfDay(),
                    'FECHA_PAG' => Carbon::now()->startOfDay(),
                    'FECHA_CANCELA' => $orden_compra->FECHA_CANCELA,
                    'CAN_TOT' => $orden_compra->CAN_TOT,
                    'IMP_TOT1' => $orden_compra->IMP_TOT1,
                    'IMP_TOT2' => $orden_compra->IMP_TOT2,
                    'IMP_TOT3' => $orden_compra->IMP_TOT3,
                    'IMP_TOT4' => $orden_compra->IMP_TOT4,
                    'DES_TOT' => $orden_compra->DES_TOT,
                    'DES_FIN' => $orden_compra->DES_FIN,
                    'TOT_IND' => $orden_compra->TOT_IND,
                    'IMPORTE' => $orden_compra->IMPORTE,
                    'OBS_COND' => $orden_compra->OBS_COND,
                    'CVE_OBS' => $orden_compra->CVE_OBS,
                    'NUM_ALMA' => $orden_compra->NUM_ALMA,
                    'ACT_CXP' => $orden_compra->ACT_CXP,
                    'ACT_COI' => $orden_compra->ACT_COI,
                    'NUM_MONED' => $orden_compra->NUM_MONED,
                    'TIPCAMB' => $orden_compra->TIPCAMB,
                    'ENLAZADO' => 'O',
                    'TIP_DOC_E' => 'o',
                    'NUM_PAGOS' => 1,
                    'FECHAELAB' => Carbon::now(),
                    'SERIE' => $orden_compra->SERIE,
                    'FOLIO' => $folio,
                    'CTLPOL' => $orden_compra->CTLPOL,
                    'ESCFD' => $orden_compra->ESCFD,
                    'CONTADO' => $orden_compra->CONTADO,
                    'BLOQ' => $orden_compra->BLOQ,
                    'DES_FIN_PORC' => $orden_compra->DES_FIN_PORC,
                    'DES_TOT_PORC' => $orden_compra->DES_TOT_PORC,
                    'TIP_DOC_ANT' => $orden_compra->TIP_DOC,
                    'DOC_ANT' => $orden_compra->CVE_DOC,
                    'TIP_DOC_SIG' => null,
                    'DOC_SIG' => null,
                    'FORMAENVIO' => null,
                    'METODODEPAGO' => $orden_compra->METODODEPAGO,
                ]);

                $compra->save();

                $num_par = 0;
                foreach($orden_compra->productos as $producto) {
                    $compra->productos()->create([
                        'CVE_DOC' => $compra->CVE_DOC,
                        'NUM_PAR' => $producto->NUM_PAR,
                        'CVE_ART' => $producto->CVE_ART,
                        'CANT' => $producto->CANT,
                        'PXR' => $producto->PXR,
                        'PREC' => $producto->PREC,
                        'COST' => $producto->COST,
                        'IMPU1' => $producto->IMPU1,
                        'IMPU2' => $producto->IMPU2,
                        'IMPU3' => $producto->IMPU3,
                        'IMPU4' => $producto->IMPU4,
                        'IMP1APLA' => $producto->IMP1APLA,
                        'IMP2APLA' => $producto->IMP2APLA,
                        'IMP3APLA' => $producto->IMP3APLA,
                        'IMP4APLA' => $producto->IMP4APLA,
                        'TOTIMP1' => $producto->TOTIMP1,
                        'TOTIMP2' => $producto->TOTIMP2,
                        'TOTIMP3' => $producto->TOTIMP3,
                        'TOTIMP4' => $producto->TOTIMP4,
                        'DESCU' => $producto->DESCU,
                        'ACT_INV' => $producto->ACT_INV,
                        'TIP_CAM' => $producto->TIP_CAM,
                        'UNI_VENTA' => $producto->UNI_VENTA,
                        'TIPO_ELEM' => $producto->TIPO_ELEM,
                        'TIPO_PROD' => $producto->TIPO_PROD,
                        'CVE_OBS' => $producto->CVE_OBS,
                        'E_LTPD' => $producto->E_LTPD,
                        'REG_SERIE' => $producto->REG_SERIE,
                        'FACTCONV' => $producto->FACTCONV,
                        'COST_DEV' => $producto->COST_DEV,
                        'NUM_ALM' => $producto->NUM_ALM,
                        'MINDIRECTO' => $producto->MINDIRECTO,
                        'NUM_MOV' => $producto->NUM_MOV,
                        'TOT_PARTIDA' => $producto->TOT_PARTIDA,
                        'MAN_IEPS' => $producto->MAN_IEPS,
                        'APL_MAN_IMP' => $producto->APL_MAN_IMP,
                        'CUOTA_IEPS' => $producto->CUOTA_IEPS,
                        'APL_MAN_IEPS' => $producto->APL_MAN_IEPS,
                        'MTO_PORC' => $producto->MTO_PORC,
                        'MTO_CUOTA' => $producto->MTO_CUOTA,
                        'CVE_ESQ' => $producto->CVE_ESQ,
                        'DESCR_ART' => $producto->DESCR_ART,
                    ]);
                    $num_par = $producto->NUM_PAR;
                }

                //Agregar maniobras
                if(isset($portal_compra->maniobras)) {
                    $costo = $portal_compra->maniobras / 100;
                    $esquema = Esquema::where('CVE_ESQIMPU', 1)->first();
                    $producto = Producto::where('CVE_ART', ' MANIOBRAS')->first();
                    $maniobra = CompraProducto::where('CVE_ART', ' MANIOBRAS')
                        ->where('CVE_DOC', $compra->CVE_DOC)
                        ->first();
                    if(isset($esquema) && isset($producto)) {
                        if (isset($maniobra)) {
                            $maniobra = $this->updateCompraProducto($maniobra, $compra, $esquema, $producto, $costo, ++$num_par);
                            $maniobra->update();
                        } else {
                            $maniobra = new CompraProducto();
                            $maniobra = $this->updateCompraProducto($maniobra, $compra, $esquema, $producto, $costo, ++$num_par);
                            $maniobra->save();
                        }
                    } else {
                        abort(404);
                    }
                }
                //Agrega estadía
                if(isset($portal_compra->estadia)) {
                    $costo = $portal_compra->estadia / 100;
                    $esquema = Esquema::where('CVE_ESQIMPU', 1)->first();
                    $producto = Producto::where('CVE_ART', 'Estadía')->first();
                    $estadia = CompraProducto::where('CVE_ART', 'Estadía')
                        ->where('CVE_DOC', $compra->CVE_DOC)
                        ->first();
                    if(isset($esquema) && isset($producto)) {
                        if (isset($estadia)) {
                            $estadia = $this->updateCompraProducto($estadia, $compra, $esquema, $producto, $costo, ++$num_par);
                            $estadia->update();
                        } else {
                            $estadia = new CompraProducto();
                            $estadia = $this->updateCompraProducto($estadia, $compra, $esquema, $producto, $costo, ++$num_par);
                            $estadia->save();
                        }
                    } else {
                        abort(404);
                    }
                }

                $compra->fill([
                    'CAN_TOT' => $portal_compra->monto / 100,
                    'IMP_TOT1' => $compra->productos()->sum('TOTIMP1'),
                    'IMP_TOT2' => $compra->productos()->sum('TOTIMP2'),
                    'IMP_TOT3' => $compra->productos()->sum('TOTIMP3'),
                    'IMP_TOT4' => $compra->productos()->sum('TOTIMP4'),
                    'IMPORTE' => $portal_compra->importe / 100,
                ]);
                $compra->update();

                $pago = Pago::create([
                    'CVE_PROV' => $compra->CVE_CLPV,
                    'REFER' => $compra->CVE_DOC,
                    'NUM_CARGO' => 1,
                    'NUM_CPTO' => 1,
                    'CVE_FOLIO' => $compra->FOLIO,
                    'CVE_OBS' => 0,
                    'DOCTO' => $compra->CVE_DOC,
                    'IMPORTE' => $compra->IMPORTE,
                    'FECHA_APLI' => $compra->FECHA_DOC,
                    'FECHA_VENC' => $compra->FECHA_PAG,
                    'AFEC_COI' => "A",
                    'NUM_MONED' => $compra->NUM_MONED,
                    'TCAMBIO' => $compra->TIPCAMB,
                    'IMPMON_EXT' => $compra->IMPORTE,
                    'FECHAELAB' => $compra->FECHAELAB,
                    'TIPO_MOV' => "C",
                    'SIGNO' => 1,
                    'STATUS' => "A",
                ]);

                $proveedor = $compra->proveedor;
                $proveedor->fill([
                    'ULT_COMPD' => $compra->CVE_DOC,
                    'ULT_COMPM' => $compra->IMPORTE,
                    'ULT_COMPF' => $compra->FECHA_DOC,
                    'SALDO' => $proveedor->SALDO + $compra->IMPORTE,
                    'VENTAS' =>  $proveedor->VENTAS + $compra->CAN_TOT,
                ]);

                $proveedor->update();

            }
        }
        return $compra;
    }

    public function updateOrden(Compra $compra) {
        $orden_compra = OrdenCompra::where('CVE_DOC', $compra->DOC_ANT)->limit(1)->first();
        if (isset($orden_compra)) {
            $orden_compra->FECHA_PAG = $orden_compra->FECHA_DOC;
            $orden_compra->ENLAZADO = 'T';
            $orden_compra->TIP_DOC_E = 'c';
            $orden_compra->TIP_DOC_SIG = 'c';
            $orden_compra->DOC_SIG = $compra->CVE_DOC;
            $orden_compra->update();
        }
    }

    private function updateCompraProducto($compraProducto, $compra, $esquema, $producto, $costo, $num_par) {
        $compraProducto->fill( [
            'CVE_DOC' => $compra->CVE_DOC,
            'NUM_PAR' => $num_par,
            'CVE_ART' => $producto->CVE_ART,
            'CANT' => 1,
            'PXR' => 1,
            'PREC' => 0,
            'COST' => $costo,
            'IMPU1' => $esquema->IMPUESTO1,
            'IMPU2' => $esquema->IMPUESTO2,
            'IMPU3' => $esquema->IMPUESTO3,
            'IMPU4' => $esquema->IMPUESTO4,
            'IMP1APLA' => $esquema->IMP1APLICA,
            'IMP2APLA' => $esquema->IMP2APLICA,
            'IMP3APLA' => $esquema->IMP3APLICA,
            'IMP4APLA' => $esquema->IMP4APLICA,
            'TOTIMP1' => $costo * ($esquema->IMPUESTO1/100),
            'TOTIMP2' => $costo * ($esquema->IMPUESTO2/100),
            'TOTIMP3' => $costo * ($esquema->IMPUESTO3/100),
            'TOTIMP4' => $costo * ($esquema->IMPUESTO4/100),
            'DESCU' => 0,
            'ACT_INV' => 'N',
            'TIP_CAM' => $producto->NUM_MON,
            'UNI_VENTA' => $producto->UNI_ALT,
            'TIPO_ELEM' => 'N',
            'TIPO_PROD' => $producto->TIPO_ELE,
            'CVE_OBS' => $producto->CVE_OBS,
            'E_LTPD' => 0,
            'REG_SERIE' => 0,
            'FACTCONV' => $producto->FAC_CONV,
            'COST_DEV' => null,
            'NUM_ALM' => 1,
            'MINDIRECTO' => 0,
            'NUM_MOV' => 0,
            'TOT_PARTIDA' => $costo,
            'MAN_IEPS' => $producto->MAN_IEPS,
            'APL_MAN_IMP' => $producto->APL_MAN_IMP,
            'CUOTA_IEPS' => $producto->CUOTA_IEPS,
            'APL_MAN_IEPS' => $producto->APL_MAN_IEPS,
            'MTO_PORC' => 0,
            'MTO_CUOTA' => 0,
            'CVE_ESQ' => $esquema->CVE_ESQIMPU,
            'DESCR_ART' => null,
        ]);

        return $compraProducto;
    }

}
