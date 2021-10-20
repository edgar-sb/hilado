<?php

namespace App\SAE\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "ALMACENES".env("DB_TABLES_SUFIX","01");
    }

    protected $connection = 'firebird';
    //protected $table = 'ALMACENES01';
    protected $primaryKey = 'CVE_ALM';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'CVE_ALM',
        'DESCR',
        'DIRECCION',
        'ENCARGADO',
        'TELEFONO',
        'LISTA_PREC',
        'CUEN_CONT',
        'CVE_MENT',
        'CVE_MSAL',
        'STATUS',
        'LAT',
        'LON',
        'UUID',
        'VERSION_SINC',
    ];

    public $timestamps = false;

    public function ordenesCompra() {
        return $this->hasMany(OrdenCompra::class, 'NUM_ALMA', 'CVE_ALM');
    }
}
