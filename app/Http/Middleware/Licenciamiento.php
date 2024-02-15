<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Licencias;
use Illuminate\Http\Request;

class Licenciamiento
{

    public function handle(Request $request, Closure $next)
    {$vencimiento = false;
        $vencimiento = Licencias::find(1);
        $hoy = Carbon::now();
        $hoy = $hoy->format('Y-m-d');

        if ($vencimiento->fecha_vencimiento <= $hoy){
            $vencimiento = true;

        }


        return $vencimiento;
    }
}
