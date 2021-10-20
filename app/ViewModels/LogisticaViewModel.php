<?php

namespace App\ViewModels;

use Illuminate\Database\Eloquent\Builder;
use Spatie\ViewModels\ViewModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Entities\Compras\Compra;
use App\Entities\Compras\CompraEstatusLog;
use App\Entities\Compras\CompraEstatus;
use App\Entities\Proveedores\Proveedor;

class LogisticaViewModel extends BaseCompraViewModel
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }
}
