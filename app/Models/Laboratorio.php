<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    use HasFactory;
    protected $guarded= ['id'];

       /*-------------Relaciones -------------------------*/

       public function product()
       {
           return $this->hasMany(Product::class);
       }

}
