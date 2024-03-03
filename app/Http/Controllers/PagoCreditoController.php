<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\PagoCreditos;
use Illuminate\Http\Request;

class PagoCreditoController extends Controller
{
    public function show($recibo)
    {
        $empresa = Empresa::findOrFail(1);
        $recibo = PagoCreditos::findOrFail($recibo);


        return view('pagocreditos.show', compact('recibo', 'empresa'));

    }
}
