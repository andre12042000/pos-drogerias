<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumoInternoDetalles extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    //Relaciones

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
