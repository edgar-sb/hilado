<?php

namespace App\ViewModels;

use App\Entities\Compras\Compra;
use App\Entities\Compras\CompraEstatus;
use App\Entities\Proveedores\Proveedor;
use App\Http\Controllers\Dashboard\Traits\ValidaArchivos;
use Illuminate\Http\Request;

class CompraViewModel extends BaseCompraViewModel
{
    public $compra;
    public function __construct(Request $request = null, Compra $compra = null) {
        if (isset($request)) {
            $this->fecha_inicio = $request->input('fecha_inicio');
            $this->fecha_final = $request->input('fecha_final');
            $this->proveedor = $request->input('proveedor');
            $this->estatus = $request->input('estatus');
            $this->orden = $request->input('orden');
            $this->finalizado = $request->input('finalizado');
            $this->filtro = $request->input('filtro');
            $this->estatus_id = $request->input('estatus_id');
            $this->no_factura = $request->input('no_factura');
            $this->fecha_factura_inicio = $request->input('fecha_factura_inicio');
            $this->fecha_factura_final = $request->input('fecha_factura_final');
        }
        if (auth()->user()->hasRole('proveedor')) {
            $this->proveedor = auth()->user()->proveedor->id;
        }
        if(is_null($this->orden)) {
            $this->orden = 'desc';
        }
        if(is_null($this->finalizado)) {
            $this->finalizado = 'false';
        }
        if(is_null($this->fecha_inicio)) {
            $this->fecha_inicio = Carbon::now()->startOfMonth()->format('Y/m/d');
        }
        if(is_null($this->fecha_final)) {
            $this->fecha_final = Carbon::now()->endOfMonth()->format('Y/m/d');
        }
        $this->compra = $compra;
    }

    public function titulo() {
        if(isset($this->filtro)) {
            switch ($this->filtro) {
                case 'todas':
                    return 'Últimas compras';
                    break;
                case 'en_proceso':
                    return 'Últimas compras en proceso';
                    break;
                case 'por_pagar':
                    return 'Últimas compras por pagar';
                    break;
            }
        }
        return null;
    }

    public function compras() {
        $compras = Compra::with('proveedor')
            ->orderBy('fecha_envio', $this->orden)
            ->orderBy('no_pedido', $this->orden);

        if (auth()->user()->hasRole('proveedor')) {
           $compras = $compras->where('proveedor_id', auth()->user()->proveedor->id);
        }
        if (isset($this->fecha_inicio) && isset($this->fecha_final)) {
            $fecha_inicio = Carbon::parse($this->fecha_inicio)->startOfDay();
            $fecha_final = Carbon::parse($this->fecha_final)->endOfDay();
            $compras = $compras->whereBetween('fecha_envio', [$fecha_inicio, $fecha_final]);
        }
        if (isset($this->proveedor) && $this->proveedor != 0) {
            $compras = $compras->where('proveedor_id', $this->proveedor);
        }
        if (isset($this->estatus) && $this->estatus != 0) {
            $compras = $compras->where('estatus_id', $this->estatus);
        }
        if (isset($this->filtro)) {
            switch ($this->filtro) {
                case 'en_proceso':
                    $compras = $compras->whereIn('estatus_id', [1, 2, 3]);
                    break;
                case 'por_pagar':
                    $compras = $compras->whereIn('estatus_id', [4]);
                    break;
            }
        }
        if (isset($this->finalizado) && $this->finalizado == 'true') {
            $estatus_finalizado = CompraEstatus::where('clave', 'complemento')->first();
            $compras = $compras->whereHas('estatusLog', function (Builder $query) use ($estatus_finalizado) {
                $query->where(function ($query) use ($estatus_finalizado) {
                    $query->where('compras_estatus_log.estatus_id', $estatus_finalizado->id)
                        ->where('compras_estatus_log.estatus', 'aprobado');
                })
                    ->where('compras.estatus_id', $estatus_finalizado->id);
            });
        } else {
            $estatus_finalizado = CompraEstatus::where('clave', 'complemento')->first();
            $compras = $compras->whereHas('estatusLog', function (Builder $query) use ($estatus_finalizado) {
                $query->where(function ($query) use ($estatus_finalizado) {
                    $query->where('compras_estatus_log.estatus_id', $estatus_finalizado->id)
                        ->where('compras_estatus_log.estatus', 'pendiente');
                })
                    ->orWhere('compras.estatus_id', '<>', $estatus_finalizado->id);
            });
        }
        if (isset($this->no_factura) && $this->no_factura != "") {
            $compras = $compras->where('no_factura', 'LIKE', '%'.$this->no_factura.'%');
        }
        if (isset($this->fecha_factura_inicio) && isset($this->fecha_factura_final)) {
            $fecha_factura_inicio = Carbon::parse($this->fecha_factura_inicio)->startOfDay();
            $fecha_factura_final = Carbon::parse($this->fecha_factura_final)->endOfDay();
            $compras = $compras->whereBetween('fecha_factura', [$fecha_factura_inicio, $fecha_factura_final]);
        }

        $compras = $compras->get();
        foreach ($compras as $compra){
            $this->limpiaArchivos($compra);
        }
        return $compras;
    }

    public function select_proveedores() {
        if (auth()->user()->hasRole('proveedor')) {
            return [auth()->user()->proveedor->id => auth()->user()->proveedor->razon_social];
        }
        return Proveedor::pluck('razon_social', 'id')->prepend('Todos los proveedores', 0);
    }

    public function select_estatus() {
        return CompraEstatus::pluck('nombre', 'id')->prepend('Todos los estatus', 0);
    }

    public function estatus_log($compra) {
        return $compra->estatusLog()->where('estatus_id', $compra->estatusActual->id)->first();
    }

    public function guardar_venta() {
        $estatus = CompraEstatus::orderBy('orden', 'asc')->first();
        if(isset($this->compra)) {
            $estatus_log = $this->compra->estatusLog()->where('estatus', 'pendiente')->first();
            if(isset($estatus_log) && optional($estatus_log->estatusActual)->clave == "factura") {
                if (auth()->user()->can('agregar compra') && isset($this->estatus_id) && $estatus->id == $this->estatus_id) {
                    return true;
                }
            }
        }
        return false;
    }

    public function agrupaciones_ids() {
        $agrupaciones = [];
        foreach ($this->compras() as $compra) {
            if(!is_null($compra->agrupacion())) {
                $agrupaciones[$compra->agrupacion()->id] = json_decode($compra->agrupacion()->compras);
            }
        }
        return $agrupaciones;
    }
}
