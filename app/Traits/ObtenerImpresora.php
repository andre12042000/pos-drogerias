<?php

namespace App\Traits;

use App\Models\Impresora;
use App\Models\HistorialAsiganacion;
use Illuminate\Http\Request;

trait ObtenerImpresora
{

    public function obtenerimpresora(){



       $ip = $this->obtnerip();
  dd($ip);


        $impresora = Impresora::where('name_pc', $ip)->first();

        if($impresora){
            return $impresora->nombre;

        }else{
            return false;
        }
    }

    public function obtnerip(Request $request){
        $ip = $request->ip();
        /*    $hostname = gethostbyaddr($ip); */
        return $ip;
    }


}
