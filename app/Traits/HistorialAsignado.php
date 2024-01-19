<?php

namespace App\Traits;

use App\Models\HistorialAsiganacion;

trait HistorialAsignado
{

    public function CrearHistorial($orden, $user)
    {

        if($user != ''){

             $historial =  HistorialAsiganacion::create([
            'order_id'           => $orden,
            'user_id'           => $user,
        ]);
        }


    }


}
