<?php

namespace App\SAE\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CompraProducto extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "PAR_COMPC".env("DB_TABLES_SUFIX","01");
    }

    protected $connection = 'firebird';
    //protected $table = 'PAR_COMPC01';
    public $incrementing = false;
    protected $primaryKey = ['CVE_DOC', 'CVE_ART'];

    protected $fillable = [
        'CVE_DOC',
        'NUM_PAR',
        'CVE_ART',
        'CANT',
        'PXR',
        'PREC',
        'COST',
        'IMPU1',
        'IMPU2',
        'IMPU3',
        'IMPU4',
        'IMP1APLA',
        'IMP2APLA',
        'IMP3APLA',
        'IMP4APLA',
        'TOTIMP1',
        'TOTIMP2',
        'TOTIMP3',
        'TOTIMP4',
        'DESCU',
        'ACT_INV',
        'TIP_CAM',
        'UNI_VENTA',
        'TIPO_ELEM',
        'TIPO_PROD',
        'CVE_OBS',
        'E_LTPD',
        'REG_SERIE',
        'FACTCONV',
        'COST_DEV',
        'NUM_ALM',
        'MINDIRECTO',
        'NUM_MOV',
        'TOT_PARTIDA',
        'MAN_IEPS',
        'APL_MAN_IMP',
        'CUOTA_IEPS',
        'APL_MAN_IEPS',
        'MTO_PORC',
        'MTO_CUOTA',
        'CVE_ESQ',
        'DESCR_ART',
    ];

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @param mixed $keyName
     * @return mixed
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

    public $timestamps = false;

    protected $appends = [
        'cantidad_format',
        'importe_format',
        'impuesto_format',
        'precio_format',
        'descuento_format',
    ];

    public function producto() {
        return $this->belongsTo(Producto::class, 'CVE_ART', 'CVE_ART');
    }

    public function getImporteIntAttribute() {
        return $this->TOT_PARTIDA * 100;
    }
    public function getImpuestoIntAttribute() {
        $impuestos = $this->TOTIMP1 + $this->TOTIMP2 +  $this->TOTIMP3 +  $this->TOTIMP4;
        return $impuestos * 100;
    }
    public function getPrecioIntAttribute() {
        return $this->COST * 100;
    }
    public function getDescuentoIntAttribute() {
        return $this->DESCU * 100;
    }

    public function getCantidadFormatAttribute() {
        return number_format($this->CANT, 0);
    }
    public function getImporteFormatAttribute() {
        return "$".number_format($this->TOT_PARTIDA, 2);
    }
    public function getImpuestoFormatAttribute() {
        $impuestos = $this->TOTIMP1 + $this->TOTIMP2 +  $this->TOTIMP3 +  $this->TOTIMP4;
        return "$".number_format($impuestos, 2);
    }
    public function getPrecioFormatAttribute() {
        return "$".number_format($this->COST, 2);
    }
    public function getDescuentoFormatAttribute() {
        return "$".number_format($this->DESCU, 2);
    }
}
