<?php

namespace App\SAE\Models;

use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "FOLIOSC".env("DB_TABLES_SUFIX","01");
    }

    protected $connection = 'firebird';
    //protected $table = 'FOLIOSC01';
    protected $primaryKey = 'TIP_DOC';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'TIP_DOC',
        'FOLIODESDE',
        'FOLIOHASTA',
        'SERIE',
        'ULT_DOC',
        'FECH_ULT_DOC',
        'STATUS',
    ];

    public $timestamps = false;
}
