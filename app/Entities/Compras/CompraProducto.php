<?php

namespace App\Entities\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompraProducto extends Model
{
    use SoftDeletes;

    protected $table = 'compras_productos';

    protected $fillable = [
        'compra_id',
        'portal',
        'cantidad',
        'clave',
        'descripcion',
        'descuento',
        'precio',
        'impuesto',
        'importe',
        'esquema',
        'iva',
        'ret_iva',
        'observaciones',
    ];

    //Relaciones
    public function compra() {
        return $this->belongsTo(Compra::class, 'compra_id');
    }


    //Atributos
    public function getPartidaAttribute() {
        return $this->precio * $this->cantidad;
    }
    public function getPartidaFormatAttribute() {
        return '$'.number_format(($this->getPartidaAttribute())/100, 2);
    }
    public function getPrecioFormatAttribute() {
        return '$'.number_format($this->precio/100, 2);
    }
    public function getIvaFormatAttribute() {
        return '$'.number_format(($this->getPartidaAttribute() * ($this->iva/10000))/100, 2);
    }
    public function getRetIvaFormatAttribute() {
        return '$'.number_format(($this->getPartidaAttribute() * ($this->ret_iva/10000))/100, 2);
    }
    public function getImporteFormatAttribute() {
        return '$'.number_format($this->importe/100, 2);
    }
}
