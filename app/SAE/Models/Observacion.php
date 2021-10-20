<?php

namespace App\SAE\Models;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "OBS_DOCC".env("DB_TABLES_SUFIX","01");
    }

    protected $connection = 'firebird';
    //protected $table = 'OBS_DOCC01';
    protected $primaryKey = 'CVE_OBS';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'CVE_OBS',
        'STR_OBS',
    ];

    public $timestamps = false;

    public function producto() {
        return $this->belongsTo(OrdenCompraProducto::class, 'CVE_OBS', 'CVE_OBS');
    }
}
