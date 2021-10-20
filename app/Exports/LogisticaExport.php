<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\ViewModels\LogisticaViewModel;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LogisticaExport implements FromView, WithColumnFormatting
{
      /**
      * @return \Illuminate\Support\Collection
      */
      public $request;
      public function __construct(Request $request)
      {
           $this->request = $request;
      }

      public function view(): View
      {
          $viewModel = new LogisticaViewModel($this->request);
          return view('dashboard.logistica.table', $viewModel);
      }

      public function columnFormats(): array
      {
        return [
              'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
          ];
      }
}
