<?php

namespace App\Http\Controllers\Dashboard;

use App\Entities\Compras\Compra;
use App\Http\Controllers\Controller;
use App\ViewModels\BaseCompraViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DatatableController extends Controller
{
    private $viewModel;

    public function __construct(Request $request)
    {
        $this->viewModel = new BaseCompraViewModel($request);
    }

    public function getCompras()
    {
        $compras_ids = $this->viewModel->compras()->pluck('id')->toArray();

        $model = Compra::select([
            'compras.*',
        ])
            ->whereIn('compras.id', $compras_ids)
            ->orderBy('compras.fecha_envio', $this->viewModel->orden)
            ->orderBy('compras.no_pedido', $this->viewModel->orden);


        return DataTables::eloquent($model)
            ->addColumn('check', function(Compra $compra) {
                $finalizado = $this->viewModel->finalizado;
                return view('dashboard.compras.partials.datatable.td-check', compact('compra', 'finalizado'));
            })
            ->addColumn('detalle', function(Compra $compra) {
                return view('dashboard.compras.partials.datatable.td-detalle', compact('compra'));
            })
            ->addColumn('progreso', function(Compra $compra) {
                return view('dashboard.compras.partials.datatable.td-progreso', compact('compra'));
            })
            ->rawColumns(['check', 'detalle', 'progreso'])
            ->toJson();
    }

    public function getFinanzas()
    {
        $compras_ids = $this->viewModel->compras()->pluck('id')->toArray();
        $sub = Compra::select(
            'compras.id',
            'compras.id as proveedor_razon_social',
            'compras.id as factura_log',
            'compras.id as monto_format',
            'compras.id as plazo_credito',
            'compras.id as dias_vencer',
            'compras.id as acuse_log',
            'compras.id as complemento_log',
            'compras.id as pago_log',
            'compras.id as pago_log_fecha'
        )
            ->whereIn('compras.id', $compras_ids);

        $model = Compra::select([
            'compras.*',
            'sub.proveedor_razon_social',
            'sub.factura_log',
            'sub.monto_format',
            'sub.plazo_credito',
            'sub.dias_vencer',
            'sub.acuse_log',
            'sub.complemento_log',
            'sub.pago_log',
            'sub.pago_log_fecha',
        ])
            ->joinSub($sub, 'sub', function ($join) {
                $join->on('compras.id', '=', 'sub.id');
            })
            ->orderBy('compras.fecha_envio', $this->viewModel->orden)
            ->orderBy('compras.no_pedido', $this->viewModel->orden);

        return DataTables::eloquent($model)
            ->toJson();
    }

    public function getLogistica()
    {
        $compras_ids = $this->viewModel->compras()->pluck('id')->toArray();
        $sub = Compra::select(
            'compras.id',
            'compras.id as proveedor_razon_social',
            'compras.id as factura_log',
            'compras.id as monto_format',
            'compras.id as plazo_credito',
            'compras.id as acuse_log_fecha',
            'compras.id as validacion_log_fecha',
            'compras.id as pago_log',
            'compras.id as pago_log_fecha'
        )
            ->whereIn('compras.id', $compras_ids);

        $model = Compra::select([
            'compras.*',
            'sub.proveedor_razon_social',
            'sub.factura_log',
            'sub.monto_format',
            'sub.plazo_credito',
            'sub.acuse_log_fecha',
            'sub.validacion_log_fecha',
            'sub.pago_log',
            'sub.pago_log_fecha',
        ])
            ->joinSub($sub, 'sub', function ($join) {
                $join->on('compras.id', '=', 'sub.id');
            })
            ->orderBy('compras.fecha_envio', $this->viewModel->orden)
            ->orderBy('compras.no_pedido', $this->viewModel->orden);

        return DataTables::eloquent($model)
            ->addColumn('acuse', function(Compra $compra) {
                return view('dashboard.logistica.partials.datatable.td-acuse', compact('compra'));
            })
            ->addColumn('validar', function(Compra $compra) {
                return view('dashboard.logistica.partials.datatable.td-validar', compact('compra'));
            })
            ->rawColumns(['acuse', 'validar'])
            ->toJson();
    }
}
