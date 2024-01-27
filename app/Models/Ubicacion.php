<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /*-------------Relaciones -------------------------*/

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    /*----------------Scopes -----------------*/
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }
    public function scopeSearch($query, $search)
       {
           if(strlen($search) > 0){
               return $query->where('name', 'like', "%" . $search . "%");
           }else{
               return $query;
           }
       }
}
