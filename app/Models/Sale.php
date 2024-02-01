<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $guarded= ['id'];


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function saleDetails(){
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }

    public function metodopago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    //relacion polimorfica con cash

    public function cashs()
    {
        return $this->morphMany('App\Models\Cash', 'cashesable');
    }

    public function scopeSearch($query, $search)
    {
        if($search){
            return $query->where('full_nro', 'like', "%" . $search . "%");
        }else{
            return $query;
        }

    }





}
