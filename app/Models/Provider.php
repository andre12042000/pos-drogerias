<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    //relaciones

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    public function order()
    {
        return $this->hasMany(Orders::class);
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
