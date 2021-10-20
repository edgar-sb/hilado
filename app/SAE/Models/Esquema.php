<?php

namespace App\SAE\Models;

use Illuminate\Database\Eloquent\Model;

class Esquema extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "IMPU".env("DB_TABLES_SUFIX","01");
    }

    protected $connection = 'firebird';
    //protected $table = 'IMPU01';
    protected $primaryKey = 'CVE_ESQIMPU';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'CVE_ESQIMPU',
        'DESCRIPESQ',
        'IMPUESTO1',
        'IMP1APLICA',
        'IMPUESTO2',
        'IMP2APLICA',
        'IMPUESTO3',
        'IMP3APLICA',
        'IMPUESTO4',
        'IMP4APLICA',
        'STATUS',
        'UUID',
        'VERSION_SINC',
    ];

    public $timestamps = false;

    public function productos() {
        return $this->hasMany(OrdenCompraProducto::class, 'CVE_ESQ', 'CVE_ESQIMPU');
    }
}
