<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;
    protected $guarded= ['id'];

    public function scopeSearch($query, $search)
    {
        if(strlen($search) > 0){
            return $query->where('cotizacion_date', 'like', "%" . $search . "%");
        }else{
            return $query;
        }
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function saleDetails(){
        return $this->hasMany(CotizacionDetalle::class, 'cotizacion_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
