<?php

namespace App\Traits;

use App\Models\Impresora;
use App\Models\HistorialAsiganacion;

trait ObtenerImpresora
{

    public function obtenerimpresora($ip){

        $impresora = Impresora::where('name_pc', $ip)->first();
        if($impresora){
            return $impresora->nombre;

        }else{
            return false;
        }
    }


}
