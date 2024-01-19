<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    use HasFactory;
    protected $guarded= ['id'];

    const efectivo      = 1;
    const tarjeta       = 2;
    const transferencia = 3;
    const cheque        = 4;
    const deposito      = 5;

    //Relaciones

     //relacion polimorfica

     public function abonable()
     {
         return $this->morphTo();
     }


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function getMetodopagoAttribute()
    {
        switch ($this->payment_method) {
            case 1:
                $metodo = 'Efectivo';
                break;
            case 2:
                $metodo = 'Tarjeta de crédito';
                break;
            case 3:
                $metodo = 'Transferencia o QR';
                break;
            case 4:
                $metodo = 'Cheque';
                break;
            case 5:
                $metodo = 'Depósito';
                break;
            default:
                $metodo = 'Otro';
                break;
        }

        return $metodo;

    }



}
