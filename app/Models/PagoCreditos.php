<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoCreditos extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    public function pagocreditable()
    {
        return $this->morphMany('App\Models\PagoCreditos', 'cashesable');
    }

    public function metodopago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    public function pagocreditodetalles()
    {
        return $this->hasMany(PagoCreditosDetalles::class, 'recibo_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }




}
