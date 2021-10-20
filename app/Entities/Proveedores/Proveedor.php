<?php

namespace App\Entities\Proveedores;

use App\Entities\Catalogos\Colonia;
use App\Entities\Catalogos\Estado;
use App\Entities\Catalogos\Municipio;
use App\Entities\Compras\Compra;
use Illuminate\Database\Eloquent\Model;
use App\Entities\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use SoftDeletes;

    protected $table = 'proveedores';

    protected $fillable = [
        'user_id',
        'bloqueado',
        'razon_social',
        'rfc',
        'dias_credito',
        'dias_vencer_factura',
        'dias_vencer_pago',
        'nombre',
        'comentarios',
        'calle',
        'colonia_id',
        'municipio_id',
        'estado_id',
        'contacto_nombre',
        'contacto_puesto',
        'contacto_telefono',
        'contacto_email',
        'emails',
    ];

    protected $casts = [
        'emails' => 'array',
    ];

    //Relaciones
    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
    public function compras() {
        return $this->hasMany(Compra::class, 'proveedor_id');
    }
    public function comprasBloqueadas() {
        return $this->hasMany(Compra::class, 'proveedor_id')
            ->where('bloqueado', true);
    }
    public function estado() {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
    public function municipio() {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }
    public function colonia() {
        return $this->belongsTo(Colonia::class, 'colonia_id');
    }


    //Atributos
    public function getNombreAttribute() {
        return optional($this->user)->nombre;
    }
    public function getEmailAttribute() {
        return optional($this->user)->email;
    }
    public function getEstadoNombreAttribute() {
        return optional($this->estado)->nombre;
    }
    public function getMunicipioNombreAttribute() {
        return optional($this->municipio)->nombre;
    }
    public function getColoniaNombreAttribute() {
        return optional($this->colonia)->nombre;
    }
}
