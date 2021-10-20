<?php

namespace App\Entities\Compras;

use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class CompraEstatusLog extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;

    protected $table = 'compras_estatus_log';

    protected $fillable = [
        'compra_id',
        'estatus_id',
        'user_id',
        'estatus',
        'comentarios',
    ];

    public function registerMediaCollections()
    {
        $this->addMediaCollection('factura-pdf')
            ->useDisk('compras');
        $this->addMediaCollection('factura-xml')
            ->useDisk('compras');
        $this->addMediaCollection('acuse')
            ->useDisk('compras');
        $this->addMediaCollection('carta')
            ->useDisk('compras');
        $this->addMediaCollection('comprobante')
            ->useDisk('compras');
        $this->addMediaCollection('complemento-pdf')
            ->useDisk('compras');
        $this->addMediaCollection('complemento-xml')
            ->useDisk('compras');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function estatusActual() {
        return $this->belongsTo(CompraEstatus::class, 'estatus_id');
    }

    public function getFechaFormatAttribute() {
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = Carbon::parse($this->created_at);
        $mes = $meses[($fecha->format('n')) - 1];
        return $mes.' '.$fecha->format('d').', '.$fecha->format('Y');
    }
}
