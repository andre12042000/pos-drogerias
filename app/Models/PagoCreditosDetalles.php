<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoCreditosDetalles extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    /*----------------Relaciones --------------------------*/

    public function pagocredito()
    {
        return $this->belongsTo(PagoCreditos::class, 'recibo_id');
    }

    public function credito()
    {
        return $this->belongsTo(Credit::class, 'credit_id');
    }




}
