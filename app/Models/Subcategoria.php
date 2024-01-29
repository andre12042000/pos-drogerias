<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    protected $guarded= ['id'];

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
       public function categorias()
       {
           return $this->belongsTo(Category::class, 'category_id');
       }

}
