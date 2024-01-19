<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    const efectivo      = 1;
    const tarjeta       = 2;
    const transferencia = 3;
    const cheque        = 4;
    const deposito      = 5;

    const debito        = 1;
    const credito       = 2;

    public function getTipoVentaAttribute()
    {
        if($this->type_sale == 1)
        {
            $tipo = 'Contado';
        }else{
            $tipo = 'Crédito';
        }

        return $tipo;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function saleDetails(){
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }

    //relacion polimorfica con cash

    public function cashs()
    {
        return $this->morphMany('App\Models\Cash', 'cashesable');
    }

}
