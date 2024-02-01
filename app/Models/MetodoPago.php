<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    /*--------------Relaciones ----------------------*/

    public function sales()
    {
        return $this->hasMany(Sale::class, 'metodo_pago_id');
    }
}
