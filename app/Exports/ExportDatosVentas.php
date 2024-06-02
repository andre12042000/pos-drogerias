<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;


class ExportDatosVentas implements  FromView, ShouldAutoSize
{
    public $data, $totales, $compras;
    public function __construct($data, $totales, $compras){
        $this->data =   $data;
        $this->totales =   $totales;
        $this->compras = $compras;
    }

    public function view(): View
    {
        $ventas =  $this->data;
        foreach ($this->totales as $totales){
            $totales = $totales;
          }

          $compras =  $this->compras;

        return view('exportar.datosventas', compact('ventas',  'totales', 'compras'));
    }
}
