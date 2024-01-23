<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $guarded= ['id'];


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
