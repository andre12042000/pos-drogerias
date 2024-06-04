<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gastos extends Model
{
    use HasFactory;
    protected $guarded= ['id'];
    public function scopeSearch($query, $search)
    {
        if(strlen($search) > 0){
            return $query->where('descripcion', 'like', "%" . $search . "%");
        }else{
            return $query;
        }
    }


    public function categoria()
    {
        return $this->belongsTo(CategoryGastos::class, 'category_gastos_id');
    }

    public function metodopago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeCajero($query, $user)
    {
        if (strlen($user) > 0) {
            return $query->where('user_id', $user);
        } else {
            return $query;
        }
    }
}
