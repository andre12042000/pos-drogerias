<?php

namespace App\Traits;

use App\Models\Abono;
use App\Models\Cash;
use App\Models\Empresa;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait RegistrarAbono
{
    public $nuevo_nro_abono, $nro_abono, $prefijo;

    public function AddAbono($abono)
    {
        $this->obtenernumeroabono();

        $abono =  Abono::create([
            'prefijo'           => $this->prefijo,
            'nro'               => $this->nuevo_nro_abono,
            'full_nro'          => $this->nro_abono,
            'client_id'         => $abono['client_id'],
            'user_id'           => $abono['user_id'],
            'amount'            => $abono['amount'],
            'payment_method'    => $abono['payment_method'],
            'abonable_id'       => $abono['abonableble_id'],
            'abonable_type'     => $abono['abonable_type'],
        ]);



        $this->reportarencaja($abono);
    }

    public function DiscountSaldo()
    {
    }

    public function reportarencaja($abono)
    {
        Cash::create([
            'user_id'           => $abono['user_id'],
            'cashesable_id'     => $abono['id'],
            'cashesable_type'   => 'App\Models\Abono',
            'quantity'          => $abono['amount'],
        ]);
    }

    public function obtenernumeroabono()
    {
        $empresa = Empresa::findOrFail(1); //Obtener prefijos
        $this->prefijo = $empresa->pre_abono;

        $ultimo_numero = Abono::max('nro'); //ultimo numero de facturacion

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
