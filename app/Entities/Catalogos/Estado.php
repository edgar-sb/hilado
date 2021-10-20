<?php

namespace App\Entities\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'catalogo_estados';

    protected $fillable = [
        'nombre',
    ];
}
