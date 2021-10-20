<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\LogisticaExport;
use App\ViewModels\LogisticaViewModel;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class LogisticaController extends Controller
{
  public function index (Request $request)
  {
    $viewModel = new LogisticaViewModel($request);
    return view('dashboard.logistica.index', $viewModel);
  }

  public function downloadLogistica(Request $request)
  {
      $hoy = Carbon::now()->format('Y-m-d');
      return Excel::download(new LogisticaExport($request), 'Logistica_'.$hoy.'.xlsx');
  }
}
