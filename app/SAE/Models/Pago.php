<?php

namespace App\SAE\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "PAGA_M".env("DB_TABLES_SUFIX","01");
    }

    protected $connection = 'firebird';
    //protected $table = 'PAGA_M01';
    public $incrementing = false;
    protected $primaryKey = ['CVE_PROV', 'DOCTO'];

    protected $fillable = [
        'CVE_PROV',
        'REFER',
        'NUM_CARGO',
        'NUM_CPTO',
        'CVE_FOLIO',
        'CVE_OBS',
        'NO_FACTURA',
        'DOCTO',
        'IMPORTE',
        'FECHA_APLI',
        'FECHA_VENC',
        'AFEC_COI',
        'NUM_MONED',
        'TCAMBIO',
        'IMPMON_EXT',
        'FECHAELAB',
        'CTLPOL',
        'TIPO_MOV',
        'CVE_BITA',
        'SIGNO',
        'CVE_AUT',
        'USUARIO',
        'ENTREGADA',
        'FECHA_ENTREGA',
        'REF_SIST',
        'STATUS',
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

    public function compra() {
        return $this->belongsTo(Compra::class, 'CVE_DOC', 'DOCTO');
    }

    public function proveedor() {
        return $this->belongsTo(Proveedor::class, 'CLAVE', 'CVE_PROV');
    }
}
