<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderComentario extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

     //relaciones

     public function user()
     {
         return $this->belongsTo(User::class);
     }

     public function order()
     {
         return $this->belongsTo(Orders::class);
     }


}
