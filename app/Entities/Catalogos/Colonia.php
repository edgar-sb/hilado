<?php

namespace App\Entities\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Colonia extends Model
{
    protected $table = 'catalogo_colonias';

    protected $fillable = [
        'municipio_id',
        'nombre',
        'codigo_postal',
    ];

    //Relaciones
    public function municipio() {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }
}
