<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

    //scopes

    public function scopeSearch($query, $search)
    {
        if(strlen($search) > 0){
            return $query->where('name', 'like', "%" . $search . "%");
        }else{
            return $query;
        }
    }
    public function subcategoria()
    {
        return $this->hasMany(Subcategoria::class);
    }

}
