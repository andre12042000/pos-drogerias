<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;


class ExportInventario implements  FromView, ShouldAutoSize
{
    public $data;
    public function __construct($data){
        $this->data =   $data;

    }

    public function view(): View
    {
        $productos =  $this->data;


        return view('exportar.inventario', compact('productos'));
    }
}
