<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentacion extends Model
{
    use HasFactory;
    protected $guarded= ['id'];


     public function scopeActive($query)
       {
            return $query->where('status', 'ACTIVE');
       }

}
