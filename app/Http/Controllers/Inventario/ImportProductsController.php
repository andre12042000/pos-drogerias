<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportProductsController extends Controller
{
    public function importview()
    {
        return view('inventario.import.index');
    }

    public function importardata(Request $request)
    {
        $request->validate([
            'file'  => ['required']
        ]);

        $file = $request->file('file');
        Excel::import(new ProductsImport,  $file);

        return back()->with('message', 'Importación de productos completada');

    }

}
