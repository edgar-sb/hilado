<?php

namespace App\SAE\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "COMPC".env("DB_TABLES_SUFIX","01");
    }

    protected $connection = 'firebird';
    //protected $table = 'COMPC01';
    protected $primaryKey = 'CVE_DOC';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'TIP_DOC',
        'CVE_DOC',
        'CVE_CLPV',
        'STATUS',
        'SU_REFER',
        'FECHA_DOC',
        'FECHA_REC',
        'FECHA_PAG',
        'FECHA_CANCELA',
        'CAN_TOT',
        'IMP_TOT1',
        'IMP_TOT2',
        'IMP_TOT3',
        'IMP_TOT4',
        'DES_TOT',
        'DES_FIN',
        'TOT_IND',
        'OBS_COND',
        'CVE_OBS',
        'NUM_ALMA',
        'ACT_CXP',
        'ACT_COI',
        'NUM_MONED',
        'TIPCAMB',
        'ENLAZADO',
        'TIP_DOC_E',
        'NUM_PAGOS',
        'FECHAELAB',
        'SERIE',
        'FOLIO',
        'CTLPOL',
        'ESCFD',
        'CONTADO',
        'BLOQ',
        'DES_FIN_PORC',
        'DES_TOT_PORC',
        'IMPORTE',
        'TIP_DOC_ANT',
        'DOC_ANT',
        'TIP_DOC_SIG',
        'DOC_SIG',
        'FORMAENVIO',
        'METODODEPAGO',
    ];

    public $timestamps = false;

    protected $appends = [
        'fecha_format',
        'monto_format',
        'importe_format',
        'impuesto_format',
    ];

    public function proveedor() {
        return $this->belongsTo(Proveedor::class, 'CVE_CLPV', 'CLAVE');
    }

    public function almacen() {
        return $this->belongsTo(Almacen::class, 'NUM_ALMA', 'CVE_ALM');
    }

    public function productos() {
        return $this->hasMany(CompraProducto::class, 'CVE_DOC', 'CVE_DOC');
    }

    public function getFechaFormatAttribute() {
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = Carbon::parse($this->FECHA_DOC);
        $mes = $meses[($fecha->format('n')) - 1];
        return $mes.' '.$fecha->format('d').', '.$fecha->format('Y');
    }
    public function getImporteIntAttribute() {
        return $this->IMPORTE * 100;
    }
    public function getImpuestoIntAttribute() {
        $impuestos = $this->IMP_TOT1 + $this->IMP_TOT2 +  $this->IMP_TOT3 +  $this->IMP_TOT4;
        return $impuestos * 100;
    }
    public function getMontoIntAttribute() {
        return $this->CAN_TOT * 100;
    }
    public function getImporteFormatAttribute() {
        return "$".number_format($this->IMPORTE, 2);
    }
    public function getImpuestoFormatAttribute() {
        $impuestos = $this->IMP_TOT1 + $this->IMP_TOT2 +  $this->IMP_TOT3 +  $this->IMP_TOT4;
        return "$".number_format($impuestos, 2);
    }
    public function getMontoFormatAttribute() {
        return "$".number_format($this->CAN_TOT, 2);
    }
}
