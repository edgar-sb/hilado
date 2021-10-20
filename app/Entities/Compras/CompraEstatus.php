<?php

namespace App\Entities\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompraEstatus extends Model
{
    protected $table = 'compras_estatus';

    protected $fillable = [
        'orden',
        'clave',
        'nombre',
    ];

    //Relaciones
    public function compras() {
        return $this->belongsToMany(
            Compra::class,
            'compras_estatus_log',
            'estatus_id',
            'compra_id'
        )
            ->whereNull('deleted_at')
            ->withPivot('comentarios')
            ->withTimestamps();
    }



    //Atributos
    public function getSiguienteEstatusAttribute() {
        return CompraEstatus::where('orden', '>', $this->orden)->orderBy('orden', 'asc')->first();
    }
}
