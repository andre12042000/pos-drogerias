<?php

namespace App\Traits;

use App\Models\Abono;
use App\Models\AbonoPurchase;
use App\Models\Cash;
use App\Models\Empresa;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait AbonoCompra
{
    public $nuevo_nro_abono, $nro_abono, $prefijo;

    public function AddAbonoCompra($abono)
    {
        $user = $abono['user_id'];
        $abono =  AbonoPurchase::create([
 
            'amount'            => $abono['amount'],
            'purchase_id'       => $abono['abonableble_id'],
          
        ]);

        $this->reportarencaja($abono, $user);
    }

    
    public function reportarencaja($abono, $user)
    {
        Cash::create([
            'user_id'           => $user,
            'cashesable_id'     => $abono['id'],
            'cashesable_type'   => 'App\Models\AbonoPurchase',
            'quantity'          => $abono['amount'],
        ]);
    }

    public function obtenernumeroabono()
    {
        $empresa = Empresa::findOrFail(1); //Obtener prefijos
        $this->prefijo = $empresa->pre_abono;

        $ultimo_numero = AbonoPurchase::max('nro'); //ultimo numero de facturacion

        if (is_null($this->prefijo)) {
            $this->prefijo = "";
        }

        if (is_null($ultimo_numero)) {
            $ultimo_numero = 0;
        }

        $nuevo_numero = $ultimo_numero + 1;

        $this->nuevo_nro_abono = $nuevo_numero;

        $cantidad_numeros = strlen($nuevo_numero);


        switch ($cantidad_numeros) {
            case 1:
                $nuevo_numero = str_pad($nuevo_numero, 4, "0", STR_PAD_LEFT);
                break;
            case 2:
                $nuevo_numero = str_pad($nuevo_numero, 3, "0", STR_PAD_LEFT);
                break;
            case 3:
                $nuevo_numero = str_pad($nuevo_numero, 2, "0", STR_PAD_LEFT);
                break;
            default:
                $nuevo_numero = str_pad($nuevo_numero, 1, "0", STR_PAD_LEFT);
        }


        $this->nro_abono = $this->prefijo . $nuevo_numero;
    }
}
