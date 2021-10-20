<?php

namespace App\SAE\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "INVE".env("DB_TABLES_SUFIX","01");
    }

    protected $connection = 'firebird';
    //protected $table = 'INVE01';
    protected $primaryKey = 'CVE_ART';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'CVE_ART',
        'DESCR',
        'LIN_PROD',
        'CON_SERIE',
        'UNI_MED',
        'UNI_EMP',
        'CTRL_ALM',
        'TIEM_SURT',
        'STOCK_MIN',
        'STOCK_MAX',
        'TIP_COSTEO',
        'NUM_MON',
        'FCH_ULTCOM',
        'COMP_X_REC',
        'FCH_ULTVTA',
        'PEND_SURT',
        'EXIST',
        'COSTO_PROM',
        'ULT_COSTO',
        'CVE_OBS',
        'TIPO_ELE',
        'UNI_ALT',
        'FAC_CONV',
        'APART',
        'CON_LOTE',
        'CON_PEDIMENTO',
        'PESO',
        'VOLUMEN',
        'CVE_ESQIMPU',
        'CVE_BITA',
        'VTAS_ANL_C',
        'VTAS_ANL_M',
        'COMP_ANL_C',
        'COMP_ANL_M',
        'PREFIJO',
        'TALLA',
        'COLOR',
        'CUENT_CONT',
        'CVE_IMAGEN',
        'BLK_CST_EXT',
        'STATUS',
        'MAN_IEPS',
        'APL_MAN_IMP',
        'CUOTA_IEPS',
        'APL_MAN_IEPS',
        'UUID',
        'VERSION_SINC',
        'VERSION_SINC_FECHA_IMG',
        'CVE_PRODSERV',
        'CVE_UNIDAD',
    ];

    public $timestamps = false;
}
