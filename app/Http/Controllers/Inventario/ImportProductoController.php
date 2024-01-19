<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SaldosImport;

class ImportProductoController extends Controller
{
    public function importar(Request $request)
    {
        $file = $request->file('file');

       

        Excel::import(new SaldosImport, $file);

        return back()->with('success', 'Datos Importados correctamente');
    }
}
