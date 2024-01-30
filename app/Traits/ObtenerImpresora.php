<?php

namespace App\Traits;

use App\Models\Impresora;
use App\Models\HistorialAsiganacion;

trait ObtenerImpresora
{

    public function obtenerimpresora(){

        $impresora = Impresora::where('predeterminada', true)->first();

        if($impresora){
            return $impresora->nombre;

        }else{
            return false;
        }
    }


}
