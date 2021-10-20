<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\FinanzasExport;
use App\ViewModels\FinanzaViewModel;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class FinanzaController extends Controller
{
    public function index (Request $request)
    {
      $viewModel = new FinanzaViewModel($request);
      return view('dashboard.finanzas.index', $viewModel);
    }

    public function downloadFinanzas(Request $request)
    {
        $hoy = Carbon::now()->format('Y-m-d');
        return Excel::download(new FinanzasExport($request), 'Finanzas_'.$hoy.'.xlsx');
    }

}
