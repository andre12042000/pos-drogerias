<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersDetails extends Model
{
    use HasFactory;
    protected $guarded= ['id'];

    /*relaciones */

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }


}
