<?php

namespace App\Entities\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompraCliente extends Model
{
    use SoftDeletes;

    protected $table = 'compras_clientes';

    protected $fillable = [
        'compra_id',
        'razon_social',
        'rfc',
        'direccion'
    ];

    //Relaciones
    public function compra() {
        return $this->belongsTo(Compra::class, 'compra_id');
    }
}
