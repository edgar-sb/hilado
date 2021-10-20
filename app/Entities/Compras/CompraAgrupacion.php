<?php

namespace App\Entities\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompraAgrupacion extends Model
{
    protected $table = 'compras_agrupaciones';

    protected $fillable = [
        'compras',
    ];

    //Relaciones
    public function compras() {
        return Compra::whereIn('id', json_decode($this->compras))->get();
    }
}
