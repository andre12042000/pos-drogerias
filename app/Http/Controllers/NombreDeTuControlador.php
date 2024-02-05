<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NombreDeTuControlador extends Controller
{
    public function obtenerInformacionCliente(Request $request)
    {
        $ip = $request->ip();
        $hostname = gethostbyaddr($ip);

        return response()->json(['ip' => $ip, 'hostname' => $hostname]);
    }
}
