<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    const credito      = 1;
    const contado      = 2;


    //Relaciones

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseDetails(){
        return $this->hasMany(PurchaseDetail::class);
    }



    //Atributos

    public function getCantproductsAttribute()
    {
        if($this->purchaseDetails->count() > 0)
        {
            $cantidad_productos = 0;
            foreach($this->purchaseDetails as $cantidad)
            {
                $cantidad_productos = $cantidad_productos + $cantidad->quantity;
            }

        }else{
            $cantidad_productos = 0;
        }

        return $cantidad_productos;
    }



    public function getIvaAttribute()
    {
        return '$' . number_format($this->mount_tax, 0);
    }

    public function getTotalCompraAttribute()
    {
        return '$' . number_format($this->total, 0);
    }

    public function getFechaCompraAttribute()
    {
        return  \Carbon\Carbon::parse($this->purchase_date)->format('d M Y');
    }
    public function scopeSearch($query, $search)
    {
        if(strlen($search) > 0){
            return $query->where('provider_id', 'like', "%" . $search . "%");
        }else{
            return $query;
        }


    }
}
