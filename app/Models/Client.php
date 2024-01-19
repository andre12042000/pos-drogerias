<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    //relaciones

    public function orderservices()
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function orders()
    {
        return $this->hasMany(Orders::class);
    }
    public function scopeSearch($query, $search)
    {
        if(strlen($search) > 0){
            return $query->where(DB::raw('concat_ws(" ", name, email, number_document, phone)'), 'like', "%" . $search . "%");
        }else{
            return $query;
        }


    }
    public function equipos()
    {
        return $this->hasMany(Orders::class);
    }
}
