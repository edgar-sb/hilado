<?php

namespace App\ViewModels;

use App\Entities\Catalogos\Estado;
use App\Entities\Compras\Compra;
use App\Entities\Compras\CompraEstatus;
use Spatie\ViewModels\ViewModel;
use App\Entities\Proveedores\Proveedor;

class ProveedorViewModel extends ViewModel
{
    public $proveedor;

    public function __construct(Proveedor $proveedor = null) {
      $this->proveedor = $proveedor;
    }

    public function proveedores()
    {
      return Proveedor::get();
    }

    public function compras_bloqueadas() {
        return optional($this->proveedor)->comprasBloqueadas;
    }

    public function compras_bloqueadas_count() {
        return optional(optional($this->proveedor)->comprasBloqueadas())->count();
    }

    public function select_estados() {
        return Estado::pluck('nombre', 'id');
    }

    public function facturaLog($compra) {
        return $this->evaluaEstatusLog($compra, 'factura');
    }

    public function acuseLog($compra) {
        return $this->evaluaEstatusLog($compra, 'acuse-y-carta-porte');
    }

    public function complementoLog($compra) {
        return $this->evaluaEstatusLog($compra, 'complemento');
    }

    public function pagoLog($compra) {
        return $this->evaluaEstatusLog($compra, 'pago', true);
    }

    private function evaluaEstatusLog(Compra $compra, $clave, $proceder = false) {
        $estatus = CompraEstatus::where('clave', $clave)->first();
        if (isset($estatus)) {
            $estatus_log = $compra->estatusLog()->where('estatus_id', $estatus->id)->first();
            if (isset($estatus_log)) {
                switch ($estatus_log->estatus) {
                    case "aprobado":
                        if ($proceder) {
                            return "Realizado";
                        }
                        return "Si";
                        break;
                    case "rechazado":
                        if ($proceder) {
                            return "Rechazado";
                        }
                        return "Rechazado";
                    case "pendiente":
                        if ($proceder) {
                            return "Si";
                        }
                        return "Pendiente";
                        break;
                }
            }
        }
        if ($proceder) {
            return "No";
        }
        return "Pendiente";
    }
}
