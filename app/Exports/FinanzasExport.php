<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\ViewModels\FinanzaViewModel;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class FinanzasExport implements FromView, WithColumnFormatting
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
        $viewModel = new FinanzaViewModel($this->request);
        return view('dashboard.finanzas.table', $viewModel);
    }

    public function columnFormats(): array
    {
      return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
