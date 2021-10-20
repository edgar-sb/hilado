<?php

namespace App\SAE\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "PROV".env("DB_TABLES_SUFIX","01");
    }

    protected $connection = 'firebird';
    //protected $table = 'PROV01';
    protected $primaryKey = 'CLAVE';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'CLAVE',
        'STATUS',
        'NOMBRE',
        'RFC',
        'CALLE',
        'NUMINT',
        'NUMEXT',
        'CRUZAMIENTOS',
        'CRUZAMIENTOS2',
        'COLONIA',
        'CODIGO',
        'LOCALIDAD',
        'MUNICIPIO',
        'ESTADO',
        'CVE_PAIS',
        'NACIONALIDAD',
        'TELEFONO',
        'CLASIFIC',
        'FAX',
        'PAG_WEB',
        'CURP',
        'CVE_ZONA',
        'CON_CREDITO',
        'DIASCRED',
        'LIMCRED',
        'CVE_BITA',
        'ULT_PAGOD',
        'ULT_PAGOM',
        'ULT_PAGOF',
        'ULT_COMPD',
        'ULT_COMPM',
        'ULT_COMPF',
        'SALDO',
        'VENTAS',
        'DESCUENTO',
        'TIP_TERCERO',
        'TIP_OPERA',
        'CVE_OBS',
        'CUENTA_CONTABLE',
        'FORMA_PAGO',
        'BENEFICIARIO',
        'TITULAR_CUENTA',
        'BANCO',
        'SUCURSAL_BANCO',
        'CUENTA_BANCO',
        'CLABE',
        'DESC_OTROS',
        'IMPRIR',
        'MAIL',
        'NIVELSEC',
        'ENVIOSILEN',
        'EMAILPRED',
        'MODELO',
        'LAT',
        'LON',
    ];

    public $timestamps = false;

    public function scopeRFC($query) {
        return $query->whereNotNull('RFC');
    }

    public function ordenesCompra() {
        return $this->hasMany(OrdenCompra::class, 'CVE_CLPV', 'CLAVE');
    }
}
