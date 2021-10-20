<?php

namespace App\Entities\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'catalogo_municipios';

    protected $fillable = [
        'estado_id',
        'nombre',
    ];

    //Relaciones
    public function estado() {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
}
