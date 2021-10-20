<?php

namespace App\Http\Controllers\Api\SAE;


use App\SAE\Models\OrdenCompra;
use App\Http\Controllers\Controller;
use App\SAE\SAERepository;
use Illuminate\Http\Request;


class SAEController extends Controller {

    public $SAERepository;

    public function __construct(SAERepository $SAERepository)
    {
        $this->SAERepository = $SAERepository;
    }

    public function getODC(Request $request) {
        $orden_compra = $this->SAERepository->ordenesCompras()->whereRaw("TRIM(CVE_DOC) LIKE '".$request->input('cve_doc')."'")
            ->with(['proveedor', 'productos.producto', 'almacen'])
            ->first();
        return response()->json($orden_compra? $orden_compra : false, 200);
    }
}
