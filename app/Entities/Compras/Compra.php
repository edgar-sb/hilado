<?php

namespace App\Entities\Compras;

use App\Entities\Proveedores\Proveedor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Compra extends Model
{
    use SoftDeletes;

    protected $table = 'compras';

    protected $fillable = [
        'proveedor_id',
        'estatus_id',
        'bloqueado',
        'no_pedido',
        'no_compra',
        'no_factura',
        'pedido_sae',
        'fecha_envio',
        'fecha_flete',
        'fecha_pago',
        'fecha_factura',
        'monto',
        'impuesto',
        'importe',
        'direccion',
        'almacen',
        'maniobras',
        'estadia',
        'factura',
    ];

    //Relaciones
    public function proveedor() {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
    public function estatusActual() {
        return $this->belongsTo(CompraEstatus::class, 'estatus_id');
    }
    public function estatus() {
        return $this->belongsToMany(
            CompraEstatus::class,
            'compras_estatus_log',
            'compra_id',
            'estatus_id'
        )
            ->whereNull('deleted_at')
            ->withPivot('comentarios', 'estatus', 'id')
            ->withTimestamps()
            ->orderBy('pivot_created_at', 'desc');
    }
    public function estatusLog() {
        return $this->hasMany(CompraEstatusLog::class, 'compra_id')->orderBy('created_at', 'desc');
    }
    public function productos() {
        return $this->hasMany(CompraProducto::class, 'compra_id');
    }
    public function cliente() {
        return $this->hasOne(CompraCliente::class, 'compra_id');
    }
    public function agrupacion() {
        return CompraAgrupacion::whereJsonContains('compras', $this->id)->first();
    }



    //Atributos
    public function getProveedorRazonSocialAttribute() {
        return optional($this->proveedor)->razon_social;
    }
    public function getFechaEnvioFormatAttribute() {
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = Carbon::parse($this->fecha_envio);
        $mes = $meses[($fecha->format('n')) - 1];
        return $mes.' '.$fecha->format('d').', '.$fecha->format('Y');
    }
    public function getFechaFleteFormatAttribute() {
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = Carbon::parse($this->fecha_flete);
        $mes = $meses[($fecha->format('n')) - 1];
        return $mes.' '.$fecha->format('d').', '.$fecha->format('Y');
    }
    public function getPlazoCreditoAttribute() {
        return optional($this->proveedor)->dias_credito;
    }
    public function getDiasVencerAttribute() {
        if (isset($this->proveedor->dias_credito)) {
            $estatus_log = $this->estatus()->where('clave', 'factura')->first();
            if (isset($estatus_log) && $estatus_log->pivot->estatus == "aprobado") {
                $hoy = Carbon::now()->endOfDay();
                $fecha_limite = Carbon::parse($estatus_log->pivot->updated_at)->addDays($this->proveedor->dias_credito)->endOfDay();
                return $fecha_limite->diffInDays($hoy, false)."d";
            }
            return "Factura no aprobada";
        }
        return "No establecido";
    }
    public function getDiasVencerFacturaAttribute() {
        $limite_dias = $this->proveedor->dias_vencer_factura;
        if (!isset($limite_dias)) {
            $limite_dias = 7;
        }

        $estatus_log = $this->estatus()->where('clave', 'factura')->first();
        if (isset($estatus_log) && $estatus_log->pivot->estatus == "aprobado") {
            $hoy = Carbon::now()->endOfDay();
            $fecha_limite = Carbon::parse($estatus_log->pivot->updated_at)->addDays($limite_dias)->endOfDay();
            return $fecha_limite->diffInDays($hoy, false)."d";
        }
        return "Factura no aprobada";
    }
    public function getFechaVencerFacturaAttribute() {
        $limite_dias = $this->proveedor->dias_vencer_factura;
        if (!isset($limite_dias)) {
            $limite_dias = 7;
        }

        $estatus_log = $this->estatus()->where('clave', 'factura')->first();
        if (isset($estatus_log) && $estatus_log->pivot->estatus == "aprobado") {
            $fecha_limite = Carbon::parse($estatus_log->pivot->updated_at)->addDays($limite_dias)->endOfDay();
            return $fecha_limite->toDateString();
        }
        return "Factura no aprobada";
    }
    public function getDiasVencerPagoAttribute() {
        $limite_dias = $this->proveedor->dias_vencer_pago;
        if (!isset($limite_dias)) {
            $limite_dias = 7;
        }

        $estatus_log = $this->estatus()->where('clave', 'pago')->first();
        if (isset($estatus_log) && $estatus_log->pivot->estatus == "aprobado") {
            $hoy = Carbon::now()->endOfDay();
            $fecha_limite = Carbon::parse($estatus_log->pivot->updated_at)->addDays($limite_dias)->endOfDay();
            return $fecha_limite->diffInDays($hoy, false)."d";
        }
        return "Pago no realizado";
    }
    public function getFechaVencerPagoAttribute() {
        $limite_dias = $this->proveedor->dias_vencer_pago;
        if (!isset($limite_dias)) {
            $limite_dias = 7;
        }

        $estatus_log = $this->estatus()->where('clave', 'pago')->first();
        if (isset($estatus_log) && $estatus_log->pivot->estatus == "aprobado") {
            $hoy = Carbon::now()->endOfDay();
            $fecha_limite = Carbon::parse($estatus_log->pivot->updated_at)->addDays($limite_dias)->endOfDay();
            return $fecha_limite->toDateString();
        }
        return "Pago no realizado";
    }

    public function getRechazoComentariosAttribute() {
        $estatus_log = $this->estatusLog()->whereNotNull('comentarios')->first();
        if(isset($estatus_log) && $estatus_log->estatus == 'rechazado'){
            return $estatus_log->comentarios;
        }
        return null;
    }
    public function getParcialComentariosAttribute() {
        $estatus_log = $this->estatusLog()->whereNotNull('comentarios')->first();
        if(isset($estatus_log) && $estatus_log->estatus == 'aprobado-parcial'){
            return $estatus_log->comentarios;
        }
        return null;
    }
    public function getDestinoAttribute() {
        return optional($this->productos()->first())->observaciones;
    }

    public function progreso($estatus) {
        $estatus_log = $this->estatus()->where('clave', $estatus)->first();
        if(isset($estatus_log->pivot)) {
            switch ($estatus_log->pivot->estatus) {
                case "aprobado":
                    return 'complete';
                    break;
                case "rechazado":
                    return 'disabled';
                    break;
                case "pendiente":
                    if($this->bloqueado) {
                        return 'active danger';
                    }
                    return 'active';
                    break;
                default:
                    return 'disabled';
            }
        }
        return 'disabled';
    }
    public function progresoUrl($estatus) {
        $estatus_log = $this->estatus()->where('clave', $estatus)->first();
        if(isset($estatus_log->pivot)) {
            switch ($estatus_log->pivot->estatus) {
                case "aprobado":
                    return route('compras.show', [$this, 'estatus_id' => $estatus_log->id]);
                    break;
                case "rechazado":
                    return '#';
                    break;
                case "pendiente":
                    switch ($estatus) {
                        case "venta":
                            if(auth()->user()->can('agregar compra')) {
                                return route('compras.edit', $this);
                            } else {
                                return route('compras.show', [$this, 'estatus_id' => $estatus_log->id]);
                            }
                            break;
                        case "factura":
                            if(auth()->user()->can('agregar factura')) {
                                return route('compras.edit', $this);
                            } else {
                                return route('compras.show', [$this, 'estatus_id' => $estatus_log->id]);
                            }
                            break;
                        case "acuse-y-carta-porte":
                            if(auth()->user()->can('agregar acuse')) {
                                return route('compras.edit', $this);
                            } else {
                                return route('compras.show', [$this, 'estatus_id' => $estatus_log->id]);
                            }
                            break;
                        case "validacion":
                            if(auth()->user()->can('validar acuse')) {
                                return route('compras.edit', $this);
                            } else {
                                return route('compras.show', [$this, 'estatus_id' => $estatus_log->id]);
                            }
                            break;
                        case "pago":
                            if(auth()->user()->can('agregar comprobante')) {
                                return route('compras.edit', $this);
                            } else {
                                return route('compras.show', [$this, 'estatus_id' => $estatus_log->id]);
                            }
                            break;
                        case "complemento":
                            if(auth()->user()->can('agregar complemento')) {
                                return route('compras.edit', $this);
                            } else {
                                return route('compras.show', [$this, 'estatus_id' => $estatus_log->id]);
                            }
                            break;
                    }
                    break;
                default:
                    return '#';
            }
        }
        return '#';
    }

    public function getIvaAttribute() {
        return $this->productos()->select(DB::raw('SUM((precio * cantidad) * (iva/10000)) as impuesto'))->first()->impuesto;
    }

    public function getRetIvaAttribute() {
        return $this->productos()->select(DB::raw('SUM((precio * cantidad) * (ret_iva/10000)) as impuesto'))->first()->impuesto;
    }

    public function getImporteFormatAttribute() {
        return '$'.number_format(($this->importe)/100, 2);
    }

    public function getMontoFormatAttribute() {
        return '$'.number_format(($this->monto)/100, 2);
    }

    public function getImpuestoFormatAttribute() {
        return '$'.number_format(($this->impuesto)/100, 2);
    }

    public function eliminar() {
        $this->cliente()->delete();
        $this->productos()->delete();
        foreach ($this->estatus as $estatus) {
            $this->estatus()->updateExistingPivot($estatus, ['deleted_at' => Carbon::now()]);
        }
        $this->no_pedido = $this->no_pedido.'-DELETED-'.Carbon::now();
        $this->update();
        $this->delete();
    }

    //Logs
    public function getFacturaLogAttribute() {
        return $this->evaluaEstatusLog('factura');
    }

    public function getAcuseLogAttribute() {
        return $this->evaluaEstatusLog('acuse-y-carta-porte');
    }

    public function getComplementoLogAttribute() {
        return $this->evaluaEstatusLog('complemento');
    }

    public function getPagoLogAttribute() {
        return $this->evaluaEstatusLog('pago', true);
    }

    public function getAcuseLogFilesAttribute() {
        return $this->getFiles('acuse-y-carta-porte', 'acuse');
    }

    public function getCartaLogFilesAttribute() {
        return $this->getFiles('acuse-y-carta-porte', 'carta');
    }

    public function getAcuseLogFilesDownloadAttribute() {
        return $this->downloadFiles('acuse-y-carta-porte', 'acuse');
    }

    public function getCartaLogFilesDownloadAttribute() {
        return $this->downloadFiles('acuse-y-carta-porte', 'carta');
    }

    public function getAcuseLogFechaAttribute() {
        return $this->evaluaEstatusLogFecha('acuse-y-carta-porte');
    }

    public function getValidacionLogAttribute() {
        return $this->evaluaEstatusLog('validacion', true);
    }

    public function getValidacionLogFechaAttribute() {
        return $this->evaluaEstatusLogFecha('validacion');
    }

    public function getPagoLogFechaAttribute() {
        return $this->evaluaEstatusLogFecha('pago');
    }

    private function evaluaEstatusLog($clave, $proceder = false) {
        $estatus = CompraEstatus::where('clave', $clave)->first();
        if (isset($estatus)) {
            $estatus_log = $this->estatusLog()->where('estatus_id', $estatus->id)->first();
            if (isset($estatus_log)) {
                switch ($estatus_log->estatus) {
                    case "aprobado":
                        if ($proceder) {
                            if($this->getComplementoLogAttribute() == "Si") {
                                return "Finalizado";
                            }
                            return "Realizado";
                        }
                        return "Si";
                        break;
                    case "rechazado":
                        if ($proceder) {
                            return "Rechazado";
                        }
                        return "Rechazado";
                    case "pendiente":
                        if ($proceder) {
                            return "Si";
                        }
                        return "Pendiente";
                        break;
                }
            }
        }
        if ($proceder) {
            if($this->evaluaEstatusLog('acuse-y-carta-porte') == "Si") {
                return "Pendiente";
            }
            return "Detenido";
        }
        return "Pendiente";
    }



    private function evaluaEstatusLogFecha($clave) {
        $estatus = CompraEstatus::where('clave', $clave)->first();
        if (isset($estatus)) {
            $estatus_log = $this->estatusLog()->where('estatus_id', $estatus->id)->first();
            if (isset($estatus_log)) {
                switch ($estatus_log->estatus) {
                    case "aprobado":
                        return Carbon::parse($estatus_log->created_at)->toDateTimeString();
                        break;
                    case "rechazado":
                        return "Rechazado";
                    case "pendiente":
                        return "Pendiente";
                        break;
                }
            }
        }
        return "Pendiente";
    }

    private function getFiles($clave, $collection) {
        $estatus = CompraEstatus::where('clave', $clave)->first();
        $files_url = [];
        if (isset($estatus)) {
            $estatus_log = $this->estatusLog()->where('estatus_id', $estatus->id)->first();
            foreach ($estatus_log->getMedia($collection) as $media) {
                $files_url[] = ['mime' => $media->mime_type, 'url' => $media->getFullUrl()];
            }
        }
        return json_encode($files_url);
    }

    private function downloadFiles($clave, $collection) {
        $estatus = CompraEstatus::where('clave', $clave)->first();
        if (isset($estatus)) {
            $estatus_log = $this->estatusLog()->where('estatus_id', $estatus->id)->first();
            return route('compras.download', ['compra_estatus_log' => $estatus_log, 'collection' => $collection]);
        }
        return null;
    }
}
