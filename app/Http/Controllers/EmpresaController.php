<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresaController extends Controller
{
  
    public function index()
    {
        $empresas = Empresa::all();
        return view('setting.empresas.index', compact('empresas'));
    }
  
}
