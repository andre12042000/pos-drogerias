<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumoInterno extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    //Relaciones

    public function detalles()
    {
        return $this->hasMany(ConsumoInternoDetalles::class, 'consumo_interno_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }



    public function scopeSearch($query, $search)
    {
        if(strlen($search) > 0){
            return $query->where('full_nro', 'like', "%" . $search . "%");
        }else{
            return $query;
        }
    }
}
